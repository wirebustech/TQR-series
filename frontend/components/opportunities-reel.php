<?php
/**
 * Opportunities News Reel Component
 * Displays latest research opportunities and collaborations
 */

// Get current language and translations
require_once __DIR__ . '/../includes/translation.php';
$currentLang = getCurrentLanguage();
$texts = getTranslations($currentLang);

// Get opportunities from API
$opportunities = [];
$apiUrl = 'http://localhost:8000/api/opportunities/latest';

try {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json',
            'timeout' => 5
        ]
    ]);
    
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response !== false) {
        $data = json_decode($response, true);
        if ($data && isset($data['data'])) {
            $opportunities = $data['data'];
        }
    }
} catch (Exception $e) {
    // Fallback to mock data if API is not available
    $opportunities = [
        [
            'id' => 1,
            'title' => 'Research Collaboration Opportunity',
            'content' => 'Join our international research team studying qualitative methodologies in healthcare.',
            'type' => 'Collaboration',
            'url' => '#',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 2,
            'title' => 'Funding Available for Qualitative Research',
            'content' => 'Apply for our research grant program supporting innovative qualitative research projects.',
            'type' => 'Funding',
            'url' => '#',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'id' => 3,
            'title' => 'Call for Papers: Qualitative Research Conference',
            'content' => 'Submit your research papers for our upcoming international conference on qualitative research.',
            'type' => 'Conference',
            'url' => '#',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ]
    ];
}

// Add view all text if not present
if (!isset($texts['view_all'])) {
    $texts['view_all'] = $currentLang === 'fr' ? 'Voir Tout' : ($currentLang === 'es' ? 'Ver Todo' : 'View All');
}
?>

<!-- Opportunities News Reel Section -->
<section class="opportunities-reel bg-light py-4">
    <div class="container">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h3 class="mb-0">
                    <i class="fas fa-bullhorn text-primary me-2"></i>
                    <?= htmlspecialchars($texts['opportunities_title']) ?>
                </h3>
            </div>
            <div class="col-auto">
                <a href="admin/opportunities.php" class="btn btn-outline-primary btn-sm">
                    <?= htmlspecialchars($texts['view_all']) ?>
                </a>
            </div>
        </div>
        
        <div class="opportunities-ticker">
            <?php if (empty($opportunities)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-1"><?= htmlspecialchars($texts['no_opportunities']) ?></p>
                    <small class="text-muted"><?= htmlspecialchars($texts['check_back']) ?></small>
                </div>
            <?php else: ?>
                <div class="ticker-track">
                    <?php foreach ($opportunities as $opportunity): ?>
                        <div class="opportunity-item">
                            <div class="opportunity-content">
                                <span class="opportunity-type <?= getTypeColor($opportunity['type']) ?>">
                                    <?= htmlspecialchars($texts['opportunity_types'][$opportunity['type']] ?? $opportunity['type']) ?>
                                </span>
                                <h5 class="opportunity-title">
                                    <?php if (!empty($opportunity['url'])): ?>
                                        <a href="<?= htmlspecialchars($opportunity['url']) ?>" target="_blank" rel="noopener">
                                            <?= htmlspecialchars($opportunity['title']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($opportunity['title']) ?>
                                    <?php endif; ?>
                                </h5>
                                <p class="opportunity-description">
                                    <?= htmlspecialchars($opportunity['content']) ?>
                                </p>
                                <?php if (!empty($opportunity['url'])): ?>
                                    <a href="<?= htmlspecialchars($opportunity['url']) ?>" 
                                       class="btn btn-sm btn-primary" 
                                       target="_blank" 
                                       rel="noopener">
                                        <?= $currentLang === 'fr' ? 'En savoir plus' : ($currentLang === 'es' ? 'Leer más' : 'Learn More') ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.opportunities-reel {
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.opportunities-ticker {
    overflow: hidden;
    position: relative;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.ticker-track {
    display: flex;
    animation: scroll 60s linear infinite;
    gap: 2rem;
    padding: 1rem 0;
}

.opportunity-item {
    flex: 0 0 auto;
    width: 350px;
    padding: 1.5rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid #0d6efd;
}

.opportunity-type {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.opportunity-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.opportunity-title a {
    color: inherit;
    text-decoration: none;
}

.opportunity-title a:hover {
    color: #0d6efd;
}

.opportunity-description {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.type-research { background-color: #e3f2fd; color: #1976d2; }
.type-collaboration { background-color: #e8f5e8; color: #388e3c; }
.type-funding { background-color: #fff3e0; color: #f57c00; }
.type-conference { background-color: #f3e5f5; color: #7b1fa2; }
.type-publication { background-color: #fce4ec; color: #c2185b; }

@keyframes scroll {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

.opportunities-ticker:hover .ticker-track {
    animation-play-state: paused;
}

@media (max-width: 768px) {
    .opportunity-item {
        width: 300px;
    }
    
    .ticker-track {
        animation-duration: 40s;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh opportunities every 30 seconds
    setInterval(function() {
        fetchOpportunities();
    }, 30000);
    
    function fetchOpportunities() {
        fetch('http://localhost:8000/api/opportunities/latest')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateOpportunitiesDisplay(data.data);
                }
            })
            .catch(error => {
                console.log('Failed to fetch opportunities:', error);
            });
    }
    
    function updateOpportunitiesDisplay(opportunities) {
        const tickerTrack = document.querySelector('.ticker-track');
        if (!tickerTrack || opportunities.length === 0) return;
        
        tickerTrack.innerHTML = opportunities.map(opportunity => `
            <div class="opportunity-item">
                <div class="opportunity-content">
                    <span class="opportunity-type ${getTypeColor(opportunity.type)}">
                        ${getTypeTranslation(opportunity.type)}
                    </span>
                    <h5 class="opportunity-title">
                        ${opportunity.url ? 
                            `<a href="${opportunity.url}" target="_blank" rel="noopener">${opportunity.title}</a>` : 
                            opportunity.title
                        }
                    </h5>
                    <p class="opportunity-description">${opportunity.content}</p>
                    ${opportunity.url ? 
                        `<a href="${opportunity.url}" class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                            ${getLearnMoreText()}
                        </a>` : 
                        ''
                    }
                </div>
            </div>
        `).join('');
    }
    
    function getTypeTranslation(type) {
        const translations = {
            'en': {
                'Research': 'Research',
                'Collaboration': 'Collaboration',
                'Funding': 'Funding',
                'Conference': 'Conference',
                'Publication': 'Publication'
            },
            'fr': {
                'Research': 'Recherche',
                'Collaboration': 'Collaboration',
                'Funding': 'Financement',
                'Conference': 'Conférence',
                'Publication': 'Publication'
            },
            'es': {
                'Research': 'Investigación',
                'Collaboration': 'Colaboración',
                'Funding': 'Financiación',
                'Conference': 'Conferencia',
                'Publication': 'Publicación'
            }
        };
        
        const currentLang = '<?= $currentLang ?>';
        return translations[currentLang] ? translations[currentLang][type] || type : type;
    }
    
    function getLearnMoreText() {
        const currentLang = '<?= $currentLang ?>';
        return currentLang === 'fr' ? 'En savoir plus' : 
               currentLang === 'es' ? 'Leer más' : 'Learn More';
    }
});
</script>

<?php
function getTypeColor($type) {
    $colors = [
        'Research' => 'type-research',
        'Collaboration' => 'type-collaboration',
        'Funding' => 'type-funding',
        'Conference' => 'type-conference',
        'Publication' => 'type-publication'
    ];
    return $colors[$type] ?? 'type-research';
}
?> 