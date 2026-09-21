<?php
declare(strict_types=1);

/**
 * Page bodies shared by the English and Arabic entry points.
 * Each public/<page>/index.php only sets the language and calls one of these.
 */

require_once __DIR__ . '/View.php';
require_once __DIR__ . '/../bootstrap.php';

function render_reviews_page(string $lang): void
{
    $ar = $lang === 'ar';
    $reviews = Store::published('reviews');
    $average = Review::averageRating($reviews);

    View::head([
        'lang' => $lang,
        'path' => 'reviews/',
        'active' => 'reviews',
        'title' => $ar ? 'آراء العملاء | Junk Removal Team Dubai' : 'Customer Reviews | Junk Removal Team Dubai',
        'description' => $ar
            ? 'اقرأ آراء عملائنا في دبي عن خدمات إزالة المخلفات والإخلاء، وشاركنا تجربتك أنت أيضًا.'
            : 'Read what customers in Dubai say about our junk removal and clearance work — and share your own experience.',
    ]);

    View::pageHero($lang, [
        'crumb' => $ar ? 'آراء العملاء' : 'Reviews',
        'eyebrow' => $ar ? 'آراء العملاء' : 'Reviews',
        'title' => $ar ? 'ماذا يقول عملاؤنا' : 'What our customers say',
        'lead' => $ar
            ? 'مراجعات كتبها عملاء بعد انتهاء العمل. إذا تعاملت معنا، يسعدنا أن تشاركنا رأيك.'
            : 'Reviews written by customers after the job was done. If we have worked for you, we’d be glad to hear how it went.',
    ]);
    ?>
  <section class="section">
    <div class="container">
<?php if ($reviews !== []): ?>
      <div class="review-summary">
        <div class="stars" role="img" aria-label="<?= View::e(($average ?? 0) . ($ar ? ' من 5' : ' out of 5')) ?>">
<?php for ($i = 1; $i <= 5; $i++): ?>
          <svg class="icon<?= $i <= round((float) $average) ? '' : ' is-empty' ?>" aria-hidden="true"><use href="#i-star"/></svg>
<?php endfor; ?>
        </div>
        <p><strong><span class="ltr"><?= View::e((string) $average) ?></span></strong>
          <?= $ar ? 'من 5 — بناءً على' : 'out of 5 — based on' ?>
          <span class="ltr"><?= count($reviews) ?></span>
          <?= $ar ? 'مراجعة على هذا الموقع' : ('review' . (count($reviews) === 1 ? '' : 's') . ' left on this site') ?></p>
      </div>

      <div class="review-grid">
<?php foreach ($reviews as $r):
        $rating = (int) ($r['rating'] ?? 5); ?>
        <article class="review-card"<?= ($r['lang'] ?? 'en') !== $lang ? ' lang="' . View::e((string) $r['lang']) . '" dir="' . (($r['lang'] ?? '') === 'ar' ? 'rtl' : 'ltr') . '"' : '' ?>>
          <div class="stars" role="img" aria-label="<?= $rating ?><?= $ar ? ' من 5' : ' out of 5' ?>">
<?php for ($i = 1; $i <= 5; $i++): ?>
            <svg class="icon<?= $i <= $rating ? '' : ' is-empty' ?>" aria-hidden="true"><use href="#i-star"/></svg>
<?php endfor; ?>
          </div>
          <blockquote><?= View::e((string) $r['text']) ?></blockquote>
          <div class="reviewer">
            <span class="avatar" aria-hidden="true"><?= View::e(Review::initials((string) $r['name'])) ?></span>
            <div>
              <strong><?= View::e((string) $r['name']) ?></strong>
              <span><?= View::e(trim(((string) ($r['area'] ?? '')) . (($r['area'] ?? '') && ($r['service'] ?? '') ? ' · ' : '') . ((string) ($r['service'] ?? '')))) ?></span>
            </div>
          </div>
          <p class="review-source"><?= View::e(date('j M Y', strtotime((string) ($r['created_at'] ?? 'now')))) ?></p>
        </article>
<?php endforeach; ?>
      </div>
<?php else: ?>
      <div class="empty-state">
        <p><?= $ar
            ? 'لا توجد مراجعات على الموقع بعد. إذا تعاملت معنا، كن أول من يشاركنا رأيه.'
            : 'No reviews on the site yet. If we have worked for you, be the first to leave one.' ?></p>
      </div>
<?php endif; ?>
    </div>
  </section>

  <section class="section section-light" id="review-form">
    <div class="container split">
      <div class="section-intro">
        <h2><?= $ar ? 'شاركنا تجربتك' : 'Leave a review' ?></h2>
        <p><?= $ar
            ? 'رأيك يساعد غيرك على اتخاذ القرار. تُنشر المراجعة على هذه الصفحة فور إرسالها.'
            : 'Your experience helps the next person decide. Reviews appear on this page as soon as you send them.' ?></p>
        <ul class="tick-list">
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= $ar ? 'اكتب عن الخدمة التي تلقيتها فعلًا' : 'Please write about work we actually did for you' ?></li>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= $ar ? 'يظهر اسمك كما تكتبه' : 'Your name appears exactly as you type it' ?></li>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= $ar ? 'لا تُنشر بيانات الاتصال' : 'No contact details are published' ?></li>
        </ul>
      </div>

      <div class="quote-card">
        <h2><?= $ar ? 'اكتب مراجعتك' : 'Write your review' ?></h2>
        <p class="sub"><?= $ar ? 'تستغرق أقل من دقيقة.' : 'It takes less than a minute.' ?></p>
        <form data-review-form action="/review-submit.php" method="post" novalidate>
          <input type="hidden" name="language" value="<?= $lang ?>">
          <div class="hp" aria-hidden="true"><label for="r-website">Website</label><input id="r-website" name="website" tabindex="-1" autocomplete="off"></div>

          <div class="form-row">
            <div>
              <label class="field-label" for="r-name"><?= $ar ? 'الاسم' : 'Your name' ?></label>
              <input class="input" id="r-name" name="name" placeholder="<?= $ar ? 'الاسم *' : 'Your name *' ?>" autocomplete="name" required>
            </div>
          </div>

          <fieldset class="rating-input">
            <legend><?= $ar ? 'تقييمك' : 'Your rating' ?></legend>
<?php for ($i = 1; $i <= 5; $i++): ?>
            <input type="radio" id="r-star-<?= $i ?>" name="rating" value="<?= $i ?>"<?= $i === 5 ? ' checked' : '' ?>>
            <label for="r-star-<?= $i ?>"><span class="ltr"><?= $i ?></span> <svg class="icon" aria-hidden="true"><use href="#i-star"/></svg></label>
<?php endfor; ?>
          </fieldset>

          <div class="form-row two">
            <div>
              <label class="field-label" for="r-service"><?= $ar ? 'الخدمة' : 'Service' ?></label>
              <select class="select" id="r-service" name="service">
                <option value=""><?= $ar ? 'الخدمة (اختياري)' : 'Service (optional)' ?></option>
<?php foreach (View::services($lang) as $s): ?>
                <option value="<?= View::e($s['value']) ?>"><?= View::e($s['title']) ?></option>
<?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="field-label" for="r-area"><?= $ar ? 'المنطقة' : 'Area' ?></label>
              <select class="select" id="r-area" name="area">
                <option value=""><?= $ar ? 'المنطقة (اختياري)' : 'Area (optional)' ?></option>
<?php foreach (View::areas($lang) as $value => $label): ?>
                <option value="<?= View::e($value) ?>"><?= View::e($label) ?></option>
<?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div>
              <label class="field-label" for="r-text"><?= $ar ? 'مراجعتك' : 'Your review' ?></label>
              <textarea class="textarea" id="r-text" name="text" rows="5" placeholder="<?= $ar ? 'كيف كانت تجربتك معنا؟' : 'How did the job go?' ?>" required></textarea>
            </div>
          </div>

          <button class="btn btn-green btn-block" type="submit"><?= $ar ? 'انشر المراجعة' : 'Post review' ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></button>
          <div class="form-success" data-form-success hidden><?= $ar ? 'شكرًا لك! تم نشر مراجعتك.' : 'Thank you! Your review is now on the page.' ?></div>
        </form>
      </div>
    </div>
  </section>
<?php
    View::ctaBand($lang, View::base($lang) . 'contact-us/#quote');
    View::foot($lang);
}

