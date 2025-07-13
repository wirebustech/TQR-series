<?php
session_start();

// Check if user is admin (simple check for demo)
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'user') !== 'admin') {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$pageTitle = 'Manage Pages - TQRS Admin';
$pageDescription = 'Admin center for creating and managing website pages with multi-language support.';

$lang = $_GET['lang'] ?? 'en';

// Load translations
require_once __DIR__ . '/../includes/translation.php';
$translations = getTranslations($lang);

$texts = [
    'page_title' => 'Manage Pages',
    'page_subtitle' => 'Create, edit, and manage website pages with multi-language support',
    'add_page' => 'Add New Page',
    'edit_page' => 'Edit Page',
    'delete_page' => 'Delete Page',
    'title' => 'Title',
    'slug' => 'Slug',
    'language' => 'Language',
    'description' => 'Description',
    'content' => 'Content',
    'meta_title' => 'Meta Title',
    'meta_description' => 'Meta Description',
    'meta_keywords' => 'Meta Keywords',
    'status' => 'Status',
    'actions' => 'Actions',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'view' => 'View',
    'published' => 'Published',
    'draft' => 'Draft',
    'publish' => 'Publish',
    'unpublish' => 'Unpublish',
    'no_pages' => 'No pages found',
    'page_added' => 'Page added successfully',
    'page_updated' => 'Page updated successfully',
    'page_deleted' => 'Page deleted successfully',
    'error_occurred' => 'An error occurred',
    'confirm_delete' => 'Are you sure you want to delete this page?',
    'loading' => 'Loading...',
    'search_pages' => 'Search pages...',
    'filter_by_language' => 'Filter by language',
    'filter_by_status' => 'Filter by status',
    'all_languages' => 'All Languages',
    'all_statuses' => 'All Statuses',
    'english' => 'English',
    'french' => 'Français',
    'spanish' => 'Español',
    'created_by' => 'Created By',
    'created_at' => 'Created',
    'updated_at' => 'Updated',
    'slug_help' => 'URL-friendly version of the title (auto-generated if empty)',
    'meta_help' => 'SEO metadata for search engines',
    'content_help' => 'Main page content (HTML allowed)',
    'preview' => 'Preview',
    'duplicate' => 'Duplicate',
    'bulk_actions' => 'Bulk Actions',
    'select_all' => 'Select All',
    'bulk_publish' => 'Publish Selected',
    'bulk_unpublish' => 'Unpublish Selected',
    'bulk_delete' => 'Delete Selected',
    'showing_results' => 'Showing {count} pages'
];

// API helper function
function makeApiRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'data' => json_decode($response, true),
        'http_code' => $httpCode
    ];
}

// Load pages from API
$pages = [];
$apiUrl = 'http://localhost:8000/api/pages';

