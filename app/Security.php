<?php
declare(strict_types=1);

/**
 * Form protection that works on statically cached pages.
 *
 * A CSRF token needs a session, and a session cannot be started on a page that
 * is served from cache, so instead we check: the request method, that it came
 * from our own site, a hidden field no human fills in, how fast it arrived,
 * and how many have arrived recently from the same visitor.
 */
final class Security
{
    /** Same-origin check. Returns false when the POST came from another site. */
    public static function originLooksValid(): bool
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($host === '') {
            return false;
        }

        $source = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        if ($source === '') {
            return true; // some privacy tools strip both; other checks still apply
        }

        $sourceHost = parse_url($source, PHP_URL_HOST) ?: '';

        return $sourceHost === '' || str_ends_with($sourceHost, preg_replace('/^www\./', '', $host));
    }

    /** A bot fills every field, including the one hidden from people. */
    public static function honeypotTripped(array $input): bool
    {
        return trim((string) ($input['website'] ?? '')) !== '';
    }

    /** Anything submitted within a few seconds of loading was not typed by a person. */
    public static function submittedTooFast(array $input, int $minSeconds = 3): bool
    {
        $started = (int) ($input['form_started'] ?? 0);
        if ($started <= 0) {
            return false; // field missing (no JS) — do not punish the visitor
        }

        return (time() - intdiv($started, 1000)) < $minSeconds;
    }

    /** Never store a raw IP address: hash it with the app key. */
    public static function visitorHash(): string
    {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP']
            ?? $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? 'unknown';
        $ip = trim(explode(',', (string) $ip)[0]);

        return hash_hmac('sha256', $ip, Config::get('APP_KEY', 'insecure-development-key'));
    }

    /**
     * Simple file-based limit: $max submissions per $windowSeconds per visitor.
     */
    public static function rateLimited(string $bucket, int $max = 5, int $windowSeconds = 3600): bool
    {
        $file = Config::storagePath('ratelimit/' . $bucket . '-' . substr(self::visitorHash(), 0, 32) . '.json');

        $now = time();
        $hits = [];
        if (is_readable($file)) {
            $decoded = json_decode((string) file_get_contents($file), true);
            if (is_array($decoded)) {
                $hits = array_filter($decoded, static fn ($t): bool => is_int($t) && ($now - $t) < $windowSeconds);
            }
        }

        if (count($hits) >= $max) {
            return true;
        }

        $hits[] = $now;
        @file_put_contents($file, json_encode(array_values($hits)), LOCK_EX);

        return false;
    }
}
