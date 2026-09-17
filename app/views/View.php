<?php
declare(strict_types=1);

/**
 * Shared layout for the PHP pages (services, about, contact).
 *
 * The homepages are still static HTML; this class reproduces the same header,
 * footer and quote form so every page looks identical. When you change the
 * header or footer on the homepages, change it here too.
 */
final class View
{
    /** Bump together with ?v= in public/index.html and public/ar/index.html. */
    public const ASSET_VERSION = '9';

    public const SITE          = 'https://junkremovalteamdubai.com';
    public const PHONE_TEL     = '+971567021884';
    public const PHONE_DISPLAY = '056 702 1884';
    public const WHATSAPP      = 'https://wa.me/971567021884';
    public const EMAIL         = 'contact.junkremovalteam@gmail.com';

    public static function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function base(string $lang): string
    {
        return $lang === 'ar' ? '/ar/' : '/';
    }

    public static function whatsappLink(string $text = ''): string
    {
        return self::WHATSAPP . ($text !== '' ? '?text=' . rawurlencode($text) : '');
    }

    /* ------------------------------------------------------------------ strings */

    public static function t(string $lang): array
    {
        if ($lang === 'ar') {
            return [
                'skip' => 'تخطَّ إلى المحتوى',
                'home_aria' => 'Junk Removal Team Dubai — الصفحة الرئيسية',
                'main_nav' => 'القائمة الرئيسية',
                'menu' => 'القائمة',
                'nav' => ['home' => 'الرئيسية', 'services' => 'الخدمات', 'areas' => 'المناطق', 'projects' => 'المشاريع', 'reviews' => 'آراء العملاء', 'about' => 'من نحن'],
                'breadcrumb' => 'مسار التنقل',
                'call' => 'اتصال',
                'whatsapp' => 'واتساب',
                'whatsapp_us' => 'راسلنا عبر واتساب',
                'quick_contact' => 'تواصل سريع',
                'social' => ['فيسبوك', 'إنستغرام', 'لينكد إن', 'يوتيوب'],
                'footer_about' => 'نتخلّص من الأثاث والأجهزة والأغراض المنزلية والمكتبية غير المرغوب فيها من الشقق والفلل والمكاتب والمحلات في جميع أنحاء دبي، ونتولى عنك الحمل والتحميل والنقل.',
                'quick_links' => 'روابط سريعة',
                'contact' => 'اتصل بنا',
                'blog' => 'المدونة',
                'services_heading' => 'الخدمات',
                'our_location' => 'موقعنا',
                'address_html' => 'منطقة القوز الصناعية 3<br>دبي، الإمارات',
                'skyline_html' => 'دبي أنظف<br>من أجل غد أفضل',
                'rights_html' => '© 2026 <span lang="en" dir="ltr">Junk Removal Team Dubai</span>. جميع الحقوق محفوظة.',
                'legal' => 'روابط قانونية',
                'privacy' => 'سياسة الخصوصية',
                'terms_html' => 'الشروط والأحكام',
                'cookies' => 'سياسة ملفات تعريف الارتباط',
                'designed_by' => 'تصميم',
                'cta_title' => 'جاهز لإخلاء مساحتك؟',
                'cta_text' => 'تواصل معنا اليوم للحصول على عرض سعر مجاني وغير ملزم.',
                'get_quote' => 'اطلب عرض سعر مجاني',
                'form_title' => 'احصل على عرض سعر مجاني',
                'form_sub' => 'املأ النموذج وسنعاود التواصل معك قريبًا.',
                'f_name' => 'الاسم الكامل',
                'f_phone' => 'رقم الهاتف',
                'f_email' => 'البريد الإلكتروني',
                'f_service' => 'اختر الخدمة',
                'f_area' => 'اختر المنطقة',
                'f_message' => 'رسالة',
                'f_message_ph' => 'رسالة (اختياري)',
                'f_submit' => 'اطلب عرض السعر',
                'f_note' => 'بإرسال هذا النموذج فإنك توافق على',
                'f_success' => 'شكرًا لك. لقد استلمنا بياناتك وسنعاود التواصل معك قريبًا.',
            ];
        }

        return [
            'skip' => 'Skip to content',
            'home_aria' => 'Junk Removal Team Dubai — home',
            'main_nav' => 'Main',
            'menu' => 'Menu',
            'nav' => ['home' => 'Home', 'services' => 'Services', 'areas' => 'Areas We Serve', 'projects' => 'Projects', 'reviews' => 'Reviews', 'about' => 'About Us'],
            'breadcrumb' => 'Breadcrumb',
            'call' => 'Call',
            'whatsapp' => 'WhatsApp',
            'whatsapp_us' => 'WhatsApp Us',
            'quick_contact' => 'Quick contact',
            'social' => ['Facebook', 'Instagram', 'LinkedIn', 'YouTube'],
            'footer_about' => 'We clear unwanted furniture, appliances and household or office junk from apartments, villas, offices and shops across Dubai — we do the lifting, loading and taking away.',
            'quick_links' => 'Quick Links',
            'contact' => 'Contact',
            'blog' => 'Blog',
            'services_heading' => 'Services',
            'our_location' => 'Our Location',
            'address_html' => 'Al Qouz 3 Industrial Areas<br>Dubai, UAE',
            'skyline_html' => 'A Cleaner Dubai<br>for a Better Tomorrow',
            'rights_html' => '© 2026 Junk Removal Team Dubai. All rights reserved.',
            'legal' => 'Legal',
            'privacy' => 'Privacy Policy',
            'terms_html' => 'Terms &amp; Conditions',
            'cookies' => 'Cookie Policy',
            'designed_by' => 'Designed By',
            'cta_title' => 'Ready to Clear Your Space?',
            'cta_text' => 'Get in touch today for a free, no-obligation quote.',
            'get_quote' => 'Get a Free Quote',
            'form_title' => 'Get a Free Quote',
            'form_sub' => 'Fill out the form and we’ll get back to you shortly.',
            'f_name' => 'Full Name',
            'f_phone' => 'Phone Number',
            'f_email' => 'Email Address',
            'f_service' => 'Select Service',
            'f_area' => 'Select Area',
            'f_message' => 'Message',
            'f_message_ph' => 'Message (Optional)',
            'f_submit' => 'Request a Quote',
            'f_note' => 'By sending this form you agree to our',
            'f_success' => 'Thank you. We have your details and will get back to you shortly.',
        ];
    }

