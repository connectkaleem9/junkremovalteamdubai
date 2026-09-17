<?php
declare(strict_types=1);

/**
 * Admin dashboard: projects, reviews and leads.
 *
 * One page, small enough to read in one sitting. Everything it writes goes to
 * ~/appdata (outside the web root), so deploys never overwrite the content the
 * owner adds here.
 */

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/views/View.php';

header('X-Robots-Tag: noindex, nofollow');
header('Referrer-Policy: same-origin');
header('X-Content-Type-Options: nosniff');

$page = (string) ($_GET['p'] ?? 'dashboard');
$notice = null;
$error = null;

/* ---------------------------------------------------------------- actions */

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'login') {
        $error = Admin::attemptLogin((string) ($_POST['password'] ?? ''));
        if ($error === null) {
            header('Location: /admin/?p=dashboard', true, 303);
            exit;
        }
    } elseif (!Admin::isLoggedIn()) {
        $error = 'Your session expired. Please sign in again.';
    } elseif (!Admin::checkCsrf()) {
        $error = 'Security check failed. Please try again.';
    } else {
        switch ($action) {
            case 'logout':
                Admin::logout();
                header('Location: /admin/', true, 303);
                exit;

            case 'project_save':
                $id = trim((string) ($_POST['id'] ?? ''));
                $row = [
                    'title_en'   => mb_substr(trim((string) ($_POST['title_en'] ?? '')), 0, 120),
                    'title_ar'   => mb_substr(trim((string) ($_POST['title_ar'] ?? '')), 0, 120),
                    'area'       => mb_substr(trim((string) ($_POST['area'] ?? '')), 0, 60),
                    'service'    => mb_substr(trim((string) ($_POST['service'] ?? '')), 0, 60),
                    'date'       => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($_POST['date'] ?? '')) === 1 ? (string) $_POST['date'] : date('Y-m-d'),
                    'summary_en' => mb_substr(trim((string) ($_POST['summary_en'] ?? '')), 0, 600),
                    'summary_ar' => mb_substr(trim((string) ($_POST['summary_ar'] ?? '')), 0, 600),
                    'status'     => ($_POST['status'] ?? 'published') === 'hidden' ? 'hidden' : 'published',
                ];

                if ($row['title_en'] === '' && $row['title_ar'] === '') {
                    $error = 'Give the project a title (English or Arabic).';
                    break;
                }

                $existing = $id !== '' ? Store::find('projects', $id) : null;

                foreach (['before', 'after'] as $slot) {
                    [$stored, $uploadError] = Uploads::saveImage($_FILES[$slot] ?? [], 'projects');
                    if ($uploadError !== null) {
                        $error = ucfirst($slot) . ' image: ' . $uploadError;
                        break 2;
                    }
                    if ($stored !== null) {
                        $row[$slot] = $stored;
                        if ($existing !== null && !empty($existing[$slot])) {
                            Uploads::delete((string) $existing[$slot]);
                        }
                    }
                }

                if ($existing !== null) {
                    Store::update('projects', $id, $row);
                    $notice = 'Project updated.';
                } else {
                    Store::add('projects', $row);
                    $notice = 'Project added.';
                }
                $page = 'projects';
                break;

            case 'project_delete':
                $id = (string) ($_POST['id'] ?? '');
                $project = Store::find('projects', $id);
                if ($project !== null) {
                    foreach (['before', 'after'] as $slot) {
                        if (!empty($project[$slot])) {
                            Uploads::delete((string) $project[$slot]);
                        }
                    }
                    Store::delete('projects', $id);
                    $notice = 'Project deleted.';
                }
                $page = 'projects';
                break;

            case 'review_status':
                Store::update('reviews', (string) ($_POST['id'] ?? ''), [
                    'status' => ($_POST['status'] ?? 'published') === 'hidden' ? 'hidden' : 'published',
                ]);
                $notice = 'Review updated.';
                $page = 'reviews';
                break;

            case 'review_delete':
                Store::delete('reviews', (string) ($_POST['id'] ?? ''));
                $notice = 'Review deleted.';
                $page = 'reviews';
                break;
        }
    }
}

$loggedIn = Admin::isLoggedIn();
$csrf = $loggedIn ? Admin::csrfToken() : '';
$editing = null;

if ($loggedIn && $page === 'projects' && isset($_GET['edit'])) {
    $editing = Store::find('projects', (string) $_GET['edit']);
}

