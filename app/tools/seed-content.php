<?php
declare(strict_types=1);

/**
 * One-off: copies the three projects and three reviews that are hard-coded on
 * the homepage into the content store, so /projects/ and /reviews/ show the
 * same things. Safe to run twice — it skips anything already there.
 *
 *   php app/tools/seed-content.php
 *
 * VERIFY G1/G3/G4: these came from the design. Before they stay live, confirm
 * the photos are of real jobs and the reviews are from real customers.
 */

require_once __DIR__ . '/../bootstrap.php';

$projects = [
    [
        'title_en' => 'Villa Clearance – Emirates Hills',
        'title_ar' => 'إخلاء فيلا – تلال الإمارات',
        'image'    => 'assets/images/project-villa-clearance.jpg',
        'service'  => 'House Clearance',
        'area'     => '',
        'date'     => '2026-08-12',
        'summary_en' => 'A villa cleared of furniture and household items, room by room.',
        'summary_ar' => 'إخلاء فيلا من الأثاث والأغراض المنزلية، غرفة بغرفة.',
    ],
    [
        'title_en' => 'Office Clearance – Business Bay',
        'title_ar' => 'إخلاء مكتب – الخليج التجاري',
        'image'    => 'assets/images/project-office-clearance.jpg',
        'service'  => 'Office Clearance',
        'area'     => 'Business Bay',
        'date'     => '2026-07-28',
        'summary_en' => 'Desks, chairs and office clutter removed so the unit could be handed back.',
        'summary_ar' => 'إزالة المكاتب والكراسي والأغراض المكتبية لتسليم الوحدة.',
    ],
    [
        'title_en' => 'Construction Waste Removal – Al Quoz',
        'title_ar' => 'إزالة مخلفات بناء – القوز',
        'image'    => 'assets/images/project-construction-waste.jpg',
        'service'  => 'Construction Waste',
        'area'     => 'Al Quoz',
        'date'     => '2026-07-05',
        'summary_en' => 'Debris left after a fit-out, cleared and taken away.',
        'summary_ar' => 'مخلفات متبقية بعد أعمال التشطيب، تمت إزالتها ونقلها.',
    ],
];

$reviews = [
    [
        'name' => 'Ahmed R.', 'rating' => 5, 'area' => 'Jumeirah', 'service' => 'Junk Removal',
        'text' => 'Excellent service! The team was professional, fast and very helpful. Highly recommended for any junk removal in Dubai.',
    ],
    [
        'name' => 'Sarah M.', 'rating' => 5, 'area' => '', 'service' => 'House Clearance',
        'text' => 'They cleared our villa quickly and left everything clean. Great communication and very reliable team. Will definitely use them again.',
    ],
    [
        'name' => 'Khalid A.', 'rating' => 5, 'area' => 'Downtown Dubai', 'service' => 'Junk Removal',
        'text' => 'Best junk removal service in Dubai. On time, friendly staff and affordable prices. Highly recommended!',
    ],
];

$existingProjects = array_column(Store::all('projects'), 'title_en');
$added = 0;

foreach ($projects as $project) {
    if (in_array($project['title_en'], $existingProjects, true)) {
        echo "skip project: {$project['title_en']}\n";
        continue;
    }
    Store::add('projects', $project + ['status' => 'published', 'created_at' => date('c', strtotime($project['date']))]);
    echo "added project: {$project['title_en']}\n";
    $added++;
}

$existingReviews = array_column(Store::all('reviews'), 'name');

foreach ($reviews as $review) {
    if (in_array($review['name'], $existingReviews, true)) {
        echo "skip review: {$review['name']}\n";
        continue;
    }
    Store::add('reviews', $review + [
        'lang'   => 'en',
        'source' => 'homepage',
        'status' => 'published',
    ]);
    echo "added review: {$review['name']}\n";
    $added++;
}

echo "done, {$added} item(s) added\n";
