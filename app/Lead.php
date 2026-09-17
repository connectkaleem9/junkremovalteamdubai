<?php
declare(strict_types=1);

/**
 * Validates and stores an enquiry.
 *
 * Every lead is written to a file first, then to the database if one is
 * configured. The file is the safety net: a lead is never lost because the
 * database was unreachable.
 */
final class Lead
{
    public const SERVICES = [
        'Junk Removal', 'Furniture Removal', 'House Clearance',
        'Office Clearance', 'Construction Waste', 'E-Waste Disposal',
    ];

    public const AREAS = [
        'Al Quoz', 'Al Barsha', 'Jumeirah', 'Dubai Marina',
        'Business Bay', 'Downtown Dubai', 'JVC', 'Other',
    ];

    /**
     * @return array{0: array<string,string>, 1: array<string,string>} [clean, errors]
     */
    public static function validate(array $input): array
    {
        $clean = [];
        $errors = [];

        $name = trim((string) ($input['name'] ?? ''));
        if ($name === '') {
            $errors['name'] = 'Please enter your name.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'That name is too long.';
        }
        $clean['name'] = mb_substr($name, 0, 100);

        $phone = self::normalisePhone((string) ($input['phone'] ?? ''));
        if ($phone === null) {
            $errors['phone'] = 'Please enter a valid UAE phone number.';
            $clean['phone'] = mb_substr(trim((string) ($input['phone'] ?? '')), 0, 20);
        } else {
            $clean['phone'] = $phone;
        }

        $email = trim((string) ($input['email'] ?? ''));
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'That email address does not look right.';
        }
        $clean['email'] = mb_substr($email, 0, 190);

        $service = trim((string) ($input['service'] ?? ''));
        $clean['service'] = in_array($service, self::SERVICES, true) ? $service : '';

        $area = trim((string) ($input['area'] ?? ''));
        $clean['area'] = in_array($area, self::AREAS, true) ? $area : '';

        $clean['message']  = mb_substr(trim((string) ($input['message'] ?? '')), 0, 2000);
        $clean['language'] = ($input['language'] ?? 'en') === 'ar' ? 'ar' : 'en';

        // Campaign attribution, captured by the page and passed through hidden fields
        foreach (['landing_page', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid'] as $key) {
            $clean[$key] = mb_substr(trim((string) ($input[$key] ?? '')), 0, 500);
        }

        return [$clean, $errors];
    }

    /** UAE mobile numbers, returned as +9715XXXXXXXX. Null when unusable. */
    public static function normalisePhone(string $raw): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', $raw) ?? '';

        if (str_starts_with($digits, '00971')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '971')) {
            $digits = substr($digits, 3);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // UAE mobiles are 9 digits starting with 5 once the prefix is removed
        if (preg_match('/^5[0-9]{8}$/', $digits) !== 1) {
            return null;
        }

        return '+971' . $digits;
    }

    /** @return array{stored_in: string[], id: ?int} */
    public static function store(array $clean): array
    {
        $record = $clean + [
            'ip_hash'    => Security::visitorHash(),
            'user_agent' => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            'created_at' => date('c'),
        ];

        $stored = [];
        if (self::appendToFile($record)) {
            $stored[] = 'file';
        }

        $id = self::insertIntoDatabase($clean, $record['ip_hash']);
        if ($id !== null) {
            $stored[] = 'database';
        }

        return ['stored_in' => $stored, 'id' => $id];
    }

    private static function appendToFile(array $record): bool
    {
        $path = Config::storagePath('leads/' . date('Y-m') . '.jsonl');
        $line = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $line !== false && @file_put_contents($path, $line . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
    }

    private static function insertIntoDatabase(array $c, string $ipHash): ?int
    {
        $pdo = Db::connection();
        if ($pdo === null) {
            return null;
        }

        try {
            $sql = 'INSERT INTO leads
                    (name, phone, email, language, service, area, message,
                     landing_page, referrer, utm_source, utm_medium, utm_campaign,
                     utm_term, utm_content, gclid, ip_hash, status)
                    VALUES
                    (:name, :phone, :email, :language, :service, :area, :message,
                     :landing_page, :referrer, :utm_source, :utm_medium, :utm_campaign,
                     :utm_term, :utm_content, :gclid, :ip_hash, :status)';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'         => $c['name'],
                ':phone'        => $c['phone'],
                ':email'        => $c['email'] !== '' ? $c['email'] : null,
                ':language'     => $c['language'],
                ':service'      => $c['service'] !== '' ? $c['service'] : null,
                ':area'         => $c['area'] !== '' ? $c['area'] : null,
                ':message'      => $c['message'] !== '' ? $c['message'] : null,
                ':landing_page' => $c['landing_page'] !== '' ? $c['landing_page'] : null,
                ':referrer'     => $c['referrer'] !== '' ? $c['referrer'] : null,
                ':utm_source'   => $c['utm_source'] !== '' ? $c['utm_source'] : null,
                ':utm_medium'   => $c['utm_medium'] !== '' ? $c['utm_medium'] : null,
                ':utm_campaign' => $c['utm_campaign'] !== '' ? $c['utm_campaign'] : null,
                ':utm_term'     => $c['utm_term'] !== '' ? $c['utm_term'] : null,
                ':utm_content'  => $c['utm_content'] !== '' ? $c['utm_content'] : null,
                ':gclid'        => $c['gclid'] !== '' ? $c['gclid'] : null,
                ':ip_hash'      => $ipHash,
                ':status'       => 'new',
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log('Lead insert failed: ' . $e->getMessage());

            return null;
        }
    }
}
