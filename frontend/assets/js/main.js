/**
 * TQRS Main JavaScript File
 * Common functionality for the frontend
 */

// Import API client
// Note: Make sure api.js is loaded before main.js in your HTML

// Global variables
let currentLang = 'en';
let isOnline = navigator.onLine;
let deferredPrompt = null;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

/**
 * Initialize the application
 */
function initializeApp() {
    // Get current language from URL or localStorage
    currentLang = getCurrentLanguage();
    
    // Initialize components
    initializeSearch();
    initializeNotifications();
    initializePWA();
    initializeOfflineDetection();
    initializeAnimations();
    initializeDashboard();
    
    // Set up event listeners
    setupEventListeners();
}

/**
 * Get current language from URL parameters or localStorage
 */
function getCurrentLanguage() {
    const urlParams = new URLSearchParams(window.location.search);
    const langFromUrl = urlParams.get('lang');
    
    if (langFromUrl) {
        localStorage.setItem('tqrs_language', langFromUrl);
        return langFromUrl;
    }
    
    return localStorage.getItem('tqrs_language') || 'en';
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.getElementById('searchInput');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                performSearch(query);
            }
        });
        
        // Add search suggestions
        searchInput.addEventListener('input', debounce(function() {
            const query = this.value.trim();
            if (query.length > 2) {
                showSearchSuggestions(query);
            } else {
                hideSearchSuggestions();
            }
        }, 300));
    }
}

/**
 * Perform search using API
 */
async function performSearch(query) {
    try {
        // Track search query
        await api.trackSearch(query);
        
        // Redirect to search results page
        const searchUrl = `search.php?q=${encodeURIComponent(query)}&lang=${currentLang}`;
        window.location.href = searchUrl;
    } catch (error) {
        console.error('Search error:', error);
        // Fallback to direct search
        const searchUrl = `search.php?q=${encodeURIComponent(query)}&lang=${currentLang}`;
        window.location.href = searchUrl;
    }
}

/**
 * Show search suggestions using API
 */
async function showSearchSuggestions(query) {
    // Remove existing suggestions
    hideSearchSuggestions();
    
    try {
        // Get search filters which include suggestions
        const response = await api.getSearchFilters();
        const suggestions = response.data.popular_tags || [];
        
        // Filter suggestions based on query
        const filteredSuggestions = suggestions.filter(s => 
            s.toLowerCase().includes(query.toLowerCase())
        ).slice(0, 5);
        
        if (filteredSuggestions.length > 0) {
            createSuggestionsContainer(filteredSuggestions);
        }
    } catch (error) {
        console.error('Error fetching suggestions:', error);
        // Fallback to static suggestions
        const fallbackSuggestions = [
            'Qualitative research methods',
            'Grounded theory',
            'Phenomenology',
            'Case study research',
            'Interview techniques'
        ].filter(s => s.toLowerCase().includes(query.toLowerCase()));
        
        if (fallbackSuggestions.length > 0) {
            createSuggestionsContainer(fallbackSuggestions);
        }
    }
}

/**
 * Create suggestions container
 */
function createSuggestionsContainer(suggestions) {
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'search-suggestions';
    suggestionsContainer.style.cssText = `
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 0.375rem 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
    `;
    
    suggestions.forEach(suggestion => {
        const suggestionItem = document.createElement('div');
        suggestionItem.className = 'suggestion-item p-2 border-bottom';
        suggestionItem.style.cursor = 'pointer';
        suggestionItem.textContent = suggestion;
        
        suggestionItem.addEventListener('click', function() {
            document.getElementById('searchInput').value = suggestion;
            performSearch(suggestion);
        });
        
        suggestionItem.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        
        suggestionItem.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'white';
        });
        
        suggestionsContainer.appendChild(suggestionItem);
    });
    
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.style.position = 'relative';
        searchForm.appendChild(suggestionsContainer);
    }
}

/**
 * Hide search suggestions
 */
function hideSearchSuggestions() {
    const existingSuggestions = document.querySelector('.search-suggestions');
    if (existingSuggestions) {
        existingSuggestions.remove();
    }
}

/**
 * Initialize dashboard functionality
 */
