<?php
declare(strict_types=1);

require_once __DIR__ . '/pages.php';

/**
 * Privacy policy, terms and cookie policy.
 *
 * These describe what this site actually does — the quote form writes to a file
 * and emails us, a review is published after approval, GA4 is the only third
 * party script, and a visitor's IP is stored only as a one-way hash (Lead.php).
 * Nothing here invents a business fact. The items the owner still has to settle
 * (legal entity name, trade licence number, exactly how long records are kept,
 * payment methods) are tracked in docs/00-business-verification.md rather than
 * guessed at here.
 */
function render_legal_page(string $lang, string $which): void
{
    $ar = $lang === 'ar';
    $updated = $ar ? '18 سبتمبر 2026' : '18 September 2026';

    $pages = [
        'privacy' => [
            'slug' => 'privacy-policy/',
            'title' => $ar ? 'سياسة الخصوصية' : 'Privacy Policy',
            'desc' => $ar
                ? 'ما البيانات التي نجمعها عبر هذا الموقع، ولماذا نجمعها، وكيف يمكنك أن تطلب حذفها.'
                : 'What we collect through this website, why we collect it, and how to ask us to delete it.',
            'lead' => $ar
                ? 'توضح هذه الصفحة البيانات التي نستلمها عندما تستخدم هذا الموقع أو ترسل لنا طلب عرض سعر.'
                : 'This page explains what we receive when you use this website or send us a quote request.',
            'sections' => $ar ? [
                ['من نحن', [
                    'يدير هذا الموقع Junk Removal Team Dubai، وهي خدمة لإزالة المخلفات والإخلاء تعمل في دبي، الإمارات العربية المتحدة.',
                    'للتواصل بخصوص الخصوصية: البريد الإلكتروني contact.junkremovalteam@gmail.com أو الهاتف 056 702 1884.',
                ]],
                ['ما الذي نجمعه', [
                    'عند إرسال نموذج طلب السعر: الاسم، ورقم الهاتف، والبريد الإلكتروني إن أدخلته، والمنطقة، والخدمة المطلوبة، وأي تفاصيل تكتبها بنفسك.',
                    'عند إرسال تقييم: الاسم الذي تختاره، والمنطقة إن أدخلتها، ونص التقييم. هذه البيانات معدّة للنشر العلني على الموقع.',
                    'بيانات تقنية عادية يسجلها الخادم مثل وقت الطلب ونوع المتصفح. لا نخزّن عنوان IP كما هو، بل نخزّن نسخة مشفّرة منه بطريقة لا يمكن عكسها، وذلك للحد من الرسائل المزعجة فقط.',
                ]],
                ['لماذا نستخدمها', [
                    'للرد على طلبك بعرض سعر وترتيب موعد العمل.',
                    'لنشر التقييم الذي أرسلته أنت بنفسك على صفحة آراء العملاء.',
                    'لحماية النماذج من الإرسال الآلي والمزعج.',
                    'لا نستخدم بياناتك لإرسال رسائل تسويقية، ولا نبيعها ولا نؤجّرها لأي جهة.',
                ]],
                ['من يطّلع عليها', [
                    'فريقنا فقط، إضافة إلى مزوّد الاستضافة الذي يخزّن الموقع والبريد.',
                    'نستخدم Google Analytics 4 لقياس عدد الزيارات والصفحات الأكثر مشاهدة. تُرسل هذه البيانات إلى Google وتخضع لسياسة خصوصية Google.',
                    'لا نشارك بيانات طلبك مع أي جهة إعلانية.',
                ]],
                ['مدة الاحتفاظ', [
                    'نحتفظ بطلبات العروض للمدة التي نحتاجها لتنفيذ العمل ولسجلاتنا الخاصة، ثم نحذفها.',
                    'يبقى التقييم منشورًا إلى أن تطلب إزالته.',
                ]],
                ['حقوقك', [
                    'يمكنك أن تطلب منا نسخة من بياناتك، أو تصحيحها، أو حذفها، أو حذف تقييمك المنشور.',
                    'راسلنا على contact.junkremovalteam@gmail.com وسنتعامل مع طلبك.',
                ]],
                ['التعديلات', [
                    'قد نحدّث هذه الصفحة عند تغيّر طريقة عملنا. التاريخ أدناه يوضح آخر تحديث.',
                ]],
            ] : [
                ['Who we are', [
                    'This website is run by Junk Removal Team Dubai, a junk removal and clearance service operating in Dubai, United Arab Emirates.',
                    'For anything about privacy, email contact.junkremovalteam@gmail.com or call 056 702 1884.',
                ]],
                ['What we collect', [
                    'When you send the quote form: your name, your phone number, your email address if you enter one, your area, the service you need and any details you type yourself.',
                    'When you submit a review: the name you choose to show, your area if you enter one, and the text of the review. That information is meant to be published on the site.',
                    'Ordinary technical information that a web server records, such as the time of the request and the browser type. We do not store your IP address itself — we store a one-way hashed form of it, and only to limit spam.',
                ]],
                ['Why we use it', [
                    'To reply to your enquiry with a price and to arrange the job.',
                    'To publish the review you chose to send us on the reviews page.',
                    'To stop automated and spam submissions.',
                    'We do not use your details for marketing messages, and we do not sell or rent them to anyone.',
                ]],
                ['Who sees it', [
                    'Our own team, plus the hosting provider that stores the website and our email.',
                    'We use Google Analytics 4 to count visits and see which pages are read. That data goes to Google and is covered by Google’s own privacy policy.',
                    'We do not share your enquiry with advertisers.',
                ]],
                ['How long we keep it', [
                    'We keep enquiries for as long as we need them for the job and for our own records, then delete them.',
                    'A published review stays up until you ask us to remove it.',
                ]],
                ['Your choices', [
                    'You can ask us for a copy of your details, ask us to correct them, or ask us to delete them or take your review down.',
                    'Email contact.junkremovalteam@gmail.com and we will deal with your request.',
                ]],
                ['Changes', [
                    'We update this page when the way we work changes. The date below shows when it was last updated.',
                ]],
            ],
        ],
        'terms' => [
            'slug' => 'terms-and-conditions/',
            'title' => $ar ? 'الشروط والأحكام' : 'Terms & Conditions',
            'desc' => $ar
                ? 'شروط استخدام هذا الموقع، وكيف تعمل عروض الأسعار والحجوزات، وما لا نستطيع نقله.'
                : 'The terms for using this website, how quotes and bookings work, and what we cannot take away.',
            'lead' => $ar
                ? 'توضح هذه الصفحة ما يمكنك توقعه منا وما نحتاجه منك عند حجز الخدمة.'
                : 'This page sets out what you can expect from us and what we need from you when you book.',
            'sections' => $ar ? [
                ['استخدام هذا الموقع', [
                    'محتوى هذا الموقع مخصّص للتعريف بخدماتنا، ونبذل جهدنا لإبقائه دقيقًا ومحدّثًا.',
                    'نصوص الموقع وصوره مملوكة لنا، ولا يجوز نسخها لاستخدامها في موقع آخر.',
                ]],
                ['عروض الأسعار', [
                    'السعر الذي نعطيه عبر الهاتف أو واتساب أو النموذج يعتمد على ما تصفه لنا: كمية الأغراض ونوعها وسهولة الوصول إليها.',
                    'إذا اختلف الوضع على أرض الواقع عمّا تم وصفه، نخبرك بالسعر المعدّل قبل بدء العمل، ولك أن توافق أو ترفض.',
                ]],
                ['الحجز والوصول', [
                    'نتفق معك على الموعد مسبقًا. إن كان المبنى يتطلب حجز مصعد خدمة أو تصريح دخول، فيرجى ترتيب ذلك قبل وصولنا.',
                    'إذا احتجت إلى تغيير الموعد أو إلغائه، أخبرنا في أقرب وقت ممكن.',
                ]],
                ['ما لا نستطيع أخذه', [
                    'لا نتعامل مع المواد الخطرة أو الكيميائية أو الدهانات أو أسطوانات الغاز أو النفايات الطبية.',
                    'بعض أعمال الهدم والمخلفات الكبيرة تحتاج إلى مقاول مرخّص لنقلها؛ أخبرنا بحجم العمل مسبقًا.',
                ]],
                ['قبل الاستلام', [
                    'يرجى إخراج الأشياء الثمينة والمستندات الشخصية من بين الأغراض المراد نقلها.',
                    'يرجى مسح أو إزالة أي جهاز يحتوي على بيانات، وفصل الأجهزة الكهربائية عن الكهرباء والماء.',
                    'بمجرد نقل الأغراض لا يمكننا استرجاعها.',
                ]],
                ['الدفع', [
                    'نتفق على طريقة الدفع وتوقيته عند الحجز.',
                ]],
                ['التقييمات التي ترسلها', [
                    'عند إرسال تقييم فإنك تؤكد أنه يعبّر عن تجربتك الفعلية معنا.',
                    'لا ننشر التقييمات التي تحتوي على روابط أو لغة مسيئة أو بيانات شخصية لأشخاص آخرين.',
                ]],
            ] : [
                ['Using this website', [
                    'The content on this site is here to explain our services, and we try to keep it accurate and up to date.',
                    'The text and photos on this site are ours and may not be copied for use on another website.',
                ]],
                ['Quotes', [
                    'A price we give by phone, WhatsApp or through the form is based on what you describe to us — how much there is, what type of items, and how easy they are to reach.',
                    'If the job on the day turns out to be different from what was described, we tell you the revised price before any work starts, and you are free to accept it or not.',
                ]],
                ['Booking and access', [
                    'We agree a time with you in advance. If your building needs a service lift booked or a security pass arranged, please sort that out before we arrive.',
                    'If you need to change or cancel the booking, tell us as early as you can.',
                ]],
                ['What we cannot take', [
                    'We do not handle hazardous or chemical material, paint, gas cylinders or medical waste.',
                    'Some demolition work and large waste loads need a licensed waste contractor — tell us the scale of the job beforehand.',
                ]],
                ['Before we collect', [
                    'Please take valuables and personal documents out of anything that is going.',
                    'Please wipe or remove any device that holds data, and disconnect appliances from power and water.',
                    'Once items have been taken away we cannot get them back.',
                ]],
                ['Payment', [
                    'How and when you pay is agreed when you book.',
                ]],
                ['Reviews you submit', [
                    'When you send a review you confirm that it describes your own experience with us.',
                    'We do not publish reviews that contain links, abusive language, or other people’s personal details.',
                ]],
            ],
        ],
        'cookies' => [
            'slug' => 'cookie-policy/',
            'title' => $ar ? 'سياسة ملفات تعريف الارتباط' : 'Cookie Policy',
            'desc' => $ar
                ? 'ملفات تعريف الارتباط التي يستخدمها هذا الموقع، وما الذي يخزّنه Google Analytics، وكيف يمكنك إيقافها من متصفحك.'
                : 'The cookies this website uses, what Google Analytics stores, and how you can turn them off in your browser.',
            'lead' => $ar
                ? 'يستخدم هذا الموقع عددًا محدودًا جدًا من ملفات تعريف الارتباط.'
                : 'This website uses very few cookies.',
            'sections' => $ar ? [
                ['ما هي ملفات تعريف الارتباط', [
                    'هي ملفات نصية صغيرة يحفظها المتصفح على جهازك ليتذكر الموقع شيئًا ما بين الصفحات.',
                ]],
                ['ما نستخدمه', [
                    'القياس: نستخدم Google Analytics 4، الذي يضع ملفات تعريف ارتباط لمعرفة عدد الزوار والصفحات الأكثر قراءة. لا يعرّفنا ذلك بهويتك الشخصية.',
                    'لوحة التحكم: عند تسجيل دخول فريقنا إلى لوحة الإدارة يُستخدم ملف تعريف ارتباط للجلسة، وهذا لا يخص الزوار.',
                    'لا نستخدم ملفات تعريف ارتباط إعلانية أو لإعادة الاستهداف على هذا الموقع.',
                ]],
                ['كيف توقفها', [
                    'يمكنك حذف ملفات تعريف الارتباط أو حظرها من إعدادات متصفحك، وسيظل الموقع يعمل بشكل طبيعي.',
                    'يمكنك أيضًا تثبيت أداة Google الرسمية لإيقاف Google Analytics من موقع Google.',
                ]],
            ] : [
                ['What cookies are', [
                    'They are small text files your browser saves on your device so a website can remember something between pages.',
                ]],
                ['What this site uses', [
                    'Measurement: we use Google Analytics 4, which sets cookies so we can see how many people visit and which pages get read. It does not tell us who you are personally.',
                    'Admin: when our own team signs in to the admin panel a session cookie is used. That does not apply to visitors.',
                    'We do not use advertising or retargeting cookies on this site.',
                ]],
                ['Turning them off', [
                    'You can delete or block cookies in your browser settings. The site will still work normally.',
                    'You can also install Google’s own opt-out add-on for Google Analytics from Google’s website.',
                ]],
            ],
        ],
    ];

    $page = $pages[$which] ?? null;
    if ($page === null) {
        http_response_code(404);
        echo 'Page not found';
        return;
    }

    View::head([
        'lang' => $lang,
        'path' => $page['slug'],
        'active' => '',
        'title' => $page['title'] . ' | Junk Removal Team Dubai',
        'description' => $page['desc'],
    ]);

    View::pageHero($lang, [
        'crumb' => $page['title'],
        'eyebrow' => $ar ? 'روابط قانونية' : 'Legal',
        'title' => $page['title'],
        'lead' => $page['lead'],
    ]);
    ?>
  <section class="section">
    <div class="container">
      <div class="legal-doc">
<?php foreach ($page['sections'] as [$heading, $paras]): ?>
        <h2><?= View::e($heading) ?></h2>
<?php foreach ($paras as $para): ?>
        <p><?= View::e($para) ?></p>
<?php endforeach; ?>
<?php endforeach; ?>
        <p class="legal-updated"><?= $ar ? 'آخر تحديث: ' : 'Last updated: ' ?><?= View::e($updated) ?></p>
      </div>
    </div>
  </section>
<?php
    View::foot($lang);
}
