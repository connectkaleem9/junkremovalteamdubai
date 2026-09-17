<?php
declare(strict_types=1);

/**
 * Loaded by every PHP entry point in public/.
 *
 * This file lives OUTSIDE the web root: the deploy script copies app/ next to
 * public_html, never inside it, so none of this is reachable over HTTP.
 */

define('APP_ROOT', __DIR__);

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Db.php';
require_once __DIR__ . '/Lead.php';
require_once __DIR__ . '/Mailer.php';
require_once __DIR__ . '/Store.php';
require_once __DIR__ . '/Review.php';
require_once __DIR__ . '/Uploads.php';
require_once __DIR__ . '/Admin.php';

Config::load();

// Never show PHP errors to visitors; log them instead.
error_reporting(E_ALL);
ini_set('display_errors', Config::get('APP_DEBUG') === 'true' ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', Config::storagePath('logs/php-' . date('Y-m') . '.log'));

date_default_timezone_set(Config::get('APP_TIMEZONE', 'Asia/Dubai'));
