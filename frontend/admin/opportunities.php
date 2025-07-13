<?php
session_start();

// Check if user is admin (simple check for demo)
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'user') !== 'admin') {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$pageTitle = 'Manage Opportunities - TQRS Admin';
$pageDescription = 'Admin center for managing research opportunities and collaborations.';

$lang = $_GET['lang'] ?? 'en';

// Load translations
require_once __DIR__ . '/../includes/translation.php';
$translations = getTranslations($lang);

$texts = [
    'page_title' => 'Manage Opportunities',
    'page_subtitle' => 'Add, edit, and manage research opportunities and collaborations',
    'add_opportunity' => 'Add New Opportunity',
    'edit_opportunity' => 'Edit Opportunity',
    'delete_opportunity' => 'Delete Opportunity',
    'title' => 'Title',
    'content' => 'Content',
    'url' => 'URL (Optional)',
    'type' => 'Type',
    'status' => 'Status',
    'actions' => 'Actions',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'no_opportunities' => 'No opportunities found',
    'opportunity_added' => 'Opportunity added successfully',
    'opportunity_updated' => 'Opportunity updated successfully',
    'opportunity_deleted' => 'Opportunity deleted successfully',
    'error_occurred' => 'An error occurred',
    'confirm_delete' => 'Are you sure you want to delete this opportunity?',
    'loading' => 'Loading...',
    'search_opportunities' => 'Search opportunities...',
    'filter_by_type' => 'Filter by type',
    'all_types' => 'All Types',
    'research' => 'Research',
    'collaboration' => 'Collaboration',
    'funding' => 'Funding',
    'conference' => 'Conference',
    'publication' => 'Publication'
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
        $apiUrl = 'http://localhost:8000/api/opportunities';
        
        switch ($_POST['action']) {
            case 'create':
                $data = [
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    'url' => $_POST['url'],
                    'type' => $_POST['type'],
                    'is_active' => isset($_POST['is_active'])
                ];
                
                $response = makeApiRequest($apiUrl, 'POST', $data);
                if ($response && $response['success']) {
                    $message = $texts['opportunity_added'];
                    $messageType = 'success';
                } else {
                    $message = $texts['error_occurred'];
                    $messageType = 'error';
                }
                break;
                
            case 'update':
                $id = $_POST['id'];
                $data = [
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    'url' => $_POST['url'],
                    'type' => $_POST['type'],
                    'is_active' => isset($_POST['is_active'])
                ];
                
                $response = makeApiRequest($apiUrl . '/' . $id, 'PUT', $data);
                if ($response && $response['success']) {
                    $message = $texts['opportunity_updated'];
                    $messageType = 'success';
                } else {
                    $message = $texts['error_occurred'];
                    $messageType = 'error';
                }
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $response = makeApiRequest($apiUrl . '/' . $id, 'DELETE');
                if ($response && $response['success']) {
                    $message = $texts['opportunity_deleted'];
                    $messageType = 'success';
                } else {
                    $message = $texts['error_occurred'];
                    $messageType = 'error';
                }
                break;
        }
    }
}

// Load opportunities
$opportunities = [];
$apiUrl = 'http://localhost:8000/api/opportunities';

try {
    $response = makeApiRequest($apiUrl, 'GET');
    if ($response && $response['success']) {
        $opportunities = $response['data'];
    }
} catch (Exception $e) {
    // Fallback to mock data
    $opportunities = [
        [
            'id' => 1,
            'title' => 'Research Collaboration Opportunity',
            'content' => 'Join our international research team studying qualitative methodologies in healthcare.',
            'type' => 'Collaboration',
            'url' => 'https://example.com/collaboration',
            'is_active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 2,
            'title' => 'Funding Available for Qualitative Research',
            'content' => 'Apply for our research grant program supporting innovative qualitative research projects.',
            'type' => 'Funding',
            'url' => 'https://example.com/funding',
            'is_active' => true,
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ]
    ];
}

