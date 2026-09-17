<?php
declare(strict_types=1);

/**
 * PDO connection.
 *
 * Returns null when the database is not configured yet, so the site keeps
 * working (email + file backup) until the credentials exist.
 */
final class Db
{
    private static ?PDO $pdo = null;
    private static bool $tried = false;

    public static function connection(): ?PDO
    {
        if (self::$tried) {
            return self::$pdo;
        }
        self::$tried = true;

        $database = Config::get('DB_DATABASE');
        $username = Config::get('DB_USERNAME');
        if ($database === null || $username === null) {
            return null;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            Config::get('DB_HOST', '127.0.0.1'),
            Config::get('DB_PORT', '3306'),
            $database,
            Config::get('DB_CHARSET', 'utf8mb4')
        );

        try {
            self::$pdo = new PDO($dsn, $username, Config::get('DB_PASSWORD', ''), [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log('DB connection failed: ' . $e->getMessage());
            self::$pdo = null;
        }

        return self::$pdo;
    }

    public static function isAvailable(): bool
    {
        return self::connection() instanceof PDO;
    }
}