function render_areas_hub_page(string $lang): void
{
    $ar = $lang === 'ar';
    $areas = View::areaDetail($lang);
    $b = View::base($lang);

    View::head([
        'lang' => $lang,
        'path' => 'areas/',
        'active' => 'areas',
        'title' => $ar ? 'المناطق التي نخدمها في دبي | Junk Removal Team Dubai' : 'Areas We Serve in Dubai | Junk Removal Team Dubai',
        'description' => $ar
            ? 'نقدم خدمات إزالة المخلفات والإخلاء في مختلف مناطق دبي — القوز والبرشاء وجميرا ودبي مارينا والخليج التجاري ووسط المدينة وقرية جميرا الدائرية.'
            : 'Junk removal and clearance across Dubai — Al Quoz, Al Barsha, Jumeirah, Dubai Marina, Business Bay, Downtown Dubai and JVC.',
    ]);

    View::pageHero($lang, [
        'crumb' => $ar ? 'المناطق' : 'Areas We Serve',
        'eyebrow' => $ar ? 'مناطق الخدمة' : 'Service Areas',
        'title' => $ar ? 'المناطق التي نخدمها في دبي' : 'Areas we serve in Dubai',
        'lead' => $ar
            ? 'نعمل في مختلف أنحاء دبي. اختر منطقتك لمعرفة الأعمال التي نقوم بها هناك عادةً.'
            : 'We work across Dubai. Pick your area to see the kind of jobs we usually do there.',
    ]);
    ?>
  <section class="section">
    <div class="container">
      <!-- VERIFY F1: confirm each area is genuinely covered -->
      <div class="card-grid">
<?php foreach ($areas as $slug => $area): ?>
        <a class="info-card area-card" href="<?= $b ?>areas/<?= View::e($slug) ?>/">
          <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg></span>
          <h3><?= View::e($area['name']) ?></h3>
          <p><?= View::e($area['blurb']) ?></p>
          <span class="more"><?= $ar ? 'التفاصيل' : 'See details' ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></span>
        </a>
<?php endforeach; ?>
      </div>
      <p class="area-ask">
        <a href="<?= View::e(View::whatsappLink($ar ? 'مرحبًا، هل تخدمون منطقتي؟' : 'Hi, do you cover my area?')) ?>" data-track="whatsapp_click">
          <?= $ar ? 'لا تجد منطقتك؟ اسألنا عبر واتساب' : 'Don’t see your area? Ask us on WhatsApp' ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg>
        </a>
      </p>
    </div>
  </section>
<?php
    View::ctaBand($lang, $b . 'contact-us/#quote');
    View::foot($lang);
}

