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
    public const ASSET_VERSION = '17';

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
                'all_services' => 'جميع الخدمات',
                'all_areas' => 'جميع المناطق',
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
            'all_services' => 'All Services',
            'all_areas' => 'All Areas',
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
            [
                'slug' => 'villa-clearance', 'value' => 'Villa Clearance', 'icon' => 'i-home', 'image' => 'svc-house-clearance.jpg',
                'title' => $ar ? 'إخلاء الفلل' : 'Villa Clearance',
                'alt' => $ar ? 'فيلا في دبي أثناء الإعداد لخدمة إخلاء الفلل' : 'Dubai villa being prepared for a villa clearance',
                'text' => $ar
                    ? 'إخلاء كامل أو جزئي للفلل: غرف النوم والمجلس وغرف التخزين والمرآب وأثاث الحديقة، مرتّب حول موعد الانتقال أو التسليم.'
                    : 'Full or partial villa clearances: bedrooms, majlis, storage rooms, the garage and garden furniture, planned around your move or handover date.',
                'list' => $ar
                    ? ['الفيلا بالكامل أو غرف محددة', 'غرف التخزين والمرائب', 'أثاث الحدائق والمساحات الخارجية', 'غرف الخدم والمجالس']
                    : ['The whole villa or selected rooms', 'Storage rooms and garages', 'Garden and outdoor furniture', 'Maid’s rooms and majlis'],
            ],
            [
                'slug' => 'waste-rubbish-removal', 'value' => 'Waste & Rubbish Removal', 'icon' => 'i-truck-fast', 'image' => 'svc-junk-removal.jpg',
                'title' => $ar ? 'إزالة النفايات والقمامة' : 'Waste & Rubbish Removal',
                'alt' => $ar ? 'أكياس ومواد تغليف جاهزة لإزالة النفايات' : 'Bagged rubbish and packaging ready for waste removal',
                'text' => $ar
                    ? 'جمع النفايات العامة من المنازل والمحلات والمواقع: الأكياس ومواد التغليف وما يتبقى بعد الترتيب أو التجديد.'
                    : 'General waste collected from homes, shops and sites: bagged rubbish, packaging and the odds and ends left after a clear-out.',
                'list' => $ar
                    ? ['أكياس النفايات المنزلية', 'الكراتين ومواد التغليف', 'ما يتبقى بعد الترتيب', 'نفايات المحلات والمخازن']
                    : ['Bagged household rubbish', 'Boxes and packaging', 'Leftovers after a tidy-up', 'Shop and storeroom waste'],
                'note' => $ar
                    ? 'لا نتعامل مع المواد الخطرة أو الكيميائية أو النفايات الطبية.'
                    : 'We don’t handle hazardous, chemical or medical waste.',
            ],
            [
                'slug' => 'sofa-removal', 'value' => 'Sofa Removal', 'icon' => 'i-sofa', 'image' => 'svc-furniture-removal.jpg',
                'title' => $ar ? 'إزالة الكنب' : 'Sofa Removal',
                'alt' => $ar ? 'كنبة قديمة جاهزة للإزالة من الشقة' : 'Old sofa ready to be removed from an apartment',
                'text' => $ar
                    ? 'كنبة مفردة أو طقم زاوية أو كرسي مريح — نأخذه من أي طابق، ونفكه أولًا إذا لم يمر من الباب أو المصعد.'
                    : 'A single sofa, a corner unit or an armchair — taken from any floor, and dismantled first if it won’t fit through the door or the lift.',
                'list' => $ar
                    ? ['الكنب المفرد وأطقم الزاوية', 'الكراسي المريحة والبفات', 'كنب السرير', 'فك القطع الكبيرة عند الحاجة']
                    : ['Single sofas and corner units', 'Armchairs and footstools', 'Sofa beds', 'Dismantling where needed'],
            ],
            [
                'slug' => 'mattress-removal', 'value' => 'Mattress Removal', 'icon' => 'i-box', 'image' => 'svc-furniture-removal.jpg',
                'title' => $ar ? 'إزالة المراتب' : 'Mattress Removal',
                'alt' => $ar ? 'مرتبة قديمة معدّة للنقل من غرفة النوم' : 'Old mattress being taken out of a bedroom',
                'text' => $ar
                    ? 'نأخذ المراتب وقواعد الأسرّة القديمة من أي طابق، ونغلّفها قبل حملها للخارج حتى تبقى الممرات نظيفة.'
                    : 'Old mattresses and bed bases collected from any floor, wrapped before they’re carried out so hallways stay clean.',
                'list' => $ar
                    ? ['المراتب بجميع المقاسات', 'قواعد الأسرّة والهياكل', 'مراتب السرير القابل للطي', 'الكميات الكبيرة للسكنات']
                    : ['Mattresses of any size', 'Bed bases and frames', 'Folding bed mattresses', 'Bulk lots for accommodation'],
            ],
            [
                'slug' => 'appliance-removal', 'value' => 'Appliance Removal', 'icon' => 'i-monitor', 'image' => 'svc-ewaste-disposal.jpg',
                'title' => $ar ? 'إزالة الأجهزة' : 'Appliance Removal',
                'alt' => $ar ? 'غسالة وثلاجة قديمة جاهزة للإزالة' : 'Old washing machine and fridge ready for removal',
                'text' => $ar
                    ? 'الثلاجات والغسالات والأفران ومكيفات النوافذ وغيرها من الأجهزة الكبيرة — نحملها من مكانها وننقلها بعيدًا.'
                    : 'Fridges, washing machines, ovens, window air conditioners and other large appliances carried out and taken away.',
                'list' => $ar
                    ? ['الثلاجات والمجمدات', 'الغسالات والنشافات', 'الأفران والمواقد', 'الأجهزة الصغيرة']
                    : ['Fridges and freezers', 'Washing machines and dryers', 'Ovens and cookers', 'Smaller appliances'],
                'note' => $ar
                    ? 'يرجى فصل الأجهزة عن الكهرباء والماء قبل موعد الاستلام.'
                    : 'Please disconnect appliances from power and water before the collection time.',
            ],
            [
                'slug' => 'garden-waste-removal', 'value' => 'Garden Waste Removal', 'icon' => 'i-leaf', 'image' => 'svc-house-clearance.jpg',
                'title' => $ar ? 'إزالة مخلفات الحدائق' : 'Garden Waste Removal',
                'alt' => $ar ? 'أثاث حديقة ومخلفات نباتية جاهزة للإزالة' : 'Garden furniture and cuttings ready for removal',
                'text' => $ar
                    ? 'أثاث الحدائق ومخلفات التقليم والأغراض المتراكمة في المساحات الخارجية للفلل والشرفات.'
                    : 'Garden furniture, cuttings and the clutter that builds up in villa gardens, yards and balconies.',
                'list' => $ar
                    ? ['أثاث الحدائق والمظلات', 'مخلفات التقليم والنباتات', 'الأحواض وأكياس التربة', 'أغراض الشرفات والأسطح']
                    : ['Garden furniture and parasols', 'Cuttings and green waste', 'Pots and soil bags', 'Balcony and terrace items'],
            ],
            [
                'slug' => 'commercial-junk-removal', 'value' => 'Commercial Junk Removal', 'icon' => 'i-building', 'image' => 'svc-office-clearance.jpg',
                'title' => $ar ? 'إزالة المخلفات التجارية' : 'Commercial Junk Removal',
                'alt' => $ar ? 'مخزن تجاري أثناء إزالة الأغراض غير المستخدمة' : 'Commercial storeroom being cleared of unused stock and fittings',
                'text' => $ar
                    ? 'خدمة للمحلات والمطاعم والمستودعات ومديري المرافق: إخلاء المخازن والتجهيزات القديمة ومخلفات التشطيب، بمواعيد تناسب عملك.'
                    : 'For shops, restaurants, warehouses and facilities managers: storerooms cleared, old fittings taken out and fit-out debris removed, at a time that suits your business.',
                'list' => $ar
                    ? ['المخازن والمستودعات', 'تجهيزات المحلات والعرض', 'الطبالي ومواد التغليف', 'مخلفات ما بعد التشطيب']
                    : ['Storerooms and warehouses', 'Shop fittings and display units', 'Pallets and packaging', 'Debris after a fit-out'],
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

    /**
     * Extra content for the individual service pages, keyed by slug.
     * VERIFY C / D1 / D2 / E1–E3 / E11 / E14 — see docs/00-business-verification.md.
     */
    public static function serviceDetail(string $lang, string $slug): ?array
    {
        $ar = $lang === 'ar';

        $detail = [
            'junk-removal' => [
                'intro' => $ar
                    ? ['إذا تراكمت لديك أغراض لم تعد تحتاجها، فنحن نأتي ونأخذها. نعمل في الشقق والفلل والمكاتب والمحلات في جميع أنحاء دبي، سواء كانت قطعة واحدة كبيرة أو حمولة شاحنة كاملة.',
                       'أنت تخبرنا بما تريد التخلص منه، ونتفق على السعر قبل البدء، ثم يتولى فريقنا الحمل والتحميل والنقل. لا تحتاج إلى إنزال أي شيء بنفسك.']
                    : ['When things pile up that you no longer need, we come and take them away. We work in apartments, villas, offices and shops across Dubai, whether it’s one bulky item or a full truckload.',
                       'You tell us what needs to go, we agree the price before starting, then our team carries, loads and removes it. You don’t need to bring anything downstairs yourself.'],
                'includes' => $ar
                    ? ['الحمل من داخل العقار', 'التحميل في الشاحنة', 'نقل كل ما تم الاتفاق عليه', 'كنس المكان بعد الانتهاء']
                    : ['Carrying items out from inside the property', 'Loading into the truck', 'Removing everything agreed', 'A quick tidy-up of the space afterwards'],
                'good_for' => $ar
                    ? ['أصحاب المنازل والمستأجرون', 'الملاك ومديرو العقارات', 'المكاتب والمحلات الصغيرة']
                    : ['Homeowners and tenants', 'Landlords and property managers', 'Small offices and shops'],
                'faqs' => $ar
                    ? [['هل يجب أن أنزل الأغراض بنفسي؟', 'لا. يتولى فريقنا إخراج الأغراض من داخل العقار وتحميلها في الشاحنة.'],
                       ['هل يمكنكم أخذ قطعة واحدة فقط؟', 'نعم، نتعامل مع القطع المفردة كما نتعامل مع الحمولات الكاملة. أرسل صورة وسنحدد لك السعر.'],
                       ['كيف يتم تحديد السعر؟', 'يعتمد على حجم الأغراض ونوعها وسهولة الوصول إليها. نؤكد السعر قبل بدء العمل.']]
                    : [['Do I need to bring things downstairs myself?', 'No. Our team carries items out from inside the property and loads them into the truck.'],
                       ['Can you take just one item?', 'Yes — single items are as welcome as full loads. Send a photo and we’ll give you a price.'],
                       ['How is the price worked out?', 'It depends on the volume, the type of items and how easy they are to reach. We confirm the price before any work starts.']],
                'related' => ['furniture-removal', 'house-clearance'],
            ],
            'furniture-removal' => [
                'intro' => $ar
                    ? ['نأخذ الأثاث الذي لم تعد تريده من المنازل والمكاتب: الكنب والأسرّة والمراتب والخزائن والطاولات والمكاتب وأثاث الحدائق.',
                       'القطع الكبيرة لا تمر دائمًا من الباب أو المصعد، لذلك نفكها عند الحاجة قبل إخراجها.']
                    : ['We take furniture you no longer want from homes and offices: sofas, beds, mattresses, wardrobes, tables, desks and garden furniture.',
                       'Large pieces don’t always fit through a door or a lift, so we take them apart where needed before carrying them out.'],
                'includes' => $ar
                    ? ['فك القطع الكبيرة عند الحاجة', 'الحمل من أي طابق', 'حماية الممرات أثناء الإخراج', 'نقل الأثاث بعيدًا']
                    : ['Dismantling large pieces where needed', 'Carrying from any floor', 'Care taken on the way out', 'Taking the furniture away'],
                'good_for' => $ar
                    ? ['من يستبدل أثاثه', 'الملاك بين المستأجرين', 'المكاتب التي تجدد أثاثها']
                    : ['Anyone replacing furniture', 'Landlords between tenants', 'Offices refitting a floor'],
                'faqs' => $ar
                    ? [['هل تفكّون الأسرّة والخزائن؟', 'نعم، عندما لا تمر القطعة من الباب أو المصعد نفكها أولًا.'],
                       ['هل تأخذون المراتب؟', 'نعم، المراتب من أكثر القطع التي ننقلها.'],
                       ['ماذا لو كان الأثاث في طابق مرتفع بلا مصعد؟', 'ما زلنا نستطيع أخذه، لكن أخبرنا بالطابق مسبقًا لأن ذلك يؤثر في السعر.']]
                    : [['Do you dismantle beds and wardrobes?', 'Yes. When a piece won’t fit through a door or lift, we take it apart first.'],
                       ['Do you take mattresses?', 'Yes — mattresses are one of the items we move most often.'],
                       ['What if the furniture is on a high floor with no lift?', 'We can still take it, but tell us the floor beforehand because it affects the price.']],
                'related' => ['junk-removal', 'house-clearance'],
            ],
            'house-clearance' => [
                'intro' => $ar
                    ? ['نُخلي الشقق والفلل بالكامل أو جزئيًا: قبل الانتقال، أو عند تسليم العقار، أو قبل البيع أو التجديد.',
                       'نخطط العمل حول موعدك، ونتفق معك على الغرف والمساحات التي تريد إخلاءها وما الذي يبقى.']
                    : ['We clear apartments and villas, fully or in part: before a move, at handover, or ahead of a sale or renovation.',
                       'We plan the work around your date and agree with you which rooms and spaces to clear, and what stays.'],
                'includes' => $ar
                    ? ['إخلاء غرفة واحدة أو العقار بالكامل', 'غرف التخزين والمرائب والشرفات', 'الأثاث والأجهزة والأغراض المتفرقة', 'التنسيق مع مواعيد التسليم']
                    : ['One room or the whole property', 'Storage rooms, garages and balconies', 'Furniture, appliances and loose items', 'Working to your handover date'],
                'good_for' => $ar
                    ? ['المستأجرون عند نهاية العقد', 'العائلات المنتقلة', 'الملاك ووكلاء العقارات']
                    : ['Tenants at the end of a lease', 'Families relocating', 'Landlords and property agents'],
                'faqs' => $ar
                    ? [['كم من الوقت يستغرق إخلاء شقة؟', 'يعتمد على حجم العقار وكمية الأغراض. أخبرنا بالتفاصيل وسنقدّر لك المدة قبل الحجز.'],
                       ['هل يمكنكم العمل في يوم التسليم؟', 'أخبرنا بالموعد مبكرًا قدر الإمكان حتى نرتب العمل قبله.'],
                       ['هل تنظفون العقار بعد الإخلاء؟', 'نكنس المكان بعد إخراج الأغراض، لكننا لسنا شركة تنظيف عميق.']]
                    : [['How long does clearing an apartment take?', 'It depends on the size of the property and how much there is. Tell us the details and we’ll estimate the time before you book.'],
                       ['Can you work on my handover day?', 'Tell us the date as early as you can so we can schedule the work before it.'],
                       ['Do you clean the property afterwards?', 'We tidy up after removing everything, but we are not a deep-cleaning company.']],
                'related' => ['furniture-removal', 'junk-removal'],
            ],
            'office-clearance' => [
                'intro' => $ar
                    ? ['نُخلي المكاتب عند الانتقال أو تقليص المساحة أو تسليم الوحدة إلى المالك.',
                       'نرتب العمل وفق قواعد المبنى: أوقات الدخول، ومصعد الخدمة، وتصاريح الأمن، حتى لا يتعطل عملك.']
                    : ['We clear offices when you relocate, downsize or hand a unit back to the landlord.',
                       'We plan around your building’s rules — access times, the service lift and security passes — so your business isn’t disrupted.'],
                'includes' => $ar
                    ? ['محطات العمل والمكاتب والكراسي', 'خزائن الملفات والأرفف', 'الفواصل والأثاث المركب', 'الأغراض المكتبية العامة']
                    : ['Workstations, desks and chairs', 'Filing cabinets and shelving', 'Partitions and built-up furniture', 'General office clutter'],
                'good_for' => $ar
                    ? ['الشركات الصغيرة والمتوسطة', 'مديرو المرافق', 'ملاك الوحدات التجارية']
                    : ['Small and medium businesses', 'Facilities managers', 'Commercial landlords'],
                'faqs' => $ar
                    ? [['هل يمكنكم العمل خارج ساعات الدوام؟', 'أخبرنا بالوقت الذي يناسب مبناك وسنرى ما يمكننا ترتيبه.'],
                       ['ماذا عن أجهزة الكمبيوتر التي تحتوي على بيانات؟', 'يرجى إزالة الأقراص الصلبة أو مسحها قبل الاستلام. نحن لا نقدم خدمة إتلاف البيانات.'],
                       ['هل تصدرون فاتورة للشركة؟', 'أخبرنا باسم الشركة وتفاصيلها عند الحجز.']]
                    : [['Can you work outside office hours?', 'Tell us what time suits your building and we’ll see what we can arrange.'],
                       ['What about computers that hold data?', 'Please remove or wipe hard drives before collection. We don’t offer a data destruction service.'],
                       ['Can you invoice the company?', 'Tell us the company name and details when you book.']],
                'related' => ['construction-waste', 'e-waste-disposal'],
            ],
            'construction-waste' => [
                'intro' => $ar
                    ? ['بعد أعمال التجديد أو التشطيب تبقى مخلفات لا يمكن وضعها في حاويات المبنى العادية. نأتي ونأخذها من المنازل والوحدات التجارية.',
                       'أخبرنا بنوع المخلفات وكميتها وأين توجد بالضبط، لأن الركام ثقيل ويؤثر ذلك في طريقة التحميل والسعر.']
                    : ['Renovation and fit-out work leaves debris that can’t go in the building’s normal bins. We collect it from homes and commercial units.',
                       'Tell us the type of debris, roughly how much there is and exactly where it sits — rubble is heavy, and that changes how we load and price the job.'],
                'includes' => $ar
                    ? ['الركام والبلاط المكسور', 'التركيبات والتجهيزات القديمة', 'الخشب والألواح ومواد التغليف', 'بقايا أعمال التشطيب']
                    : ['Rubble and broken tiles', 'Old fixtures and fittings', 'Wood, board and packaging', 'Fit-out leftovers'],
                'good_for' => $ar
                    ? ['أصحاب المنازل بعد التجديد', 'مقاولو التشطيبات', 'ملاك الوحدات التجارية']
                    : ['Homeowners after a renovation', 'Fit-out contractors', 'Commercial unit owners'],
                'faqs' => $ar
                    ? [['هل تأخذون مخلفات هدم كاملة؟', 'أخبرنا بحجم العمل أولًا؛ بعض المشاريع تحتاج إلى مقاول مرخّص لنقل المخلفات.'],
                       ['هل يجب أن أضع المخلفات في أكياس؟', 'يساعد ذلك كثيرًا في التحميل، لكن أخبرنا بالوضع الحالي وسنرتب الأمر.'],
                       ['هل تأخذون المواد الخطرة؟', 'لا. المواد الكيميائية والدهانات وأسطوانات الغاز خارج نطاق خدمتنا.']]
                    : [['Do you take full demolition waste?', 'Tell us the scale of the job first — some projects need a licensed waste contractor.'],
                       ['Should I bag the debris first?', 'It helps with loading, but tell us how it is now and we’ll plan around it.'],
                       ['Do you take hazardous material?', 'No. Chemicals, paint and gas cylinders are outside what we handle.']],
                'related' => ['office-clearance', 'junk-removal'],
            ],
            'e-waste-disposal' => [
                'intro' => $ar
                    ? ['نجمع الأجهزة الإلكترونية والكهربائية القديمة من المنازل والمكاتب: الشاشات وأجهزة الكمبيوتر والطابعات والأجهزة الصغيرة.',
                       'هذه الأجهزة لا ينبغي أن توضع مع النفايات المنزلية العادية، لذلك نأخذها ضمن عملية الإخلاء نفسها.']
                    : ['We collect old electronics and appliances from homes and offices: screens, computers, printers and small appliances.',
                       'These shouldn’t go out with ordinary household rubbish, so we take them as part of the same collection.'],
                'includes' => $ar
                    ? ['التلفزيونات والشاشات', 'أجهزة الكمبيوتر والطابعات', 'الكابلات والملحقات', 'الأجهزة الكهربائية الصغيرة']
                    : ['TVs and monitors', 'Computers and printers', 'Cables and accessories', 'Small appliances'],
                'good_for' => $ar
                    ? ['المكاتب التي تجدد أجهزتها', 'المنازل بعد الترقية', 'المحلات والمخازن']
                    : ['Offices upgrading equipment', 'Homes after an upgrade', 'Shops and storerooms'],
                'faqs' => $ar
                    ? [['هل تأخذون الثلاجات والغسالات؟', 'نعم، الأجهزة الكبيرة تُجمع ضمن خدمة إزالة المخلفات.'],
                       ['ماذا يحدث للأجهزة بعد جمعها؟', 'أخبرنا إن كنت تحتاج تفاصيل مسار التخلص، وسنوضح لك ما ينطبق على أغراضك.'],
                       ['هل أمسح بياناتي قبل التسليم؟', 'نعم، يرجى مسح أو إزالة أي جهاز يحتوي على بيانات قبل الاستلام.']]
                    : [['Do you take fridges and washing machines?', 'Yes — larger appliances are collected as part of junk removal.'],
                       ['What happens to the equipment afterwards?', 'Ask us if you need the disposal route for your items and we’ll explain what applies.'],
                       ['Should I wipe my data first?', 'Yes. Please wipe or remove anything that holds data before collection.']],
                'related' => ['office-clearance', 'junk-removal'],
            ],
            'villa-clearance' => [
                'intro' => $ar
                    ? ['الفيلا فيها مساحات أكثر مما يظهر: غرف التخزين والمرآب وغرفة الخادمة والحديقة. نُخليها بالكامل أو نأخذ ما تحدده أنت فقط.',
                       'نتفق معك قبل البدء على ما يخرج وما يبقى، ثم نعمل غرفة بعد أخرى حتى ينتهي كل ما تم الاتفاق عليه.']
                    : ['A villa holds more space than it looks: storage rooms, the garage, the maid’s room and the garden. We clear all of it, or only the parts you point out.',
                       'We agree before we start what goes and what stays, then work room by room until everything agreed is done.'],
                'includes' => $ar
                    ? ['الفيلا بالكامل أو غرف محددة', 'المرآب وغرف التخزين', 'أثاث الحديقة والمساحات الخارجية', 'الأثاث والأجهزة الكبيرة']
                    : ['The whole villa or selected rooms', 'The garage and storage rooms', 'Garden and outdoor furniture', 'Large furniture and appliances'],
                'good_for' => $ar
                    ? ['العائلات عند الانتقال', 'الملاك قبل التسليم أو البيع', 'وكلاء العقارات']
                    : ['Families relocating', 'Owners before a handover or sale', 'Property agents'],
                'faqs' => $ar
                    ? [['هل يمكنكم إخلاء جزء من الفيلا فقط؟', 'نعم. حدّد الغرف أو المساحات التي تريدها وسنعمل عليها فقط.'],
                       ['هل تأخذون أثاث الحديقة والمظلات؟', 'نعم، الأغراض الخارجية تُجمع ضمن العمل نفسه.'],
                       ['كم من الوقت يستغرق إخلاء فيلا؟', 'يعتمد على عدد الغرف وكمية الأغراض. أرسل صورًا أو تفاصيل وسنقدّر لك المدة قبل الحجز.']]
                    : [['Can you clear only part of the villa?', 'Yes. Point out the rooms or areas you want cleared and we work on those only.'],
                       ['Do you take garden furniture and parasols?', 'Yes — outdoor items are collected in the same job.'],
                       ['How long does a villa take?', 'It depends on the number of rooms and how much there is. Send photos or details and we’ll estimate the time before you book.']],
                'related' => ['house-clearance', 'furniture-removal'],
            ],
            'waste-rubbish-removal' => [
                'intro' => $ar
                    ? ['بعد الترتيب أو التجديد أو الانتقال تبقى أكياس وكراتين وأغراض متفرقة لا تتسع لها حاويات المبنى. نأتي ونأخذها دفعة واحدة.',
                       'أخبرنا بالكمية تقريبًا وأين توجد، وسنحدد السعر والموعد قبل الحضور.']
                    : ['After a tidy-up, a renovation or a move there are bags, boxes and loose items that the building’s bins can’t take. We collect the lot in one go.',
                       'Tell us roughly how much there is and where it sits, and we’ll confirm the price and the time before we come.'],
                'includes' => $ar
                    ? ['أكياس النفايات المنزلية', 'الكراتين ومواد التغليف', 'الأغراض المتفرقة بعد الترتيب', 'الحمل من داخل العقار']
                    : ['Bagged household rubbish', 'Boxes and packaging', 'Loose items after a clear-out', 'Carrying it out from inside'],
                'good_for' => $ar
                    ? ['المنازل بعد الترتيب الكبير', 'المحلات والمخازن الصغيرة', 'الملاك بين المستأجرين']
                    : ['Homes after a big tidy-up', 'Shops and small storerooms', 'Landlords between tenants'],
                'faqs' => $ar
                    ? [['هل يجب أن أضع كل شيء في أكياس؟', 'ليس شرطًا، لكنه يسرّع العمل. أخبرنا بالوضع كما هو.'],
                       ['هل تأخذون المواد الخطرة؟', 'لا. المواد الكيميائية والدهانات وأسطوانات الغاز والنفايات الطبية خارج نطاق خدمتنا.'],
                       ['هل هناك حد أدنى للكمية؟', 'نتعامل مع الكميات الصغيرة والكبيرة. أرسل صورة وسنحدد السعر.']]
                    : [['Do I need to bag everything first?', 'Not necessarily, though it speeds the job up. Tell us how it is now.'],
                       ['Do you take hazardous material?', 'No. Chemicals, paint, gas cylinders and medical waste are outside what we handle.'],
                       ['Is there a minimum load?', 'We handle small loads as well as large ones. Send a photo and we’ll price it.']],
                'related' => ['junk-removal', 'construction-waste'],
            ],
            'sofa-removal' => [
                'intro' => $ar
                    ? ['الكنب من أكثر ما يصعب إخراجه: ثقيل، وغالبًا لا يمر من الباب أو المصعد. نتولى ذلك عنك.',
                       'أرسل صورة للكنبة وأخبرنا بالطابق ووجود مصعد من عدمه، وسنصلك بالعدد المناسب من الأشخاص والأدوات اللازمة.']
                    : ['A sofa is one of the harder things to get out: heavy, and often too big for the door or the lift. We deal with that part.',
                       'Send a photo of the sofa and tell us the floor and whether there’s a lift — we’ll come with the right number of people and the tools to take it apart.'],
                'includes' => $ar
                    ? ['الحمل من أي طابق', 'فك القطعة عند الحاجة', 'حماية الممرات والجدران', 'النقل بعيدًا في اليوم نفسه المتفق عليه']
                    : ['Carrying from any floor', 'Dismantling the piece where needed', 'Care taken in hallways and lifts', 'Taking it away on the agreed visit'],
                'good_for' => $ar
                    ? ['من يستبدل كنبته', 'الشقق في الطوابق العليا', 'الملاك والمستأجرون']
                    : ['Anyone replacing a sofa', 'Upper-floor apartments', 'Landlords and tenants'],
                'faqs' => $ar
                    ? [['ماذا لو لم تمر الكنبة من الباب؟', 'نفكها قبل الإخراج. هذا جزء معتاد من العمل.'],
                       ['هل تأخذون كنبة واحدة فقط؟', 'نعم، القطعة الواحدة مرحّب بها.'],
                       ['هل تحتاجون إلى حجز مصعد الخدمة؟', 'في كثير من الأبراج نعم. أخبر إدارة المبنى بالموعد بعد تأكيده.']]
                    : [['What if the sofa won’t fit through the door?', 'We take it apart before carrying it out — that’s a normal part of the job.'],
                       ['Will you take just one sofa?', 'Yes, single items are welcome.'],
                       ['Does the service lift need booking?', 'In many towers, yes. Let your building know once the time is confirmed.']],
                'related' => ['furniture-removal', 'mattress-removal'],
            ],
            'mattress-removal' => [
                'intro' => $ar
                    ? ['نأخذ المراتب وقواعد الأسرّة القديمة من الشقق والفلل وسكنات الموظفين، من أي طابق.',
                       'نغلّف المرتبة قبل حملها للخارج حتى تبقى الممرات والمصاعد نظيفة.']
                    : ['We take old mattresses and bed bases from apartments, villas and staff accommodation, from any floor.',
                       'The mattress is wrapped before it’s carried out so hallways and lifts stay clean.'],
                'includes' => $ar
                    ? ['المراتب بجميع المقاسات', 'قواعد الأسرّة والهياكل', 'التغليف قبل الحمل', 'الكميات الكبيرة عند الحاجة']
                    : ['Mattresses of any size', 'Bed bases and frames', 'Wrapping before carrying', 'Bulk lots where needed'],
                'good_for' => $ar
                    ? ['المنازل عند تجديد الأسرّة', 'سكنات الموظفين', 'الشقق المفروشة']
                    : ['Homes replacing beds', 'Staff accommodation', 'Furnished apartments'],
                'faqs' => $ar
                    ? [['كم مرتبة يمكنكم أخذها؟', 'من مرتبة واحدة إلى حمولة كاملة. أخبرنا بالعدد وسنرسل المركبة المناسبة.'],
                       ['هل تفكّون السرير أيضًا؟', 'نعم، نفك الهيكل عند الحاجة لإخراجه.'],
                       ['هل تأخذون المراتب المتضررة؟', 'نعم. أخبرنا بحالتها عند الحجز.']]
                    : [['How many mattresses can you take?', 'From one to a full load. Tell us the number and we’ll send the right vehicle.'],
                       ['Do you dismantle the bed as well?', 'Yes — we take the frame apart when that’s needed to get it out.'],
                       ['Do you take damaged mattresses?', 'Yes. Just tell us the condition when you book.']],
                'related' => ['furniture-removal', 'sofa-removal'],
            ],
            'appliance-removal' => [
                'intro' => $ar
                    ? ['الأجهزة الكبيرة ثقيلة وغير عملية للحمل على الدرج. نأتي بالعدد المناسب من الأشخاص ونأخذها من مكانها.',
                       'يرجى فصل الجهاز عن الكهرباء والماء قبل الموعد، وتفريغ الثلاجة إن أمكن.']
                    : ['Large appliances are heavy and awkward on a staircase. We come with enough people and take them from where they stand.',
                       'Please disconnect the appliance from power and water before the visit, and empty a fridge if you can.'],
                'includes' => $ar
                    ? ['الثلاجات والمجمدات', 'الغسالات والنشافات', 'الأفران والمواقد ومكيفات النوافذ', 'الحمل من أي طابق']
                    : ['Fridges and freezers', 'Washing machines and dryers', 'Ovens, cookers and window ACs', 'Carrying from any floor'],
                'good_for' => $ar
                    ? ['المنازل عند ترقية الأجهزة', 'الشقق المفروشة والملاك', 'المطاعم والمحلات الصغيرة']
                    : ['Homes upgrading appliances', 'Furnished apartments and landlords', 'Restaurants and small shops'],
                'faqs' => $ar
                    ? [['هل تفصلون الجهاز عن التوصيلات؟', 'لا. يرجى فصل الكهرباء والماء قبل وصولنا.'],
                       ['هل تأخذون المكيفات المركّبة؟', 'مكيفات النوافذ نعم. أما الأنظمة المركّبة في السقف فتحتاج إلى فني لفكّها أولًا.'],
                       ['هل تأخذون أكثر من جهاز في زيارة واحدة؟', 'نعم، أخبرنا بالقائمة كاملة وسنسعّرها معًا.']]
                    : [['Do you disconnect the appliance?', 'No. Please disconnect power and water before we arrive.'],
                       ['Do you take air conditioners?', 'Window units, yes. Fitted split systems need a technician to remove them first.'],
                       ['Can you take several appliances in one visit?', 'Yes — send us the full list and we’ll price it together.']],
                'related' => ['e-waste-disposal', 'junk-removal'],
            ],
            'garden-waste-removal' => [
                'intro' => $ar
                    ? ['المساحات الخارجية تتراكم فيها الأشياء بهدوء: أثاث حديقة قديم، أحواض مكسورة، مخلفات تقليم، وأغراض لم تعد تُستخدم.',
                       'نُخلي الحديقة أو الفناء أو الشرفة ونأخذ كل ما تم الاتفاق عليه في الزيارة نفسها.']
                    : ['Outdoor space fills up quietly: old garden furniture, broken pots, cuttings and things that stopped being used.',
                       'We clear the garden, yard or balcony and take everything agreed away in the same visit.'],
                'includes' => $ar
                    ? ['أثاث الحدائق والمظلات', 'مخلفات التقليم والنباتات', 'الأحواض وأكياس التربة', 'أغراض الشرفات والأسطح']
                    : ['Garden furniture and parasols', 'Cuttings and green waste', 'Pots and soil bags', 'Balcony and terrace items'],
                'good_for' => $ar
                    ? ['أصحاب الفلل', 'الملاك قبل التسليم', 'المجمعات السكنية']
                    : ['Villa owners', 'Landlords before a handover', 'Residential communities'],
                'faqs' => $ar
                    ? [['هل تقومون بأعمال التشذيب؟', 'لا، نحن ننقل المخلفات فقط. إذا كانت جاهزة فسنأخذها.'],
                       ['هل تأخذون التربة والرمل؟', 'أخبرنا بالكمية أولًا؛ الوزن يؤثر في السعر وفي نوع المركبة.'],
                       ['هل يجب أن أجمع المخلفات مسبقًا؟', 'ليس شرطًا، لكن ذلك يجعل العمل أسرع وأقل تكلفة.']]
                    : [['Do you do the trimming as well?', 'No — we remove the waste. If it’s already cut, we’ll take it.'],
                       ['Do you take soil and sand?', 'Tell us the quantity first; weight affects the price and the vehicle we send.'],
                       ['Should I pile it up beforehand?', 'Not required, but it makes the job quicker and cheaper.']],
                'related' => ['house-clearance', 'waste-rubbish-removal'],
            ],
            'commercial-junk-removal' => [
                'intro' => $ar
                    ? ['نعمل مع المحلات والمطاعم والمستودعات ومديري المرافق لإخلاء ما لم يعد مستخدمًا: مخزون قديم، تجهيزات، أثاث، ومخلفات تشطيب.',
                       'نرتب الموعد حول ساعات عملك وقواعد المبنى حتى لا يتأثر نشاطك أثناء العمل.']
                    : ['We work with shops, restaurants, warehouses and facilities managers to clear what’s no longer used: old stock, fittings, furniture and fit-out debris.',
                       'We schedule around your trading hours and the building’s rules so the work doesn’t get in the way of business.'],
                'includes' => $ar
                    ? ['المخازن والمستودعات', 'تجهيزات المحلات ووحدات العرض', 'الطبالي ومواد التغليف', 'مخلفات ما بعد التشطيب']
                    : ['Storerooms and warehouses', 'Shop fittings and display units', 'Pallets and packaging', 'Debris after a fit-out'],
                'good_for' => $ar
                    ? ['المحلات والمطاعم', 'المستودعات والوحدات الصناعية', 'مديرو المرافق والعقارات']
                    : ['Shops and restaurants', 'Warehouses and industrial units', 'Facilities and property managers'],
                'faqs' => $ar
                    ? [['هل يمكنكم العمل خارج ساعات الدوام؟', 'أخبرنا بالوقت الذي يناسب موقعك وسنرى ما يمكننا ترتيبه.'],
                       ['هل تصدرون فاتورة باسم الشركة؟', 'نعم، أرسل اسم الشركة وتفاصيلها عند الحجز.'],
                       ['هل تتعاملون مع المواقع المتكررة؟', 'أخبرنا بعدد المواقع وجدولها وسنرتب السعر على هذا الأساس.']]
                    : [['Can you work outside trading hours?', 'Tell us what time suits your site and we’ll see what we can arrange.'],
                       ['Can you invoice the company?', 'Yes — send the company name and details when you book.'],
                       ['Do you handle repeat or multi-site work?', 'Tell us how many sites and how often, and we’ll price it on that basis.']],
                'related' => ['office-clearance', 'construction-waste'],
            ],
        ];

        if (!isset($detail[$slug])) {
            return null;
        }

        foreach (self::services($lang) as $service) {
            if ($service['slug'] === $slug) {
                return $service + $detail[$slug];
            }
        }

        return null;
    }

    /**
     * Area pages. The property mix below is general knowledge about each part
     * of Dubai, not a claim about jobs done there.
     * VERIFY F1: confirm every area is genuinely served before promoting these.
     */
    public static function areaDetail(string $lang, ?string $slug = null): array|null
    {
        $ar = $lang === 'ar';

        // The jobs that come up most often for each kind of neighbourhood
        $typical = $ar ? [
            'villa' => ['إخلاء الفلل بالكامل أو جزئيًا', 'أثاث الحدائق والمساحات الخارجية', 'غرف التخزين والمرائب', 'الأثاث والأجهزة القديمة'],
            'apartment' => ['إخلاء الشقق عند الانتقال', 'الكنب والأسرّة والمراتب', 'الأجهزة المنزلية', 'أغراض الشرفات'],
            'mixed' => ['إخلاء الشقق والفلل', 'الأثاث والأجهزة القديمة', 'أغراض المكاتب والمحلات', 'مخلفات ما بعد التجديد'],
            'commercial' => ['إخلاء المستودعات والمخازن', 'أثاث المكاتب والورش', 'الطبالي ومواد التغليف', 'مخلفات ما بعد التشطيب'],
        ] : [
            'villa' => ['Full or partial villa clearances', 'Garden and outdoor furniture', 'Storage rooms and garages', 'Old furniture and appliances'],
            'apartment' => ['Move-out apartment clearances', 'Sofas, beds and mattresses', 'Household appliances', 'Balcony items'],
            'mixed' => ['Apartment and villa clearances', 'Old furniture and appliances', 'Office and shop items', 'Post-renovation debris'],
            'commercial' => ['Warehouse and storeroom clear-outs', 'Office and workshop furniture', 'Pallets and packaging waste', 'Debris after a fit-out'],
        ];

        $extra = [
            'jumeirah-lake-towers' => [
                'name' => $ar ? 'أبراج بحيرات جميرا' : 'Jumeirah Lake Towers',
                'blurb' => $ar
                    ? 'أبراج سكنية ومكاتب صغيرة في مجموعات، وعادةً يُحجز مصعد الخدمة ومنطقة التحميل مسبقًا.'
                    : 'Clusters of towers with apartments and small offices, where the service lift and loading bay are booked in advance.',
                'type' => 'apartment',
            ],
            'palm-jumeirah' => [
                'name' => $ar ? 'نخلة جميرا' : 'Palm Jumeirah',
                'blurb' => $ar
                    ? 'فلل على السعف وشقق على الجذع، مع بوابات ونقاط دخول محددة.'
                    : 'Villas on the fronds and apartments along the trunk, with gated access points.',
                'type' => 'villa',
            ],
            'dubai-hills-estate' => [
                'name' => $ar ? 'دبي هيلز إستيت' : 'Dubai Hills Estate',
                'blurb' => $ar
                    ? 'فلل وتاون هاوس ومبانٍ سكنية حديثة، مع شوارع واسعة تسهّل وصول الشاحنات.'
                    : 'Newer villas, townhouses and apartment buildings, with wide streets that make truck access easy.',
                'type' => 'villa',
            ],
            'arabian-ranches' => [
                'name' => $ar ? 'المرابع العربية' : 'Arabian Ranches',
                'blurb' => $ar
                    ? 'مجتمع فلل عائلية بحدائق ومرائب ومساحات تخزين.'
                    : 'A community of family villas with gardens, garages and storage space.',
                'type' => 'villa',
            ],
            'damac-hills' => [
                'name' => $ar ? 'داماك هيلز' : 'DAMAC Hills',
                'blurb' => $ar
                    ? 'فلل وتاون هاوس وشقق داخل مجتمع مسوّر.'
                    : 'Villas, townhouses and apartments inside a gated community.',
                'type' => 'villa',
            ],
            'emirates-hills' => [
                'name' => $ar ? 'تلال الإمارات' : 'Emirates Hills',
                'blurb' => $ar
                    ? 'فلل خاصة كبيرة بحدائق واسعة وأكثر من مساحة تخزين.'
                    : 'Large private villas with gardens and more than one storage area.',
                'type' => 'villa',
            ],
            'mirdif' => [
                'name' => $ar ? 'مردف' : 'Mirdif',
                'blurb' => $ar
                    ? 'فلل عائلية ومبانٍ سكنية منخفضة، وغالبًا موقف السيارة أمام الباب مباشرة.'
                    : 'Family villas and low-rise apartment buildings, usually with parking right at the door.',
                'type' => 'mixed',
            ],
            'deira' => [
                'name' => $ar ? 'ديرة' : 'Deira',
                'blurb' => $ar
                    ? 'مبانٍ سكنية أقدم ومحلات ومكاتب صغيرة في منطقة مزدحمة.'
                    : 'Older apartment buildings, shops and small offices in a busy part of the city.',
                'type' => 'mixed',
            ],
            'bur-dubai' => [
                'name' => $ar ? 'بر دبي' : 'Bur Dubai',
                'blurb' => $ar
                    ? 'شقق ومحلات ومكاتب في واحدة من أقدم مناطق دبي.'
                    : 'Apartments, shops and offices in one of the oldest parts of Dubai.',
                'type' => 'mixed',
            ],
            'international-city' => [
                'name' => $ar ? 'المدينة العالمية' : 'International City',
                'blurb' => $ar
                    ? 'مجمعات سكنية منخفضة الارتفاع مع مواقف مشتركة.'
                    : 'Low-rise apartment clusters with shared parking areas.',
                'type' => 'apartment',
            ],
            'discovery-gardens' => [
                'name' => $ar ? 'ديسكفري جاردنز' : 'Discovery Gardens',
                'blurb' => $ar
                    ? 'مبانٍ سكنية منخفضة بسلالم مشتركة ومواقف قريبة.'
                    : 'Low-rise apartment buildings with shared stairwells and parking close by.',
                'type' => 'apartment',
            ],
            'dubai-silicon-oasis' => [
                'name' => $ar ? 'واحة دبي للسيليكون' : 'Dubai Silicon Oasis',
                'blurb' => $ar
                    ? 'شقق وفلل ووحدات تجارية جنبًا إلى جنب.'
                    : 'Apartments, villas and business units side by side.',
                'type' => 'mixed',
            ],
            'jebel-ali' => [
                'name' => $ar ? 'جبل علي' : 'Jebel Ali',
                'blurb' => $ar
                    ? 'وحدات صناعية ومستودعات وسكن للعمال.'
                    : 'Industrial units, warehouses and staff accommodation.',
                'type' => 'commercial',
            ],
            'dubai-south' => [
                'name' => $ar ? 'دبي الجنوب' : 'Dubai South',
                'blurb' => $ar
                    ? 'أحياء سكنية حديثة ووحدات أعمال ولوجستيات.'
                    : 'Newer residential districts alongside business and logistics units.',
                'type' => 'mixed',
            ],
        ];

        $areas = [
            'al-quoz' => [
                'name' => $ar ? 'القوز' : 'Al Quoz',
                'blurb' => $ar
                    ? 'منطقة تجمع بين المستودعات والمكاتب والورش والمناطق السكنية، ومقرنا فيها.'
                    : 'A mix of warehouses, offices, workshops and homes — and where we are based.',
                'typical' => $ar
                    ? ['إخلاء المستودعات والمخازن', 'أثاث المكاتب والورش', 'مخلفات ما بعد التشطيب', 'إخلاء المنازل والشقق']
                    : ['Warehouse and storeroom clear-outs', 'Office and workshop furniture', 'Leftovers after a fit-out', 'Home and apartment clearances'],
            ],
            'al-barsha' => [
                'name' => $ar ? 'البرشاء' : 'Al Barsha',
                'blurb' => $ar
                    ? 'مزيج من الفلل والمباني السكنية والمحلات، مع مواقف قريبة تسهّل التحميل.'
                    : 'Villas, apartment buildings and shops side by side, usually with parking close to the door.',
                'typical' => $ar
                    ? ['إخلاء الفلل والشقق', 'الأثاث القديم والأجهزة', 'إخلاء المحلات الصغيرة', 'غرف التخزين والمرائب']
                    : ['Villa and apartment clearances', 'Old furniture and appliances', 'Small shop clear-outs', 'Storage rooms and garages'],
            ],
            'jumeirah' => [
                'name' => $ar ? 'جميرا' : 'Jumeirah',
                'blurb' => $ar
                    ? 'منطقة فلل في الأغلب، مع حدائق ومساحات خارجية وغرف تخزين.'
                    : 'Mostly villas, with gardens, outdoor space and storage rooms.',
                'typical' => $ar
                    ? ['إخلاء الفلل بالكامل أو جزئيًا', 'أثاث الحدائق والشرفات', 'غرف الخدم والمخازن', 'الأثاث والأجهزة القديمة']
                    : ['Full or partial villa clearances', 'Garden and balcony furniture', 'Maid’s rooms and storerooms', 'Old furniture and appliances'],
            ],
            'dubai-marina' => [
                'name' => $ar ? 'دبي مارينا' : 'Dubai Marina',
                'blurb' => $ar
                    ? 'أبراج سكنية عالية، وغالبًا يتطلب العمل حجز مصعد الخدمة ومنطقة تحميل.'
                    : 'High-rise apartments, where jobs usually mean booking a service lift and a loading bay.',
                'typical' => $ar
                    ? ['إخلاء الشقق عند الانتقال', 'الكنب والأسرّة والمراتب', 'الأجهزة المنزلية', 'أغراض الشرفات']
                    : ['Move-out apartment clearances', 'Sofas, beds and mattresses', 'Household appliances', 'Balcony items'],
            ],
            'business-bay' => [
                'name' => $ar ? 'الخليج التجاري' : 'Business Bay',
                'blurb' => $ar
                    ? 'مكاتب وشقق في أبراج واحدة، لذلك يُرتَّب العمل غالبًا مع إدارة المبنى.'
                    : 'Offices and apartments in the same towers, so work is usually arranged with building management.',
                'typical' => $ar
                    ? ['إخلاء المكاتب عند الانتقال', 'محطات العمل والكراسي والخزائن', 'إخلاء الشقق', 'الأجهزة الإلكترونية القديمة']
                    : ['Office clearances on relocation', 'Workstations, chairs and cabinets', 'Apartment clearances', 'Old IT equipment'],
            ],
            'downtown-dubai' => [
                'name' => $ar ? 'وسط مدينة دبي' : 'Downtown Dubai',
                'blurb' => $ar
                    ? 'أبراج سكنية ومكاتب ومحلات، مع قواعد دخول وتحميل صارمة عادةً.'
                    : 'Residential towers, offices and retail, usually with strict access and loading rules.',
                'typical' => $ar
                    ? ['إخلاء الشقق والاستوديوهات', 'الأثاث والأجهزة', 'إخلاء المكاتب الصغيرة', 'أغراض المحلات']
                    : ['Apartment and studio clearances', 'Furniture and appliances', 'Small office clearances', 'Retail items'],
            ],
            'jvc' => [
                'name' => $ar ? 'قرية جميرا الدائرية' : 'Jumeirah Village Circle',
                'blurb' => $ar
                    ? 'مزيج من الفلل والتاون هاوس والمباني السكنية، مع وصول سهل للشاحنات.'
                    : 'Villas, townhouses and apartment buildings, with easy truck access on most streets.',
                'typical' => $ar
                    ? ['إخلاء الفلل والتاون هاوس', 'إخلاء الشقق عند الانتقال', 'الأثاث والأجهزة القديمة', 'مخلفات ما بعد التجديد']
                    : ['Villa and townhouse clearances', 'Move-out apartment clearances', 'Old furniture and appliances', 'Post-renovation debris'],
            ],
        ];

        // Fill in the typical-jobs list for the areas defined by type
        foreach ($extra as $extraSlug => $row) {
            $areas[$extraSlug] = [
                'name' => $row['name'],
                'blurb' => $row['blurb'],
                'typical' => $typical[$row['type']],
            ];
        }

        // Keep a sensible order: the busiest areas first
        $order = [
            'al-quoz', 'al-barsha', 'jumeirah', 'dubai-marina', 'business-bay', 'downtown-dubai', 'jvc',
            'jumeirah-lake-towers', 'palm-jumeirah', 'dubai-hills-estate', 'arabian-ranches', 'damac-hills',
            'emirates-hills', 'mirdif', 'deira', 'bur-dubai', 'international-city', 'discovery-gardens',
            'dubai-silicon-oasis', 'jebel-ali', 'dubai-south',
        ];
        $sorted = [];
        foreach ($order as $key) {
            if (isset($areas[$key])) {
                $sorted[$key] = $areas[$key];
            }
        }
        $areas = $sorted + $areas;

        if ($slug === null) {
            return $areas;
        }

        return $areas[$slug] ?? null;
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
        $out = [];
        foreach (self::areaDetail('en') as $slug => $area) {
            $out[$area['name']] = $lang === 'ar' ? self::areaDetail('ar', $slug)['name'] : $area['name'];
        }
        $out['Other'] = $lang === 'ar' ? 'منطقة أخرى' : 'Other';

        return $out;
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
        $robots = $p['robots'] ?? 'index, follow';
        $indexable = !str_contains($robots, 'noindex');

        $nav = [
            'home'     => $b,
            'services' => $b . 'services/',
            'areas'    => $b . 'areas/',
            'projects' => $b . 'projects/',
            'reviews'  => $b . 'reviews/',
            'about'    => $b . 'about-us/',
        ];
        $serviceLinks = self::services($lang);
        ?>
<!doctype html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= self::e($p['title']) ?></title>
<meta name="description" content="<?= self::e($p['description']) ?>">
<meta name="robots" content="<?= self::e($robots) ?>">
<?php if ($indexable): ?>
<link rel="canonical" href="<?= self::e($lang === 'ar' ? $arUrl : $enUrl) ?>">
<link rel="alternate" hreflang="en" href="<?= self::e($enUrl) ?>">
<link rel="alternate" hreflang="ar" href="<?= self::e($arUrl) ?>">
<link rel="alternate" hreflang="x-default" href="<?= self::e($enUrl) ?>">
<?php endif; ?>
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" type="image/png" href="/assets/images/favicon-32.png" sizes="32x32">
<link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
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
        $hasSub = in_array($key, ['services', 'areas'], true);
        ?>
        <li<?= $hasSub ? ' class="has-sub"' : '' ?>>
          <a href="<?= self::e($href) ?>"<?= $active ? ' class="is-active" aria-current="page"' : '' ?>><?= self::e($t['nav'][$key]) ?><?= $hasSub ? ' <svg class="icon caret" aria-hidden="true"><use href="#i-caret"/></svg>' : '' ?></a>
<?php if ($key === 'services'): ?>
          <ul class="submenu submenu-2col">
<?php foreach ($serviceLinks as $s): ?>
            <li><a href="<?= $b ?>services/<?= self::e($s['slug']) ?>/"><?= self::e($s['title']) ?></a></li>
<?php endforeach; ?>
            <li class="sub-all"><a href="<?= $b ?>services/"><?= self::e($t['all_services']) ?></a></li>
          </ul>
<?php elseif ($key === 'areas'): ?>
          <ul class="submenu submenu-3col">
<?php foreach (self::areaDetail($lang) as $areaSlug => $area): ?>
            <li><a href="<?= $b ?>areas/<?= self::e($areaSlug) ?>/"><?= self::e($area['name']) ?></a></li>
<?php endforeach; ?>
            <li class="sub-all"><a href="<?= $b ?>areas/"><?= self::e($t['all_areas']) ?></a></li>
          </ul>
<?php endif; ?>
        </li>
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
<?php foreach (($h['parents'] ?? []) as $label => $href): ?>
          <li><a href="<?= self::e($href) ?>"><?= self::e($label) ?></a></li>
<?php endforeach; ?>
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
          <li><a href="<?= $b ?>reviews/"><?= self::e($t['nav']['reviews']) ?></a></li>
          <li><a href="#"><?= self::e($t['blog']) ?></a></li>
          <li><a href="<?= $b ?>areas/"><?= self::e($t['nav']['areas']) ?></a></li>
          <li><a href="<?= $b ?>projects/"><?= self::e($t['nav']['projects']) ?></a></li>
        </ul>
      </div>
      <div>
        <h3><?= self::e($t['services_heading']) ?></h3>
        <ul class="footer-links">
<?php foreach (array_slice(self::services($lang), 0, 8) as $s): ?>
          <li><a href="<?= $b ?>services/<?= self::e($s['slug']) ?>/"><?= self::e($s['title']) ?></a></li>
<?php endforeach; ?>
          <li><a href="<?= $b ?>services/"><?= self::e($t['all_services']) ?></a></li>
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
