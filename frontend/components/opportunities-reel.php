<?php
/**
 * Opportunities News Reel Component
 * Displays latest research opportunities and collaborations
 */

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

$texts = [
    'opportunities_title' => 'Latest Opportunities',
    'view_all' => 'View All',
    'no_opportunities' => 'No opportunities available at the moment.',
    'check_back' => 'Check back soon for new opportunities!'
];

if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        $texts[$k] = translateText($v, $lang, 'en');
    }
}
?>

<!-- Opportunities News Reel Section -->
<section class="opportunities-reel bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-lightning-charge me-2"></i>
                    <?= htmlspecialchars($texts['opportunities_title']) ?>
                </h5>
            </div>
            <div class="col-md-9">
                <div class="opportunities-ticker">
                    <?php if (empty($opportunities)): ?>
                        <div class="text-muted">
                            <i class="bi bi-info-circle me-2"></i>
                            <?= htmlspecialchars($texts['no_opportunities']) ?>
                            <?= htmlspecialchars($texts['check_back']) ?>
                        </div>
                    <?php else: ?>
                        <div class="ticker-container">
                            <div class="ticker-track">
                                <?php foreach ($opportunities as $opportunity): ?>
                                    <div class="ticker-item">
                                        <span class="badge bg-<?= getTypeColor($opportunity['type']) ?> me-2">
                                            <?= htmlspecialchars($opportunity['type']) ?>
                                        </span>
                                        <span class="opportunity-title">
                                            <?= htmlspecialchars($opportunity['title']) ?>
                                        </span>
                                        <?php if ($opportunity['url']): ?>
                                            <a href="<?= htmlspecialchars($opportunity['url']) ?>" 
                                               class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.opportunities-reel {
    border-bottom: 1px solid #e9ecef;
}

.ticker-container {
    overflow: hidden;
    position: relative;
    height: 40px;
}

.ticker-track {
    display: flex;
    animation: ticker 30s linear infinite;
    white-space: nowrap;
}

.ticker-item {
    display: flex;
    align-items: center;
    padding: 0 20px;
    min-width: 300px;
    flex-shrink: 0;
}

@keyframes ticker {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

.opportunity-title {
    font-weight: 500;
    color: #333;
}

@media (max-width: 768px) {
    .ticker-container {
        height: auto;
    }
    
    .ticker-track {
        animation: none;
        flex-direction: column;
        gap: 10px;
    }
    
    .ticker-item {
        min-width: auto;
        padding: 5px 0;
    }
}
</style>

<script>
// Auto-refresh opportunities every 30 seconds
setInterval(function() {
    fetchOpportunities();
}, 30000);

function fetchOpportunities() {
    fetch('http://localhost:8000/api/opportunities/latest')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                updateOpportunitiesDisplay(data.data);
            }
        })
        .catch(error => {
            console.log('Failed to fetch opportunities:', error);
        });
}

function updateOpportunitiesDisplay(opportunities) {
    const tickerTrack = document.querySelector('.ticker-track');
    if (!tickerTrack) return;
    
    tickerTrack.innerHTML = opportunities.map(opportunity => `
        <div class="ticker-item">
            <span class="badge bg-${getTypeColor(opportunity.type)} me-2">
                ${opportunity.type}
            </span>
            <span class="opportunity-title">
                ${opportunity.title}
            </span>
            ${opportunity.url ? `
                <a href="${opportunity.url}" class="btn btn-sm btn-outline-primary ms-2">
                    <i class="bi bi-arrow-right"></i>
                </a>
            ` : ''}
        </div>
    `).join('');
}

function getTypeColor(type) {
    const colors = {
        'Research': 'primary',
        'Collaboration': 'success',
        'Funding': 'warning',
        'Conference': 'info',
        'Publication': 'secondary'
    };
    return colors[type] || 'primary';
}
</script>

<?php
function getTypeColor($type) {
    $colors = [
        'Research' => 'primary',
        'Collaboration' => 'success',
        'Funding' => 'warning',
        'Conference' => 'info',
        'Publication' => 'secondary'
    ];
    return $colors[$type] ?? 'primary';
}
?> 