function initializeDashboard() {
    // Only initialize if we're on a dashboard page
    if (document.querySelector('.dashboard-container')) {
        loadDashboardData();
    }
}

/**
 * Load dashboard data from API
 */
async function loadDashboardData() {
    try {
        const response = await api.getDashboard();
        if (response.success) {
            updateDashboardUI(response.data);
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);
        api.handleError(error);
    }
}

/**
 * Update dashboard UI with data
 */
function updateDashboardUI(data) {
    // Update user stats
    if (data.stats) {
        updateUserStats(data.stats);
    }
    
    // Update recent activity
    if (data.recent_activity) {
        updateRecentActivity(data.recent_activity);
    }
    
    // Update upcoming webinars
    if (data.upcoming_webinars) {
        updateUpcomingWebinars(data.upcoming_webinars);
    }
    
    // Update recommendations
    if (data.recommendations) {
        updateRecommendations(data.recommendations);
    }
    
    // Update notifications
    if (data.notifications) {
        updateNotifications(data.notifications);
    }
}

/**
 * Update user statistics
 */
function updateUserStats(stats) {
    const statsContainer = document.querySelector('.user-stats');
    if (!statsContainer) return;
    
    statsContainer.innerHTML = `
        <div class="row text-center">
            <div class="col-4">
                <div class="bg-primary bg-opacity-10 rounded p-2">
                    <h6 class="text-primary mb-0">${stats.webinars_watched || 0}</h6>
                    <small class="text-muted">Webinars</small>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-success bg-opacity-10 rounded p-2">
                    <h6 class="text-success mb-0">${stats.blogs_read || 0}</h6>
                    <small class="text-muted">Articles</small>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-warning bg-opacity-10 rounded p-2">
                    <h6 class="text-warning mb-0">${stats.contributions_made || 0}</h6>
                    <small class="text-muted">Contributions</small>
                </div>
            </div>
        </div>
    `;
}

/**
 * Update recent activity
 */
function updateRecentActivity(activities) {
    const activityContainer = document.querySelector('.recent-activity');
    if (!activityContainer) return;
    
    const activityHTML = activities.map(activity => `
        <div class="timeline-item">
            <div class="timeline-marker bg-primary"></div>
            <div class="timeline-content">
                <h6 class="mb-1">${activity.title}</h6>
                <p class="text-muted mb-1">${activity.description || ''}</p>
                <small class="text-muted">${formatDate(activity.date)}</small>
            </div>
        </div>
    `).join('');
    
    activityContainer.innerHTML = activityHTML;
}

/**
 * Update upcoming webinars
 */
function updateUpcomingWebinars(webinars) {
    const webinarsContainer = document.querySelector('.upcoming-webinars');
    if (!webinarsContainer) return;
    
    const webinarsHTML = webinars.map(webinar => `
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">${webinar.title}</h6>
                <p class="card-text small text-muted">${webinar.description}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">${formatDate(webinar.scheduled_at)}</small>
                    <a href="${webinar.registration_url}" class="btn btn-sm btn-primary">
                        ${webinar.is_registered ? 'View' : 'Register'}
                    </a>
                </div>
            </div>
        </div>
    `).join('');
    
    webinarsContainer.innerHTML = webinarsHTML;
}

/**
 * Update recommendations
 */
function updateRecommendations(recommendations) {
    const recommendationsContainer = document.querySelector('.recommendations');
    if (!recommendationsContainer) return;
    
    const recommendationsHTML = recommendations.map(rec => `
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">${rec.title}</h6>
                <p class="card-text small">${rec.description}</p>
                <small class="text-muted">${rec.reason}</small>
                <a href="${rec.url}" class="btn btn-sm btn-outline-primary mt-2">View</a>
            </div>
        </div>
    `).join('');
    
    recommendationsContainer.innerHTML = recommendationsHTML;
}

/**
 * Update notifications
 */
function updateNotifications(notifications) {
    const notificationsContainer = document.querySelector('.notifications');
    if (!notificationsContainer) return;
    
    const notificationsHTML = notifications.map(notification => `
        <div class="alert alert-info alert-dismissible fade show">
            <strong>${notification.title}</strong>
            <p class="mb-0">${notification.message}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `).join('');
    
    notificationsContainer.innerHTML = notificationsHTML;
}