    /* ------------------------------------------------------------------ content */

    /**
     * Services shown on the services page and in the footer.
     * VERIFY C / D1 / E4 / E11 / E14 — see docs/00-business-verification.md.
     */
    public static function services(string $lang): array
    {
        $ar = $lang === 'ar';

        $items = [
            [
                'slug' => 'junk-removal', 'value' => 'Junk Removal', 'icon' => 'i-truck', 'image' => 'svc-junk-removal.jpg',
                'title' => $ar ? 'إزالة المخلفات' : 'Junk Removal',
                'alt' => $ar ? 'كنبة في غرفة معيشة جاهزة للنقل ضمن خدمة إزالة المخلفات' : 'Living room sofa ready for junk removal',
                'text' => $ar
                    ? 'إزالة المخلفات العامة للمنازل والمكاتب والمحلات — من بضع قطع غير مرغوب فيها إلى حمولة كاملة. أخبرنا بما تريد التخلص منه، واتفق معنا على السعر والموعد، ونتولى نحن الحمل والتحميل والنقل.'
                    : 'General junk removal for homes, offices and shops — from a few unwanted items to a full load. Tell us what needs to go, agree a price and a time, and we carry it out, load it and take it away.',
                'list' => $ar
                    ? ['الأثاث والمراتب القديمة', 'الأجهزة المنزلية', 'الكراتين والأكياس والأغراض المتراكمة', 'أغراض المكاتب والمحلات']
                    : ['Old furniture and mattresses', 'Household appliances', 'Boxes, bags and clutter', 'Office and shop items'],
            ],
            [
                'slug' => 'furniture-removal', 'value' => 'Furniture Removal', 'icon' => 'i-sofa', 'image' => 'svc-furniture-removal.jpg',
                'title' => $ar ? 'نقل الأثاث' : 'Furniture Removal',
                'alt' => $ar ? 'كرسي وطاولة جانبية ضمن خدمة نقل الأثاث القديم' : 'Armchair and side table collected during a furniture removal job',
                'text' => $ar
                    ? 'تستبدل أثاثك أو تُخلي غرفة؟ ننقل الكنب والأسرّة والخزائن والطاولات وأثاث المكاتب، ونفك القطع الكبيرة عندما لا تمر من الباب أو المصعد.'
                    : 'Replacing furniture or clearing a room? We remove sofas, beds, wardrobes, tables and office furniture, and take large pieces apart when they won’t fit through a door or lift.',
                'list' => $ar
                    ? ['الكنب والكراسي المريحة', 'الأسرّة والمراتب', 'الخزائن والدواليب', 'المكاتب والطاولات والكراسي']
                    : ['Sofas and armchairs', 'Beds and mattresses', 'Wardrobes and cabinets', 'Desks, tables and chairs'],
            ],
            [
                'slug' => 'house-clearance', 'value' => 'House Clearance', 'icon' => 'i-home', 'image' => 'svc-house-clearance.jpg',
                'title' => $ar ? 'إخلاء المنازل' : 'House Clearance',
                'alt' => $ar ? 'فيلا في دبي جاهزة لخدمة الإخلاء الكامل' : 'Villa exterior in Dubai prepared for a full house clearance',
                'text' => $ar
                    ? 'إخلاء كامل أو جزئي للشقق والفلل — قبل الانتقال أو التسليم أو البيع أو التجديد. نخطط العمل حسب موعدك ونُخلي الغرف والمخازن والمساحات الخارجية التي تحددها.'
                    : 'Full or partial clearance of apartments and villas — before a move, a handover, a sale or a renovation. We plan the job around your date and clear the rooms, storage and outdoor areas you choose.',
                'list' => $ar
                    ? ['الشقق والفلل', 'الإخلاء عند الانتقال أو التسليم', 'غرف التخزين والمرائب', 'أثاث الحدائق والشرفات']
                    : ['Apartments and villas', 'Move-out and handover clearances', 'Storage rooms and garages', 'Garden and balcony furniture'],
            ],
            [
                'slug' => 'office-clearance', 'value' => 'Office Clearance', 'icon' => 'i-building', 'image' => 'svc-office-clearance.jpg',
                'title' => $ar ? 'إخلاء المكاتب' : 'Office Clearance',
                'alt' => $ar ? 'مكتب يحتوي على طاولات وكراسي بانتظار الإخلاء' : 'Office meeting room with desks and chairs awaiting clearance',
                'text' => $ar
                    ? 'تنتقل أو تقلص مساحة مكتبك أو تسلّمه؟ نُخلي محطات العمل والكراسي والخزائن والأغراض المكتبية، ونرتب العمل وفق قواعد الدخول في المبنى.'
                    : 'Relocating, downsizing or handing an office back? We clear workstations, chairs, cabinets and general office junk, and plan the work around your building’s access rules.',
                'list' => $ar
                    ? ['المكاتب ومحطات العمل', 'كراسي المكاتب وطاولات الاجتماعات', 'خزائن الملفات والتخزين', 'الأغراض المكتبية العامة']
                    : ['Desks and workstations', 'Office chairs and meeting tables', 'Filing cabinets and storage', 'General office junk'],
                'note' => $ar
                    ? 'يرجى إزالة الأقراص الصلبة والأجهزة التي تحتوي على بيانات أو مسحها قبل الاستلام.'
                    : 'Please remove or wipe hard drives and other devices that hold data before collection.',
            ],
            [
                'slug' => 'construction-waste', 'value' => 'Construction Waste', 'icon' => 'i-brick', 'image' => 'svc-construction-waste.jpg',
                'title' => $ar ? 'مخلفات البناء' : 'Construction Waste',
                'alt' => $ar ? 'كومة من مخلفات البناء جاهزة للإزالة' : 'Pile of construction rubble ready for removal',
                'text' => $ar
                    ? 'المخلفات المتبقية بعد أعمال التجديد أو التشطيب، نزيلها من المنازل والوحدات التجارية.'
                    : 'Debris left after renovation or fit-out work, removed from homes and commercial units.',
                'list' => $ar
                    ? ['الركام والبلاط المكسور', 'التركيبات والتجهيزات القديمة', 'الخشب والألواح ومواد التغليف', 'بقايا أعمال التشطيب']
                    : ['Rubble and broken tiles', 'Old fixtures and fittings', 'Wood, board and packaging', 'Fit-out leftovers'],
            ],
            [
                'slug' => 'e-waste-disposal', 'value' => 'E-Waste Disposal', 'icon' => 'i-monitor', 'image' => 'svc-ewaste-disposal.jpg',
                'title' => $ar ? 'النفايات الإلكترونية' : 'E-Waste Disposal',
                'alt' => $ar ? 'شاشات حاسوب وأجهزة إلكترونية قديمة جاهزة للتخلص منها' : 'Old computer monitors and electronics collected for e-waste disposal',
                'text' => $ar
                    ? 'نجمع الأجهزة الإلكترونية والكهربائية القديمة من المنازل والمكاتب — الشاشات وأجهزة الكمبيوتر والطابعات والأجهزة الصغيرة.'
                    : 'Old electronics and appliances collected from homes and offices — screens, computers, printers and small appliances.',
                'list' => $ar
                    ? ['التلفزيونات والشاشات', 'أجهزة الكمبيوتر والطابعات', 'الكابلات والملحقات', 'الأجهزة الكهربائية الصغيرة']
                    : ['TVs and monitors', 'Computers and printers', 'Cables and accessories', 'Small appliances'],
            ],
        ];

        foreach ($items as &$item) {
            $item['ask'] = $ar ? 'اسأل عن ' . $item['title'] : 'Ask about ' . $item['title'];
            $item['wa_text'] = $ar
                ? 'مرحبًا، أرغب في عرض سعر لخدمة ' . $item['title'] . '.'
                : 'Hi, I’d like a quote for ' . $item['title'] . '.';
        }
        unset($item);

        return $items;
    }

