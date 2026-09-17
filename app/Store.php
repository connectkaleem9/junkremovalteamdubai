<?php
declare(strict_types=1);

/**
 * Simple JSON store for site content (reviews, projects).
 *
 * Files live in ~/appdata/content/, outside the web root and outside the repo,
 * so deploys never touch them and nothing here can be fetched over HTTP.
 * When MySQL is configured this can be swapped for tables without changing
 * the pages that call it.
 */
final class Store
{
    private static function path(string $name): string
    {
        return Config::storagePath('content/' . $name . '.json');
    }

    /** @return array<int,array<string,mixed>> */
    public static function all(string $name): array
    {
        $file = self::path($name);
        if (!is_readable($file)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($file), true);

        return is_array($decoded) ? $decoded : [];
    }

    /** Published items, newest first. */
    public static function published(string $name, ?string $lang = null): array
    {
        $rows = array_filter(self::all($name), static function (array $row) use ($lang): bool {
            if (($row['status'] ?? 'published') !== 'published') {
                return false;
            }
            // Reviews are shown in the language they were written in;
            // projects are shown in both.
            return $lang === null || ($row['lang'] ?? null) === null || $row['lang'] === $lang;
        });

        usort($rows, static fn (array $a, array $b): int => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')));

        return array_values($rows);
    }

    public static function find(string $name, string $id): ?array
    {
        foreach (self::all($name) as $row) {
            if (($row['id'] ?? '') === $id) {
                return $row;
            }
        }

        return null;
    }

    /** Adds a row (generating an id) and returns it. */
    public static function add(string $name, array $row): array
    {
        $row['id'] = $row['id'] ?? bin2hex(random_bytes(8));
        $row['created_at'] = $row['created_at'] ?? date('c');

        self::mutate($name, static function (array $rows) use ($row): array {
            $rows[] = $row;
            return $rows;
        });

        return $row;
    }

    public static function update(string $name, string $id, array $changes): bool
    {
        $found = false;

        self::mutate($name, static function (array $rows) use ($id, $changes, &$found): array {
            foreach ($rows as $i => $row) {
                if (($row['id'] ?? '') === $id) {
                    $rows[$i] = array_merge($row, $changes);
                    $found = true;
                }
            }
            return $rows;
        });

        return $found;
    }

    public static function delete(string $name, string $id): bool
    {
        $found = false;

        self::mutate($name, static function (array $rows) use ($id, &$found): array {
            $kept = [];
            foreach ($rows as $row) {
                if (($row['id'] ?? '') === $id) { $found = true; continue; }
                $kept[] = $row;
            }
            return $kept;
        });

        return $found;
    }

    /** Read-modify-write under an exclusive lock so two visitors can't clash. */
    private static function mutate(string $name, callable $change): void
    {
        $file = self::path($name);
        $handle = fopen($file, 'c+');
        if ($handle === false) {
            error_log('Store: cannot open ' . $file);
            return;
        }

        try {
            flock($handle, LOCK_EX);
            $size = (int) (fstat($handle)['size'] ?? 0);
            $raw = $size > 0 ? (string) fread($handle, $size) : '';
            $rows = json_decode($raw, true);
            $rows = is_array($rows) ? $rows : [];

            $rows = $change($rows);

            $json = json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, $json === false ? '[]' : $json);
            fflush($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
