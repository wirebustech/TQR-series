<?php
session_start();

$pageTitle = 'Register - TQRS';
$pageDescription = 'Create your TQRS account to access research content, webinars, and join the qualitative research community.';

include_once __DIR__ . '/includes/header.php';

$texts = [
    'registerTitle' => 'Join TQRS',
    'registerSubtitle' => 'Create your account to get started',
    'fullName' => 'Full Name',
    'email' => 'Email Address',
    'password' => 'Password',
    'confirmPassword' => 'Confirm Password',
    'institution' => 'Institution/Organization',
    'researchArea' => 'Research Area',
    'role' => 'Role',
    'student' => 'Student',
    'researcher' => 'Researcher',
    'academic' => 'Academic',
    'practitioner' => 'Practitioner',
    'other' => 'Other',
    'agreeTerms' => 'I agree to the',
    'termsOfService' => 'Terms of Service',
    'privacyPolicy' => 'Privacy Policy',
    'newsletterConsent' => 'I would like to receive newsletters and updates',
    'createAccount' => 'Create Account',
    'alreadyHaveAccount' => 'Already have an account?',
    'signIn' => 'Sign In',
    'orContinueWith' => 'Or continue with',
    'googleSignUp' => 'Sign up with Google',
    'facebookSignUp' => 'Sign up with Facebook',
    'linkedinSignUp' => 'Sign up with LinkedIn',
    'registrationSuccess' => 'Account created successfully! Please check your email to verify your account.',
    'registrationError' => 'Error creating account. Please try again.',
    'passwordMismatch' => 'Passwords do not match',
    'weakPassword' => 'Password must be at least 8 characters long',
    'invalidEmail' => 'Please enter a valid email address',
    'nameRequired' => 'Full name is required',
    'termsRequired' => 'You must agree to the terms of service',
    'verificationEmail' => 'Verification Email Sent',
    'verificationText' => 'We\'ve sent a verification email to your address. Please check your inbox and click the verification link to activate your account.'
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}

// Handle form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $institution = $_POST['institution'] ?? '';
    $researchArea = $_POST['research_area'] ?? '';
    $role = $_POST['role'] ?? '';
    $agreeTerms = isset($_POST['agree_terms']);
    $newsletterConsent = isset($_POST['newsletter_consent']);
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = $texts['nameRequired'];
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $texts['invalidEmail'];
    }
    
    if (strlen($password) < 8) {
        $errors[] = $texts['weakPassword'];
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = $texts['passwordMismatch'];
    }
    
    if (!$agreeTerms) {
        $errors[] = $texts['termsRequired'];
    }
    
    if (empty($errors)) {
        // Here you would create the user account via your backend API
        // For now, we'll simulate a successful registration
        
        // Mock user creation - replace with actual API call
        $userId = rand(1000, 9999); // Mock user ID
        
        // Store user data in session for demo purposes
        $_SESSION['temp_user'] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'institution' => $institution,
            'research_area' => $researchArea,
            'role' => $role
        ];
        
        $message = $texts['registrationSuccess'];
        $messageType = 'success';
        
        // In a real app, you would send verification email here
        // For demo, we'll redirect to a verification page
        header('Location: verify-email.php?lang=' . urlencode($lang));
        exit;
    } else {
        $message = implode(', ', $errors);
        $messageType = 'error';
    }
}
?>

