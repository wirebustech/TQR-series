<?php
require_once 'includes/translation.php';

$lang = getCurrentLanguage();
$texts = getTranslations($lang);

$pageTitle = $texts['research_ai_title'];
$pageDescription = $texts['research_ai_description'];

include 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold mb-4">
                            <?= htmlspecialchars($texts['research_ai_title']) ?>
                        </h1>
                        <p class="lead mb-4">
                            <?= htmlspecialchars($texts['research_ai_subtitle']) ?>
                        </p>
                        <p class="mb-5">
                            <?= htmlspecialchars($texts['research_ai_description']) ?>
                        </p>
                        <div class="d-flex gap-3">
                            <a href="#beta-signup" class="btn btn-light btn-lg">
                                <?= htmlspecialchars($texts['beta_signup_title']) ?>
                            </a>
                            <a href="#features" class="btn btn-outline-light btn-lg">
                                <?= $lang === 'fr' ? 'Fonctionnalités' : ($lang === 'es' ? 'Características' : 'Features') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center">
                        <div class="ai-visualization">
                            <div class="ai-brain">
                                <i class="bi bi-cpu" style="font-size: 8rem; opacity: 0.8;"></i>
                            </div>
                            <div class="floating-elements">
                                <div class="float-item" style="animation-delay: 0s;">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div class="float-item" style="animation-delay: 0.5s;">
                                    <i class="bi bi-lightbulb"></i>
                                </div>
                                <div class="float-item" style="animation-delay: 1s;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="float-item" style="animation-delay: 1.5s;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="display-5 fw-bold">
                        <?= $lang === 'fr' ? 'Fonctionnalités Puissantes' : ($lang === 'es' ? 'Características Poderosas' : 'Powerful Features') ?>
                    </h2>
                    <p class="lead text-muted">
                        <?= $lang === 'fr' ? 'Découvrez les capacités révolutionnaires de notre plateforme IA' : 
                            ($lang === 'es' ? 'Descubre las capacidades revolucionarias de nuestra plataforma de IA' : 
                             'Discover the revolutionary capabilities of our AI platform') ?>
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h4><?= $lang === 'fr' ? 'Analyse Automatisée' : ($lang === 'es' ? 'Análisis Automatizado' : 'Automated Analysis') ?></h4>
                        <p><?= $lang === 'fr' ? 'Codage et identification de thèmes alimentés par l\'IA' : 
                               ($lang === 'es' ? 'Codificación e identificación de temas impulsada por IA' : 
                                'AI-powered coding and theme identification') ?></p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h4><?= $lang === 'fr' ? 'Insights Intelligents' : ($lang === 'es' ? 'Insights Inteligentes' : 'Intelligent Insights') ?></h4>
                        <p><?= $lang === 'fr' ? 'Reconnaissance de motifs profonds et analyse de tendances' : 
                               ($lang === 'es' ? 'Reconocimiento profundo de patrones y análisis de tendencias' : 
                                'Deep pattern recognition and trend analysis') ?></p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4><?= $lang === 'fr' ? 'Outils Collaboratifs' : ($lang === 'es' ? 'Herramientas Colaborativas' : 'Collaborative Tools') ?></h4>
                        <p><?= $lang === 'fr' ? 'Recherche en équipe avec collaboration en temps réel' : 
                               ($lang === 'es' ? 'Investigación en equipo con colaboración en tiempo real' : 
                                'Team-based research with real-time collaboration') ?></p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <h4><?= $lang === 'fr' ? 'Plateforme Sécurisée' : ($lang === 'es' ? 'Plataforma Segura' : 'Secure Platform') ?></h4>
                        <p><?= $lang === 'fr' ? 'Sécurité de niveau entreprise pour les données de recherche sensibles' : 
                               ($lang === 'es' ? 'Seguridad de nivel empresarial para datos de investigación sensibles' : 
                                'Enterprise-grade security for sensitive research data') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Beta Signup Section -->
    <section id="beta-signup" class="beta-signup-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="signup-card">
                        <div class="text-center mb-4">
                            <h2 class="display-6 fw-bold">
                                <?= htmlspecialchars($texts['beta_signup_title']) ?>
                            </h2>
                            <p class="lead text-muted">
                                <?= htmlspecialchars($texts['beta_signup_subtitle']) ?>
                            </p>
                        </div>
                        
                        <form id="betaSignupForm" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        <?= htmlspecialchars($texts['beta_form_name']) ?> *
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback">
                                        <?= $lang === 'fr' ? 'Veuillez fournir un nom valide.' : 
                                            ($lang === 'es' ? 'Por favor proporciona un nombre válido.' : 
                                             'Please provide a valid name.') ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <?= htmlspecialchars($texts['beta_form_email']) ?> *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">
                                        <?= $lang === 'fr' ? 'Veuillez fournir une adresse email valide.' : 
                                            ($lang === 'es' ? 'Por favor proporciona una dirección de email válida.' : 
                                             'Please provide a valid email address.') ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="organization" class="form-label">
                                        <?= htmlspecialchars($texts['beta_form_organization']) ?>
                                    </label>
                                    <input type="text" class="form-control" id="organization" name="organization">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="role" class="form-label">
                                        <?= htmlspecialchars($texts['beta_form_role']) ?>
                                    </label>
                                    <input type="text" class="form-control" id="role" name="role">
                                </div>
                                
                                <div class="col-12">
                                    <label for="preferences" class="form-label">
                                        <?= htmlspecialchars($texts['beta_form_preferences']) ?>
                                    </label>
                                    <textarea class="form-control" id="preferences" name="preferences" rows="3" 
                                              placeholder="<?= htmlspecialchars($texts['beta_form_preferences_placeholder']) ?>"></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="priorityAccess" name="priority_access" value="1">
                                        <label class="form-check-label" for="priorityAccess">
                                            <strong><?= htmlspecialchars($texts['beta_form_priority']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($texts['beta_form_priority_desc']) ?></small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="bi bi-rocket"></i>
                                        <?= htmlspecialchars($texts['beta_form_submit']) ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div id="signupMessage" class="mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
}

.min-vh-75 {
    min-height: 75vh;
}

.ai-visualization {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
}

.ai-brain {
    position: relative;
    z-index: 2;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.float-item {
    position: absolute;
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.6);
    animation: float 3s ease-in-out infinite;
}

.float-item:nth-child(1) { top: 20%; left: 20%; }
.float-item:nth-child(2) { top: 30%; right: 20%; }
.float-item:nth-child(3) { bottom: 30%; left: 15%; }
.float-item:nth-child(4) { bottom: 20%; right: 15%; }

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.feature-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.feature-icon {
    font-size: 3rem;
    color: #0d6efd;
    margin-bottom: 1rem;
}

.signup-card {
    background: white;
    border-radius: 1rem;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #5a0fc8 100%);
}

@media (max-width: 768px) {
    .ai-visualization {
        min-height: 300px;
    }
    
    .float-item {
        font-size: 1.5rem;
    }
    
    .signup-card {
        padding: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('betaSignupForm');
    const messageDiv = document.getElementById('signupMessage');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/api/beta-signups', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.ok) {
                messageDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        <?= htmlspecialchars($texts['beta_success']) ?>
                    </div>
                `;
                form.reset();
                form.classList.remove('was-validated');
            } else {
                throw new Error(result.message || 'Signup failed');
            }
        } catch (error) {
            messageDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= htmlspecialchars($texts['beta_error']) ?>
                </div>
            `;
        }
        
        messageDiv.style.display = 'block';
        messageDiv.scrollIntoView({ behavior: 'smooth' });
    });
});
</script>

<?php include 'includes/footer.php'; ?> 