    /** VERIFY E1–E3: the steps must match how the business actually books jobs. */
    public static function steps(string $lang): array
    {
        return $lang === 'ar'
            ? [
                ['أرسل لنا التفاصيل', 'اتصل بنا أو راسلنا عبر واتساب أو استخدم النموذج. صور الأغراض تساعدنا على تحديد السعر بدقة.'],
                ['احصل على السعر', 'نؤكد لك السعر قبل حجز أي عمل.'],
                ['اختر الموعد', 'اختر التاريخ والوقت المناسبين لك.'],
                ['نُخلي المكان', 'يتولى فريقنا الحمل والتحميل ونقل كل ما تم الاتفاق عليه.'],
            ]
            : [
                ['Send us the details', 'Call, WhatsApp or use the form. Photos of the items help us price the job accurately.'],
                ['Get your price', 'We confirm the price with you before any work is booked.'],
                ['Pick a time', 'Choose a date and time that suits you.'],
                ['We clear it', 'Our team carries, loads and takes away everything agreed.'],
            ];
    }

    public static function fasterQuote(string $lang): array
    {
        return $lang === 'ar'
            ? ['صور الأغراض', 'منطقتك ونوع المبنى', 'رقم الطابق وتوفر المصعد', 'الموعد المفضل']
            : ['Photos of the items', 'Your area and type of building', 'Which floor, and whether there’s a lift', 'Your preferred date'];
    }

