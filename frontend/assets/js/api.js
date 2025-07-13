/**
 * TQRS API Integration
 * Centralized API client for frontend-backend communication
 */

class TQRSAPI {
    constructor() {
        this.baseUrl = 'http://localhost:8000/api';
        this.token = localStorage.getItem('tqrs_token');
        this.user = JSON.parse(localStorage.getItem('tqrs_user') || 'null');
        
        // API endpoints
        this.endpoints = {
            // Authentication
            login: '/login',
            register: '/register',
            logout: '/logout',
            user: '/user',
            
            // Search
            search: '/search',
            searchFilters: '/search/filters',
            searchTrack: '/search/track',
            searchAnalytics: '/search/analytics',
            
            // Dashboard
            dashboard: '/dashboard',
            dashboardLastSeen: '/dashboard/update-last-seen',
            dashboardLearningPath: '/dashboard/learning-path',
            
            // Webinars
            webinars: '/webinars',
            webinar: (id) => `/webinars/${id}`,
            webinarStats: '/webinars/stats',
            webinarRegistrations: (id) => `/webinars/${id}/registrations`,
            
            // Blogs
            blogs: '/blogs',
            blog: (id) => `/blogs/${id}`,
            
            // Users
            users: '/users',
            user: (id) => `/users/${id}`,
            userStats: '/users/stats',
            userProfile: '/profile',
            userUpdateProfile: '/profile',
            
            // Content Management
            contentOverview: '/content-management/overview',
            contentList: '/content-management/content',
            contentCreate: '/content-management/content',
            contentUpdate: (id) => `/content-management/content/${id}`,
            contentDelete: (id) => `/content-management/content/${id}`,
            contentBulkAction: '/content-management/bulk-action',
            contentAnalytics: '/content-management/analytics',
            mediaLibrary: '/content-management/media-library',
            uploadMedia: '/content-management/upload-media',
            
            // Analytics
            analytics: {
                userGrowth: '/analytics/user-growth',
                userDistribution: '/analytics/user-distribution',
                webinarPerformance: '/analytics/webinar-performance',
                contributionStatus: '/analytics/contribution-status',
                recentActivity: '/analytics/recent-activity',
                topContent: '/analytics/top-content',
                overview: '/analytics/overview',
                exportReport: '/analytics/export-report'
            },
            
            // Advanced Analytics
            advancedAnalytics: {
                overview: '/advanced-analytics/overview',
                users: '/advanced-analytics/users',
                content: '/advanced-analytics/content',
                financial: '/advanced-analytics/financial',
                realTime: '/advanced-analytics/real-time',
                export: '/advanced-analytics/export'
            },
            
            // Payments
            payments: {
                webinar: '/payments/webinar',
                donation: '/payments/donation',
                subscription: '/payments/subscription',
                confirm: '/payments/confirm',
                history: '/payments/history',
                methods: '/payments/methods',
                addMethod: '/payments/methods',
                removeMethod: (id) => `/payments/methods/${id}`
            },
            
            // Sitemap
            sitemap: {
                status: '/sitemap/status',
                stats: '/sitemap/stats',
                generate: '/sitemap/generate',
                validate: '/sitemap/validate'
            },
            
            // Beta Signups
            betaSignups: '/beta-signups',
            
            // Newsletter
            newsletter: '/newsletter-subscriptions',
            
            // Pages
            pages: '/pages',
            page: (id) => `/pages/${id}`,
            
            // Contributions
            contributions: '/research-contributions',
            contribution: (id) => `/research-contributions/${id}`,
            
            // Donations
            donations: '/support-donations',
            donation: (id) => `/support-donations/${id}`
        };
    }

    /**
     * Set authentication token
     */
    setToken(token, user = null) {
        this.token = token;
        this.user = user;
        localStorage.setItem('tqrs_token', token);
        if (user) {
            localStorage.setItem('tqrs_user', JSON.stringify(user));
        }
    }

    /**
     * Clear authentication
     */
    clearAuth() {
        this.token = null;
        this.user = null;
        localStorage.removeItem('tqrs_token');
        localStorage.removeItem('tqrs_user');
    }