function makeApiRequest($url, $method = 'GET', $data = null) {
    $context = stream_context_create([
        'http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json',
            'content' => $data ? json_encode($data) : null,
            'timeout' => 10
        ]
    ]);
    
    $response = file_get_contents($url, false, $context);
    return $response ? json_decode($response, true) : null;
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .opportunity-card {
            transition: transform 0.2s;
        }
        
        .opportunity-card:hover {
            transform: translateY(-2px);
        }
        
        .type-badge {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-gear me-2"></i>
                        <?= htmlspecialchars($texts['page_title']) ?>
                    </h1>
                    <p class="mb-0 opacity-75"><?= htmlspecialchars($texts['page_subtitle']) ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="../index.php" class="btn btn-outline-light me-2">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                    <button class="btn btn-light" onclick="showAddModal()">
                        <i class="bi bi-plus-circle me-1"></i><?= htmlspecialchars($texts['add_opportunity']) ?>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="py-4">
        <div class="container">
            <!-- Message Display -->
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="searchInput" 
                           placeholder="<?= htmlspecialchars($texts['search_opportunities']) ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="typeFilter">
                        <option value=""><?= htmlspecialchars($texts['all_types']) ?></option>
                        <option value="Research"><?= htmlspecialchars($texts['research']) ?></option>
                        <option value="Collaboration"><?= htmlspecialchars($texts['collaboration']) ?></option>
                        <option value="Funding"><?= htmlspecialchars($texts['funding']) ?></option>
                        <option value="Conference"><?= htmlspecialchars($texts['conference']) ?></option>
                        <option value="Publication"><?= htmlspecialchars($texts['publication']) ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                        <i class="bi bi-x-circle me-1"></i>Clear Filters
                    </button>
                </div>
            </div>

            <!-- Opportunities List -->
            <div class="row" id="opportunitiesList">
                <?php if (empty($opportunities)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                        <h3 class="text-muted"><?= htmlspecialchars($texts['no_opportunities']) ?></h3>
                        <p class="text-muted">Start by adding your first opportunity.</p>
                        <button class="btn btn-primary" onclick="showAddModal()">
                            <i class="bi bi-plus-circle me-1"></i><?= htmlspecialchars($texts['add_opportunity']) ?>
                        </button>
                    </div>
                <?php else: ?>
                    <?php foreach ($opportunities as $opportunity): ?>
                        <div class="col-lg-6 col-xl-4 mb-4 opportunity-item" 
                             data-type="<?= htmlspecialchars($opportunity['type']) ?>"
                             data-title="<?= htmlspecialchars(strtolower($opportunity['title'])) ?>">
                            <div class="card opportunity-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-<?= getTypeColor($opportunity['type']) ?> type-badge">
                                            <?= htmlspecialchars($opportunity['type']) ?>
                                        </span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   <?= $opportunity['is_active'] ? 'checked' : '' ?>
                                                   onchange="toggleStatus(<?= $opportunity['id'] ?>, this.checked)">
                                        </div>
                                    </div>
                                    
                                    <h5 class="card-title"><?= htmlspecialchars($opportunity['title']) ?></h5>
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars(truncateText($opportunity['content'] ?? '', 100)) ?>
                                    </p>
                                    
                                    <?php if ($opportunity['url']): ?>
                                        <p class="small text-muted mb-3">
                                            <i class="bi bi-link-45deg me-1"></i>
                                            <a href="<?= htmlspecialchars($opportunity['url']) ?>" target="_blank">
                                                <?= htmlspecialchars($opportunity['url']) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <?= formatDate($opportunity['created_at']) ?>
                                        </small>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="editOpportunity(<?= htmlspecialchars(json_encode($opportunity)) ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteOpportunity(<?= $opportunity['id'] ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="opportunityModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><?= htmlspecialchars($texts['add_opportunity']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="opportunityForm" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="create">
                        <input type="hidden" name="id" id="opportunityId">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label"><?= htmlspecialchars($texts['title']) ?> *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label"><?= htmlspecialchars($texts['content']) ?></label>
                            <textarea class="form-control" id="content" name="content" rows="4"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="url" class="form-label"><?= htmlspecialchars($texts['url']) ?></label>
                            <input type="url" class="form-control" id="url" name="url">
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label"><?= htmlspecialchars($texts['type']) ?> *</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="Research"><?= htmlspecialchars($texts['research']) ?></option>
                                <option value="Collaboration"><?= htmlspecialchars($texts['collaboration']) ?></option>
                                <option value="Funding"><?= htmlspecialchars($texts['funding']) ?></option>
                                <option value="Conference"><?= htmlspecialchars($texts['conference']) ?></option>
                                <option value="Publication"><?= htmlspecialchars($texts['publication']) ?></option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    <?= htmlspecialchars($texts['active']) ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= htmlspecialchars($texts['cancel']) ?>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <?= htmlspecialchars($texts['save']) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= htmlspecialchars($texts['delete_opportunity']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><?= htmlspecialchars($texts['confirm_delete']) ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?= htmlspecialchars($texts['cancel']) ?>
                    </button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="btn btn-danger">
                            <?= htmlspecialchars($texts['delete']) ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search and filter functionality
        document.getElementById('searchInput').addEventListener('input', filterOpportunities);
        document.getElementById('typeFilter').addEventListener('change', filterOpportunities);
        
        function filterOpportunities() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value;
            const items = document.querySelectorAll('.opportunity-item');
            
            items.forEach(item => {
                const title = item.dataset.title;
                const type = item.dataset.type;
                const matchesSearch = title.includes(searchTerm);
                const matchesType = !typeFilter || type === typeFilter;
                
                item.style.display = matchesSearch && matchesType ? 'block' : 'none';
            });
        }
        
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('typeFilter').value = '';
            filterOpportunities();
        }
        
        // Modal functions
        function showAddModal() {
            document.getElementById('modalTitle').textContent = '<?= htmlspecialchars($texts['add_opportunity']) ?>';
            document.getElementById('formAction').value = 'create';
            document.getElementById('opportunityForm').reset();
            document.getElementById('opportunityId').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('opportunityModal'));
            modal.show();
        }
        
        function editOpportunity(opportunity) {
            document.getElementById('modalTitle').textContent = '<?= htmlspecialchars($texts['edit_opportunity']) ?>';
            document.getElementById('formAction').value = 'update';
            document.getElementById('opportunityId').value = opportunity.id;
            document.getElementById('title').value = opportunity.title;
            document.getElementById('content').value = opportunity.content || '';
            document.getElementById('url').value = opportunity.url || '';
            document.getElementById('type').value = opportunity.type;
            document.getElementById('is_active').checked = opportunity.is_active;
            
            const modal = new bootstrap.Modal(document.getElementById('opportunityModal'));
            modal.show();
        }
        
        function deleteOpportunity(id) {
            document.getElementById('deleteId').value = id;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        
        function toggleStatus(id, isActive) {
            // Implement status toggle via API
            console.log('Toggle status for opportunity', id, 'to', isActive);
        }
        
        function truncateText(text, maxLength) {
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString();
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
    
    function truncateText($text, $maxLength) {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        return substr($text, 0, $maxLength) . '...';
    }
    
    function formatDate($dateString) {
        $date = new DateTime($dateString);
        return $date->format('M j, Y');
    }
    ?>
</body>
</html> 