    /** value => label. VERIFY F1: only areas the business genuinely serves. */
    public static function areas(string $lang): array
    {
        $values = ['Al Quoz', 'Al Barsha', 'Jumeirah', 'Dubai Marina', 'Business Bay', 'Downtown Dubai', 'JVC', 'Other'];
        if ($lang !== 'ar') {
            return array_combine($values, $values);
        }

        return array_combine($values, ['القوز', 'البرشاء', 'جميرا', 'دبي مارينا', 'الخليج التجاري', 'وسط مدينة دبي', 'قرية جميرا الدائرية', 'منطقة أخرى']);
    }

    /* ------------------------------------------------------------------ layout */

    /**
     * @param array{lang:string,path:string,active:string,title:string,description:string} $p
     */
    public static function head(array $p): void
    {
        $lang = $p['lang'];
        $t = self::t($lang);
        $b = self::base($lang);
        $dir = $lang === 'ar' ? 'rtl' : 'ltr';
        $enUrl = self::SITE . '/' . $p['path'];
        $arUrl = self::SITE . '/ar/' . $p['path'];
        $fonts = $lang === 'ar'
            ? 'family=Tajawal:wght@400;500;700&family=Poppins:wght@600;700'
            : 'family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&family=Caveat:wght@600;700';
        $v = self::ASSET_VERSION;

        $nav = [
            'home'     => $b,
            'services' => $b . 'services/',
            'areas'    => $b . '#areas',
            'projects' => $b . '#projects',
            'reviews'  => $b . '#reviews',
            'about'    => $b . 'about-us/',
        ];
        ?>
<!doctype html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= self::e($p['title']) ?></title>
<meta name="description" content="<?= self::e($p['description']) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= self::e($lang === 'ar' ? $arUrl : $enUrl) ?>">
<link rel="alternate" hreflang="en" href="<?= self::e($enUrl) ?>">
<link rel="alternate" hreflang="ar" href="<?= self::e($arUrl) ?>">
<link rel="alternate" hreflang="x-default" href="<?= self::e($enUrl) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?<?= $fonts ?>&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/site.css?v=<?= $v ?>">
</head>
<body>

<?php readfile(__DIR__ . '/sprite.svg'); ?>

<a class="skip-link" href="#main"><?= self::e($t['skip']) ?></a>

<div class="topbar">
  <div class="container">
    <span class="tb-social">
      <a href="#" aria-label="<?= self::e($t['social'][0]) ?>"><svg class="icon" aria-hidden="true"><use href="#i-fb"/></svg></a>
      <a href="#" aria-label="<?= self::e($t['social'][1]) ?>"><svg class="icon" aria-hidden="true"><use href="#i-ig"/></svg></a>
      <a href="#" aria-label="<?= self::e($t['social'][2]) ?>"><svg class="icon" aria-hidden="true"><use href="#i-li"/></svg></a>
      <a href="#" aria-label="<?= self::e($t['social'][3]) ?>"><svg class="icon" aria-hidden="true"><use href="#i-yt"/></svg></a>
    </span>
    <span class="tb-right lang">
      <svg class="icon" aria-hidden="true"><use href="#i-globe"/></svg>
      <a href="<?= self::e('/' . $p['path']) ?>" lang="en" hreflang="en"<?= $lang === 'en' ? ' class="is-active"' : '' ?>>English</a>
      <span class="sep">|</span>
      <a href="<?= self::e('/ar/' . $p['path']) ?>" lang="ar" hreflang="ar"<?= $lang === 'ar' ? ' class="is-active"' : '' ?>>العربية</a>
    </span>
  </div>
</div>

<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="<?= $b ?>" aria-label="<?= self::e($t['home_aria']) ?>">
      <img class="brand-logo" src="/assets/images/logo-header.png" alt="Junk Removal Team Dubai" width="420" height="140">
    </a>

    <nav class="main-nav" id="primary-nav" aria-label="<?= self::e($t['main_nav']) ?>">
      <ul>
<?php foreach ($nav as $key => $href):
        $active = $key === $p['active'];
        $caret = in_array($key, ['services', 'areas'], true) ? ' <svg class="icon caret" aria-hidden="true"><use href="#i-caret"/></svg>' : '';
        ?>
        <li><a href="<?= self::e($href) ?>"<?= $active ? ' class="is-active" aria-current="page"' : '' ?>><?= self::e($t['nav'][$key]) ?><?= $caret ?></a></li>
<?php endforeach; ?>
      </ul>
    </nav>

    <div class="header-cta">
      <a class="btn btn-phone" href="tel:<?= self::PHONE_TEL ?>" data-track="phone_click">
        <svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg><span class="ltr"><?= self::PHONE_DISPLAY ?></span>
      </a>
      <button class="nav-toggle" type="button" data-nav-toggle aria-controls="primary-nav" aria-expanded="false">
        <svg class="icon" aria-hidden="true"><use href="#i-menu"/></svg><span class="sr-only"><?= self::e($t['menu']) ?></span>
      </button>
    </div>
  </div>
</header>

<main id="main">
<?php
    }

