// Analytics Dashboard JavaScript
class AnalyticsManager {
    constructor() {
        this.apiBase = '/api';
        this.charts = {};
        this.dateRange = 30;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadAnalytics();
        this.initCharts();
    }

    bindEvents() {
        // Date range filter
        $('#dateRange').on('change', (e) => {
            this.dateRange = e.target.value;
            if (this.dateRange === 'custom') {
                $('#customDateFrom, #customDateTo').prop('disabled', false);
            } else {
                $('#customDateFrom, #customDateTo').prop('disabled', true);
            }
        });

        // Apply date filter
        $('#applyDateRangeBtn').on('click', () => {
            this.loadAnalytics();
        });

        // Refresh data
        $('#refreshDataBtn').on('click', () => {
            this.loadAnalytics();
        });

        // Export report
        $('#exportReportBtn').on('click', () => {
            this.exportReport();
        });
    }

    async loadAnalytics() {
        try {
            this.showLoadingState(true);
            
            // Load all analytics data
            await Promise.all([
                this.loadKeyMetrics(),
                this.loadUserGrowthData(),
                this.loadUserDistributionData(),
                this.loadWebinarPerformanceData(),
                this.loadContributionStatusData(),
                this.loadRecentActivity(),
                this.loadTopContent()
            ]);

            this.updateCharts();
            this.showLoadingState(false);
        } catch (error) {
            console.error('Error loading analytics:', error);
            this.showAlert('Error loading analytics data', 'danger');
            this.showLoadingState(false);
        }
    }

    async loadKeyMetrics() {
        try {
            const [usersResponse, webinarsResponse, contributionsResponse, blogsResponse] = await Promise.all([
                fetch(`${this.apiBase}/users/stats`, {
                    headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
                }),
                fetch(`${this.apiBase}/webinars/stats`, {
                    headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
                }),
                fetch(`${this.apiBase}/research-contributions/stats`, {
                    headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
                }),
                fetch(`${this.apiBase}/blogs/stats`, {
                    headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
                })
            ]);

            const usersData = await usersResponse.json();
            const webinarsData = await webinarsResponse.json();
            const contributionsData = await contributionsResponse.json();
            const blogsData = await blogsResponse.json();

            // Update key metrics
            $('#totalUsers').text(usersData.data?.total || 0);
            $('#totalWebinars').text(webinarsData.data?.total || 0);
            $('#totalContributions').text(contributionsData.data?.total || 0);
            $('#totalBlogs').text(blogsData.data?.total || 0);

            // Update detailed stats
            $('#newUsersMonth').text(usersData.data?.new_this_month || 0);
            $('#activeUsers').text(usersData.data?.active || 0);
            $('#verifiedUsers').text(usersData.data?.verified || 0);
            $('#adminUsers').text(usersData.data?.admin || 0);
            $('#publishedBlogs').text(blogsData.data?.published || 0);
            $('#upcomingWebinars').text(webinarsData.data?.upcoming || 0);
            $('#approvedContributions').text(contributionsData.data?.approved || 0);

            // Calculate growth percentages (mock data for now)
            $('#userGrowth').text('+12% from last period');
            $('#webinarGrowth').text('+8% from last period');
            $('#contributionGrowth').text('+15% from last period');
            $('#blogGrowth').text('+5% from last period');

        } catch (error) {
            console.error('Error loading key metrics:', error);
        }
    }

    async loadUserGrowthData() {
        try {
            const response = await fetch(`${this.apiBase}/analytics/user-growth?days=${this.dateRange}`, {
                headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
            });

            if (response.ok) {
                const data = await response.json();
                this.userGrowthData = data.data || this.generateMockUserGrowthData();
            } else {
                this.userGrowthData = this.generateMockUserGrowthData();
            }
        } catch (error) {
            console.error('Error loading user growth data:', error);
            this.userGrowthData = this.generateMockUserGrowthData();
        }
    }

    async loadUserDistributionData() {
        try {
            const response = await fetch(`${this.apiBase}/analytics/user-distribution`, {
                headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
            });

            if (response.ok) {
                const data = await response.json();
                this.userDistributionData = data.data || this.generateMockUserDistributionData();
            } else {
                this.userDistributionData = this.generateMockUserDistributionData();
            }
        } catch (error) {
            console.error('Error loading user distribution data:', error);
            this.userDistributionData = this.generateMockUserDistributionData();
        }
    }