    /**
     * Get request headers
     */
    getHeaders(includeAuth = true) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        if (includeAuth && this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    /**
     * Make API request
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        const config = {
            headers: this.getHeaders(options.includeAuth !== false),
            ...options
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `HTTP ${response.status}`);
            }

            return data;
        } catch (error) {
            console.error('API Request Error:', error);
            throw error;
        }
    }

    // Authentication Methods
    async login(email, password) {
        const response = await this.request(this.endpoints.login, {
            method: 'POST',
            body: JSON.stringify({ email, password }),
            includeAuth: false
        });

        if (response.success) {
            this.setToken(response.data.token, response.data.user);
        }

        return response;
    }

    async register(userData) {
        const response = await this.request(this.endpoints.register, {
            method: 'POST',
            body: JSON.stringify(userData),
            includeAuth: false
        });

        if (response.success) {
            this.setToken(response.data.token, response.data.user);
        }

        return response;
    }

    async logout() {
        const response = await this.request(this.endpoints.logout, {
            method: 'POST'
        });

        this.clearAuth();
        return response;
    }

    async getUser() {
        return await this.request(this.endpoints.user);
    }

    // Search Methods
    async search(params) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.search}?${queryString}`, {
            includeAuth: false
        });
    }

    async getSearchFilters() {
        return await this.request(this.endpoints.searchFilters, {
            includeAuth: false
        });
    }

    async trackSearch(query, resultsCount = 0, clickedResult = null) {
        return await this.request(this.endpoints.searchTrack, {
            method: 'POST',
            body: JSON.stringify({ query, results_count: resultsCount, clicked_result: clickedResult }),
            includeAuth: false
        });
    }

    async getSearchAnalytics(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.searchAnalytics}?${queryString}`);
    }

    // Dashboard Methods
    async getDashboard() {
        return await this.request(this.endpoints.dashboard);
    }

    async updateLastSeen() {
        return await this.request(this.endpoints.dashboardLastSeen, {
            method: 'POST'
        });
    }

    async getLearningPath() {
        return await this.request(this.endpoints.dashboardLearningPath);
    }

    // Webinar Methods
    async getWebinars(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.webinars}?${queryString}`, {
            includeAuth: false
        });
    }

    async getWebinar(id) {
        return await this.request(this.endpoints.webinar(id), {
            includeAuth: false
        });
    }

    async getWebinarStats() {
        return await this.request(this.endpoints.webinarStats, {
            includeAuth: false
        });
    }

    async createWebinar(webinarData) {
        return await this.request(this.endpoints.webinars, {
            method: 'POST',
            body: JSON.stringify(webinarData)
        });
    }

    async updateWebinar(id, webinarData) {
        return await this.request(this.endpoints.webinar(id), {
            method: 'PUT',
            body: JSON.stringify(webinarData)
        });
    }

    async deleteWebinar(id) {
        return await this.request(this.endpoints.webinar(id), {
            method: 'DELETE'
        });
    }

    async getWebinarRegistrations(id) {
        return await this.request(this.endpoints.webinarRegistrations(id));
    }

    // Blog Methods
    async getBlogs(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.blogs}?${queryString}`, {
            includeAuth: false
        });
    }

    async getBlog(id) {
        return await this.request(this.endpoints.blog(id), {
            includeAuth: false
        });
    }

    async createBlog(blogData) {
        return await this.request(this.endpoints.blogs, {
            method: 'POST',
            body: JSON.stringify(blogData)
        });
    }

    async updateBlog(id, blogData) {
        return await this.request(this.endpoints.blog(id), {
            method: 'PUT',
            body: JSON.stringify(blogData)
        });
    }

    async deleteBlog(id) {
        return await this.request(this.endpoints.blog(id), {
            method: 'DELETE'
        });
    }

    // User Methods
    async getUsers(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.users}?${queryString}`);
    }

    async getUserById(id) {
        return await this.request(this.endpoints.user(id));
    }

    async getUserStats() {
        return await this.request(this.endpoints.userStats);
    }

    async getUserProfile() {
        return await this.request(this.endpoints.userProfile);
    }

    async updateUserProfile(profileData) {
        return await this.request(this.endpoints.userUpdateProfile, {
            method: 'PUT',
            body: JSON.stringify(profileData)
        });
    }

    // Content Management Methods
    async getContentOverview() {
        return await this.request(this.endpoints.contentOverview);
    }

    async getContent(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.contentList}?${queryString}`);
    }

    async createContent(contentData) {
        return await this.request(this.endpoints.contentCreate, {
            method: 'POST',
            body: JSON.stringify(contentData)
        });
    }

    async updateContent(id, contentData) {
        return await this.request(this.endpoints.contentUpdate(id), {
            method: 'PUT',
            body: JSON.stringify(contentData)
        });
    }

    async deleteContent(id) {
        return await this.request(this.endpoints.contentDelete(id), {
            method: 'DELETE'
        });
    }

    async bulkContentAction(actionData) {
        return await this.request(this.endpoints.contentBulkAction, {
            method: 'POST',
            body: JSON.stringify(actionData)
        });
    }

    async getContentAnalytics(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.contentAnalytics}?${queryString}`);
    }

    async getMediaLibrary(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.mediaLibrary}?${queryString}`);
    }

    async uploadMedia(formData) {
        return await this.request(this.endpoints.uploadMedia, {
            method: 'POST',
            headers: {}, // Let browser set Content-Type for FormData
            body: formData
        });
    }

    // Analytics Methods
    async getAnalytics(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.analytics[endpoint]}?${queryString}`);
    }

    async getAdvancedAnalytics(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.advancedAnalytics[endpoint]}?${queryString}`);
    }

    // Payment Methods
    async createWebinarPayment(paymentData) {
        return await this.request(this.endpoints.payments.webinar, {
            method: 'POST',
            body: JSON.stringify(paymentData)
        });
    }

    async createDonation(donationData) {
        return await this.request(this.endpoints.payments.donation, {
            method: 'POST',
            body: JSON.stringify(donationData)
        });
    }

    async createSubscription(subscriptionData) {
        return await this.request(this.endpoints.payments.subscription, {
            method: 'POST',
            body: JSON.stringify(subscriptionData)
        });
    }

    async confirmPayment(paymentData) {
        return await this.request(this.endpoints.payments.confirm, {
            method: 'POST',
            body: JSON.stringify(paymentData)
        });
    }

    async getPaymentHistory(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.payments.history}?${queryString}`);
    }

    async getPaymentMethods() {
        return await this.request(this.endpoints.payments.methods);
    }

    async addPaymentMethod(methodData) {
        return await this.request(this.endpoints.payments.addMethod, {
            method: 'POST',
            body: JSON.stringify(methodData)
        });
    }

    async removePaymentMethod(id) {
        return await this.request(this.endpoints.payments.removeMethod(id), {
            method: 'DELETE'
        });
    }

    // Sitemap Methods
    async getSitemapStatus() {
        return await this.request(this.endpoints.sitemap.status, {
            includeAuth: false
        });
    }

    async getSitemapStats() {
        return await this.request(this.endpoints.sitemap.stats, {
            includeAuth: false
        });
    }

    async generateSitemap() {
        return await this.request(this.endpoints.sitemap.generate, {
            method: 'POST'
        });
    }

    async validateSitemap() {
        return await this.request(this.endpoints.sitemap.validate, {
            method: 'POST'
        });
    }

    // Beta Signup Methods
    async createBetaSignup(email) {
        return await this.request(this.endpoints.betaSignups, {
            method: 'POST',
            body: JSON.stringify({ email }),
            includeAuth: false
        });
    }

    // Newsletter Methods
    async subscribeNewsletter(email) {
        return await this.request(this.endpoints.newsletter, {
            method: 'POST',
            body: JSON.stringify({ email }),
            includeAuth: false
        });
    }

    // Page Methods
    async getPages(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.pages}?${queryString}`, {
            includeAuth: false
        });
    }

    async getPage(id) {
        return await this.request(this.endpoints.page(id), {
            includeAuth: false
        });
    }

    async createPage(pageData) {
        return await this.request(this.endpoints.pages, {
            method: 'POST',
            body: JSON.stringify(pageData)
        });
    }

    async updatePage(id, pageData) {
        return await this.request(this.endpoints.page(id), {
            method: 'PUT',
            body: JSON.stringify(pageData)
        });
    }

    async deletePage(id) {
        return await this.request(this.endpoints.page(id), {
            method: 'DELETE'
        });
    }

    // Contribution Methods
    async getContributions(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.contributions}?${queryString}`, {
            includeAuth: false
        });
    }

    async getContribution(id) {
        return await this.request(this.endpoints.contribution(id), {
            includeAuth: false
        });
    }

    async createContribution(contributionData) {
        return await this.request(this.endpoints.contributions, {
            method: 'POST',
            body: JSON.stringify(contributionData)
        });
    }

    async updateContribution(id, contributionData) {
        return await this.request(this.endpoints.contribution(id), {
            method: 'PUT',
            body: JSON.stringify(contributionData)
        });
    }

    async deleteContribution(id) {
        return await this.request(this.endpoints.contribution(id), {
            method: 'DELETE'
        });
    }

    // Donation Methods
    async getDonations(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await this.request(`${this.endpoints.donations}?${queryString}`, {
            includeAuth: false
        });
    }

    async getDonation(id) {
        return await this.request(this.endpoints.donation(id), {
            includeAuth: false
        });
    }

    async createDonation(donationData) {
        return await this.request(this.endpoints.donations, {
            method: 'POST',
            body: JSON.stringify(donationData)
        });
    }

    async updateDonation(id, donationData) {
        return await this.request(this.endpoints.donation(id), {
            method: 'PUT',
            body: JSON.stringify(donationData)
        });
    }

    async deleteDonation(id) {
        return await this.request(this.endpoints.donation(id), {
            method: 'DELETE'
        });
    }

    /**
     * Error handler
     */
    handleError(error) {
        console.error('API Error:', error);
        
        // Handle authentication errors
        if (error.message.includes('401') || error.message.includes('Unauthorized')) {
            this.clearAuth();
            window.location.href = '/login.php';
            return;
        }

        // Handle rate limiting
        if (error.message.includes('429') || error.message.includes('Rate limit')) {
            alert('Too many requests. Please try again later.');
            return;
        }

        // Handle network errors
        if (error.message.includes('Network') || error.message.includes('fetch')) {
            alert('Network error. Please check your connection and try again.');
            return;
        }

        // Generic error
        alert(`Error: ${error.message}`);
    }

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        return !!this.token && !!this.user;
    }

    /**
     * Get current user
     */
    getCurrentUser() {
        return this.user;
    }

    /**
     * Check if user has role
     */
    hasRole(role) {
        return this.user && this.user.role === role;
    }

    /**
     * Check if user is admin
     */
    isAdmin() {
        return this.hasRole('admin');
    }
}

// Create global API instance
const api = new TQRSAPI();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TQRSAPI;
} 