function render_area_page(string $lang, string $slug): void
{
    $ar = $lang === 'ar';
    $area = View::areaDetail($lang, $slug);

    if ($area === null) {
        http_response_code(404);
        echo 'Area not found';
        return;
    }

    $b = View::base($lang);
    $t = View::t($lang);
    $name = $area['name'];
    $title = $ar ? 'إزالة المخلفات في ' . $name : 'Junk Removal in ' . $name;

    // A few area names are long enough to push the title past the ~60 characters
    // Google shows, so those pages use a shorter, well-known form in the <title>
    // only — the H1 and the page still use the full name.
    $shortName = [
        'jvc' => $ar ? 'قرية جميرا الدائرية' : 'JVC',
        'jumeirah-lake-towers' => $ar ? 'أبراج بحيرات جميرا' : 'JLT',
        'dubai-silicon-oasis' => $ar ? 'واحة دبي للسيليكون' : 'Silicon Oasis',
        'dubai-hills-estate' => $ar ? 'دبي هيلز' : 'Dubai Hills',
    ][$slug] ?? $name;
    $metaTitle = ($ar ? 'إزالة المخلفات في ' . $shortName : 'Junk Removal in ' . $shortName)
        . ' | Junk Removal Team Dubai';

    View::head([
        'lang' => $lang,
        'path' => 'areas/' . $slug . '/',
        'active' => 'areas',
        'title' => $metaTitle,
        'description' => $ar
            ? 'خدمات إزالة المخلفات وإخلاء المنازل والمكاتب في ' . $name . '، دبي. اتصل بنا أو راسلنا عبر واتساب للحصول على سعر.'
            : 'Junk removal, furniture removal and property clearance in ' . $name . ', Dubai. Call or WhatsApp us for a price.',
    ]);

    View::pageHero($lang, [
        'crumb' => $name,
        'parents' => [$t['nav']['areas'] => $b . 'areas/'],
        'eyebrow' => $ar ? 'مناطق الخدمة' : 'Service Areas',
        'title' => $title,
        'lead' => $area['blurb'],
    ]);
    ?>
  <section class="section">
    <div class="container split center">
      <div class="section-intro">
        <h2><?= $ar ? 'ما الذي نقوم به في ' . View::e($name) : 'What we do in ' . View::e($name) ?></h2>
        <p><?= $ar
            ? 'نتولى إزالة الأثاث والأجهزة والأغراض غير المرغوب فيها من العقارات في ' . View::e($name) . '. أخبرنا بما تريد التخلص منه، ونتفق على السعر قبل البدء، ثم نتولى الحمل والتحميل والنقل.'
            : 'We remove unwanted furniture, appliances and general junk from properties in ' . View::e($name) . '. Tell us what needs to go, we agree the price before starting, then we carry, load and take it away.' ?></p>
        <ul class="tick-list">
<?php foreach ($area['typical'] as $item): ?>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
        </ul>
        <div class="btn-group">
          <a class="btn btn-wa" href="<?= View::e(View::whatsappLink($ar ? 'مرحبًا، أحتاج إلى خدمة في ' . $name : 'Hi, I need a job done in ' . $name)) ?>" data-track="whatsapp_click">
            <svg class="icon" aria-hidden="true"><use href="#i-wa"/></svg><?= View::e($t['whatsapp_us']) ?>
          </a>
          <a class="btn btn-outline" href="tel:<?= View::PHONE_TEL ?>" data-track="phone_click">
            <svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg><span class="ltr"><?= View::PHONE_DISPLAY ?></span>
          </a>
        </div>
      </div>
      <div class="svc-media is-photo">
        <img src="/assets/images/areas-bg.jpg" alt="<?= $ar ? 'شاحنة Junk Removal Team Dubai أثناء العمل في دبي' : 'Junk Removal Team Dubai truck on a job in Dubai' ?>" width="1800" height="600" loading="lazy" decoding="async">
      </div>
    </div>
  </section>

  <section class="section section-light">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'الخدمات المتاحة في ' . View::e($name) : 'Services available in ' . View::e($name) ?></h2>
      </div>
      <div class="card-grid">
<?php foreach (View::services($lang) as $s): ?>
        <a class="info-card area-card" href="<?= $b ?>services/<?= View::e($s['slug']) ?>/">
          <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#<?= View::e($s['icon']) ?>"/></svg></span>
          <h3><?= View::e($s['title']) ?></h3>
          <p><?= View::e(mb_strimwidth($s['text'], 0, 95, '…')) ?></p>
          <span class="more"><?= $ar ? 'التفاصيل' : 'See details' ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></span>
        </a>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'طريقة العمل' : 'How it works' ?></h2>
      </div>
      <ol class="steps">
<?php foreach (View::steps($lang) as [$stepTitle, $stepText]): ?>
        <li class="step"><h3><?= View::e($stepTitle) ?></h3><p><?= View::e($stepText) ?></p></li>
<?php endforeach; ?>
      </ol>
    </div>
  </section>

  <section class="section section-light">
    <div class="container split center">
      <div class="section-intro">
        <h2><?= $ar ? 'احصل على سعر في ' . View::e($name) : 'Get a price in ' . View::e($name) ?></h2>
        <p><?= $ar
            ? 'أرسل لنا التفاصيل وسنعاود التواصل معك بالسعر.'
            : 'Send us the details and we’ll come back to you with a price.' ?></p>
        <ul class="tick-list">
<?php foreach (View::fasterQuote($lang) as $item): ?>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
        </ul>
      </div>
<?php View::quoteForm($lang); ?>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'مناطق أخرى نخدمها' : 'Other areas we serve' ?></h2>
      </div>
      <ul class="areas-chips" style="justify-content:flex-start">
<?php foreach (View::areaDetail($lang) as $otherSlug => $other):
        if ($otherSlug === $slug) { continue; } ?>
        <li><a href="<?= $b ?>areas/<?= View::e($otherSlug) ?>/"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><?= View::e($other['name']) ?></a></li>
<?php endforeach; ?>
      </ul>
    </div>
  </section>
<?php
    View::ctaBand($lang, '#quote');
    View::foot($lang);
}

