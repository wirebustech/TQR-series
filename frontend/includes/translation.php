<?php
// Google Translate API utility with file cache
function translateText($text, $target, $source = 'en') {
    // If target is English, return original text
    if ($target === 'en') {
        return $text;
    }
    
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) mkdir($cacheDir, 0777, true);
    $cacheKey = md5($text . $target . $source);
    $cacheFile = "$cacheDir/$cacheKey.txt";
    if (file_exists($cacheFile)) {
        return file_get_contents($cacheFile);
    }
    
    // For now, return original text if no API key
    $apiKey = getenv('GOOGLE_TRANSLATE_API_KEY');
    if (!$apiKey) {
        return $text;
    }
    
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
    $translated = $result['data']['translations'][0]['translatedText'] ?? $text;
    file_put_contents($cacheFile, $translated);
    return $translated;
}

// Get translations for a specific language
function getTranslations($lang = 'en') {
    $translations = [
        'en' => [
            'home' => 'Home',
            'webinars' => 'Webinars',
            'blog' => 'Blog',
            'about' => 'About',
            'contact' => 'Contact',
            'login' => 'Login',
            'register' => 'Register',
            'dashboard' => 'Dashboard',
            'search' => 'Search',
            'research_ai' => 'Research AI',
            
            // Opportunities
            'opportunities_title' => 'Latest Opportunities',
            'opportunities_subtitle' => 'Discover new research collaborations and funding opportunities',
            'no_opportunities' => 'No opportunities available at the moment.',
            'check_back' => 'Check back soon for new opportunities!',
            'opportunity_types' => [
                'research' => 'Research',
                'collaboration' => 'Collaboration',
                'funding' => 'Funding',
                'conference' => 'Conference',
                'publication' => 'Publication'
            ],
            
            // Research AI
            'research_ai_title' => 'Research AI - Coming Soon',
            'research_ai_subtitle' => 'Revolutionary AI-powered qualitative research platform',
            'research_ai_description' => 'Join the future of qualitative research with our cutting-edge AI platform. Get early access to tools that will transform how you analyze, interpret, and discover insights from qualitative data.',
            'beta_signup_title' => 'Join the Beta',
            'beta_signup_subtitle' => 'Be among the first to experience the future of qualitative research',
            'beta_form_name' => 'Full Name',
            'beta_form_email' => 'Email Address',
            'beta_form_organization' => 'Organization',
            'beta_form_role' => 'Role/Position',
            'beta_form_preferences' => 'Research Interests',
            'beta_form_preferences_placeholder' => 'Tell us about your research interests and how you plan to use Research AI...',
            'beta_form_priority' => 'Priority Access',
            'beta_form_priority_desc' => 'I\'m interested in priority access for beta testing',
            'beta_form_submit' => 'Join Beta Waitlist',
            'beta_success' => 'Thank you for joining our beta waitlist! We\'ll notify you when Research AI becomes available.',
            'beta_error' => 'There was an error processing your request. Please try again.',
            
            // Language switcher
            'language_switcher' => 'Language',
            'select_language' => 'Select Language'
        ],
        'fr' => [
            'home' => 'Accueil',
            'webinars' => 'Webinaires',
            'blog' => 'Blog',
            'about' => 'À propos',
            'contact' => 'Contact',
            'login' => 'Connexion',
            'register' => 'S\'inscrire',
            'dashboard' => 'Tableau de bord',
            'search' => 'Recherche',
            'research_ai' => 'IA de recherche',
            
            // Opportunities
            'opportunities_title' => 'Dernières Opportunités',
            'opportunities_subtitle' => 'Découvrez de nouvelles collaborations de recherche et opportunités de financement',
            'no_opportunities' => 'Aucune opportunité disponible pour le moment.',
            'check_back' => 'Revenez bientôt pour de nouvelles opportunités !',
            'opportunity_types' => [
                'research' => 'Recherche',
                'collaboration' => 'Collaboration',
                'funding' => 'Financement',
                'conference' => 'Conférence',
                'publication' => 'Publication'
            ],
            
            // Research AI
            'research_ai_title' => 'IA de Recherche - Bientôt Disponible',
            'research_ai_subtitle' => 'Plateforme révolutionnaire de recherche qualitative alimentée par l\'IA',
            'research_ai_description' => 'Rejoignez l\'avenir de la recherche qualitative avec notre plateforme IA de pointe. Obtenez un accès anticipé aux outils qui transformeront la façon dont vous analysez, interprétez et découvrez des insights à partir de données qualitatives.',
            'beta_signup_title' => 'Rejoindre la Bêta',
            'beta_signup_subtitle' => 'Soyez parmi les premiers à découvrir l\'avenir de la recherche qualitative',
            'beta_form_name' => 'Nom Complet',
            'beta_form_email' => 'Adresse Email',
            'beta_form_organization' => 'Organisation',
            'beta_form_role' => 'Rôle/Poste',
            'beta_form_preferences' => 'Intérêts de Recherche',
            'beta_form_preferences_placeholder' => 'Parlez-nous de vos intérêts de recherche et comment vous prévoyez utiliser l\'IA de Recherche...',
            'beta_form_priority' => 'Accès Prioritaire',
            'beta_form_priority_desc' => 'Je suis intéressé par l\'accès prioritaire pour les tests bêta',
            'beta_form_submit' => 'Rejoindre la Liste d\'Attente Bêta',
            'beta_success' => 'Merci d\'avoir rejoint notre liste d\'attente bêta ! Nous vous informerons quand l\'IA de Recherche sera disponible.',
            'beta_error' => 'Il y a eu une erreur lors du traitement de votre demande. Veuillez réessayer.',
            
            // Language switcher
            'language_switcher' => 'Langue',
            'select_language' => 'Sélectionner la Langue'
        ],
        'es' => [
            'home' => 'Inicio',
            'webinars' => 'Seminarios web',
            'blog' => 'Blog',
            'about' => 'Acerca de',
            'contact' => 'Contacto',
            'login' => 'Iniciar sesión',
            'register' => 'Registrarse',
            'dashboard' => 'Panel de control',
            'search' => 'Buscar',
            'research_ai' => 'IA de investigación',
            
            // Opportunities
            'opportunities_title' => 'Últimas Oportunidades',
            'opportunities_subtitle' => 'Descubre nuevas colaboraciones de investigación y oportunidades de financiación',
            'no_opportunities' => 'No hay oportunidades disponibles en este momento.',
            'check_back' => '¡Vuelve pronto para nuevas oportunidades!',
            'opportunity_types' => [
                'research' => 'Investigación',
                'collaboration' => 'Colaboración',
                'funding' => 'Financiación',
                'conference' => 'Conferencia',
                'publication' => 'Publicación'
            ],
            
            // Research AI
            'research_ai_title' => 'IA de Investigación - Próximamente',
            'research_ai_subtitle' => 'Plataforma revolucionaria de investigación cualitativa impulsada por IA',
            'research_ai_description' => 'Únete al futuro de la investigación cualitativa con nuestra plataforma de IA de vanguardia. Obtén acceso temprano a herramientas que transformarán cómo analizas, interpretas y descubres insights de datos cualitativos.',
            'beta_signup_title' => 'Únete a la Beta',
            'beta_signup_subtitle' => 'Sé uno de los primeros en experimentar el futuro de la investigación cualitativa',
            'beta_form_name' => 'Nombre Completo',
            'beta_form_email' => 'Dirección de Email',
            'beta_form_organization' => 'Organización',
            'beta_form_role' => 'Rol/Posición',
            'beta_form_preferences' => 'Intereses de Investigación',
            'beta_form_preferences_placeholder' => 'Cuéntanos sobre tus intereses de investigación y cómo planeas usar la IA de Investigación...',
            'beta_form_priority' => 'Acceso Prioritario',
            'beta_form_priority_desc' => 'Estoy interesado en acceso prioritario para pruebas beta',
            'beta_form_submit' => 'Unirse a Lista de Espera Beta',
            'beta_success' => '¡Gracias por unirte a nuestra lista de espera beta! Te notificaremos cuando la IA de Investigación esté disponible.',
            'beta_error' => 'Hubo un error procesando tu solicitud. Por favor, inténtalo de nuevo.',
            
            // Language switcher
            'language_switcher' => 'Idioma',
            'select_language' => 'Seleccionar Idioma'
        ]
    ];
    
    return $translations[$lang] ?? $translations['en'];
}

// Get current language from URL or session
function getCurrentLanguage() {
    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
        if (in_array($lang, ['en', 'fr', 'es'])) {
            $_SESSION['language'] = $lang;
            return $lang;
        }
    }
    
    return $_SESSION['language'] ?? 'en';
}

// Get available languages
function getAvailableLanguages() {
    return [
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇺🇸'],
        'fr' => ['name' => 'French', 'native' => 'Français', 'flag' => '🇫🇷'],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸']
    ];
}
?> 