    /** @param array{eyebrow:string,title?:string,title_html?:string,lead:string,crumb:string} $h */
    public static function pageHero(string $lang, array $h): void
    {
        $t = self::t($lang);
        ?>
  <section class="page-hero">
    <div class="container">
      <nav class="breadcrumb" aria-label="<?= self::e($t['breadcrumb']) ?>">
        <ol>
          <li><a href="<?= self::base($lang) ?>"><?= self::e($t['nav']['home']) ?></a></li>
          <li aria-current="page"><?= self::e($h['crumb']) ?></li>
        </ol>
      </nav>
      <span class="eyebrow"><?= self::e($h['eyebrow']) ?></span>
      <h1><?= $h['title_html'] ?? self::e($h['title'] ?? '') ?></h1>
      <p class="lead"><?= self::e($h['lead']) ?></p>
      <div class="btn-group">
        <a class="btn btn-wa" href="<?= self::WHATSAPP ?>" data-track="whatsapp_click"><svg class="icon" aria-hidden="true"><use href="#i-wa"/></svg><?= self::e($t['whatsapp_us']) ?></a>
        <a class="btn btn-ghost" href="tel:<?= self::PHONE_TEL ?>" data-track="phone_click"><svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg><span class="ltr"><?= self::PHONE_DISPLAY ?></span></a>
      </div>
    </div>
  </section>
<?php
    }

