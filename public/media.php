<?php
declare(strict_types=1);

/**
 * Serves images that the admin uploaded.
 *
 * They live outside the web root (see app/Uploads.php), so this script reads
 * the file and streams it back. Only names matching the strict pattern in
 * Uploads::isSafeName() are accepted, which rules out path traversal.
 */

require_once __DIR__ . '/../app/bootstrap.php';

$name = (string) ($_GET['f'] ?? '');

if (!Uploads::isSafeName($name)) {
    http_response_code(404);
    exit;
}

$path = Config::storagePath('uploads/' . $name);

if (!is_file($path)) {
    http_response_code(404);
    exit;
}

$modified = (int) filemtime($path);
$etag = '"' . md5($name . $modified) . '"';

header('Content-Type: image/jpeg');
header('Cache-Control: public, max-age=31536000, immutable');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT');
header('ETag: ' . $etag);
header('X-Content-Type-Options: nosniff');

// Let the browser reuse what it already has
if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $etag) {
    http_response_code(304);
    exit;
}

header('Content-Length: ' . filesize($path));
readfile($path);