function admin_e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Admin — Junk Removal Team Dubai</title>
<link rel="icon" href="/favicon.ico" sizes="any">
<style>
  :root { --navy:#0A2C46; --teal:#03717E; --green:#098065; --red:#B42318; --line:#E1E8ED; --muted:#55636F; --bg:#F4F7F9; }
  * { box-sizing: border-box; }
  body { margin:0; font:15px/1.6 system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif; background:var(--bg); color:#16202B; }
  a { color: var(--teal); }
  header.bar { background:var(--navy); color:#fff; padding:.85rem 1.25rem; display:flex; flex-wrap:wrap; align-items:center; gap:1rem; }
  header.bar strong { font-size:1.05rem; }
  header.bar nav { display:flex; gap:.35rem; flex-wrap:wrap; margin-inline-start:auto; }
  header.bar nav a { color:#cfe0ea; text-decoration:none; padding:.4rem .7rem; border-radius:6px; }
  header.bar nav a:hover, header.bar nav a.on { background:rgb(255 255 255/.14); color:#fff; }
  main { max-width:1100px; margin:1.5rem auto 4rem; padding:0 1.25rem; }
  h1 { font-size:1.4rem; margin:0 0 1rem; }
  h2 { font-size:1.1rem; margin:2rem 0 .75rem; }
  .card { background:#fff; border:1px solid var(--line); border-radius:12px; padding:1.25rem; margin-block-end:1.25rem; }
  .grid { display:grid; gap:1rem; grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr)); }
  .stat { background:#fff; border:1px solid var(--line); border-radius:12px; padding:1rem 1.25rem; }
  .stat b { display:block; font-size:1.8rem; color:var(--navy); }
  .stat span { color:var(--muted); font-size:.9rem; }
  label { display:block; font-weight:600; font-size:.88rem; margin-block-end:.25rem; }
  input[type=text], input[type=date], input[type=password], select, textarea {
    width:100%; padding:.6rem .7rem; border:1px solid #CBD5DD; border-radius:8px; font:inherit; background:#fff;
  }
  textarea { min-height:90px; resize:vertical; }
  .row { display:grid; gap:1rem; margin-block-end:1rem; }
  @media (min-width:720px){ .row.two { grid-template-columns:1fr 1fr; } .row.three { grid-template-columns:1fr 1fr 1fr; } }
  .btn { display:inline-flex; align-items:center; gap:.4rem; border:0; border-radius:8px; padding:.6rem 1.1rem; font:600 .95rem/1 inherit; cursor:pointer; text-decoration:none; }
  .btn-primary { background:var(--green); color:#fff; }
  .btn-secondary { background:#fff; border:1px solid var(--line); color:var(--navy); }
  .btn-danger { background:#fff; border:1px solid #f1c9c5; color:var(--red); }
  .btn + .btn { margin-inline-start:.4rem; }
  table { width:100%; border-collapse:collapse; }
  th, td { text-align:start; padding:.65rem .5rem; border-block-end:1px solid var(--line); vertical-align:top; font-size:.92rem; }
  th { color:var(--muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; }
  .notice, .error { padding:.8rem 1rem; border-radius:8px; margin-block-end:1rem; }
  .notice { background:#E8F5EF; color:#0B5D3B; }
  .error { background:#FDECEC; color:#8A1C1C; }
  .thumb { width:110px; height:74px; object-fit:cover; border-radius:6px; border:1px solid var(--line); }
  .muted { color:var(--muted); font-size:.85rem; }
  .pill { display:inline-block; padding:.15rem .55rem; border-radius:999px; font-size:.75rem; font-weight:600; }
  .pill.published { background:#E8F5EF; color:#0B5D3B; }
  .pill.hidden { background:#FFF4DB; color:#7A5300; }
  .login { max-width:380px; margin:8vh auto; }
</style>
</head>
<body>

<?php if (!$loggedIn): ?>
<main class="login">
  <div class="card">
    <h1>Admin sign in</h1>
<?php if ($error !== null): ?>    <p class="error"><?= admin_e($error) ?></p><?php endif; ?>
    <form method="post" action="/admin/">
      <input type="hidden" name="action" value="login">
      <div class="row">
        <div>
          <label for="password">Password</label>
          <input type="password" id="password" name="password" autocomplete="current-password" required autofocus>
        </div>
      </div>
      <button class="btn btn-primary" type="submit">Sign in</button>
    </form>
  </div>
  <p class="muted" style="text-align:center">Junk Removal Team Dubai</p>
</main>
<?php else: ?>

<header class="bar">
  <strong>Junk Removal Team Dubai</strong>
  <nav>
    <a href="/admin/?p=dashboard" class="<?= $page === 'dashboard' ? 'on' : '' ?>">Dashboard</a>
    <a href="/admin/?p=projects" class="<?= $page === 'projects' ? 'on' : '' ?>">Projects</a>
    <a href="/admin/?p=reviews" class="<?= $page === 'reviews' ? 'on' : '' ?>">Reviews</a>
    <a href="/admin/?p=leads" class="<?= $page === 'leads' ? 'on' : '' ?>">Leads</a>
    <a href="/" target="_blank" rel="noopener">View site ↗</a>
    <form method="post" action="/admin/" style="display:inline">
      <input type="hidden" name="csrf" value="<?= admin_e($csrf) ?>">
      <input type="hidden" name="action" value="logout">
      <button class="btn btn-secondary" type="submit">Sign out</button>
    </form>
  </nav>
</header>

<main>
<?php if ($notice !== null): ?><p class="notice"><?= admin_e($notice) ?></p><?php endif; ?>
<?php if ($error !== null): ?><p class="error"><?= admin_e($error) ?></p><?php endif; ?>

<?php if ($page === 'dashboard'):
    $projects = Store::all('projects');
    $reviews = Store::all('reviews');
    $leads = Admin::recentLeads(200); ?>
  <h1>Dashboard</h1>
  <div class="grid">
    <div class="stat"><b><?= count($projects) ?></b><span>Projects</span></div>
    <div class="stat"><b><?= count($reviews) ?></b><span>Reviews</span></div>
    <div class="stat"><b><?= count($leads) ?></b><span>Leads stored</span></div>
    <div class="stat"><b><?= Db::isAvailable() ? 'MySQL' : 'Files' ?></b><span>Storage in use</span></div>
  </div>

  <h2>Latest leads</h2>
  <div class="card">
<?php if ($leads === []): ?>
    <p class="muted">No leads yet.</p>
<?php else: ?>
    <table>
      <tr><th>When</th><th>Name</th><th>Phone</th><th>Service</th><th>Area</th></tr>
<?php foreach (array_slice($leads, 0, 8) as $lead): ?>
      <tr>
        <td class="muted"><?= admin_e(date('j M, H:i', strtotime((string) ($lead['created_at'] ?? 'now')))) ?></td>
        <td><?= admin_e((string) ($lead['name'] ?? '')) ?></td>
        <td dir="ltr"><?= admin_e((string) ($lead['phone'] ?? '')) ?></td>
        <td><?= admin_e((string) ($lead['service'] ?? '—')) ?></td>
        <td><?= admin_e((string) ($lead['area'] ?? '—')) ?></td>
      </tr>
<?php endforeach; ?>
    </table>
    <p style="margin-block-start:1rem"><a href="/admin/?p=leads">See all leads →</a></p>
<?php endif; ?>
  </div>

<?php elseif ($page === 'projects'):
    $projects = Store::all('projects'); ?>
  <h1>Projects</h1>

  <div class="card">
    <h2 style="margin-top:0"><?= $editing ? 'Edit project' : 'Add a project' ?></h2>
    <p class="muted">Photos should be of jobs you actually did. They appear on <a href="/projects/" target="_blank" rel="noopener">the projects page</a>.</p>
    <form method="post" action="/admin/" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= admin_e($csrf) ?>">
      <input type="hidden" name="action" value="project_save">
<?php if ($editing): ?>      <input type="hidden" name="id" value="<?= admin_e((string) $editing['id']) ?>"><?php endif; ?>

      <div class="row two">
        <div>
          <label for="title_en">Title (English)</label>
          <input type="text" id="title_en" name="title_en" value="<?= admin_e((string) ($editing['title_en'] ?? '')) ?>" placeholder="Villa Clearance – Al Barsha">
        </div>
        <div>
          <label for="title_ar">Title (Arabic)</label>
          <input type="text" id="title_ar" name="title_ar" dir="rtl" value="<?= admin_e((string) ($editing['title_ar'] ?? '')) ?>" placeholder="إخلاء فيلا – البرشاء">
        </div>
      </div>

      <div class="row three">
        <div>
          <label for="service">Service</label>
          <select id="service" name="service">
            <option value="">—</option>
<?php foreach (View::services('en') as $s): ?>
            <option value="<?= admin_e($s['title']) ?>"<?= ($editing['service'] ?? '') === $s['title'] ? ' selected' : '' ?>><?= admin_e($s['title']) ?></option>
<?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="area">Area</label>
          <select id="area" name="area">
            <option value="">—</option>
<?php foreach (array_keys(View::areas('en')) as $areaName): ?>
            <option value="<?= admin_e($areaName) ?>"<?= ($editing['area'] ?? '') === $areaName ? ' selected' : '' ?>><?= admin_e($areaName) ?></option>
<?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="date">Date of the job</label>
          <input type="date" id="date" name="date" value="<?= admin_e((string) ($editing['date'] ?? date('Y-m-d'))) ?>">
        </div>
      </div>

      <div class="row two">
        <div>
          <label for="summary_en">Short description (English)</label>
          <textarea id="summary_en" name="summary_en"><?= admin_e((string) ($editing['summary_en'] ?? '')) ?></textarea>
        </div>
        <div>
          <label for="summary_ar">Short description (Arabic)</label>
          <textarea id="summary_ar" name="summary_ar" dir="rtl"><?= admin_e((string) ($editing['summary_ar'] ?? '')) ?></textarea>
        </div>
      </div>

      <div class="row two">
        <div>
          <label for="before">Before photo</label>
          <input type="file" id="before" name="before" accept="image/jpeg,image/png,image/webp">
<?php if (!empty($editing['before'])): ?>
          <p class="muted">Current: <img class="thumb" src="<?= admin_e(Uploads::url((string) $editing['before'])) ?>" alt=""></p>
<?php endif; ?>
        </div>
        <div>
          <label for="after">After photo</label>
          <input type="file" id="after" name="after" accept="image/jpeg,image/png,image/webp">
<?php if (!empty($editing['after'])): ?>
          <p class="muted">Current: <img class="thumb" src="<?= admin_e(Uploads::url((string) $editing['after'])) ?>" alt=""></p>
<?php endif; ?>
        </div>
      </div>

      <div class="row two">
        <div>
          <label for="status">Status</label>
          <select id="status" name="status">
            <option value="published"<?= ($editing['status'] ?? 'published') === 'published' ? ' selected' : '' ?>>Published</option>
            <option value="hidden"<?= ($editing['status'] ?? '') === 'hidden' ? ' selected' : '' ?>>Hidden</option>
          </select>
        </div>
      </div>

      <button class="btn btn-primary" type="submit"><?= $editing ? 'Save changes' : 'Add project' ?></button>
<?php if ($editing): ?>      <a class="btn btn-secondary" href="/admin/?p=projects">Cancel</a><?php endif; ?>
    </form>
  </div>

  <h2>All projects (<?= count($projects) ?>)</h2>
  <div class="card">
<?php if ($projects === []): ?>
    <p class="muted">Nothing yet. Add your first project above.</p>
<?php else: ?>
    <table>
      <tr><th>Photos</th><th>Title</th><th>Details</th><th>Status</th><th></th></tr>
<?php foreach ($projects as $p): ?>
      <tr>
        <td>
<?php if (!empty($p['before'])): ?><img class="thumb" src="<?= admin_e(Uploads::url((string) $p['before'])) ?>" alt=""><?php endif; ?>
<?php if (!empty($p['after'])): ?><img class="thumb" src="<?= admin_e(Uploads::url((string) $p['after'])) ?>" alt=""><?php endif; ?>
        </td>
        <td>
          <strong><?= admin_e((string) ($p['title_en'] ?? '')) ?></strong>
<?php if (!empty($p['title_ar'])): ?><br><span dir="rtl"><?= admin_e((string) $p['title_ar']) ?></span><?php endif; ?>
        </td>
        <td class="muted">
          <?= admin_e(trim(((string) ($p['area'] ?? '')) . ' ' . ((string) ($p['service'] ?? '')))) ?><br>
          <?= admin_e((string) ($p['date'] ?? '')) ?>
        </td>
        <td><span class="pill <?= admin_e((string) ($p['status'] ?? 'published')) ?>"><?= admin_e((string) ($p['status'] ?? 'published')) ?></span></td>
        <td>
          <a class="btn btn-secondary" href="/admin/?p=projects&amp;edit=<?= admin_e((string) $p['id']) ?>">Edit</a>
          <form method="post" action="/admin/" style="display:inline" onsubmit="return confirm('Delete this project and its photos?');">
            <input type="hidden" name="csrf" value="<?= admin_e($csrf) ?>">
            <input type="hidden" name="action" value="project_delete">
            <input type="hidden" name="id" value="<?= admin_e((string) $p['id']) ?>">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </td>
      </tr>
<?php endforeach; ?>
    </table>
<?php endif; ?>
  </div>

<?php elseif ($page === 'reviews'):
    $reviews = Store::all('reviews');
    usort($reviews, static fn ($a, $b) => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? ''))); ?>
  <h1>Reviews</h1>
  <p class="muted">Reviews go live the moment a customer sends them. Hide or delete anything that doesn’t belong.</p>
  <div class="card">
<?php if ($reviews === []): ?>
    <p class="muted">No reviews yet.</p>
<?php else: ?>
    <table>
      <tr><th>When</th><th>Name</th><th>Rating</th><th>Review</th><th>Status</th><th></th></tr>
<?php foreach ($reviews as $r):
      $status = (string) ($r['status'] ?? 'published'); ?>
      <tr>
        <td class="muted"><?= admin_e(date('j M Y', strtotime((string) ($r['created_at'] ?? 'now')))) ?></td>
        <td><?= admin_e((string) ($r['name'] ?? '')) ?><br><span class="muted"><?= admin_e((string) ($r['area'] ?? '')) ?></span></td>
        <td><?= str_repeat('★', (int) ($r['rating'] ?? 0)) ?></td>
        <td<?= ($r['lang'] ?? 'en') === 'ar' ? ' dir="rtl"' : '' ?>><?= admin_e(mb_strimwidth((string) ($r['text'] ?? ''), 0, 160, '…')) ?></td>
        <td><span class="pill <?= admin_e($status) ?>"><?= admin_e($status) ?></span></td>
        <td>
          <form method="post" action="/admin/" style="display:inline">
            <input type="hidden" name="csrf" value="<?= admin_e($csrf) ?>">
            <input type="hidden" name="action" value="review_status">
            <input type="hidden" name="id" value="<?= admin_e((string) $r['id']) ?>">
            <input type="hidden" name="status" value="<?= $status === 'published' ? 'hidden' : 'published' ?>">
            <button class="btn btn-secondary" type="submit"><?= $status === 'published' ? 'Hide' : 'Publish' ?></button>
          </form>
          <form method="post" action="/admin/" style="display:inline" onsubmit="return confirm('Delete this review?');">
            <input type="hidden" name="csrf" value="<?= admin_e($csrf) ?>">
            <input type="hidden" name="action" value="review_delete">
            <input type="hidden" name="id" value="<?= admin_e((string) $r['id']) ?>">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </td>
      </tr>
<?php endforeach; ?>
    </table>
<?php endif; ?>
  </div>

<?php elseif ($page === 'leads'):
    $leads = Admin::recentLeads(100); ?>
  <h1>Leads</h1>
  <p class="muted">The 100 most recent enquiries. Each one is also emailed to you when it arrives.</p>
  <div class="card">
<?php if ($leads === []): ?>
    <p class="muted">No leads yet.</p>
<?php else: ?>
    <table>
      <tr><th>When</th><th>Name</th><th>Phone</th><th>Email</th><th>Service / Area</th><th>Message</th><th>Source</th></tr>
<?php foreach ($leads as $lead): ?>
      <tr>
        <td class="muted"><?= admin_e(date('j M, H:i', strtotime((string) ($lead['created_at'] ?? 'now')))) ?></td>
        <td><?= admin_e((string) ($lead['name'] ?? '')) ?></td>
        <td dir="ltr"><a href="tel:<?= admin_e((string) ($lead['phone'] ?? '')) ?>"><?= admin_e((string) ($lead['phone'] ?? '')) ?></a></td>
        <td><?= admin_e((string) ($lead['email'] ?? '—')) ?></td>
        <td class="muted"><?= admin_e(trim(((string) ($lead['service'] ?? '')) . ' / ' . ((string) ($lead['area'] ?? '')), ' /')) ?></td>
        <td><?= admin_e(mb_strimwidth((string) ($lead['message'] ?? ''), 0, 120, '…')) ?></td>
        <td class="muted"><?= admin_e((string) ($lead['utm_campaign'] ?? $lead['utm_source'] ?? 'direct')) ?></td>
      </tr>
<?php endforeach; ?>
    </table>
<?php endif; ?>
  </div>
<?php endif; ?>
</main>
<?php endif; ?>

</body>
</html>