function render_projects_page(string $lang): void
{
    $ar = $lang === 'ar';
    $projects = Store::published('projects');

    View::head([
        'lang' => $lang,
        'path' => 'projects/',
        'active' => 'projects',
        'title' => $ar ? 'مشاريعنا | Junk Removal Team Dubai' : 'Our Projects | Junk Removal Team Dubai',
        'description' => $ar
            ? 'صور قبل وبعد من أعمال إزالة المخلفات وإخلاء المنازل والمكاتب التي نفذناها في مناطق مختلفة من دبي.'
            : 'Before and after photos from junk removal and clearance jobs we have completed across Dubai.',
    ]);

    View::pageHero($lang, [
        'crumb' => $ar ? 'المشاريع' : 'Projects',
        'eyebrow' => $ar ? 'المشاريع' : 'Projects',
        'title' => $ar ? 'أعمالنا الأخيرة' : 'Our recent work',
        'lead' => $ar
            ? 'صور من مهام نفذناها فعليًا في دبي — قبل وبعد.'
            : 'Photos from jobs we have actually carried out in Dubai — before and after.',
    ]);
    ?>
  <section class="section">
    <div class="container">
<?php if ($projects !== []): ?>
      <div class="project-list">
<?php foreach ($projects as $p):
        $title = $ar ? ($p['title_ar'] ?? $p['title_en'] ?? '') : ($p['title_en'] ?? $p['title_ar'] ?? '');
        $summary = $ar ? ($p['summary_ar'] ?? '') : ($p['summary_en'] ?? '');
        $meta = array_filter([
            (string) ($p['area'] ?? ''),
            (string) ($p['service'] ?? ''),
            !empty($p['date']) ? date('F Y', strtotime((string) $p['date'])) : '',
        ]); ?>
        <article class="project-item">
<?php if (!empty($p['image'])): ?>
          <!-- One photo that already contains the before/after split -->
          <div class="project-photo-single">
            <img src="<?= View::e(Uploads::publicUrl((string) $p['image'])) ?>" alt="<?= View::e($title) ?>" loading="lazy" decoding="async">
          </div>
<?php else: ?>
          <div class="ba-pair">
<?php if (!empty($p['before'])): ?>
            <figure>
              <img src="<?= View::e(Uploads::publicUrl((string) $p['before'])) ?>" alt="<?= View::e($title) ?> — <?= $ar ? 'قبل' : 'before' ?>" loading="lazy" decoding="async">
              <figcaption><?= $ar ? 'قبل' : 'Before' ?></figcaption>
            </figure>
<?php endif; ?>
<?php if (!empty($p['after'])): ?>
            <figure>
              <img src="<?= View::e(Uploads::publicUrl((string) $p['after'])) ?>" alt="<?= View::e($title) ?> — <?= $ar ? 'بعد' : 'after' ?>" loading="lazy" decoding="async">
              <figcaption class="is-after"><?= $ar ? 'بعد' : 'After' ?></figcaption>
            </figure>
<?php endif; ?>
          </div>
<?php endif; ?>
          <div class="project-body">
            <h2><?= View::e($title) ?></h2>
<?php if ($meta !== []): ?>
            <p class="project-meta"><?= View::e(implode(' · ', $meta)) ?></p>
<?php endif; ?>
<?php if ($summary !== ''): ?>
            <p><?= View::e($summary) ?></p>
<?php endif; ?>
          </div>
        </article>
<?php endforeach; ?>
      </div>
<?php else: ?>
      <div class="empty-state">
        <p><?= $ar
            ? 'لم نضف صور المشاريع بعد. تواصل معنا وسنخبرك بأعمال مشابهة لما تحتاجه.'
            : 'We haven’t added project photos yet. Get in touch and we’ll tell you about similar jobs we’ve done.' ?></p>
        <a class="btn btn-teal" href="<?= View::base($lang) ?>contact-us/#quote"><?= $ar ? 'تواصل معنا' : 'Contact us' ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></a>
      </div>
<?php endif; ?>
    </div>
  </section>
<?php
    View::ctaBand($lang, View::base($lang) . 'contact-us/#quote');
    View::foot($lang);
}