    async loadWebinarPerformanceData() {
        try {
            const response = await fetch(`${this.apiBase}/analytics/webinar-performance?days=${this.dateRange}`, {
                headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
            });

            if (response.ok) {
                const data = await response.json();
                this.webinarPerformanceData = data.data || this.generateMockWebinarPerformanceData();
            } else {
                this.webinarPerformanceData = this.generateMockWebinarPerformanceData();
            }
        } catch (error) {
            console.error('Error loading webinar performance data:', error);
            this.webinarPerformanceData = this.generateMockWebinarPerformanceData();
        }
    }

    async loadContributionStatusData() {
        try {
            const response = await fetch(`${this.apiBase}/analytics/contribution-status`, {
                headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
            });

            if (response.ok) {
                const data = await response.json();
                this.contributionStatusData = data.data || this.generateMockContributionStatusData();
            } else {
                this.contributionStatusData = this.generateMockContributionStatusData();
            }
        } catch (error) {
            console.error('Error loading contribution status data:', error);
            this.contributionStatusData = this.generateMockContributionStatusData();
        }
    }

    async loadRecentActivity() {
        try {
            const response = await fetch(`${this.apiBase}/analytics/recent-activity?limit=10`, {
                headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
            });

            if (response.ok) {
                const data = await response.json();
                this.populateRecentActivity(data.data || this.generateMockRecentActivity());
            } else {
                this.populateRecentActivity(this.generateMockRecentActivity());
            }
        } catch (error) {
            console.error('Error loading recent activity:', error);
            this.populateRecentActivity(this.generateMockRecentActivity());
        }
    }

    async loadTopContent() {
        try {
            const response = await fetch(`${this.apiBase}/analytics/top-content?limit=5`, {
                headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
            });

            if (response.ok) {
                const data = await response.json();
                this.populateTopContent(data.data || this.generateMockTopContent());
            } else {
                this.populateTopContent(this.generateMockTopContent());
            }
        } catch (error) {
            console.error('Error loading top content:', error);
            this.populateTopContent(this.generateMockTopContent());
        }
    }

    initCharts() {
        // Initialize all charts with empty data
        this.initUserGrowthChart();
        this.initUserDistributionChart();
        this.initWebinarPerformanceChart();
        this.initContributionStatusChart();
    }

