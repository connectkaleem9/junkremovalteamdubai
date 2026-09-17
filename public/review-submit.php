<?php
declare(strict_types=1);

/**
 * Customer review endpoint. Publishes straight away (owner's decision), so the
 * spam checks here are the only gate; the admin can hide or delete afterwards.
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/views/View.php';

$lang = ($_POST['language'] ?? 'en') === 'ar' ? 'ar' : 'en';
$ar = $lang === 'ar';
$back = ($ar ? '/ar/reviews/' : '/reviews/');

$wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';

function review_respond(bool $ok, string $message, array $errors, string $back): never
{
    global $wantsJson;

    if ($wantsJson) {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        http_response_code($ok ? 200 : 422);
        echo json_encode(['ok' => $ok, 'message' => $message, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
        exit;
    }

    header('Location: ' . $back . ($ok ? '?posted=1' : '?error=1') . '#review-form', true, 303);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    review_respond(false, $ar ? 'يرجى استخدام النموذج.' : 'Please use the form.', [], $back);
}

if (!Security::originLooksValid()) {
    review_respond(false, $ar ? 'تعذر التحقق من طلبك.' : 'Your request could not be verified.', [], $back);
}

// Bots: accept quietly so they don't retry, but store nothing
if (Security::honeypotTripped($_POST) || Security::submittedTooFast($_POST, 5)) {
    review_respond(true, $ar ? 'شكرًا لك.' : 'Thank you.', [], $back);
}

if (Security::rateLimited('review', Review::MAX_PER_DAY, 86400)) {
    review_respond(false, $ar
        ? 'لقد أرسلت عدة مراجعات اليوم. شكرًا لك!'
        : 'You have already posted a few reviews today. Thank you!', [], $back);
}

[$clean, $errors] = Review::validate($_POST, $lang);

if ($errors !== []) {
    review_respond(false, $ar ? 'يرجى مراجعة الحقول المحددة.' : 'Please check the highlighted fields.', $errors, $back);
}

Review::add($clean);

review_respond(true, $ar
    ? 'شكرًا لك! تم نشر مراجعتك.'
    : 'Thank you! Your review is now on the page.', [], $back);