function render_services_page(string $lang): void
{
    $ar = $lang === 'ar';

    View::head([
        'lang' => $lang,
        'path' => 'services/',
        'active' => 'services',
        'title' => $ar
            // Kept under ~60 characters so Google does not truncate it in results
            ? 'خدمات إزالة المخلفات في دبي | Junk Removal Team Dubai'
            : 'Junk Removal Services in Dubai | Junk Removal Team Dubai',
        'description' => $ar
            ? 'إزالة المخلفات ونقل الأثاث وإخلاء المنازل والمكاتب ومخلفات البناء والنفايات الإلكترونية في دبي. تعرّف على كل خدمة واحصل على عرض سعر.'
            : 'Junk removal, furniture removal, house and office clearance, construction waste and e-waste collection across Dubai. See what each service covers and get a quote.',
    ]);

    View::pageHero($lang, [
        'crumb' => $ar ? 'الخدمات' : 'Services',
        'eyebrow' => $ar ? 'خدماتنا' : 'Our Services',
        'title' => $ar ? 'خدمات إزالة المخلفات والإخلاء في دبي' : 'Junk Removal & Clearance Services in Dubai',
        'lead' => $ar
            ? 'من كنبة واحدة إلى إخلاء فيلا أو مكتب بالكامل — اختر الخدمة التي تحتاجها، ونتولى نحن الحمل والتحميل والتخلص من الأغراض.'
            : 'From a single sofa to a full villa or office clearance — pick the service you need and we’ll handle the lifting, loading and disposal.',
    ]);
    ?>
  <section class="section">
    <div class="container">
<?php foreach (View::services($lang) as $i => $s): ?>
      <article class="svc-row" id="<?= View::e($s['slug']) ?>">
        <div class="svc-media">
          <img src="/assets/images/<?= View::e($s['image']) ?>?v=<?= View::ASSET_VERSION ?>" alt="<?= View::e($s['alt']) ?>" width="760" height="475"<?= $i === 0 ? '' : ' loading="lazy"' ?> decoding="async">
        </div>
        <div class="svc-body">
          <h2><svg class="icon" aria-hidden="true"><use href="#<?= View::e($s['icon']) ?>"/></svg><?= View::e($s['title']) ?></h2>
          <p><?= View::e($s['text']) ?></p>
          <ul class="tick-list cols">
<?php foreach ($s['list'] as $item): ?>
            <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
          </ul>
<?php if (!empty($s['note'])): ?>
          <p class="svc-note"><?= View::e($s['note']) ?></p>
<?php endif; ?>
          <div class="btn-group">
            <a class="btn btn-teal" href="<?= View::base($lang) ?>services/<?= View::e($s['slug']) ?>/">
              <?= $ar ? 'تفاصيل الخدمة' : 'See full details' ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg>
            </a>
            <a class="btn btn-outline" href="<?= View::e(View::whatsappLink($s['wa_text'], $lang)) ?>" data-track="whatsapp_click">
              <svg class="icon" aria-hidden="true"><use href="#i-wa"/></svg><?= View::e($s['ask']) ?>
            </a>
          </div>
        </div>
      </article>
<?php endforeach; ?>
    </div>
  </section>

  <section class="section section-light">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'طريقة العمل' : 'How it works' ?></h2>
        <p><?= $ar ? 'أربع خطوات بسيطة من أول رسالة حتى إخلاء المكان.' : 'Four simple steps from your first message to a cleared space.' ?></p>
      </div>
      <ol class="steps">
<?php foreach (View::steps($lang) as [$title, $text]): ?>
        <li class="step"><h3><?= View::e($title) ?></h3><p><?= View::e($text) ?></p></li>
<?php endforeach; ?>
      </ol>
    </div>
  </section>

  <section class="section">
    <div class="container split center">
      <div class="section-intro">
        <h2><?= $ar ? 'احصل على سعر لمهمتك' : 'Get a price for your job' ?></h2>
        <p><?= $ar
            ? 'أرسل لنا بعض التفاصيل وسنعاود التواصل معك بالسعر. يمكنك أيضًا إرسال الصور عبر واتساب.'
            : 'Send us a few details and we’ll come back to you with a price. You can also send photos on WhatsApp.' ?></p>
        <ul class="tick-list">
<?php foreach (View::fasterQuote($lang) as $item): ?>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
        </ul>
      </div>
<?php View::quoteForm($lang); ?>
    </div>
  </section>
<?php
    View::ctaBand($lang, '#quote');
    View::foot($lang);
}

