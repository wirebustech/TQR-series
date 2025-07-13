/**
 * TQRS Main JavaScript File
 * Common functionality for the frontend
 */

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
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length > 2) {
                showSearchSuggestions(query);
            } else {
                hideSearchSuggestions();
            }
        });
    }
}

/**
 * Perform search
 */
function performSearch(query) {
    const searchUrl = `search.php?q=${encodeURIComponent(query)}&lang=${currentLang}`;
    window.location.href = searchUrl;
}

/**
 * Show search suggestions
 */
function showSearchSuggestions(query) {
    // Remove existing suggestions
    hideSearchSuggestions();
    
    // Create suggestions container
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
    
    // Mock suggestions - in real app, this would come from API
    const suggestions = [
        'Qualitative research methods',
        'Grounded theory',
        'Phenomenology',
        'Case study research',
        'Interview techniques',
        'Data analysis software'
    ].filter(s => s.toLowerCase().includes(query.toLowerCase()));
    
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
    
    // Create toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 1rem;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 9999;
        max-width: 300px;
        animation: slideIn 0.3s ease-out;
    `;
    
    toast.innerHTML = `
        <h6 class="mb-1">${title}</h6>
        <p class="mb-0 small">${message}</p>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

/**
 * Initialize PWA features
 */
function initializePWA() {
    // Handle beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        showInstallPrompt();
    });
    
    // Handle app installed event
    window.addEventListener('appinstalled', (evt) => {
        console.log('App was installed');
        hideInstallPrompt();
    });
}

/**
 * Show PWA install prompt
 */
function showInstallPrompt() {
    const prompt = document.getElementById('pwaInstallPrompt');
    if (prompt) {
        prompt.style.display = 'block';
    }
}

/**
 * Hide PWA install prompt
 */
function hideInstallPrompt() {
    const prompt = document.getElementById('pwaInstallPrompt');
    if (prompt) {
        prompt.style.display = 'none';
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
            hideInstallPrompt();
        });
    }
}

/**
 * Initialize offline detection
 */
function initializeOfflineDetection() {
    window.addEventListener('online', function() {
        isOnline = true;
        showNotification('Connection Restored', 'You are back online!');
        document.body.classList.remove('offline');
    });
    
    window.addEventListener('offline', function() {
        isOnline = false;
        showNotification('Connection Lost', 'You are currently offline. Some features may be limited.');
        document.body.classList.add('offline');
    });
}

/**
 * Initialize animations
 */
function initializeAnimations() {
    // Intersection Observer for scroll animations
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1
        });
        
        // Observe elements with animation classes
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }
}

/**
 * Set up event listeners
 */
function setupEventListeners() {
    // Language switcher
    const langSelect = document.querySelector('select[name="lang"]');
    if (langSelect) {
        langSelect.addEventListener('change', function() {
            const newLang = this.value;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('lang', newLang);
            window.location.href = currentUrl.toString();
        });
    }
    
    // Back to top button
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Newsletter signup
    const newsletterForm = document.querySelector('form[action*="newsletter"]');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                subscribeToNewsletter(email);
            }
        });
    }
}

/**
 * Subscribe to newsletter
 */
function subscribeToNewsletter(email) {
    // In a real app, this would make an API call
    fetch('/api/newsletter/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Success', 'You have been subscribed to our newsletter!');
        } else {
            showToast('Error', 'Failed to subscribe. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'Failed to subscribe. Please try again.');
    });
}

/**
 * Utility function to format dates
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString(currentLang, {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

/**
 * Utility function to format time
 */
function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const date = new Date();
    date.setHours(hours, minutes);
    return date.toLocaleTimeString(currentLang, {
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Utility function to truncate text
 */
function truncateText(text, maxLength) {
    if (text.length <= maxLength) {
        return text;
    }
    return text.substring(0, maxLength) + '...';
}

/**
 * Utility function to debounce
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