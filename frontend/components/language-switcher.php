<?php
/**
 * Language Switcher Component
 * Provides a dropdown to switch between available languages
 */

require_once __DIR__ . '/../includes/translation.php';

$currentLang = getCurrentLanguage();
$availableLanguages = getAvailableLanguages();
$texts = getTranslations($currentLang);
?>

<div class="language-switcher dropdown">
    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="languageSwitcher" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="language-flag"><?= $availableLanguages[$currentLang]['flag'] ?></span>
        <span class="language-name d-none d-md-inline"><?= $availableLanguages[$currentLang]['native'] ?></span>
    </button>
    
    <ul class="dropdown-menu" aria-labelledby="languageSwitcher">
        <?php foreach ($availableLanguages as $code => $language): ?>
            <li>
                <a class="dropdown-item <?= $code === $currentLang ? 'active' : '' ?>" 
                   href="?<?= http_build_query(array_merge($_GET, ['lang' => $code])) ?>"
                   data-lang="<?= $code ?>">
                    <span class="language-flag me-2"><?= $language['flag'] ?></span>
                    <span class="language-native"><?= $language['native'] ?></span>
                    <small class="text-muted ms-2"><?= $language['name'] ?></small>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<style>
.language-switcher .dropdown-toggle {
    border: none;
    background: transparent;
    color: inherit;
    padding: 0.25rem 0.5rem;
}

.language-switcher .dropdown-toggle:hover,
.language-switcher .dropdown-toggle:focus {
    background: rgba(0, 0, 0, 0.1);
    color: inherit;
}

.language-switcher .dropdown-item {
    padding: 0.5rem 1rem;
}

.language-switcher .dropdown-item.active {
    background-color: var(--bs-primary);
    color: white;
}

.language-switcher .dropdown-item:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.language-switcher .dropdown-item.active:hover {
    background-color: var(--bs-primary);
}

.language-flag {
    font-size: 1.2em;
}

@media (max-width: 768px) {
    .language-switcher .dropdown-toggle {
        padding: 0.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle language switching
    const languageLinks = document.querySelectorAll('.language-switcher .dropdown-item');
    
    languageLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const newLang = this.dataset.lang;
            const currentUrl = new URL(window.location.href);
            
            // Update URL parameter
            currentUrl.searchParams.set('lang', newLang);
            
            // Store in localStorage for persistence
            localStorage.setItem('tqrs_language', newLang);
            
            // Redirect to new URL
            window.location.href = currentUrl.toString();
        });
    });
    
    // Auto-detect language from localStorage on page load
    const storedLang = localStorage.getItem('tqrs_language');
    if (storedLang && storedLang !== '<?= $currentLang ?>') {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('lang', storedLang);
        window.location.href = currentUrl.toString();
    }
});
</script> 