function render_service_detail_page(string $lang, string $slug): void
{
    $ar = $lang === 'ar';
    $s = View::serviceDetail($lang, $slug);

    if ($s === null) {
        http_response_code(404);
        echo 'Service not found';
        return;
    }

    $b = View::base($lang);
    $t = View::t($lang);
    // The homepage already targets "Junk Removal in Dubai" (docs/02-keyword-map D1),
    // so this one page says "Service" to keep the two from competing for the same
    // query with the same title and the same H1.
    $inDubai = $slug === 'junk-removal'
        ? ($ar ? 'خدمة إزالة المخلفات في دبي' : 'Junk Removal Service in Dubai')
        : ($ar ? $s['title'] . ' في دبي' : $s['title'] . ' in Dubai');

    View::head([
        'lang' => $lang,
        'path' => 'services/' . $slug . '/',
        'active' => 'services',
        'title' => $inDubai . ' | Junk Removal Team Dubai',
        'description' => mb_strimwidth($s['text'], 0, 155, '…'),
        'service' => $s, // drives the Service + FAQPage schema in View::head()
    ]);

    View::pageHero($lang, [
        'crumb' => $s['title'],
        'parents' => [$t['nav']['services'] => $b . 'services/'],
        'eyebrow' => $t['nav']['services'],
        'title' => $inDubai,
        'lead' => $s['text'],
    ]);
    ?>
  <section class="section">
    <div class="container split center">
      <div class="section-intro">
        <h2><?= $ar ? 'ما الذي تشمله الخدمة' : 'What the service covers' ?></h2>
<?php foreach ($s['intro'] as $paragraph): ?>
        <p><?= View::e($paragraph) ?></p>
<?php endforeach; ?>
        <ul class="tick-list">
<?php foreach ($s['includes'] as $item): ?>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
        </ul>
<?php if (!empty($s['note'])): ?>
        <p class="svc-note"><?= View::e($s['note']) ?></p>
<?php endif; ?>
      </div>
      <div class="svc-media">
        <img src="/assets/images/<?= View::e($s['image']) ?>?v=<?= View::ASSET_VERSION ?>" alt="<?= View::e($s['alt']) ?>" width="760" height="475" decoding="async">
      </div>
    </div>
  </section>

  <section class="section section-light">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'ما الذي نأخذه' : 'What we take' ?></h2>
        <!-- VERIFY D1: only items the business genuinely accepts -->
      </div>
      <ul class="items-grid">
<?php foreach ($s['list'] as $item): ?>
        <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
      </ul>
      <div class="section-intro" style="margin-block-start:2.25rem">
        <h2><?= $ar ? 'لمن هذه الخدمة' : 'Who it’s for' ?></h2>
      </div>
      <ul class="chip-list">
<?php foreach ($s['good_for'] as $who): ?>
        <li class="chip"><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($who) ?></li>
<?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'طريقة العمل' : 'How it works' ?></h2>
      </div>
      <ol class="steps">
<?php foreach (View::steps($lang) as [$title, $text]): ?>
        <li class="step"><h3><?= View::e($title) ?></h3><p><?= View::e($text) ?></p></li>
<?php endforeach; ?>
      </ol>
    </div>
  </section>

  <section class="section section-light">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'أسئلة شائعة' : 'Common questions' ?></h2>
      </div>
      <div class="faq-list">
<?php foreach ($s['faqs'] as [$question, $answer]): ?>
        <details class="faq">
          <summary><?= View::e($question) ?></summary>
          <div class="faq-body"><p><?= View::e($answer) ?></p></div>
        </details>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'خدمات ذات صلة' : 'Related services' ?></h2>
      </div>
      <div class="card-grid">
<?php foreach ($s['related'] as $relatedSlug):
        $r = View::serviceDetail($lang, $relatedSlug);
        if ($r === null) { continue; } ?>
        <a class="card-service" href="<?= $b ?>services/<?= View::e($r['slug']) ?>/">
          <div class="thumb"><img src="/assets/images/<?= View::e($r['image']) ?>?v=<?= View::ASSET_VERSION ?>" alt="" width="760" height="475" loading="lazy" decoding="async"></div>
          <div class="body">
            <div class="title-row"><svg class="icon" aria-hidden="true"><use href="#<?= View::e($r['icon']) ?>"/></svg><h3><?= View::e($r['title']) ?></h3></div>
            <p><?= View::e(mb_strimwidth($r['text'], 0, 90, '…')) ?></p>
            <span class="card-go"><svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></span>
          </div>
        </a>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section-light">
    <div class="container split center">
      <div class="section-intro">
        <h2><?= $ar ? 'احصل على سعر لهذه الخدمة' : 'Get a price for this job' ?></h2>
        <p><?= $ar
            ? 'أرسل لنا التفاصيل وسنعاود التواصل معك بالسعر. يمكنك أيضًا إرسال الصور عبر واتساب.'
            : 'Send us the details and we’ll come back to you with a price. You can also send photos on WhatsApp.' ?></p>
        <ul class="tick-list">
<?php foreach (View::fasterQuote($lang) as $item): ?>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
        </ul>
      </div>
<?php View::quoteForm($lang); ?>
    </div>
  </section>
<?php
    View::ctaBand($lang, '#quote');
    View::foot($lang);
}

