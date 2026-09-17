<?php
declare(strict_types=1);

/**
 * Image uploads for the admin (project before/after photos).
 *
 * Files are written to ~/appdata/uploads/, OUTSIDE the web root, because the
 * deploy script mirrors the repo into public_html with --delete: anything
 * uploaded there would be erased on the next deploy. public/media.php serves
 * them back out.
 *
 * Every upload is re-encoded, which strips EXIF (including GPS) and means a
 * file that merely pretends to be an image cannot survive the trip.
 */
final class Uploads
{
    private const MAX_BYTES = 8 * 1024 * 1024;
    private const MAX_SIDE  = 1600;

    /** @return array{0: ?string, 1: ?string} [storedName, error] */
    public static function saveImage(array $file, string $folder = 'projects'): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return [null, null]; // nothing chosen — not an error
        }

        if (($file['error'] ?? 1) !== UPLOAD_ERR_OK) {
            return [null, 'Upload failed (error ' . (int) $file['error'] . ').'];
        }

        if (!is_uploaded_file($file['tmp_name'] ?? '')) {
            return [null, 'That file was not uploaded properly.'];
        }

        if (($file['size'] ?? 0) > self::MAX_BYTES) {
            return [null, 'Image is larger than 8 MB.'];
        }

        $info = @getimagesize($file['tmp_name']);
        if ($info === false) {
            return [null, 'That file is not an image.'];
        }

        $allowed = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
        if (!in_array($info[2], $allowed, true)) {
            return [null, 'Use a JPG, PNG or WebP image.'];
        }

        if ($info[0] > 8000 || $info[1] > 8000) {
            return [null, 'Image dimensions are too large.'];
        }

        $source = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($file['tmp_name']),
            IMAGETYPE_PNG  => @imagecreatefrompng($file['tmp_name']),
            IMAGETYPE_WEBP => @imagecreatefromwebp($file['tmp_name']),
            default        => false,
        };

        if ($source === false) {
            return [null, 'That image could not be read.'];
        }

        [$width, $height] = [imagesx($source), imagesy($source)];
        $scale = min(1, self::MAX_SIDE / max($width, $height));
        $newWidth = (int) round($width * $scale);
        $newHeight = (int) round($height * $scale);

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);

        $name = $folder . '/' . bin2hex(random_bytes(10)) . '.jpg';
        $target = Config::storagePath('uploads/' . $name);

        $saved = imagejpeg($canvas, $target, 82);
        imagedestroy($canvas);

        if (!$saved) {
            return [null, 'The image could not be saved on the server.'];
        }

        @chmod($target, 0640);

        return [$name, null];
    }

    public static function delete(string $name): void
    {
        if ($name === '' || !self::isSafeName($name)) {
            return;
        }

        $path = Config::storagePath('uploads/' . $name);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /** Only "folder/name.jpg" shapes — no traversal, no absolute paths. */
    public static function isSafeName(string $name): bool
    {
        return preg_match('#^[a-z0-9_-]+/[a-f0-9]{20}\.jpg$#', $name) === 1;
    }

    public static function url(string $name): string
    {
        return '/media.php?f=' . rawurlencode($name);
    }
}