try {
    $response = makeApiRequest($apiUrl);
    if ($response['success'] && isset($response['data'])) {
        $pages = $response['data'];
    }
} catch (Exception $e) {
    // Fallback to demo data if API is not available
    $pages = [
        [
            'id' => 1,
            'title' => 'About TQRS',
            'slug' => 'about-tqrs',
            'language' => 'en',
            'description' => 'Learn about The Qualitative Research Series',
            'content' => '<p>Welcome to TQRS...</p>',
            'is_published' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 2,
            'title' => 'À Propos de TQRS',
            'slug' => 'a-propos-tqrs',
            'language' => 'fr',
            'description' => 'Découvrez The Qualitative Research Series',
            'content' => '<p>Bienvenue à TQRS...</p>',
            'is_published' => false,
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ]
    ];
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="admin-sidebar">
                <h5 class="sidebar-title">
                    <i class="bi bi-gear"></i> Admin Panel
                </h5>
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link active" href="pages.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-file-earmark-text"></i> Pages
                    </a>
                    <a class="nav-link" href="opportunities.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-bullhorn"></i> Opportunities
                    </a>
                    <a class="nav-link" href="../index.php?lang=<?= urlencode($lang) ?>">
                        <i class="bi bi-arrow-left"></i> Back to Site
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10">
            <div class="admin-content">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1"><?= htmlspecialchars($texts['page_title']) ?></h1>
                        <p class="text-muted mb-0"><?= htmlspecialchars($texts['page_subtitle']) ?></p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pageModal" onclick="openPageModal()">
                        <i class="bi bi-plus-circle"></i> <?= htmlspecialchars($texts['add_page']) ?>
                    </button>
                </div>

                <!-- Filters and Search -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="<?= htmlspecialchars($texts['search_pages']) ?>">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="languageFilter">
                                    <option value=""><?= htmlspecialchars($texts['all_languages']) ?></option>
                                    <option value="en">🇺🇸 <?= htmlspecialchars($texts['english']) ?></option>
                                    <option value="fr">🇫🇷 <?= htmlspecialchars($texts['french']) ?></option>
                                    <option value="es">🇪🇸 <?= htmlspecialchars($texts['spanish']) ?></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value=""><?= htmlspecialchars($texts['all_statuses']) ?></option>
                                    <option value="published"><?= htmlspecialchars($texts['published']) ?></option>
                                    <option value="draft"><?= htmlspecialchars($texts['draft']) ?></option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="bi bi-arrow-clockwise"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="card mb-4" id="bulkActions" style="display: none;">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted" id="selectedCount">0 selected</span>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-success" onclick="bulkAction('publish')">
                                    <i class="bi bi-check-circle"></i> <?= htmlspecialchars($texts['bulk_publish']) ?>
                                </button>
                                <button class="btn btn-sm btn-warning" onclick="bulkAction('unpublish')">
                                    <i class="bi bi-x-circle"></i> <?= htmlspecialchars($texts['bulk_unpublish']) ?>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                                    <i class="bi bi-trash"></i> <?= htmlspecialchars($texts['bulk_delete']) ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pages List -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark-text"></i> Pages
                            </h5>
                            <small class="text-muted" id="resultsCount">
                                <?= str_replace('{count}', count($pages), $texts['showing_results']) ?>
                            </small>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($pages)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-file-earmark-text text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted"><?= htmlspecialchars($texts['no_pages']) ?></h5>
                                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#pageModal" onclick="openPageModal()">
                                    <i class="bi bi-plus-circle"></i> <?= htmlspecialchars($texts['add_page']) ?>
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="pagesTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40">
                                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                            </th>
                                            <th><?= htmlspecialchars($texts['title']) ?></th>
                                            <th><?= htmlspecialchars($texts['language']) ?></th>
                                            <th><?= htmlspecialchars($texts['status']) ?></th>
                                            <th><?= htmlspecialchars($texts['created_at']) ?></th>
                                            <th width="200"><?= htmlspecialchars($texts['actions']) ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pages as $page): ?>
                                            <tr data-page-id="<?= $page['id'] ?>" 
                                                data-language="<?= htmlspecialchars($page['language']) ?>" 
                                                data-status="<?= $page['is_published'] ? 'published' : 'draft' ?>">
                                                <td>
                                                    <input type="checkbox" class="page-checkbox" value="<?= $page['id'] ?>" onchange="updateBulkActions()">
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?= htmlspecialchars($page['title']) ?></strong>
                                                        <br>
                                                        <small class="text-muted">/<?= htmlspecialchars($page['slug']) ?></small>
                                                        <?php if (!empty($page['description'])): ?>
                                                            <br>
                                                            <small class="text-muted"><?= htmlspecialchars(truncateText($page['description'], 60)) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= getLanguageFlag($page['language']) ?> <?= getLanguageName($page['language']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($page['is_published']): ?>
                                                        <span class="badge bg-success"><?= htmlspecialchars($texts['published']) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($texts['draft']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= formatDate($page['created_at']) ?></small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" onclick="editPage(<?= $page['id'] ?>)" title="<?= htmlspecialchars($texts['edit']) ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success" onclick="viewPage('<?= htmlspecialchars($page['slug']) ?>', '<?= htmlspecialchars($page['language']) ?>')" title="<?= htmlspecialchars($texts['view']) ?>">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info" onclick="duplicatePage(<?= $page['id'] ?>)" title="<?= htmlspecialchars($texts['duplicate']) ?>">
                                                            <i class="bi bi-copy"></i>
                                                        </button>
                                                        <?php if ($page['is_published']): ?>
                                                            <button class="btn btn-outline-warning" onclick="togglePageStatus(<?= $page['id'] ?>, false)" title="<?= htmlspecialchars($texts['unpublish']) ?>">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-outline-success" onclick="togglePageStatus(<?= $page['id'] ?>, true)" title="<?= htmlspecialchars($texts['publish']) ?>">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button class="btn btn-outline-danger" onclick="deletePage(<?= $page['id'] ?>)" title="<?= htmlspecialchars($texts['delete']) ?>">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" aria-labelledby="pageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pageModalLabel"><?= htmlspecialchars($texts['add_page']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pageForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="pageTitle" class="form-label"><?= htmlspecialchars($texts['title']) ?> *</label>
                            <input type="text" class="form-control" id="pageTitle" name="title" required>
                        </div>
                        <div class="col-md-4">
                            <label for="pageLanguage" class="form-label"><?= htmlspecialchars($texts['language']) ?> *</label>
                            <select class="form-select" id="pageLanguage" name="language" required>
                                <option value="en">🇺🇸 English</option>
                                <option value="fr">🇫🇷 Français</option>
                                <option value="es">🇪🇸 Español</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="pageSlug" class="form-label"><?= htmlspecialchars($texts['slug']) ?></label>
                            <input type="text" class="form-control" id="pageSlug" name="slug">
                            <div class="form-text"><?= htmlspecialchars($texts['slug_help']) ?></div>
                        </div>
                        <div class="col-md-4">
                            <label for="pageStatus" class="form-label"><?= htmlspecialchars($texts['status']) ?></label>
                            <select class="form-select" id="pageStatus" name="is_published">
                                <option value="0"><?= htmlspecialchars($texts['draft']) ?></option>
                                <option value="1"><?= htmlspecialchars($texts['published']) ?></option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="pageDescription" class="form-label"><?= htmlspecialchars($texts['description']) ?></label>
                            <textarea class="form-control" id="pageDescription" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="pageContent" class="form-label"><?= htmlspecialchars($texts['content']) ?></label>
                            <textarea class="form-control" id="pageContent" name="content" rows="10"></textarea>
                            <div class="form-text"><?= htmlspecialchars($texts['content_help']) ?></div>
                        </div>
                        
                        <!-- SEO Section -->
                        <div class="col-12">
                            <h6 class="mt-3 mb-2">
                                <i class="bi bi-search"></i> SEO Metadata
                                <small class="text-muted">(<?= htmlspecialchars($texts['meta_help']) ?>)</small>
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="pageMetaTitle" class="form-label"><?= htmlspecialchars($texts['meta_title']) ?></label>
                            <input type="text" class="form-control" id="pageMetaTitle" name="meta_title">
                        </div>
                        <div class="col-md-6">
                            <label for="pageMetaKeywords" class="form-label"><?= htmlspecialchars($texts['meta_keywords']) ?></label>
                            <input type="text" class="form-control" id="pageMetaKeywords" name="meta_keywords">
                        </div>
                        <div class="col-12">
                            <label for="pageMetaDescription" class="form-label"><?= htmlspecialchars($texts['meta_description']) ?></label>
                            <textarea class="form-control" id="pageMetaDescription" name="meta_description" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?= htmlspecialchars($texts['cancel']) ?>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> <?= htmlspecialchars($texts['save']) ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.admin-sidebar {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    height: fit-content;
}

.admin-sidebar .sidebar-title {
    color: #495057;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.admin-sidebar .nav-link {
    color: #6c757d;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    margin-bottom: 0.25rem;
    transition: all 0.2s;
}

.admin-sidebar .nav-link:hover,
.admin-sidebar .nav-link.active {
    background: #0d6efd;
    color: white;
}

.admin-content {
    background: white;
    border-radius: 0.5rem;
    padding: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.modal-xl {
    max-width: 90%;
}

@media (max-width: 768px) {
    .admin-content {
        padding: 1rem;
    }
    
    .modal-xl {
        max-width: 95%;
    }
}
</style>

<script>
let currentPageId = null;
let allPages = <?= json_encode($pages) ?>;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    document.getElementById('pageTitle').addEventListener('input', function() {
        if (!document.getElementById('pageSlug').value) {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('pageSlug').value = slug;
        }
    });
    
    // Initialize filters
    document.getElementById('searchInput').addEventListener('input', filterPages);
    document.getElementById('languageFilter').addEventListener('change', filterPages);
    document.getElementById('statusFilter').addEventListener('change', filterPages);
});

// Open page modal for adding/editing
function openPageModal(pageId = null) {
    currentPageId = pageId;
    const modal = document.getElementById('pageModal');
    const form = document.getElementById('pageForm');
    const title = document.getElementById('pageModalLabel');
    
    form.reset();
    
    if (pageId) {
        // Edit mode
        title.textContent = '<?= htmlspecialchars($texts['edit_page']) ?>';
        const page = allPages.find(p => p.id == pageId);
        if (page) {
            document.getElementById('pageTitle').value = page.title || '';
            document.getElementById('pageSlug').value = page.slug || '';
            document.getElementById('pageLanguage').value = page.language || 'en';
            document.getElementById('pageDescription').value = page.description || '';
            document.getElementById('pageContent').value = page.content || '';
            document.getElementById('pageMetaTitle').value = page.meta_title || '';
            document.getElementById('pageMetaDescription').value = page.meta_description || '';
            document.getElementById('pageMetaKeywords').value = page.meta_keywords || '';
            document.getElementById('pageStatus').value = page.is_published ? '1' : '0';
        }
    } else {
        // Add mode
        title.textContent = '<?= htmlspecialchars($texts['add_page']) ?>';
        document.getElementById('pageLanguage').value = '<?= $lang ?>';
    }
}

// Handle form submission
document.getElementById('pageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.is_published = data.is_published === '1';
    
    try {
        const url = currentPageId 
            ? `http://localhost:8000/api/pages/${currentPageId}`
            : 'http://localhost:8000/api/pages';
        const method = currentPageId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showAlert(currentPageId ? '<?= htmlspecialchars($texts['page_updated']) ?>' : '<?= htmlspecialchars($texts['page_added']) ?>', 'success');
            bootstrap.Modal.getInstance(document.getElementById('pageModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Failed to save page');
        }
    } catch (error) {
        showAlert('<?= htmlspecialchars($texts['error_occurred']) ?>', 'danger');
    }
});

// Edit page
function editPage(pageId) {
    openPageModal(pageId);
    new bootstrap.Modal(document.getElementById('pageModal')).show();
}

// View page
function viewPage(slug, language) {
    const url = `../${slug}.php?lang=${language}`;
    window.open(url, '_blank');
}

// Duplicate page
async function duplicatePage(pageId) {
    const page = allPages.find(p => p.id == pageId);
    if (!page) return;
    
    const data = {
        title: page.title + ' (Copy)',
        slug: page.slug + '-copy',
        language: page.language,
        description: page.description,
        content: page.content,
        meta_title: page.meta_title,
        meta_description: page.meta_description,
        meta_keywords: page.meta_keywords,
        is_published: false
    };
    
    try {
        const response = await fetch('http://localhost:8000/api/pages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showAlert('Page duplicated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Failed to duplicate page');
        }
    } catch (error) {
        showAlert('<?= htmlspecialchars($texts['error_occurred']) ?>', 'danger');
    }
}

// Toggle page status
async function togglePageStatus(pageId, isPublished) {
    try {
        const response = await fetch(`http://localhost:8000/api/pages/${pageId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ is_published: isPublished })
        });
        
        if (response.ok) {
            showAlert('Page status updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Failed to update page status');
        }
    } catch (error) {
        showAlert('<?= htmlspecialchars($texts['error_occurred']) ?>', 'danger');
    }
}

// Delete page
async function deletePage(pageId) {
    if (!confirm('<?= htmlspecialchars($texts['confirm_delete']) ?>')) return;
    
    try {
        const response = await fetch(`http://localhost:8000/api/pages/${pageId}`, {
            method: 'DELETE'
        });
        
        if (response.ok) {
            showAlert('<?= htmlspecialchars($texts['page_deleted']) ?>', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Failed to delete page');
        }
    } catch (error) {
        showAlert('<?= htmlspecialchars($texts['error_occurred']) ?>', 'danger');
    }
}

// Filter pages
function filterPages() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const language = document.getElementById('languageFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#pagesTable tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const rowLanguage = row.dataset.language;
        const rowStatus = row.dataset.status;
        
        const matchesSearch = title.includes(search);
        const matchesLanguage = !language || rowLanguage === language;
        const matchesStatus = !status || rowStatus === status;
        
        if (matchesSearch && matchesLanguage && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('resultsCount').textContent = 
        '<?= str_replace('{count}', '', $texts['showing_results']) ?>' + visibleCount + ' pages';
}

// Clear filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('languageFilter').value = '';
    document.getElementById('statusFilter').value = '';
    filterPages();
}

// Bulk actions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.page-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.page-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = `${checkboxes.length} selected`;
    } else {
        bulkActions.style.display = 'none';
    }
}