function render_about_page(string $lang): void
{
    $ar = $lang === 'ar';
    $brand = '<bdi lang="en" dir="ltr">Junk Removal Team Dubai</bdi>';

    View::head([
        'lang' => $lang,
        'path' => 'about-us/',
        'active' => 'about',
        'title' => $ar ? 'من نحن | Junk Removal Team Dubai' : 'About Us | Junk Removal Team Dubai',
        'description' => $ar
            ? 'تعرّف على Junk Removal Team Dubai: فريق لإزالة المخلفات مقره القوز، يُخلي المنازل والمكاتب والمساحات التجارية في جميع أنحاء دبي.'
            : 'Meet Junk Removal Team Dubai: a junk removal team based in Al Quoz, clearing homes, offices and commercial spaces across Dubai.',
    ]);

    View::pageHero($lang, [
        'crumb' => $ar ? 'من نحن' : 'About Us',
        'eyebrow' => $ar ? 'من نحن' : 'About Us',
        'title_html' => $ar ? 'عن ' . $brand : 'About ' . $brand,
        'lead' => $ar
            ? 'فريق لإزالة المخلفات في دبي مقره منطقة القوز، يُخلي المنازل والمكاتب والمساحات التجارية في جميع أنحاء المدينة.'
            : 'A Dubai junk removal team based in Al Quoz, clearing homes, offices and commercial spaces across the city.',
    ]);

    $ticks = $ar
        ? ['سعر واضح نتفق عليه قبل البدء', 'نتولى كل أعمال الحمل والتحميل', 'المنازل والمكاتب والمساحات التجارية', 'سهولة التواصل عبر الهاتف وواتساب']
        : ['A clear price agreed before we start', 'We do all the lifting and loading', 'Homes, offices and commercial spaces', 'Easy to reach by phone and WhatsApp'];

    // VERIFY E2: "price agreed upfront" must match how the business really quotes.
    $values = $ar
        ? [
            ['i-phone', 'تواصل واضح', 'نوضح لك ما نأخذه وما يؤثر في السعر وموعد وصولنا.'],
            ['i-shield', 'سعر متفق عليه مسبقًا', 'تعرف السعر قبل حجز العمل، بلا مفاجآت يوم التنفيذ.'],
            ['i-truck', 'نتولى الأعمال الشاقة', 'يحمل فريقنا الأغراض من غرفك ويحمّلها في الشاحنة وينقل كل شيء.'],
            ['i-home', 'نحافظ على مكانك', 'نعمل بعناية داخل منزلك ومبناك ومع مراعاة الجيران.'],
        ]
        : [
            ['i-phone', 'Clear communication', 'We tell you what we can take, what affects the price and when we’ll arrive.'],
            ['i-shield', 'Price agreed upfront', 'You know the price before the job is booked, so there are no surprises on the day.'],
            ['i-truck', 'We do the heavy lifting', 'Our team carries items out of your rooms, loads the truck and takes everything away.'],
            ['i-home', 'Care for your space', 'We work carefully around your home, your building and your neighbours.'],
        ];
    ?>
  <section class="section">
    <div class="container split center">
      <div class="section-intro">
        <h2><?= $ar ? 'من نحن' : 'Who we are' ?></h2>
        <!-- VERIFY A1 / A2: business name and Al Quoz base -->
        <p><?= $ar
            ? 'تتخلص ' . $brand . ' من الأثاث والأجهزة والأغراض المنزلية والمكتبية غير المرغوب فيها من الشقق والفلل والمكاتب والمحلات في جميع أنحاء دبي. نتولى الحمل والتحميل والنقل حتى لا تضطر إلى ذلك بنفسك.'
            : $brand . ' removes unwanted furniture, appliances and household or office junk from apartments, villas, offices and shops across Dubai. We handle the lifting, loading and taking away, so you don’t have to.' ?></p>
        <p><?= $ar
            ? 'مقرنا في القوز، ونعمل مع أصحاب المنازل والمستأجرين والملاك ومديري العقارات والشركات، سواء كانت كنبة واحدة أو إخلاءً كاملاً.'
            : 'We’re based in Al Quoz and work with homeowners, tenants, landlords, property managers and businesses — whether it’s a single sofa or a full clearance.' ?></p>
        <ul class="tick-list">
<?php foreach ($ticks as $item): ?>
          <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
        </ul>
      </div>
      <div class="about-figure">
        <img src="/assets/images/team-member.png" alt="<?= $ar ? 'أحد أفراد فريق Junk Removal Team Dubai بالزي الرسمي' : 'Junk Removal Team Dubai crew member in company uniform' ?>" width="620" height="812" loading="lazy" decoding="async">
      </div>
    </div>
  </section>

  <section class="section section-light">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'كيف نعمل' : 'How we work' ?></h2>
        <p><?= $ar ? 'ما يمكنك توقعه عند الحجز معنا.' : 'What you can expect when you book with us.' ?></p>
      </div>
      <div class="card-grid">
<?php foreach ($values as [$icon, $title, $text]): ?>
        <div class="info-card">
          <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#<?= $icon ?>"/></svg></span>
          <h3><?= View::e($title) ?></h3>
          <p><?= View::e($text) ?></p>
        </div>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section" id="where-we-work">
    <div class="container">
      <div class="section-intro">
        <h2><?= $ar ? 'المناطق التي نعمل بها' : 'Where we work' ?></h2>
        <!-- VERIFY F1: only areas the business genuinely serves -->
        <p><?= $ar ? 'نعمل في مختلف مناطق دبي، ومنها:' : 'We work across Dubai, including:' ?></p>
      </div>
      <ul class="chip-list">
<?php foreach (View::areas($lang) as $value => $label):
        if ($value === 'Other') { continue; } ?>
        <li class="chip"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><?= View::e($label) ?></li>
<?php endforeach; ?>
      </ul>
      <p class="area-ask">
        <a href="<?= View::e(View::whatsappLink($ar ? 'مرحبًا، هل تخدمون منطقتي؟' : 'Hi, do you cover my area?')) ?>" data-track="whatsapp_click">
          <?= $ar ? 'لا تجد منطقتك؟ اسألنا عبر واتساب' : 'Don’t see your area? Ask us on WhatsApp' ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg>
        </a>
      </p>
    </div>
  </section>
<?php
    View::ctaBand($lang, View::base($lang) . 'contact-us/#quote');
    View::foot($lang);
}

