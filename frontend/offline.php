<?php
include_once __DIR__ . '/includes/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'TQRS - Offline',
    'offlineTitle' => 'You\'re Offline',
    'offlineMessage' => 'Don\'t worry! You can still access some content while offline.',
    'cachedContent' => 'Cached Content Available',
    'newsletterSignup' => 'Newsletter Signup',
    'betaSignup' => 'Beta Signup',
    'emailPlaceholder' => 'Enter your email address',
    'subscribeBtn' => 'Subscribe',
    'betaBtn' => 'Join Beta',
    'backOnline' => 'Back Online',
    'reloadBtn' => 'Reload Page'
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .offline-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .offline-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .offline-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .lang-switcher {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
    </style>
</head>
<body>
    <div class="lang-switcher">
        <form method="get">
            <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="en"<?= $lang=='en'?' selected':'' ?>>English</option>
                <option value="fr"<?= $lang=='fr'?' selected':'' ?>>Français</option>
                <option value="es"<?= $lang=='es'?' selected':'' ?>>Español</option>
            </select>
        </form>
    </div>
    
    <div class="offline-container">
        <div class="offline-card">
            <div class="offline-icon">
                <i class="bi bi-wifi-off"></i>
            </div>
            <h1 class="mb-3"><?= htmlspecialchars($texts['offlineTitle']) ?></h1>
            <p class="lead mb-4"><?= htmlspecialchars($texts['offlineMessage']) ?></p>
            
            <div class="mb-4">
                <h5><?= htmlspecialchars($texts['cachedContent']) ?></h5>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="showNewsletterForm()">
                        <i class="bi bi-envelope"></i> <?= htmlspecialchars($texts['newsletterSignup']) ?>
                    </button>
                    <button class="btn btn-outline-success" onclick="showBetaForm()">
                        <i class="bi bi-robot"></i> <?= htmlspecialchars($texts['betaSignup']) ?>
                    </button>
                </div>
            </div>
            
            <div id="newsletterForm" style="display: none;">
                <h6><?= htmlspecialchars($texts['newsletterSignup']) ?></h6>
                <form onsubmit="handleNewsletterSignup(event)">
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="<?= htmlspecialchars($texts['emailPlaceholder']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= htmlspecialchars($texts['subscribeBtn']) ?></button>
                </form>
            </div>
            
            <div id="betaForm" style="display: none;">
                <h6><?= htmlspecialchars($texts['betaSignup']) ?></h6>
                <form onsubmit="handleBetaSignup(event)">
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="<?= htmlspecialchars($texts['emailPlaceholder']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success"><?= htmlspecialchars($texts['betaBtn']) ?></button>
                </form>
            </div>
            
            <div id="onlineStatus" style="display: none;">
                <div class="alert alert-success">
                    <i class="bi bi-wifi"></i> <?= htmlspecialchars($texts['backOnline']) ?>
                </div>
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <?= htmlspecialchars($texts['reloadBtn']) ?>
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showNewsletterForm() {
            document.getElementById('newsletterForm').style.display = 'block';
            document.getElementById('betaForm').style.display = 'none';
        }
        
        function showBetaForm() {
            document.getElementById('betaForm').style.display = 'block';
            document.getElementById('newsletterForm').style.display = 'none';
        }
        
        function handleNewsletterSignup(e) {
            e.preventDefault();
            const email = e.target.querySelector('input[type="email"]').value;
            // Store for later sync
            localStorage.setItem('pending_newsletter', email);
            alert('Newsletter signup stored for when you\'re back online!');
        }
        
        function handleBetaSignup(e) {
            e.preventDefault();
            const email = e.target.querySelector('input[type="email"]').value;
            // Store for later sync
            localStorage.setItem('pending_beta', email);
            alert('Beta signup stored for when you\'re back online!');
        }
        
        // Check for online status
        window.addEventListener('online', function() {
            document.getElementById('onlineStatus').style.display = 'block';
        });
    </script>
</body>
</html> 