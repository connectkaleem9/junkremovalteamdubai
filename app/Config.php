<?php
declare(strict_types=1);

/**
 * Reads settings from a .env file kept outside the repository and outside the
 * web root, so credentials are never committed and never downloadable.
 *
 * Looked for in this order:
 *   1. $HOME/appdata/.env        (the server)
 *   2. <project root>/.env       (local development)
 */
final class Config
{
    private static array $values = [];
    private static bool $loaded = false;

    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }
        self::$loaded = true;

        foreach (self::candidatePaths() as $path) {
            if (is_readable($path)) {
                self::parse($path);
                break;
            }
        }
    }

    /**
     * The account's home directory. Web requests often run without $HOME set,
     * so fall back to the owner of this file, then to the /home/<user>/ prefix
     * of the app path.
     */
    public static function home(): string
    {
        $home = getenv('HOME') ?: ($_SERVER['HOME'] ?? '');
        if ($home === '' && function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
            $info = posix_getpwuid(posix_geteuid());
            $home = is_array($info) ? (string) ($info['dir'] ?? '') : '';
        }
        if ($home === '' && preg_match('#^(/home/[^/]+)/#', APP_ROOT, $m) === 1) {
            $home = $m[1];
        }

        return rtrim((string) $home, '/');
    }

    /** @return string[] */
    private static function candidatePaths(): array
    {
        $paths = [];
        $home = self::home();
        if ($home !== '') {
            $paths[] = $home . '/appdata/.env';
        }
        $paths[] = dirname(APP_ROOT) . '/.env';

        return $paths;
    }

    private static function parse(string $path): void
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }
            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            // Strip surrounding quotes, and anything after an unquoted #
            if (strlen($value) > 1 && ($value[0] === '"' || $value[0] === "'") && $value[-1] === $value[0]) {
                $value = substr($value, 1, -1);
            } elseif (($hash = strpos($value, ' #')) !== false) {
                $value = rtrim(substr($value, 0, $hash));
            }

            self::$values[$key] = $value;
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        self::load();
        $value = self::$values[$key] ?? getenv($key);

        return ($value === false || $value === null || $value === '') ? $default : (string) $value;
    }

    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    /**
     * Writable storage outside the web root: $HOME/appdata/<sub>.
     * Falls back to the system temp directory if the home directory is unusable.
     */
    public static function storagePath(string $sub = ''): string
    {
        $home = self::home();
        $base = $home !== '' ? $home . '/appdata' : sys_get_temp_dir() . '/jrtd';

        $full = $sub === '' ? $base : $base . '/' . ltrim($sub, '/');
        $dir = str_contains(basename($full), '.') ? dirname($full) : $full;

        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }

        return $full;
    }
}