    public static function quoteForm(string $lang): void
    {
        $t = self::t($lang);
        ?>
      <div class="quote-card" id="quote">
        <h2><?= self::e($t['form_title']) ?></h2>
        <!-- VERIFY E1: confirm quotes are genuinely free before keeping the word "Free" -->
        <p class="sub"><?= self::e($t['form_sub']) ?></p>
        <form data-quote-form action="/submit.php" method="post" novalidate>
          <input type="hidden" name="language" value="<?= $lang ?>">
          <div class="hp" aria-hidden="true"><label for="f-website">Website</label><input id="f-website" name="website" tabindex="-1" autocomplete="off"></div>
          <div class="form-row two">
            <div>
              <label class="field-label" for="f-name"><?= self::e($t['f_name']) ?></label>
              <input class="input" id="f-name" name="name" placeholder="<?= self::e($t['f_name']) ?> *" autocomplete="name" required>
            </div>
            <div>
              <label class="field-label" for="f-phone"><?= self::e($t['f_phone']) ?></label>
              <input class="input" id="f-phone" name="phone" type="tel" inputmode="tel" dir="ltr" placeholder="<?= self::e($t['f_phone']) ?> *" autocomplete="tel" required>
            </div>
          </div>
          <div class="form-row">
            <div>
              <label class="field-label" for="f-email"><?= self::e($t['f_email']) ?></label>
              <input class="input" id="f-email" name="email" type="email" dir="ltr" placeholder="<?= self::e($t['f_email']) ?>" autocomplete="email">
            </div>
          </div>
          <div class="form-row two">
            <div>
              <label class="field-label" for="f-service"><?= self::e($t['f_service']) ?></label>
              <select class="select" id="f-service" name="service">
                <option value=""><?= self::e($t['f_service']) ?></option>
<?php foreach (self::services($lang) as $s): ?>
                <option value="<?= self::e($s['value']) ?>"><?= self::e($s['title']) ?></option>
<?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="field-label" for="f-area"><?= self::e($t['f_area']) ?></label>
              <select class="select" id="f-area" name="area">
                <option value=""><?= self::e($t['f_area']) ?></option>
<?php foreach (self::areas($lang) as $value => $label): ?>
                <option value="<?= self::e($value) ?>"><?= self::e($label) ?></option>
<?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div>
              <label class="field-label" for="f-message"><?= self::e($t['f_message']) ?></label>
              <textarea class="textarea" id="f-message" name="message" placeholder="<?= self::e($t['f_message_ph']) ?>"></textarea>
            </div>
          </div>
          <button class="btn btn-green btn-block" type="submit"><?= self::e($t['f_submit']) ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></button>
          <div class="form-success" data-form-success hidden><?= self::e($t['f_success']) ?></div>
          <p class="form-note"><?= self::e($t['f_note']) ?> <a href="#"><?= self::e($t['privacy']) ?></a>.</p>
        </form>
      </div>
<?php
    }

    public static function ctaBand(string $lang, string $quoteHref): void
    {
        $t = self::t($lang);
        $leaf = '<path d="M8 168C4 96 44 34 130 6c14 78-34 140-122 162z"/><path d="M96 40c34-14 62-18 92-16-30 22-58 34-92 44z" opacity=".7"/>';
        ?>
  <section class="cta-band">
    <svg class="cta-leaf cta-leaf-start" viewBox="0 0 200 170" aria-hidden="true" fill="currentColor"><?= $leaf ?></svg>
    <svg class="cta-leaf cta-leaf-end" viewBox="0 0 200 170" aria-hidden="true" fill="currentColor"><?= $leaf ?></svg>
    <div class="container cta-inner">
      <div>
        <h2><?= self::e($t['cta_title']) ?></h2>
        <p><?= self::e($t['cta_text']) ?></p><!-- VERIFY E1 -->
      </div>
      <div class="btn-group">
        <a class="btn btn-teal" href="tel:<?= self::PHONE_TEL ?>" data-track="phone_click"><svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg><span class="ltr"><?= self::PHONE_DISPLAY ?></span></a>
        <a class="btn btn-wa" href="<?= self::WHATSAPP ?>" data-track="whatsapp_click"><svg class="icon" aria-hidden="true"><use href="#i-wa"/></svg><?= self::e($t['whatsapp_us']) ?></a>
        <a class="btn btn-call" href="<?= self::e($quoteHref) ?>"><?= self::e($t['get_quote']) ?> <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></a>
      </div>
    </div>
  </section>
<?php
    }

