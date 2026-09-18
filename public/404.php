<?php
declare(strict_types=1);

/**
 * Branded 404. Served by ErrorDocument in public/.htaccess.
 *
 * It keeps the header, menu and footer so a visitor who lands on a dead link
 * can still reach a service, an area or the phone number instead of leaving.
 * Never redirect a 404 to the homepage (CLAUDE.md §6) — the status has to stay
 * 404 so Google drops the URL instead of indexing a soft 404.
 */

require __DIR__ . '/../app/views/pages.php';

http_response_code(404);

// Arabic visitors land on the Arabic version of the error page
$lang = str_starts_with((string) ($_SERVER['REQUEST_URI'] ?? ''), '/ar/') ? 'ar' : 'en';
$ar = $lang === 'ar';
$b = View::base($lang);
$t = View::t($lang);

View::head([
    'lang' => $lang,
    'path' => '',
    'active' => '',
    'title' => $ar ? 'الصفحة غير موجودة | Junk Removal Team Dubai' : 'Page Not Found | Junk Removal Team Dubai',
    'description' => $ar ? 'الصفحة المطلوبة غير موجودة.' : 'The page you asked for does not exist.',
    'robots' => 'noindex, follow',
]);

View::pageHero($lang, [
    'crumb' => $ar ? 'الصفحة غير موجودة' : 'Page not found',
    'eyebrow' => '404',
    'title' => $ar ? 'لم نعثر على هذه الصفحة' : 'We couldn’t find that page',
    'lead' => $ar
        ? 'ربما تغيّر الرابط أو حُذفت الصفحة. جرّب أحد الروابط بالأسفل أو اتصل بنا مباشرة.'
        : 'The link may have changed or the page may have been removed. Try one of the links below, or just call us.',
]);
?>
  <section class="section">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'روابط قد تفيدك' : 'Where you might be going' ?></h2>
      </div>
      <div class="card-grid">
        <div class="info-card">
          <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-truck"/></svg></span>
          <h3><a href="<?= $b ?>services/"><?= View::e($t['nav']['services']) ?></a></h3>
          <p><?= $ar ? 'كل خدمات الإزالة والإخلاء التي نقدمها.' : 'Every removal and clearance service we offer.' ?></p>
        </div>
        <div class="info-card">
          <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg></span>
          <h3><a href="<?= $b ?>areas/"><?= View::e($t['nav']['areas']) ?></a></h3>
          <p><?= $ar ? 'المناطق التي نعمل فيها في دبي.' : 'The parts of Dubai we work in.' ?></p>
        </div>
        <div class="info-card">
          <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg></span>
          <h3><a href="<?= $b ?>contact-us/"><?= View::e($t['contact']) ?></a></h3>
          <p><?= $ar ? 'اتصل بنا أو راسلنا عبر واتساب للحصول على سعر.' : 'Call or WhatsApp us for a price.' ?></p>
        </div>
      </div>
    </div>
  </section>
<?php
View::ctaBand($lang, $b . 'contact-us/');
View::foot($lang);
