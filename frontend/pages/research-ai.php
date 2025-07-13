<?php
// Include header
include_once '../partials/header.php';

// Google Translate API utility
function translateText($text, $target, $source = 'en') {
    $apiKey = getenv('GOOGLE_TRANSLATE_API_KEY');
    $url = 'https://translation.googleapis.com/language/translate/v2';
    $fields = [
        'q' => $text,
        'target' => $target,
        'source' => $source,
        'format' => 'text',
        'key' => $apiKey
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    return $result['data']['translations'][0]['translatedText'] ?? $text;
}

// Language selection
$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'Research AI App – Coming Soon',
    'headline' => 'Introducing the Research AI App',
    'teaser' => 'The next generation of qualitative research is almost here! Our AI-powered app will help you analyze, code, and visualize qualitative data faster and smarter than ever before.',
    'cta' => 'Be among the first to experience the future of research. Sign up for early beta access and get a front-row seat when we launch!',
    'emailLabel' => 'Email address',
    'signupBtn' => 'Subscribe for Beta',
    'featuresTitle' => 'What to Expect',
    'feature1' => 'AI-powered coding and theme discovery',
    'feature2' => 'Instant qualitative data visualization',
    'feature3' => 'Collaboration tools for research teams',
    'feature4' => 'Secure, privacy-first data handling',
    'feature5' => 'Multi-language support for global research'
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
  <title><?= htmlspecialchars($texts['title']) ?></title>
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .ai-hero { background: linear-gradient(120deg, #e0e7ff 0%, #f8fafc 100%); padding: 4rem 0 2rem 0; text-align: center; }
    .ai-hero h1 { font-size: 2.5rem; font-weight: bold; margin-bottom: 1rem; }
    .ai-hero .ai-logo { font-size: 4rem; color: #6366f1; margin-bottom: 1rem; }
    .ai-hero p { font-size: 1.25rem; color: #374151; margin-bottom: 2rem; }
    .beta-signup { max-width: 400px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 2rem; }
    .lang-switcher { position: absolute; top: 1rem; right: 1rem; }
  </style>
</head>
<body>
  <div class="lang-switcher">
    <form method="get">
      <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="en"<?= $lang=='en'?' selected':'' ?>>English</option>
        <option value="fr"<?= $lang=='fr'?' selected':'' ?>>Français</option>
        <option value="es"<?= $lang=='es'?' selected':'' ?>>Español</option>
        <!-- Add more languages as needed -->
      </select>
    </form>
  </div>
  <section class="ai-hero">
    <div class="ai-logo">
      <i class="bi bi-robot"></i>
    </div>
    <h1><?= htmlspecialchars($texts['headline']) ?></h1>
    <p><?= htmlspecialchars($texts['teaser']) ?></p>
    <p><?= htmlspecialchars($texts['cta']) ?></p>
    <div class="beta-signup">
      <form id="betaSignupForm" method="post" action="/api/beta-signups">
        <div class="mb-3">
          <label for="betaEmail" class="form-label"><?= htmlspecialchars($texts['emailLabel']) ?></label>
          <input type="email" class="form-control" id="betaEmail" name="email" required placeholder="you@example.com">
        </div>
        <button type="submit" class="btn btn-primary w-100"><?= htmlspecialchars($texts['signupBtn']) ?></button>
        <div id="signupMsg" class="mt-3"></div>
      </form>
    </div>
  </section>
  <section class="container my-5">
    <h2 class="mb-4"><?= htmlspecialchars($texts['featuresTitle']) ?></h2>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><?= htmlspecialchars($texts['feature1']) ?></li>
      <li class="list-group-item"><?= htmlspecialchars($texts['feature2']) ?></li>
      <li class="list-group-item"><?= htmlspecialchars($texts['feature3']) ?></li>
      <li class="list-group-item"><?= htmlspecialchars($texts['feature4']) ?></li>
      <li class="list-group-item"><?= htmlspecialchars($texts['feature5']) ?></li>
    </ul>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Beta signup AJAX (optional, fallback to PHP post)
    document.getElementById('betaSignupForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const email = document.getElementById('betaEmail').value;
      const msg = document.getElementById('signupMsg');
      msg.textContent = '';
      fetch('/api/beta-signups', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          msg.innerHTML = '<span class="text-success">Thank you for subscribing! You will be notified when the beta launches.</span>';
        } else {
          msg.innerHTML = '<span class="text-danger">Subscription failed. Please try again.</span>';
        }
      })
      .catch(() => {
        msg.innerHTML = '<span class="text-danger">Network error. Please try again later.</span>';
      });
    });
  </script>
<?php include_once '../partials/footer.php'; ?> 