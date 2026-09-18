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

/* ---------------------------------------------------------------- sign in */

if (!$loggedIn) {
    View::head([
        'lang' => 'en',
        'path' => 'admin/',
        'active' => '',
        'robots' => 'noindex, nofollow',
        'title' => 'Admin sign in | Junk Removal Team Dubai',
        'description' => 'Staff sign in for Junk Removal Team Dubai.',
    ]);
    ?>
  <section class="section section-light">
    <div class="container">
      <div class="admin-login">
        <div class="quote-card">
          <h2>Admin sign in</h2>
          <p class="sub">Manage projects, reviews and enquiries.</p>
<?php if ($error !== null): ?>
          <div class="form-status is-error" role="alert"><?= admin_e($error) ?></div>
<?php endif; ?>
          <form method="post" action="/admin/">
            <input type="hidden" name="action" value="login">
            <div class="form-row">
              <div>
                <label class="field-label" for="password">Password</label>
                <input class="input" type="password" id="password" name="password" autocomplete="current-password" required autofocus>
              </div>
            </div>
            <button class="btn btn-green btn-block" type="submit">Sign in <svg class="icon flip" aria-hidden="true"><use href="#i-arrow"/></svg></button>
          </form>
          <p class="form-note">This page is for the site owner. Looking for a quote? <a href="/contact-us/#quote">Contact us here</a>.</p>
        </div>
      </div>
    </div>
  </section>
<?php
    View::foot('en');
    exit;
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --navy:#0A2C46; --navy-deep:#072135; --teal:#03717E; --green:#098065; --red:#B42318;
    --line:#E3EAEF; --muted:#5B6B79; --bg:#F1F5F8; --gold:#FFC631;
    --head:"Poppins",system-ui,sans-serif; --body:"Inter",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
  }
  * { box-sizing:border-box; }
  body { margin:0; font:15px/1.6 var(--body); background:var(--bg); color:#16202B; }
  a { color:var(--teal); text-decoration:none; }
  a:hover { text-decoration:underline; }
  h1,h2,h3 { font-family:var(--head); margin:0; }
  .icon { width:1.15em; height:1.15em; flex:none; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }

  /* top bar */
  .bar { background:var(--navy); color:#fff; position:sticky; top:0; z-index:20; }
  .bar-inner { max-width:1180px; margin:0 auto; padding:.7rem 1.25rem; display:flex; flex-wrap:wrap; align-items:center; gap:.75rem 1rem; }
  .bar .brand { display:flex; align-items:center; gap:.6rem; color:#fff; font-family:var(--head); font-weight:600; }
  .bar .brand img { height:38px; width:auto; }
  .bar .tag { font-size:.7rem; letter-spacing:.14em; text-transform:uppercase; color:#8FB3C7; border:1px solid rgb(255 255 255/.22); padding:.1rem .45rem; border-radius:999px; }
  .bar nav { display:flex; gap:.2rem; flex-wrap:wrap; margin-inline-start:auto; align-items:center; }
  .bar nav a { display:inline-flex; align-items:center; gap:.4rem; color:#CFE0EA; padding:.45rem .7rem; border-radius:8px; font-weight:500; font-size:.92rem; }
  .bar nav a:hover { background:rgb(255 255 255/.12); color:#fff; text-decoration:none; }
  .bar nav a.on { background:rgb(255 255 255/.16); color:#fff; }
  .bar nav a.on::after { content:""; }

  main { max-width:1180px; margin:1.75rem auto 4rem; padding:0 1.25rem; }
  .page-head { display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:1rem; margin-block-end:1.5rem; }
  .page-head h1 { font-size:1.6rem; color:var(--navy); }
  .page-head p { margin:.25rem 0 0; color:var(--muted); font-size:.92rem; }

  .card { background:#fff; border:1px solid var(--line); border-radius:14px; padding:1.4rem; margin-block-end:1.25rem; box-shadow:0 1px 2px rgb(10 44 70/.05); }
  .card h2 { font-size:1.1rem; color:var(--navy); margin-block-end:.35rem; }
  .card + h2 { margin-block-start:2rem; }
  h2.section-title { font-size:1.1rem; color:var(--navy); margin:2rem 0 .85rem; }

  .stats { display:grid; gap:1rem; grid-template-columns:repeat(auto-fit,minmax(min(100%,210px),1fr)); margin-block-end:1.5rem; }
  .stat { background:#fff; border:1px solid var(--line); border-radius:14px; padding:1.1rem 1.25rem; display:flex; align-items:center; gap:.9rem; }
  .stat .ico { width:44px; height:44px; border-radius:12px; display:grid; place-items:center; background:#E8F1F3; color:var(--teal); flex:none; }
  .stat .ico .icon { width:1.3rem; height:1.3rem; }
  .stat b { display:block; font-family:var(--head); font-size:1.7rem; line-height:1.1; color:var(--navy); }
  .stat span { color:var(--muted); font-size:.88rem; }

  label { display:block; font-weight:600; font-size:.88rem; margin-block-end:.3rem; color:var(--navy); }
  input[type=text], input[type=date], input[type=password], input[type=file], select, textarea {
    width:100%; padding:.65rem .75rem; border:1px solid #CBD5DD; border-radius:9px; font:inherit; background:#fff; color:inherit;
  }
  input:focus-visible, select:focus-visible, textarea:focus-visible { outline:3px solid rgb(3 113 126/.25); outline-offset:1px; border-color:var(--teal); }
  textarea { min-height:95px; resize:vertical; }
  .row { display:grid; gap:1rem; margin-block-end:1rem; }
  @media (min-width:760px){ .row.two { grid-template-columns:1fr 1fr; } .row.three { grid-template-columns:repeat(3,1fr); } }

  .btn { display:inline-flex; align-items:center; gap:.45rem; border:1px solid transparent; border-radius:9px; padding:.6rem 1.15rem; font:600 .93rem/1.2 var(--head); cursor:pointer; }
  .btn:hover { text-decoration:none; }
  .btn-primary { background:var(--green); color:#fff; }
  .btn-primary:hover { background:#07694F; }
  .btn-secondary { background:#fff; border-color:var(--line); color:var(--navy); }
  .btn-secondary:hover { border-color:var(--teal); color:var(--teal); }
  .btn-danger { background:#fff; border-color:#F1C9C5; color:var(--red); }
  .btn-danger:hover { background:#FDECEC; }
  .btn-ghost { background:rgb(255 255 255/.12); color:#fff; border-color:rgb(255 255 255/.25); }
  .btn-ghost:hover { background:rgb(255 255 255/.2); }
  .btn + .btn { margin-inline-start:.35rem; }

  .table-wrap { overflow-x:auto; }
  table { width:100%; border-collapse:collapse; min-width:620px; }
  th, td { text-align:start; padding:.75rem .6rem; border-block-end:1px solid var(--line); vertical-align:top; font-size:.92rem; }
  th { color:var(--muted); font-size:.74rem; text-transform:uppercase; letter-spacing:.06em; font-weight:600; }
  tbody tr:hover { background:#FAFCFD; }
  tr:last-child td { border-block-end:0; }

  .notice, .error { padding:.85rem 1.1rem; border-radius:10px; margin-block-end:1.25rem; font-size:.93rem; }
  .notice { background:#E8F5EF; color:#0B5D3B; }
  .error { background:#FDECEC; color:#8A1C1C; }
  .thumb { width:104px; height:70px; object-fit:cover; border-radius:8px; border:1px solid var(--line); }
  .thumb + .thumb { margin-inline-start:.3rem; }
  .muted { color:var(--muted); font-size:.85rem; }
  .pill { display:inline-block; padding:.2rem .6rem; border-radius:999px; font-size:.74rem; font-weight:600; text-transform:capitalize; }
  .pill.published { background:#E8F5EF; color:#0B5D3B; }
  .pill.hidden { background:#FFF4DB; color:#7A5300; }
  .stars-cell { color:#EBA300; letter-spacing:.05em; white-space:nowrap; }
  .empty { text-align:center; padding:2rem 1rem; color:var(--muted); }
  .admin-foot { max-width:1180px; margin:0 auto; padding:1.5rem 1.25rem 3rem; color:var(--muted); font-size:.85rem; display:flex; flex-wrap:wrap; gap:.75rem 1.5rem; justify-content:space-between; }
</style>
</head>
<body>

<?php readfile(dirname(__DIR__, 2) . '/app/views/sprite.svg'); ?>

<header class="bar">
  <div class="bar-inner">
    <a class="brand" href="/admin/?p=dashboard">
      <img src="/assets/images/logo-footer.png" alt="Junk Removal Team Dubai">
      <span class="tag">Admin</span>
    </a>
    <nav>
      <a href="/admin/?p=dashboard" class="<?= $page === 'dashboard' ? 'on' : '' ?>"><svg class="icon" aria-hidden="true"><use href="#i-home"/></svg>Dashboard</a>
      <a href="/admin/?p=projects" class="<?= $page === 'projects' ? 'on' : '' ?>"><svg class="icon" aria-hidden="true"><use href="#i-truck"/></svg>Projects</a>
      <a href="/admin/?p=reviews" class="<?= $page === 'reviews' ? 'on' : '' ?>"><svg class="icon" aria-hidden="true"><use href="#i-star"/></svg>Reviews</a>
      <a href="/admin/?p=leads" class="<?= $page === 'leads' ? 'on' : '' ?>"><svg class="icon" aria-hidden="true"><use href="#i-quote-doc"/></svg>Leads</a>
      <a href="/" target="_blank" rel="noopener"><svg class="icon" aria-hidden="true"><use href="#i-globe"/></svg>View site</a>
      <form method="post" action="/admin/" style="display:inline">
        <input type="hidden" name="csrf" value="<?= admin_e($csrf) ?>">
        <input type="hidden" name="action" value="logout">
        <button class="btn btn-ghost" type="submit">Sign out</button>
      </form>
    </nav>
  </div>
</header>

<main>
<?php if ($notice !== null): ?><p class="notice"><?= admin_e($notice) ?></p><?php endif; ?>
<?php if ($error !== null): ?><p class="error"><?= admin_e($error) ?></p><?php endif; ?>

<?php if ($page === 'dashboard'):
    $projects = Store::all('projects');
    $reviews = Store::all('reviews');
    $leads = Admin::recentLeads(200);
    $hiddenReviews = count(array_filter($reviews, static fn ($r) => ($r['status'] ?? '') === 'hidden')); ?>
  <div class="page-head">
    <div>
      <h1>Dashboard</h1>
      <p>Everything customers send you, and everything you show them.</p>
    </div>
    <a class="btn btn-primary" href="/admin/?p=projects"><svg class="icon" aria-hidden="true"><use href="#i-truck"/></svg>Add a project</a>
  </div>

  <div class="stats">
    <div class="stat">
      <span class="ico"><svg class="icon" aria-hidden="true"><use href="#i-truck"/></svg></span>
      <div><b><?= count($projects) ?></b><span>Projects</span></div>
    </div>
    <div class="stat">
      <span class="ico"><svg class="icon" aria-hidden="true"><use href="#i-star"/></svg></span>
      <div><b><?= count($reviews) ?></b><span>Reviews<?= $hiddenReviews > 0 ? ' (' . $hiddenReviews . ' hidden)' : '' ?></span></div>
    </div>
    <div class="stat">
      <span class="ico"><svg class="icon" aria-hidden="true"><use href="#i-quote-doc"/></svg></span>
      <div><b><?= count($leads) ?></b><span>Leads stored</span></div>
    </div>
    <div class="stat">
      <span class="ico"><svg class="icon" aria-hidden="true"><use href="#i-shield"/></svg></span>
      <div><b><?= Db::isAvailable() ? 'MySQL' : 'Files' ?></b><span>Storage in use</span></div>
    </div>
  </div>

  <h2 class="section-title">Latest leads</h2>
  <div class="card">
<?php if ($leads === []): ?>
    <p class="empty">No leads yet. They will appear here as soon as someone sends the quote form.</p>
<?php else: ?>
    <div class="table-wrap">
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
    </div>
    <p style="margin-block-start:1rem"><a href="/admin/?p=leads">See all leads →</a></p>
<?php endif; ?>
  </div>

<?php elseif ($page === 'projects'):
    $projects = Store::all('projects'); ?>
  <div class="page-head">
    <div>
      <h1>Projects</h1>
      <p>Before and after photos shown on <a href="/projects/" target="_blank" rel="noopener">the projects page</a>.</p>
    </div>
  </div>

  <div class="card">
    <h2><?= $editing ? 'Edit project' : 'Add a project' ?></h2>
    <p class="muted">Use photos of jobs you actually did — not stock images.</p>
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

  <h2 class="section-title">All projects (<?= count($projects) ?>)</h2>
  <div class="card">
<?php if ($projects === []): ?>
    <p class="empty">Nothing yet. Add your first project above.</p>
<?php else: ?>
    <div class="table-wrap">
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
    </div>
<?php endif; ?>
  </div>

<?php elseif ($page === 'reviews'):
    $reviews = Store::all('reviews');
    usort($reviews, static fn ($a, $b) => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? ''))); ?>
  <div class="page-head">
    <div>
      <h1>Reviews</h1>
      <p>Reviews go live the moment a customer sends them. Hide or delete anything that doesn’t belong.</p>
    </div>
    <a class="btn btn-secondary" href="/reviews/" target="_blank" rel="noopener"><svg class="icon" aria-hidden="true"><use href="#i-globe"/></svg>See the page</a>
  </div>
  <div class="card">
<?php if ($reviews === []): ?>
    <p class="empty">No reviews yet.</p>
<?php else: ?>
    <div class="table-wrap">
    <table>
      <tr><th>When</th><th>Name</th><th>Rating</th><th>Review</th><th>Status</th><th></th></tr>
<?php foreach ($reviews as $r):
      $status = (string) ($r['status'] ?? 'published'); ?>
      <tr>
        <td class="muted"><?= admin_e(date('j M Y', strtotime((string) ($r['created_at'] ?? 'now')))) ?></td>
        <td><?= admin_e((string) ($r['name'] ?? '')) ?><br><span class="muted"><?= admin_e((string) ($r['area'] ?? '')) ?></span></td>
        <td class="stars-cell"><?= str_repeat('★', (int) ($r['rating'] ?? 0)) ?></td>
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
    </div>
<?php endif; ?>
  </div>

<?php elseif ($page === 'leads'):
    $leads = Admin::recentLeads(100); ?>
  <div class="page-head">
    <div>
      <h1>Leads</h1>
      <p>The 100 most recent enquiries. Each one is emailed to you as it arrives.</p>
    </div>
  </div>
  <div class="card">
<?php if ($leads === []): ?>
    <p class="empty">No leads yet.</p>
<?php else: ?>
    <div class="table-wrap">
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
    </div>
<?php endif; ?>
  </div>
<?php endif; ?>
</main>

<footer class="admin-foot">
  <span>Junk Removal Team Dubai — admin</span>
  <span>Signed in · <a href="/" target="_blank" rel="noopener">View the site</a></span>
</footer>

</body>
</html>
