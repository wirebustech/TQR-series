<?php
include_once __DIR__ . '/../frontend/includes/translate.php';
include_once __DIR__ . '/partials/header.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
  'dashboard' => 'Admin Dashboard',
  'welcome' => 'Welcome, Admin!',
  'managePages' => 'Manage Pages',
  'manageUsers' => 'Manage Users',
  'analytics' => 'Analytics',
  'logout' => 'Logout'
];
if ($lang !== 'en') {
  foreach ($texts as $k => $v) {
    $texts[$k] = translateText($v, $lang, 'en');
  }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($texts['dashboard']) ?></title>
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="lang-switcher" style="position:absolute;top:1rem;right:1rem;">
    <form method="get">
      <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="en"<?= $lang=='en'?' selected':'' ?>>English</option>
        <option value="fr"<?= $lang=='fr'?' selected':'' ?>>Français</option>
        <option value="es"<?= $lang=='es'?' selected':'' ?>>Español</option>
      </select>
    </form>
  </div>
  <main class="container my-5">
    <h1><?= htmlspecialchars($texts['dashboard']) ?></h1>
    <p><?= htmlspecialchars($texts['welcome']) ?></p>
    <div class="row mt-4">
      <div class="col-md-4 mb-3">
        <a href="/admin/pages.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-primary w-100"><?= htmlspecialchars($texts['managePages']) ?></a>
      </div>
      <div class="col-md-4 mb-3">
        <a href="/admin/users.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-secondary w-100"><?= htmlspecialchars($texts['manageUsers']) ?></a>
      </div>
      <div class="col-md-4 mb-3">
        <a href="/admin/analytics.php?lang=<?= urlencode($lang) ?>" class="btn btn-outline-success w-100"><?= htmlspecialchars($texts['analytics']) ?></a>
      </div>
    </div>
    <a href="/admin/logout.php" class="btn btn-danger mt-4"><?= htmlspecialchars($texts['logout']) ?></a>
  </main>
<?php include_once __DIR__ . '/partials/footer.php'; ?>
</body>
</html> 