<?php
include_once __DIR__ . '/includes/translate.php';
include_once __DIR__ . '/partials/header.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
  'welcome' => 'Welcome to The Qualitative Research Series (TQRS)!',
  'subtitle' => 'A modern, AI-powered platform for qualitative research.',
  'cta' => 'Explore our features, join the community, and sign up for early access to our upcoming Research AI app!'
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
  <title><?= htmlspecialchars($texts['welcome']) ?></title>
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
  <main class="container text-center my-5">
    <h1><?= htmlspecialchars($texts['welcome']) ?></h1>
    <p class="lead"><?= htmlspecialchars($texts['subtitle']) ?></p>
    <p><?= htmlspecialchars($texts['cta']) ?></p>
    <a href="/pages/research-ai.php?lang=<?= urlencode($lang) ?>" class="btn btn-primary btn-lg mt-4">Research AI App &rarr;</a>
  </main>
<?php include_once __DIR__ . '/partials/footer.php'; ?>
</body>
</html> 