/**
 * Initialize notifications
 */
function initializeNotifications() {
    // Request notification permission
    if ('Notification' in window) {
        Notification.requestPermission();
    }
    
    // Set up notification handlers
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.addEventListener('message', function(event) {
            if (event.data.type === 'notification') {
                showNotification(event.data.title, event.data.body);
            }
        });
    }
}

/**
 * Show notification
 */
function showNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, {
            body: body,
            icon: '/assets/images/logo.png',
            badge: '/assets/images/badge.png'
        });
    } else {
        // Fallback to toast notification
        showToast(title, body);
    }
}

/**
 * Show toast notification
 */
function showToast(title, message) {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    // Create new toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

/**
 * Initialize PWA functionality
 */
function initializePWA() {
    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        showInstallPrompt();
    });
    
    // Listen for app installed event
    window.addEventListener('appinstalled', () => {
        hideInstallPrompt();
        console.log('PWA was installed');
    });
}

/**
 * Show install prompt
 */
function showInstallPrompt() {
    const installPrompt = document.getElementById('installPrompt');
    if (installPrompt) {
        installPrompt.style.display = 'block';
    }
}

/**
 * Hide install prompt
 */
function hideInstallPrompt() {
    const installPrompt = document.getElementById('installPrompt');
    if (installPrompt) {
        installPrompt.style.display = 'none';
    }
}

/**
 * Install PWA
 */
function installPWA() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
            } else {
                console.log('User dismissed the install prompt');
            }
            deferredPrompt = null;
        });
    }
}

/**
 * Initialize offline detection
 */
function initializeOfflineDetection() {
    window.addEventListener('online', () => {
        isOnline = true;
        showToast('Connection Restored', 'You are back online!');
    });
    
    window.addEventListener('offline', () => {
        isOnline = false;
        showToast('Connection Lost', 'You are currently offline. Some features may not work.');
    });
}

/**
 * Initialize animations
 */
function initializeAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe elements with animation classes
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

/**
 * Set up event listeners
 */
function setupEventListeners() {
    // Language switcher
    const langSwitcher = document.querySelector('.lang-switcher select');
    if (langSwitcher) {
        langSwitcher.addEventListener('change', function() {
            const newLang = this.value;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('lang', newLang);
            window.location.href = currentUrl.toString();
        });
    }
    
    // Install PWA button
    const installBtn = document.getElementById('installPWA');
    if (installBtn) {
        installBtn.addEventListener('click', installPWA);
    }
    
    // Newsletter subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            subscribeToNewsletter(email);
        });
    }
    
    // Password toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
}

/**
 * Subscribe to newsletter using API
 */
async function subscribeToNewsletter(email) {
    try {
        const response = await api.subscribeNewsletter(email);
        if (response.success) {
            showToast('Success', 'Thank you for subscribing to our newsletter!');
        } else {
            showToast('Error', response.message || 'Failed to subscribe');
        }
    } catch (error) {
        console.error('Newsletter subscription error:', error);
        showToast('Error', 'Failed to subscribe. Please try again.');
    }
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) {
        return 'Today';
    } else if (diffDays === 2) {
        return 'Yesterday';
    } else if (diffDays <= 7) {
        return `${diffDays - 1} days ago`;
    } else {
        return date.toLocaleDateString();
    }
}

/**
 * Format time for display
 */
function formatTime(timeString) {
    const date = new Date(timeString);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

/**
 * Truncate text to specified length
 */
function truncateText(text, maxLength) {
    if (text.length <= maxLength) {
        return text;
    }
    return text.substring(0, maxLength) + '...';
}

/**
 * Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }
    
    .animate-on-scroll.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .offline {
        filter: grayscale(50%);
    }
    
    .offline::before {
        content: "You are offline";
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #dc3545;
        color: white;
        text-align: center;
        padding: 0.5rem;
        z-index: 9999;
    }
`;
document.head.appendChild(style);

// Export functions for use in other scripts
window.TQRS = {
    showNotification,
    showToast,
    formatDate,
    formatTime,
    truncateText,
    debounce,
    getCurrentLanguage: () => currentLang,
    isOnline: () => isOnline
}; 