// Webinars Management JavaScript
class WebinarsManager {
    constructor() {
        this.apiBase = '/api';
        this.webinarsTable = null;
        this.currentWebinarId = null;
        this.init();
    }

    init() {
        this.initDataTable();
        this.loadWebinars();
        this.bindEvents();
        this.loadStats();
    }

    initDataTable() {
        this.webinarsTable = $('#webinarsTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: "Search webinars:",
                lengthMenu: "Show _MENU_ webinars per page",
                info: "Showing _START_ to _END_ of _TOTAL_ webinars",
                emptyTable: "No webinars found"
            },
            columnDefs: [
                { targets: [0], width: '60px' },
                { targets: [1], width: '200px' },
                { targets: [2], width: '300px' },
                { targets: [3], width: '150px' },
                { targets: [4], width: '100px' },
                { targets: [5], width: '100px' },
                { targets: [6], width: '120px', orderable: false }
            ]
        });
    }

    bindEvents() {
        // Add new webinar button
        $('#addWebinarBtn').on('click', () => {
            this.resetForm();
            $('#webinarModalLabel').text('Add New Webinar');
            $('#webinarModal').modal('show');
        });

        // Form submission
        $('#webinarForm').on('submit', (e) => {
            e.preventDefault();
            this.saveWebinar();
        });

        // Delete confirmation
        $('#confirmDeleteWebinarBtn').on('click', () => {
            this.deleteWebinar();
        });

        // Modal events
        $('#webinarModal').on('hidden.bs.modal', () => {
            this.resetForm();
        });

        // Date and time validation
        $('#webinarDate').on('change', () => {
            this.validateDateTime();
        });

        $('#webinarTime').on('change', () => {
            this.validateDateTime();
        });
    }

    async loadWebinars() {
        try {
            const response = await fetch(`${this.apiBase}/webinars`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load webinars');
            }

            const webinars = await response.json();
            this.populateTable(webinars.data || webinars);
        } catch (error) {
            console.error('Error loading webinars:', error);
            this.showAlert('Error loading webinars', 'danger');
        }
    }

    populateTable(webinars) {
        this.webinarsTable.clear();

        webinars.forEach(webinar => {
            const row = [
                webinar.id,
                `<strong>${this.escapeHtml(webinar.title)}</strong>`,
                this.truncateText(webinar.description, 100),
                this.formatDateTime(webinar.date, webinar.time),
                `${webinar.duration || 60} min`,
                this.getStatusBadge(webinar.status),
                this.getActionButtons(webinar.id, webinar.title)
            ];

            this.webinarsTable.row.add(row);
        });

        this.webinarsTable.draw();
    }

    getStatusBadge(status) {
        const badges = {
            'draft': '<span class="badge bg-secondary">Draft</span>',
            'published': '<span class="badge bg-primary">Published</span>',
            'live': '<span class="badge bg-success">Live</span>',
            'completed': '<span class="badge bg-warning">Completed</span>'
        };
        return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
    }

    getActionButtons(id, title) {
        return `
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary" onclick="webinarsManager.editWebinar(${id})" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="webinarsManager.confirmDelete(${id}, '${this.escapeHtml(title)}')" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
    }

    async loadWebinar(id) {
        try {
            const response = await fetch(`${this.apiBase}/webinars/${id}`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load webinar');
            }

            const webinar = await response.json();
            return webinar.data || webinar;
        } catch (error) {
            console.error('Error loading webinar:', error);
            this.showAlert('Error loading webinar details', 'danger');
            return null;
        }
    }

    async editWebinar(id) {
        const webinar = await this.loadWebinar(id);
        if (!webinar) return;

        this.currentWebinarId = id;
        this.populateForm(webinar);
        $('#webinarModalLabel').text('Edit Webinar');
        $('#webinarModal').modal('show');
    }

    populateForm(webinar) {
        $('#webinarId').val(webinar.id);
        $('#webinarTitle').val(webinar.title);
        $('#webinarDescription').val(webinar.description);
        $('#webinarDate').val(webinar.date);
        $('#webinarTime').val(webinar.time);
        $('#webinarDuration').val(webinar.duration || 60);
        $('#webinarMaxAttendees').val(webinar.max_attendees || 100);
        $('#webinarPlatform').val(webinar.platform || 'zoom');
        $('#webinarMeetingUrl').val(webinar.meeting_url || '');
        $('#webinarTags').val(webinar.tags || '');
        $('#webinarStatus').val(webinar.status || 'draft');
        $('#webinarRequiresRegistration').prop('checked', webinar.requires_registration !== false);
        $('#webinarIsPublic').prop('checked', webinar.is_public !== false);
    }

    async saveWebinar() {
        const formData = this.getFormData();
        const isEdit = this.currentWebinarId !== null;

        try {
            this.setLoadingState(true);

            const url = isEdit ? `${this.apiBase}/webinars/${this.currentWebinarId}` : `${this.apiBase}/webinars`;
            const method = isEdit ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to save webinar');
            }

            const result = await response.json();
            this.showAlert(`Webinar ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            
            $('#webinarModal').modal('hide');
            this.loadWebinars();
            this.loadStats();
        } catch (error) {
            console.error('Error saving webinar:', error);
            this.showAlert(error.message || 'Error saving webinar', 'danger');
        } finally {
            this.setLoadingState(false);
        }
    }

    getFormData() {
        const form = document.getElementById('webinarForm');
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            if (key === 'requires_registration' || key === 'is_public') {
                data[key] = value === 'on';
            } else if (key === 'duration' || key === 'max_attendees') {
                data[key] = parseInt(value) || null;
            } else if (value !== '') {
                data[key] = value;
            }
        }

        // Combine date and time
        if (data.date && data.time) {
            data.scheduled_at = `${data.date} ${data.time}:00`;
            delete data.date;
            delete data.time;
        }

        return data;
    }

    confirmDelete(id, title) {
        this.currentWebinarId = id;
        $('#deleteWebinarTitle').text(title);
        $('#deleteWebinarModal').modal('show');
    }

    async deleteWebinar() {
        try {
            this.setDeleteLoadingState(true);

            const response = await fetch(`${this.apiBase}/webinars/${this.currentWebinarId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to delete webinar');
            }

            this.showAlert('Webinar deleted successfully!', 'success');
            $('#deleteWebinarModal').modal('hide');
            this.loadWebinars();
            this.loadStats();
        } catch (error) {
            console.error('Error deleting webinar:', error);
            this.showAlert(error.message || 'Error deleting webinar', 'danger');
        } finally {
            this.setDeleteLoadingState(false);
        }
    }

    async loadStats() {
        try {
            const response = await fetch(`${this.apiBase}/webinars/stats`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                $('#totalWebinars').text(stats.total || 0);
                $('#upcomingWebinars').text(stats.upcoming || 0);
                $('#liveWebinars').text(stats.live || 0);
                $('#completedWebinars').text(stats.completed || 0);
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    resetForm() {
        document.getElementById('webinarForm').reset();
        this.currentWebinarId = null;
        $('#webinarId').val('');
        $('#webinarDate').val('');
        $('#webinarTime').val('');
        $('#webinarDuration').val('60');
        $('#webinarMaxAttendees').val('100');
        $('#webinarPlatform').val('zoom');
        $('#webinarStatus').val('draft');
        $('#webinarRequiresRegistration').prop('checked', true);
        $('#webinarIsPublic').prop('checked', true);
    }

    validateDateTime() {
        const date = $('#webinarDate').val();
        const time = $('#webinarTime').val();
        
        if (date && time) {
            const selectedDateTime = new Date(`${date} ${time}`);
            const now = new Date();
            
            if (selectedDateTime < now) {
                $('#webinarDate').addClass('is-invalid');
                $('#webinarTime').addClass('is-invalid');
                this.showAlert('Please select a future date and time', 'warning');
            } else {
                $('#webinarDate').removeClass('is-invalid');
                $('#webinarTime').removeClass('is-invalid');
            }
        }
    }

    setLoadingState(loading) {
        const btn = $('#saveWebinarBtn');
        const spinner = btn.find('.spinner-border');
        const text = btn.contents().filter(function() { return this.nodeType === 3; }).first();
        
        if (loading) {
            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            text[0].textContent = ' Saving...';
        } else {
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            text[0].textContent = ' Save Webinar';
        }
    }

    setDeleteLoadingState(loading) {
        const btn = $('#confirmDeleteWebinarBtn');
        const spinner = btn.find('.spinner-border');
        const text = btn.contents().filter(function() { return this.nodeType === 3; }).first();
        
        if (loading) {
            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            text[0].textContent = ' Deleting...';
        } else {
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            text[0].textContent = ' Delete Webinar';
        }
    }

    formatDateTime(date, time) {
        if (!date) return 'N/A';
        
        const dateObj = new Date(`${date} ${time || '00:00'}`);
        return dateObj.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    truncateText(text, maxLength) {
        if (!text) return '';
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
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

// Initialize when document is ready
$(document).ready(function() {
    window.webinarsManager = new WebinarsManager();
}); 