async function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.page-checkbox:checked');
    const pageIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (pageIds.length === 0) return;
    
    if (action === 'delete' && !confirm(`Are you sure you want to delete ${pageIds.length} pages?`)) {
        return;
    }
    
    try {
        for (const pageId of pageIds) {
            if (action === 'delete') {
                await fetch(`http://localhost:8000/api/pages/${pageId}`, { method: 'DELETE' });
            } else {
                const isPublished = action === 'publish';
                await fetch(`http://localhost:8000/api/pages/${pageId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ is_published: isPublished })
                });
            }
        }
        
        showAlert(`Bulk ${action} completed successfully`, 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        showAlert('<?= htmlspecialchars($texts['error_occurred']) ?>', 'danger');
    }
}

// Utility functions
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

function truncateText(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getLanguageName(code) {
    const names = {
        'en': 'English',
        'fr': 'Français',
        'es': 'Español'
    };
    return names[code] || code;
}

function getLanguageFlag(code) {
    const flags = {
        'en': '🇺🇸',
        'fr': '🇫🇷',
        'es': '🇪🇸'
    };
    return flags[code] || '🌐';
}
</script>

<?php
// Helper functions
function truncateText($text, $maxLength) {
    return strlen($text) > $maxLength ? substr($text, 0, $maxLength) . '...' : $text;
}

function formatDate($dateString) {
    return date('M j, Y', strtotime($dateString));
}

function getLanguageName($code) {
    $names = [
        'en' => 'English',
        'fr' => 'Français',
        'es' => 'Español'
    ];
    return $names[$code] ?? $code;
}

function getLanguageFlag($code) {
    $flags = [
        'en' => '🇺🇸',
        'fr' => '🇫🇷',
        'es' => '🇪🇸'
    ];
    return $flags[$code] ?? '🌐';
}

include '../includes/footer.php';
?> 