function render_contact_page(string $lang): void
{
    $ar = $lang === 'ar';
    $brand = '<bdi lang="en" dir="ltr">Junk Removal Team Dubai</bdi>';
    $maps = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('Al Quoz Industrial Area 3, Dubai');

    View::head([
        'lang' => $lang,
        'path' => 'contact-us/',
        'active' => 'contact',
        'title' => $ar ? 'اتصل بنا | Junk Removal Team Dubai' : 'Contact Us | Junk Removal Team Dubai',
        'description' => $ar
            ? 'اتصل بـ Junk Removal Team Dubai أو راسلنا عبر واتساب أو البريد الإلكتروني، أو أرسل تفاصيل طلبك للحصول على عرض سعر.'
            : 'Call, WhatsApp or email Junk Removal Team Dubai, or send the details of your job for a quote.',
    ]);

    View::pageHero($lang, [
        'crumb' => $ar ? 'اتصل بنا' : 'Contact Us',
        'eyebrow' => $ar ? 'اتصل بنا' : 'Contact Us',
        'title_html' => $ar ? 'تواصل مع ' . $brand : 'Contact ' . $brand,
        'lead' => $ar
            ? 'اتصل بنا أو راسلنا عبر واتساب أو أرسل تفاصيل طلبك وسنعاود التواصل معك.'
            : 'Call, WhatsApp or send us the details of your job and we’ll get back to you.',
    ]);
    ?>
  <section class="section">
    <div class="container split">
      <div>
        <div class="contact-list">
          <div class="contact-item">
            <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg></span>
            <div>
              <h3><?= $ar ? 'اتصل بنا' : 'Call us' ?></h3>
              <a class="ltr" href="tel:<?= View::PHONE_TEL ?>" data-track="phone_click"><?= View::PHONE_DISPLAY ?></a>
            </div>
          </div>
          <div class="contact-item">
            <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-wa"/></svg></span>
            <div>
              <h3><?= $ar ? 'واتساب' : 'WhatsApp' ?></h3>
              <a href="<?= View::e(View::whatsappLink('', $lang)) ?>" data-track="whatsapp_click"><?= $ar ? 'ابدأ محادثة على واتساب' : 'Start a chat on WhatsApp' ?></a>
            </div>
          </div>
          <div class="contact-item">
            <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-mail"/></svg></span>
            <div>
              <h3><?= $ar ? 'البريد الإلكتروني' : 'Email' ?></h3>
              <a class="ltr" href="mailto:<?= View::EMAIL ?>" data-track="email_click"><?= View::EMAIL ?></a>
            </div>
          </div>
          <div class="contact-item">
            <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg></span>
            <div>
              <h3><?= $ar ? 'العنوان' : 'Address' ?></h3>
              <!-- VERIFY A2 / B6 / B7: exact address and whether customers can visit -->
              <p><?= $ar ? 'منطقة القوز الصناعية 3، دبي، الإمارات' : 'Al Qouz 3 Industrial Areas, Dubai, UAE' ?></p>
              <a href="<?= View::e($maps) ?>" target="_blank" rel="noopener"><?= $ar ? 'افتح في خرائط جوجل' : 'Open in Google Maps' ?></a>
            </div>
          </div>
          <div class="contact-item">
            <span class="icon-tile"><svg class="icon" aria-hidden="true"><use href="#i-clock"/></svg></span>
            <div>
              <h3><?= $ar ? 'ساعات العمل' : 'Business hours' ?></h3>
              <!-- VERIFY B5: replace with the real opening hours once confirmed -->
              <p><?= $ar ? 'اتصل بنا أو راسلنا عبر واتساب للتحقق من المواعيد المتاحة.' : 'Call or WhatsApp us to check availability.' ?></p>
            </div>
          </div>
        </div>

        <div class="info-card faster-quote">
          <h3><?= $ar ? 'لعرض سعر أسرع، أرسل لنا:' : 'For a faster quote, send us:' ?></h3>
          <ul class="tick-list">
<?php foreach (View::fasterQuote($lang) as $item): ?>
            <li><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg><?= View::e($item) ?></li>
<?php endforeach; ?>
          </ul>
        </div>
      </div>
<?php View::quoteForm($lang); ?>
    </div>
  </section>
<?php
    View::foot($lang);
}
