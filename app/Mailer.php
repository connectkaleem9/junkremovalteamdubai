<?php
declare(strict_types=1);

/**
 * Sends the lead notification to the business.
 *
 * Uses PHP mail() (the host provides hsendmail). The From address must belong
 * to this domain or the host will refuse it; the customer's address goes in
 * Reply-To so hitting reply answers the customer directly.
 */
final class Mailer
{
    public static function notifyNewLead(array $lead, array $stored): bool
    {
        $to = Config::get('LEAD_NOTIFY_EMAIL', 'contact.junkremovalteam@gmail.com');
        $from = Config::get('MAIL_FROM_ADDRESS', 'noreply@junkremovalteamdubai.com');
        $fromName = Config::get('MAIL_FROM_NAME', 'Junk Removal Team Dubai');

        $subject = sprintf(
            'New website lead: %s%s',
            $lead['name'] !== '' ? $lead['name'] : 'unnamed',
            $lead['service'] !== '' ? ' - ' . $lead['service'] : ''
        );

        $lines = [
            'A new enquiry came in from the website.',
            '',
            'Name:            ' . $lead['name'],
            'Phone:           ' . $lead['phone'],
            'Email:           ' . ($lead['email'] !== '' ? $lead['email'] : '-'),
            'Service:         ' . ($lead['service'] !== '' ? $lead['service'] : '-'),
            'Area:            ' . ($lead['area'] !== '' ? $lead['area'] : '-'),
            'Language:        ' . ($lead['language'] === 'ar' ? 'Arabic' : 'English'),
            '',
            'Message:',
            $lead['message'] !== '' ? $lead['message'] : '(none)',
            '',
            '--- where it came from ---',
            'Landing page:    ' . ($lead['landing_page'] !== '' ? $lead['landing_page'] : '-'),
            'Referrer:        ' . ($lead['referrer'] !== '' ? $lead['referrer'] : '-'),
            'Campaign:        ' . ($lead['utm_campaign'] !== '' ? $lead['utm_campaign'] : '-'),
            'Source / medium: ' . trim(($lead['utm_source'] ?: '-') . ' / ' . ($lead['utm_medium'] ?: '-')),
            'Google click id: ' . ($lead['gclid'] !== '' ? $lead['gclid'] : '-'),
            '',
            'Received:        ' . date('D, d M Y H:i') . ' (Dubai time)',
            'Saved in:        ' . (empty($stored) ? 'NOWHERE - check the server logs' : implode(' + ', $stored)),
        ];

        $headers = [
            'From: ' . self::encodeName($fromName) . ' <' . $from . '>',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
            'X-Mailer: junkremovalteamdubai.com',
        ];

        if ($lead['email'] !== '' && filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . self::encodeName($lead['name']) . ' <' . $lead['email'] . '>';
        }

        $sent = @mail(
            $to,
            self::encodeSubject($subject),
            implode("\r\n", $lines),
            implode("\r\n", $headers),
            '-f' . $from
        );

        if (!$sent) {
            error_log('Lead notification email failed for ' . $lead['phone']);
        }

        return $sent;
    }

    private static function encodeSubject(string $text): string
    {
        return preg_match('/[\x80-\xFF]/', $text) === 1
            ? '=?UTF-8?B?' . base64_encode($text) . '?='
            : $text;
    }

    private static function encodeName(string $name): string
    {
        $name = str_replace(['"', "\r", "\n"], '', $name);

        return preg_match('/[\x80-\xFF]/', $name) === 1
            ? '=?UTF-8?B?' . base64_encode($name) . '?='
            : '"' . $name . '"';
    }
}