    public static function foot(string $lang): void
    {
        $t = self::t($lang);
        $b = self::base($lang);
        $v = self::ASSET_VERSION;
        ?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a class="brand" href="<?= $b ?>" aria-label="<?= self::e($t['home_aria']) ?>">
          <img class="brand-logo" src="/assets/images/logo-footer.png" alt="Junk Removal Team Dubai" width="420" height="140" loading="lazy">
        </a>
        <p class="footer-about"><?= self::e($t['footer_about']) ?></p>
      </div>
      <div>
        <h3><?= self::e($t['quick_links']) ?></h3>
        <ul class="footer-links">
          <li><a href="<?= $b ?>"><?= self::e($t['nav']['home']) ?></a></li>
          <li><a href="<?= $b ?>services/"><?= self::e($t['nav']['services']) ?></a></li>
          <li><a href="<?= $b ?>about-us/"><?= self::e($t['nav']['about']) ?></a></li>
          <li><a href="<?= $b ?>contact-us/"><?= self::e($t['contact']) ?></a></li>
          <li><a href="<?= $b ?>#reviews"><?= self::e($t['nav']['reviews']) ?></a></li>
          <li><a href="#"><?= self::e($t['blog']) ?></a></li>
          <li><a href="<?= $b ?>#areas"><?= self::e($t['nav']['areas']) ?></a></li>
          <li><a href="<?= $b ?>#projects"><?= self::e($t['nav']['projects']) ?></a></li>
        </ul>
      </div>
      <div>
        <h3><?= self::e($t['services_heading']) ?></h3>
        <ul>
<?php foreach (self::services($lang) as $s): ?>
          <li><a href="<?= $b ?>services/#<?= self::e($s['slug']) ?>"><?= self::e($s['title']) ?></a></li>
<?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h3><?= self::e($t['our_location']) ?></h3>
        <ul class="footer-contact">
          <li><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><span><?= $t['address_html'] ?></span></li>
          <li><svg class="icon" aria-hidden="true"><use href="#i-mail"/></svg><a class="ltr" href="mailto:<?= self::EMAIL ?>" data-track="email_click"><?= self::EMAIL ?></a></li>
          <li><svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg><a class="ltr" href="tel:<?= self::PHONE_TEL ?>" data-track="phone_click"><?= self::PHONE_DISPLAY ?></a></li>
        </ul>
      </div>
      <div class="footer-skyline">
        <svg viewBox="0 0 220 70" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M2 68h216"/>
          <path d="M14 68V44h16v24M34 68V52h12v16M50 68V38h14v30"/>
          <path d="M104 68V20l4-16 4 16v48"/>
          <path d="M80 68V34h14v34M120 68V30h16v38M140 68V44h12v24M156 68V36h14v32M174 68V50h14v18M196 68V42h10v26"/>
        </svg>
        <p><?= $t['skyline_html'] ?></p>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <span><?= $t['rights_html'] ?></span>
      <nav aria-label="<?= self::e($t['legal']) ?>">
        <a href="#"><?= self::e($t['privacy']) ?></a>
        <a href="#"><?= $t['terms_html'] ?></a>
        <a href="#"><?= self::e($t['cookies']) ?></a>
      </nav>
      <span class="tagline"><?= self::e($t['designed_by']) ?> <a href="https://imwebee.com" target="_blank" rel="noopener">Webee</a></span>
    </div>
  </div>
</footer>

<nav class="mobile-bar" aria-label="<?= self::e($t['quick_contact']) ?>">
  <a href="tel:<?= self::PHONE_TEL ?>" data-track="phone_click"><svg class="icon" aria-hidden="true"><use href="#i-phone"/></svg><?= self::e($t['call']) ?></a>
  <a class="m-wa" href="<?= self::WHATSAPP ?>" data-track="whatsapp_click"><svg class="icon" aria-hidden="true"><use href="#i-wa"/></svg><?= self::e($t['whatsapp']) ?></a>
</nav>

<script src="/assets/js/site.js?v=<?= $v ?>"></script>
</body>
</html>
<?php
    }
}
