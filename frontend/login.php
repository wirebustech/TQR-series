<?php
session_start();

$pageTitle = 'Login - TQRS';
$pageDescription = 'Sign in to your TQRS account to access webinars, research content, and community features.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'loginTitle' => 'Welcome Back',
    'loginSubtitle' => 'Sign in to your TQRS account',
    'email' => 'Email Address',
    'password' => 'Password',
    'rememberMe' => 'Remember me',
    'forgotPassword' => 'Forgot your password?',
    'signIn' => 'Sign In',
    'dontHaveAccount' => 'Don\'t have an account?',
    'createAccount' => 'Create Account',
    'orContinueWith' => 'Or continue with',
    'googleSignIn' => 'Sign in with Google',
    'facebookSignIn' => 'Sign in with Facebook',
    'linkedinSignIn' => 'Sign in with LinkedIn',
    'loginError' => 'Invalid email or password',
    'loginSuccess' => 'Login successful! Redirecting...',
    'passwordReset' => 'Password Reset',
    'passwordResetText' => 'Enter your email address and we\'ll send you a link to reset your password.',
    'sendResetLink' => 'Send Reset Link',
    'backToLogin' => 'Back to Login',
    'resetEmailSent' => 'Password reset email sent!',
    'resetEmailError' => 'Error sending reset email. Please try again.'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'login':
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $remember = isset($_POST['remember']);
                
                // Here you would validate against your backend API
                if ($email && $password) {
                    // Mock authentication - replace with actual API call
                    if ($email === 'demo@tqrs.org' && $password === 'password') {
                        $_SESSION['user_id'] = 1;
                        $_SESSION['user_name'] = 'Demo User';
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_role'] = 'user';
                        
                        if ($remember) {
                            setcookie('tqrs_remember', '1', time() + (30 * 24 * 60 * 60), '/');
                        }
                        
                        $message = $texts['loginSuccess'];
                        $messageType = 'success';
                        
                        // Redirect after successful login
                        header('Location: index.php?lang=' . urlencode($lang));
                        exit;
                    } else {
                        $message = $texts['loginError'];
                        $messageType = 'error';
                    }
                }
                break;
                
            case 'reset_password':
                $email = $_POST['reset_email'] ?? '';
                if ($email) {
                    // Here you would send reset email via your backend API
                    $message = $texts['resetEmailSent'];
                    $messageType = 'success';
                } else {
                    $message = $texts['resetEmailError'];
                    $messageType = 'error';
                }
                break;
        }
    }
}
?>

<!-- Login Section -->
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h2 class="fw-bold"><?= htmlspecialchars($texts['loginTitle']) ?></h2>
                            <p class="text-muted"><?= htmlspecialchars($texts['loginSubtitle']) ?></p>
                        </div>

                        <!-- Message Display -->
                        <?php if ($message): ?>
                            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                                <i class="bi bi-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form id="loginForm" method="POST" action="login.php?lang=<?= urlencode($lang) ?>">
                            <input type="hidden" name="action" value="login">
                            
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= htmlspecialchars($texts['email']) ?></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label"><?= htmlspecialchars($texts['password']) ?></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        <?= htmlspecialchars($texts['rememberMe']) ?>
                                    </label>
                                </div>
                                <a href="#" class="text-decoration-none" onclick="showPasswordReset()">
                                    <?= htmlspecialchars($texts['forgotPassword']) ?>
                                </a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right"></i> <?= htmlspecialchars($texts['signIn']) ?>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="text-center my-4">
                            <span class="text-muted"><?= htmlspecialchars($texts['orContinueWith']) ?></span>
                        </div>

                        <!-- Social Login -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-secondary" onclick="socialLogin('google')">
                                <i class="bi bi-google"></i> <?= htmlspecialchars($texts['googleSignIn']) ?>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="socialLogin('facebook')">
                                <i class="bi bi-facebook"></i> <?= htmlspecialchars($texts['facebookSignIn']) ?>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="socialLogin('linkedin')">
                                <i class="bi bi-linkedin"></i> <?= htmlspecialchars($texts['linkedinSignIn']) ?>
                            </button>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center mt-4">
                            <p class="mb-0">
                                <?= htmlspecialchars($texts['dontHaveAccount']) ?>
                                <a href="register.php?lang=<?= urlencode($lang) ?>" class="text-decoration-none fw-bold">
                                    <?= htmlspecialchars($texts['createAccount']) ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div class="modal fade" id="passwordResetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($texts['passwordReset']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted"><?= htmlspecialchars($texts['passwordResetText']) ?></p>
                <form id="passwordResetForm" method="POST" action="login.php?lang=<?= urlencode($lang) ?>">
                    <input type="hidden" name="action" value="reset_password">
                    <div class="mb-3">
                        <label for="reset_email" class="form-label"><?= htmlspecialchars($texts['email']) ?></label>
                        <input type="email" class="form-control" id="reset_email" name="reset_email" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= htmlspecialchars($texts['sendResetLink']) ?>
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= htmlspecialchars($texts['backToLogin']) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Password visibility toggle
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        passwordInput.type = 'password';
        icon.className = 'bi bi-eye';
    }
});

// Show password reset modal
function showPasswordReset() {
    const modal = new bootstrap.Modal(document.getElementById('passwordResetModal'));
    modal.show();
}

// Social login handlers
function socialLogin(provider) {
    // In a real app, this would redirect to OAuth provider
    console.log(`Logging in with ${provider}`);
    
    // Mock social login - replace with actual OAuth flow
    const socialLoginUrl = `/auth/${provider}?lang=${currentLang}`;
    window.location.href = socialLoginUrl;
}

// Form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        e.preventDefault();
        showToast('Error', 'Please fill in all required fields.');
    }
});

// Password reset form validation
document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
    const email = document.getElementById('reset_email').value;
    
    if (!email) {
        e.preventDefault();
        showToast('Error', 'Please enter your email address.');
    }
});

// Auto-focus email field
document.addEventListener('DOMContentLoaded', function() {
    const emailField = document.getElementById('email');
    if (emailField) {
        emailField.focus();
    }
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 