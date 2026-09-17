<?php
declare(strict_types=1);

/**
 * Admin session, login and CSRF.
 *
 * One shared password, hashed in .env as ADMIN_PASSWORD_HASH. Generate it with:
 *   php -r 'echo password_hash("your password", PASSWORD_DEFAULT), PHP_EOL;'
 */
final class Admin
{
    private const IDLE_TIMEOUT = 1800;   // 30 minutes of inactivity
    private const HARD_TIMEOUT = 28800;  // 8 hours total

    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_name('jrtd_admin');
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/admin/',
            'secure'   => (($_SERVER['HTTPS'] ?? '') !== '' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public static function isLoggedIn(): bool
    {
        self::startSession();

        if (($_SESSION['admin'] ?? false) !== true) {
            return false;
        }

        $now = time();
        $lastSeen = (int) ($_SESSION['seen'] ?? 0);
        $startedAt = (int) ($_SESSION['started'] ?? 0);

        if (($now - $lastSeen) > self::IDLE_TIMEOUT || ($now - $startedAt) > self::HARD_TIMEOUT) {
            self::logout();
            return false;
        }

        $_SESSION['seen'] = $now;

        return true;
    }

    /** @return string|null error message, or null on success */
    public static function attemptLogin(string $password): ?string
    {
        self::startSession();

        if (Security::rateLimited('admin_login', 8, 900)) {
            return 'Too many attempts. Wait fifteen minutes and try again.';
        }

        $hash = Config::get('ADMIN_PASSWORD_HASH');
        if ($hash === null) {
            return 'No admin password is set on the server yet.';
        }

        if (!password_verify($password, $hash)) {
            error_log('Admin login failed from ' . substr(Security::visitorHash(), 0, 12));
            return 'Wrong password.';
        }

        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        $_SESSION['started'] = time();
        $_SESSION['seen'] = time();
        $_SESSION['csrf'] = bin2hex(random_bytes(32));

        return null;
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool) $params['secure'], (bool) $params['httponly']);
        }
        session_destroy();
    }

    public static function csrfToken(): string
    {
        self::startSession();
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return (string) $_SESSION['csrf'];
    }

    public static function checkCsrf(): bool
    {
        self::startSession();
        $sent = (string) ($_POST['csrf'] ?? '');
        $known = (string) ($_SESSION['csrf'] ?? '');

        return $known !== '' && hash_equals($known, $sent);
    }

    /** Reads the most recent leads back out of the monthly JSONL files. */
    public static function recentLeads(int $limit = 50): array
    {
        $dir = Config::storagePath('leads');
        $files = glob($dir . '/*.jsonl') ?: [];
        rsort($files);

        $leads = [];
        foreach ($files as $file) {
            foreach (array_reverse(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) as $line) {
                $row = json_decode($line, true);
                if (is_array($row)) {
                    $leads[] = $row;
                }
                if (count($leads) >= $limit) {
                    return $leads;
                }
            }
        }

        return $leads;
    }
}