    initUserGrowthChart() {
        const ctx = document.getElementById('userGrowthChart').getContext('2d');
        this.charts.userGrowth = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'New Users',
                    data: [],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    initUserDistributionChart() {
        const ctx = document.getElementById('userDistributionChart').getContext('2d');
        this.charts.userDistribution = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Users', 'Researchers', 'Moderators', 'Admins'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    initWebinarPerformanceChart() {
        const ctx = document.getElementById('webinarPerformanceChart').getContext('2d');
        this.charts.webinarPerformance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Registrations',
                    data: [],
                    backgroundColor: 'rgba(255, 99, 132, 0.8)'
                }, {
                    label: 'Attendees',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    initContributionStatusChart() {
        const ctx = document.getElementById('contributionStatusChart').getContext('2d');
        this.charts.contributionStatus = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Approved', 'Rejected', 'Published'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    updateCharts() {
        // Update User Growth Chart
        if (this.charts.userGrowth && this.userGrowthData) {
            this.charts.userGrowth.data.labels = this.userGrowthData.labels;
            this.charts.userGrowth.data.datasets[0].data = this.userGrowthData.values;
            this.charts.userGrowth.update();
        }

        // Update User Distribution Chart
        if (this.charts.userDistribution && this.userDistributionData) {
            this.charts.userDistribution.data.datasets[0].data = this.userDistributionData.values;
            this.charts.userDistribution.update();
        }

        // Update Webinar Performance Chart
        if (this.charts.webinarPerformance && this.webinarPerformanceData) {
            this.charts.webinarPerformance.data.labels = this.webinarPerformanceData.labels;
            this.charts.webinarPerformance.data.datasets[0].data = this.webinarPerformanceData.registrations;
            this.charts.webinarPerformance.data.datasets[1].data = this.webinarPerformanceData.attendees;
            this.charts.webinarPerformance.update();
        }

        // Update Contribution Status Chart
        if (this.charts.contributionStatus && this.contributionStatusData) {
            this.charts.contributionStatus.data.datasets[0].data = this.contributionStatusData.values;
            this.charts.contributionStatus.update();
        }
    }

    populateRecentActivity(activities) {
        const container = $('#recentActivity');
        container.empty();

        activities.forEach(activity => {
            const item = `
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">${activity.title}</div>
                        <small class="text-muted">${activity.description}</small>
                    </div>
                    <small class="text-muted">${this.formatTimeAgo(activity.created_at)}</small>
                </div>
            `;
            container.append(item);
        });
    }

    populateTopContent(content) {
        const container = $('#topContent');
        container.empty();

        content.forEach((item, index) => {
            const badge = `
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">${index + 1}. ${item.title}</div>
                        <small class="text-muted">${item.type} • ${item.views} views</small>
                    </div>
                    <span class="badge bg-primary rounded-pill">${item.engagement}%</span>
                </div>
            `;
            container.append(badge);
        });
    }

    // Mock data generators for development
    generateMockUserGrowthData() {
        const labels = [];
        const values = [];
        const days = this.dateRange;
        
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            values.push(Math.floor(Math.random() * 20) + 5);
        }

        return { labels, values };
    }

    generateMockUserDistributionData() {
        return {
            labels: ['Users', 'Researchers', 'Moderators', 'Admins'],
            values: [65, 20, 10, 5]
        };
    }

    generateMockWebinarPerformanceData() {
        const labels = ['Webinar 1', 'Webinar 2', 'Webinar 3', 'Webinar 4', 'Webinar 5'];
        const registrations = [45, 32, 28, 51, 38];
        const attendees = [38, 25, 22, 42, 31];

        return { labels, registrations, attendees };
    }

    generateMockContributionStatusData() {
        return {
            labels: ['Pending', 'Approved', 'Rejected', 'Published'],
            values: [12, 25, 8, 15]
        };
    }

    generateMockRecentActivity() {
        return [
            { title: 'New User Registration', description: 'John Doe joined the platform', created_at: new Date(Date.now() - 1000 * 60 * 30) },
            { title: 'Webinar Created', description: 'Introduction to Qualitative Research scheduled', created_at: new Date(Date.now() - 1000 * 60 * 60) },
            { title: 'Contribution Submitted', description: 'Research paper submitted by Jane Smith', created_at: new Date(Date.now() - 1000 * 60 * 120) },
            { title: 'Blog Published', description: 'New blog post: "Research Methodologies"', created_at: new Date(Date.now() - 1000 * 60 * 180) }
        ];
    }

    generateMockTopContent() {
        return [
            { title: 'Introduction to Qualitative Research', type: 'Webinar', views: 245, engagement: 85 },
            { title: 'Research Methodologies Guide', type: 'Blog', views: 189, engagement: 72 },
            { title: 'Data Analysis Techniques', type: 'Webinar', views: 156, engagement: 68 },
            { title: 'Ethical Considerations', type: 'Blog', views: 134, engagement: 65 },
            { title: 'Mixed Methods Research', type: 'Contribution', views: 98, engagement: 58 }
        ];
    }

    async exportReport() {
        try {
            const response = await fetch(`${this.apiBase}/analytics/export-report?days=${this.dateRange}`, {
                headers: { 'Authorization': `Bearer ${this.getAuthToken()}` }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `analytics_report_${new Date().toISOString().split('T')[0]}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                throw new Error('Failed to export report');
            }
        } catch (error) {
            console.error('Error exporting report:', error);
            this.showAlert('Error exporting report', 'danger');
        }
    }

    showLoadingState(loading) {
        const btn = $('#refreshDataBtn');
        const icon = btn.find('i');
        
        if (loading) {
            btn.prop('disabled', true);
            icon.removeClass('bi-arrow-clockwise').addClass('bi-arrow-clockwise');
            icon.addClass('spinning');
        } else {
            btn.prop('disabled', false);
            icon.removeClass('spinning');
        }
    }

    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInMinutes = Math.floor((now - date) / (1000 * 60));
        
        if (diffInMinutes < 60) {
            return `${diffInMinutes} minutes ago`;
        } else if (diffInMinutes < 1440) {
            const hours = Math.floor(diffInMinutes / 60);
            return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        } else {
            const days = Math.floor(diffInMinutes / 1440);
            return `${days} day${days > 1 ? 's' : ''} ago`;
        }
    }

    getAuthToken() {
        return localStorage.getItem('admin_token') || '';
    }

    showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of the content
        $('#content .container-fluid').prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
}

// Add CSS for spinning animation
const style = document.createElement('style');
style.textContent = `
    .spinning {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Initialize when document is ready
$(document).ready(function() {
    window.analyticsManager = new AnalyticsManager();
}); 