<?php
declare(strict_types=1);

/**
 * Customer reviews submitted through the website.
 *
 * The owner asked for reviews to appear as soon as they are sent, so there is
 * no approval queue. That makes the spam checks below the only thing standing
 * between the site and junk, and the admin can hide or delete anything that
 * slips through.
 */
final class Review
{
    /**
     * Owner's decision (18 Sep 2026): there is no daily cap — one person may
     * post as many reviews as they like. What is left is a burst guard: no more
     * than this many in a single minute. A human typing a review cannot reach
     * it; a script posting in a loop hits it immediately. Set MAX_PER_MINUTE to
     * 0 to turn even that off.
     */
    public const MAX_PER_MINUTE = 10;

    /** @return array{0: array<string,mixed>, 1: array<string,string>} [clean, errors] */
    public static function validate(array $input, string $lang): array
    {
        $ar = $lang === 'ar';
        $clean = [];
        $errors = [];

        $name = trim((string) ($input['name'] ?? ''));
        if (mb_strlen($name) < 2) {
            $errors['name'] = $ar ? 'يرجى إدخال اسمك.' : 'Please enter your name.';
        }
        $clean['name'] = mb_substr($name, 0, 60);

        $rating = (int) ($input['rating'] ?? 0);
        if ($rating < 1 || $rating > 5) {
            $errors['rating'] = $ar ? 'يرجى اختيار تقييم من 1 إلى 5.' : 'Please choose a rating from 1 to 5.';
        }
        $clean['rating'] = $rating;

        $text = trim((string) ($input['text'] ?? ''));
        if (mb_strlen($text) < 20) {
            $errors['text'] = $ar ? 'يرجى كتابة بضع كلمات عن تجربتك (20 حرفًا على الأقل).' : 'Please write a few words about your experience (at least 20 characters).';
        } elseif (mb_strlen($text) > 900) {
            $errors['text'] = $ar ? 'المراجعة طويلة جدًا.' : 'That review is too long.';
        } elseif (self::looksLikeSpam($text)) {
            // Links are the usual giveaway; say so plainly rather than silently dropping it
            $errors['text'] = $ar ? 'لا يمكن أن تحتوي المراجعة على روابط.' : 'Reviews cannot contain links.';
        }
        $clean['text'] = mb_substr($text, 0, 900);

        $area = trim((string) ($input['area'] ?? ''));
        $clean['area'] = in_array($area, array_keys(View::areas('en')), true) ? $area : '';

        $service = trim((string) ($input['service'] ?? ''));
        $clean['service'] = in_array($service, Lead::SERVICES, true) ? $service : '';

        $clean['lang'] = $lang;

        return [$clean, $errors];
    }

    private static function looksLikeSpam(string $text): bool
    {
        if (preg_match('#https?://|www\.|\[url|<a\s#i', $text) === 1) {
            return true;
        }

        // A wall of one repeated character, or no spaces at all in a long string
        return preg_match('/(.)\1{9,}/u', $text) === 1
            || (mb_strlen($text) > 60 && !str_contains($text, ' '));
    }

    public static function add(array $clean): array
    {
        return Store::add('reviews', [
            'name'    => $clean['name'],
            'rating'  => $clean['rating'],
            'text'    => $clean['text'],
            'area'    => $clean['area'],
            'service' => $clean['service'],
            'lang'    => $clean['lang'],
            'source'  => 'website',
            'status'  => 'published',
            'ip_hash' => Security::visitorHash(),
        ]);
    }

    /** Initials for the avatar circle, e.g. "Ahmed R." -> "AR" */
    public static function initials(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name)) ?: [];
        $letters = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $letters .= mb_substr($part, 0, 1);
        }

        return mb_strtoupper($letters !== '' ? $letters : '?');
    }

    public static function averageRating(array $reviews): ?float
    {
        $ratings = array_filter(array_map(static fn (array $r): int => (int) ($r['rating'] ?? 0), $reviews));

        return $ratings === [] ? null : round(array_sum($ratings) / count($ratings), 1);
    }
}