<!-- Registration Section -->
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h2 class="fw-bold"><?= htmlspecialchars($texts['registerTitle']) ?></h2>
                            <p class="text-muted"><?= htmlspecialchars($texts['registerSubtitle']) ?></p>
                        </div>

                        <!-- Message Display -->
                        <?php if ($message): ?>
                            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                                <i class="bi bi-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form id="registerForm" method="POST" action="register.php?lang=<?= urlencode($lang) ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label"><?= htmlspecialchars($texts['fullName']) ?> *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label"><?= htmlspecialchars($texts['email']) ?> *</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label"><?= htmlspecialchars($texts['password']) ?> *</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Minimum 8 characters</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label"><?= htmlspecialchars($texts['confirmPassword']) ?> *</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="institution" class="form-label"><?= htmlspecialchars($texts['institution']) ?></label>
                                    <input type="text" class="form-control" id="institution" name="institution"
                                           value="<?= htmlspecialchars($_POST['institution'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label"><?= htmlspecialchars($texts['role']) ?></label>
                                    <select class="form-select" id="role" name="role">
                                        <option value=""><?= htmlspecialchars($texts['role']) ?></option>
                                        <option value="student"<?= ($_POST['role'] ?? '') === 'student' ? ' selected' : '' ?>><?= htmlspecialchars($texts['student']) ?></option>
                                        <option value="researcher"<?= ($_POST['role'] ?? '') === 'researcher' ? ' selected' : '' ?>><?= htmlspecialchars($texts['researcher']) ?></option>
                                        <option value="academic"<?= ($_POST['role'] ?? '') === 'academic' ? ' selected' : '' ?>><?= htmlspecialchars($texts['academic']) ?></option>
                                        <option value="practitioner"<?= ($_POST['role'] ?? '') === 'practitioner' ? ' selected' : '' ?>><?= htmlspecialchars($texts['practitioner']) ?></option>
                                        <option value="other"<?= ($_POST['role'] ?? '') === 'other' ? ' selected' : '' ?>><?= htmlspecialchars($texts['other']) ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="research_area" class="form-label"><?= htmlspecialchars($texts['researchArea']) ?></label>
                                <input type="text" class="form-control" id="research_area" name="research_area"
                                       value="<?= htmlspecialchars($_POST['research_area'] ?? '') ?>"
                                       placeholder="e.g., Education, Healthcare, Social Sciences">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                                    <label class="form-check-label" for="agree_terms">
                                        <?= htmlspecialchars($texts['agreeTerms']) ?> 
                                        <a href="terms.php?lang=<?= urlencode($lang) ?>" target="_blank" class="text-decoration-none">
                                            <?= htmlspecialchars($texts['termsOfService']) ?>
                                        </a> 
                                        <?= htmlspecialchars(' and ') ?>
                                        <a href="privacy.php?lang=<?= urlencode($lang) ?>" target="_blank" class="text-decoration-none">
                                            <?= htmlspecialchars($texts['privacyPolicy']) ?>
                                        </a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter_consent" name="newsletter_consent">
                                    <label class="form-check-label" for="newsletter_consent">
                                        <?= htmlspecialchars($texts['newsletterConsent']) ?>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-person-plus"></i> <?= htmlspecialchars($texts['createAccount']) ?>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="text-center my-4">
                            <span class="text-muted"><?= htmlspecialchars($texts['orContinueWith']) ?></span>
                        </div>

                        <!-- Social Registration -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-secondary" onclick="socialRegister('google')">
                                <i class="bi bi-google"></i> <?= htmlspecialchars($texts['googleSignUp']) ?>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="socialRegister('facebook')">
                                <i class="bi bi-facebook"></i> <?= htmlspecialchars($texts['facebookSignUp']) ?>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="socialRegister('linkedin')">
                                <i class="bi bi-linkedin"></i> <?= htmlspecialchars($texts['linkedinSignUp']) ?>
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mt-4">
                            <p class="mb-0">
                                <?= htmlspecialchars($texts['alreadyHaveAccount']) ?>
                                <a href="login.php?lang=<?= urlencode($lang) ?>" class="text-decoration-none fw-bold">
                                    <?= htmlspecialchars($texts['signIn']) ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password visibility toggles
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

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('confirm_password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        passwordInput.type = 'password';
        icon.className = 'bi bi-eye';
    }
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = calculatePasswordStrength(password);
    updatePasswordStrengthIndicator(strength);
});

function calculatePasswordStrength(password) {
    let score = 0;
    
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    return score;
}

function updatePasswordStrengthIndicator(strength) {
    const strengthText = document.getElementById('password-strength');
    if (!strengthText) {
        const strengthDiv = document.createElement('div');
        strengthDiv.id = 'password-strength';
        strengthDiv.className = 'form-text mt-1';
        document.getElementById('password').parentNode.appendChild(strengthDiv);
    }
    
    const strengthDiv = document.getElementById('password-strength');
    const colors = ['text-danger', 'text-warning', 'text-info', 'text-primary', 'text-success'];
    const messages = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    
    if (strength > 0) {
        strengthDiv.className = `form-text mt-1 ${colors[strength - 1]}`;
        strengthDiv.textContent = `Password strength: ${messages[strength - 1]}`;
    } else {
        strengthDiv.textContent = '';
    }
}

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

// Social registration handlers
function socialRegister(provider) {
    // In a real app, this would redirect to OAuth provider
    console.log(`Registering with ${provider}`);
    
    // Mock social registration - replace with actual OAuth flow
    const socialRegisterUrl = `/auth/${provider}/register?lang=${currentLang}`;
    window.location.href = socialRegisterUrl;
}

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const agreeTerms = document.getElementById('agree_terms').checked;
    
    let isValid = true;
    
    if (!name) {
        showFieldError('name', 'Full name is required');
        isValid = false;
    }
    
    if (!email || !isValidEmail(email)) {
        showFieldError('email', 'Please enter a valid email address');
        isValid = false;
    }
    
    if (password.length < 8) {
        showFieldError('password', 'Password must be at least 8 characters long');
        isValid = false;
    }
    
    if (password !== confirmPassword) {
        showFieldError('confirm_password', 'Passwords do not match');
        isValid = false;
    }
    
    if (!agreeTerms) {
        showFieldError('agree_terms', 'You must agree to the terms of service');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.getElementById(`${fieldId}-error`);
    
    if (errorDiv) {
        errorDiv.remove();
    }
    
    const newErrorDiv = document.createElement('div');
    newErrorDiv.id = `${fieldId}-error`;
    newErrorDiv.className = 'text-danger small mt-1';
    newErrorDiv.textContent = message;
    
    field.parentNode.appendChild(newErrorDiv);
    field.classList.add('is-invalid');
}

// Remove error messages when user starts typing
document.querySelectorAll('input, select').forEach(field => {
    field.addEventListener('input', function() {
        const errorDiv = document.getElementById(`${this.id}-error`);
        if (errorDiv) {
            errorDiv.remove();
        }
        this.classList.remove('is-invalid');
    });
});

// Auto-focus name field
document.addEventListener('DOMContentLoaded', function() {
    const nameField = document.getElementById('name');
    if (nameField) {
        nameField.focus();
    }
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?> 