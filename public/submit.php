<?php
declare(strict_types=1);

/**
 * Quote form endpoint.
 *
 * Answers JSON when the page asks for it (the normal path, via fetch), and
 * falls back to a plain redirect so the form still works without JavaScript.
 */

require_once __DIR__ . '/../app/bootstrap.php';

$wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';

/** Ends the request: JSON for fetch, redirect for a plain form post. */
function respond(bool $ok, string $message, array $errors = [], string $lang = 'en'): never
{
    global $wantsJson;

    if ($wantsJson) {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        http_response_code($ok ? 200 : 422);
        echo json_encode(['ok' => $ok, 'message' => $message, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $base = $lang === 'ar' ? '/ar/' : '/';
    header('Location: ' . $base . ($ok ? '?sent=1' : '?error=1') . '#quote', true, 303);
    exit;
}

$language = ($_POST['language'] ?? 'en') === 'ar' ? 'ar' : 'en';

$say = static fn (string $en, string $ar): string => $language === 'ar' ? $ar : $en;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    respond(false, $say('Please use the form to send your request.', 'يرجى استخدام النموذج لإرسال طلبك.'), [], $language);
}

if (!Security::originLooksValid()) {
    respond(false, $say('Your request could not be verified. Please try again.', 'تعذر التحقق من طلبك. حاول مرة أخرى.'), [], $language);
}

// Silently accept and discard obvious bots, so they do not retry
if (Security::honeypotTripped($_POST) || Security::submittedTooFast($_POST)) {
    respond(true, $say('Thank you. We will be in touch shortly.', 'شكرًا لك. سنتواصل معك قريبًا.'), [], $language);
}

if (Security::rateLimited('quote', 5, 3600)) {
    respond(false, $say(
        'You have sent several requests already. Please call or WhatsApp us instead.',
        'لقد أرسلت عدة طلبات بالفعل. يرجى الاتصال بنا أو مراسلتنا عبر واتساب.'
    ), [], $language);
}

[$clean, $errors] = Lead::validate($_POST);

if ($errors !== []) {
    if ($language === 'ar') {
        $arabic = [
            'name'  => 'يرجى إدخال اسمك.',
            'phone' => 'يرجى إدخال رقم هاتف إماراتي صحيح.',
            'email' => 'البريد الإلكتروني غير صحيح.',
        ];
        foreach ($errors as $field => $_) {
            $errors[$field] = $arabic[$field] ?? $errors[$field];
        }
    }
    respond(false, $say('Please check the highlighted fields.', 'يرجى مراجعة الحقول المحددة.'), $errors, $language);
}

$result = Lead::store($clean);
Mailer::notifyNewLead($clean, $result['stored_in']);

if ($result['stored_in'] === []) {
    error_log('Lead could not be stored anywhere: ' . json_encode($clean, JSON_UNESCAPED_UNICODE));
}

respond(true, $say(
    'Thank you. We have your details and will get back to you shortly.',
    'شكرًا لك. لقد استلمنا بياناتك وسنعاود التواصل معك قريبًا.'
), [], $language);
