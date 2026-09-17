<?php
declare(strict_types=1);

/**
 * Page bodies shared by the English and Arabic entry points.
 * Each public/<page>/index.php only sets the language and calls one of these.
 */

require_once __DIR__ . '/View.php';

function render_services_page(string $lang): void
{
    $ar = $lang === 'ar';

    View::head([
        'lang' => $lang,
        'path' => 'services/',
        'active' => 'services',
        'title' => $ar
            ? 'خدمات إزالة المخلفات والإخلاء في دبي | Junk Removal Team Dubai'
            : 'Junk Removal & Clearance Services in Dubai | Junk Removal Team Dubai',
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
          <img src="/assets/images/<?= View::e($s['image']) ?>" alt="<?= View::e($s['alt']) ?>" width="760" height="475"<?= $i === 0 ? '' : ' loading="lazy"' ?> decoding="async">
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
          <a class="btn btn-teal" href="<?= View::e(View::whatsappLink($s['wa_text'])) ?>" data-track="whatsapp_click">
            <svg class="icon" aria-hidden="true"><use href="#i-wa"/></svg><?= View::e($s['ask']) ?>
          </a>
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
              <a href="<?= View::WHATSAPP ?>" data-track="whatsapp_click"><?= $ar ? 'ابدأ محادثة على واتساب' : 'Start a chat on WhatsApp' ?></a>
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
