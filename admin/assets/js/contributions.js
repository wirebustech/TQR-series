// Research Contributions Management JavaScript
class ContributionsManager {
    constructor() {
        this.apiBase = '/api';
        this.contributionsTable = null;
        this.currentContributionId = null;
        this.init();
    }

    init() {
        this.initDataTable();
        this.loadContributions();
        this.bindEvents();
        this.loadStats();
    }

    initDataTable() {
        this.contributionsTable = $('#contributionsTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: "Search contributions:",
                lengthMenu: "Show _MENU_ contributions per page",
                info: "Showing _START_ to _END_ of _TOTAL_ contributions",
                emptyTable: "No contributions found"
            },
            columnDefs: [
                { targets: [0], width: '60px' },
                { targets: [1], width: '250px' },
                { targets: [2], width: '150px' },
                { targets: [3], width: '120px' },
                { targets: [4], width: '100px' },
                { targets: [5], width: '120px' },
                { targets: [6], width: '150px', orderable: false }
            ]
        });
    }

    bindEvents() {
        // Add new contribution button
        $('#addContributionBtn').on('click', () => {
            this.resetForm();
            $('#contributionModalLabel').text('Add New Contribution');
            $('#contributionModal').modal('show');
        });

        // Form submission
        $('#contributionForm').on('submit', (e) => {
            e.preventDefault();
            this.saveContribution();
        });

        // Delete confirmation
        $('#confirmDeleteContributionBtn').on('click', () => {
            this.deleteContribution();
        });

        // Approve button
        $('#approveContributionBtn').on('click', () => {
            this.approveContribution();
        });

        // Reject button
        $('#rejectContributionBtn').on('click', () => {
            this.rejectContribution();
        });

        // Modal events
        $('#contributionModal').on('hidden.bs.modal', () => {
            this.resetForm();
        });

        // Filter events
        $('#applyFiltersBtn').on('click', () => {
            this.applyFilters();
        });

        $('#clearFiltersBtn').on('click', () => {
            this.clearFilters();
        });

        // Export button
        $('#exportContributionsBtn').on('click', () => {
            this.exportContributions();
        });
    }

    async loadContributions() {
        try {
            const response = await fetch(`${this.apiBase}/research-contributions`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load contributions');
            }

            const contributions = await response.json();
            this.populateTable(contributions.data || contributions);
        } catch (error) {
            console.error('Error loading contributions:', error);
            this.showAlert('Error loading contributions', 'danger');
        }
    }

    populateTable(contributions) {
        this.contributionsTable.clear();

        contributions.forEach(contribution => {
            const row = [
                contribution.id,
                `<strong>${this.escapeHtml(contribution.title)}</strong>`,
                this.escapeHtml(contribution.author_name || contribution.user?.name || 'Unknown'),
                this.getTypeBadge(contribution.type),
                this.getStatusBadge(contribution.status),
                this.formatDate(contribution.created_at),
                this.getActionButtons(contribution.id, contribution.title)
            ];

            this.contributionsTable.row.add(row);
        });

        this.contributionsTable.draw();
    }

    getTypeBadge(type) {
        const badges = {
            'research_paper': '<span class="badge bg-primary">Research Paper</span>',
            'case_study': '<span class="badge bg-info">Case Study</span>',
            'methodology': '<span class="badge bg-warning">Methodology</span>',
            'review': '<span class="badge bg-secondary">Review</span>',
            'other': '<span class="badge bg-dark">Other</span>'
        };
        return badges[type] || '<span class="badge bg-secondary">Unknown</span>';
    }

    getStatusBadge(status) {
        const badges = {
            'pending': '<span class="badge bg-warning">Pending</span>',
            'approved': '<span class="badge bg-success">Approved</span>',
            'rejected': '<span class="badge bg-danger">Rejected</span>',
            'published': '<span class="badge bg-primary">Published</span>'
        };
        return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
    }

    getActionButtons(id, title) {
        return `
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary" onclick="contributionsManager.reviewContribution(${id})" title="Review">
                    <i class="bi bi-eye"></i>
                </button>
                <button type="button" class="btn btn-outline-success" onclick="contributionsManager.quickApprove(${id})" title="Quick Approve">
                    <i class="bi bi-check"></i>
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="contributionsManager.confirmDelete(${id}, '${this.escapeHtml(title)}')" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
    }

    async loadContribution(id) {
        try {
            const response = await fetch(`${this.apiBase}/research-contributions/${id}`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load contribution');
            }

            const contribution = await response.json();
            return contribution.data || contribution;
        } catch (error) {
            console.error('Error loading contribution:', error);
            this.showAlert('Error loading contribution details', 'danger');
            return null;
        }
    }

    async reviewContribution(id) {
        const contribution = await this.loadContribution(id);
        if (!contribution) return;

        this.currentContributionId = id;
        this.populateForm(contribution);
        $('#contributionModalLabel').text('Review Contribution');
        $('#contributionModal').modal('show');
    }

    populateForm(contribution) {
        $('#contributionId').val(contribution.id);
        $('#contributionTitle').val(contribution.title);
        $('#contributionAuthor').val(contribution.author_name || contribution.user?.name || 'Unknown');
        $('#contributionType').val(contribution.type);
        $('#contributionAbstract').val(contribution.abstract || '');
        $('#contributionKeywords').val(contribution.keywords || '');
        $('#contributionStatus').val(contribution.status || 'pending');
        $('#contributionReviewNotes').val(contribution.review_notes || '');
        $('#contributionAdminNotes').val(contribution.admin_notes || '');
        $('#contributionSubmittedAt').val(this.formatDateTime(contribution.created_at));

        // Handle file info
        if (contribution.file_path) {
            $('#contributionFileInfo').text(contribution.file_name || 'File uploaded');
            $('#contributionFileDownload').attr('href', `/storage/${contribution.file_path}`).show();
        } else {
            $('#contributionFileInfo').text('No file uploaded');
            $('#contributionFileDownload').hide();
        }
    }

    async saveContribution() {
        const formData = this.getFormData();
        const isEdit = this.currentContributionId !== null;

        try {
            this.setLoadingState(true);

            const url = isEdit ? `${this.apiBase}/research-contributions/${this.currentContributionId}` : `${this.apiBase}/research-contributions`;
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
                throw new Error(error.message || 'Failed to save contribution');
            }

            const result = await response.json();
            this.showAlert(`Contribution ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            
            $('#contributionModal').modal('hide');
            this.loadContributions();
            this.loadStats();
        } catch (error) {
            console.error('Error saving contribution:', error);
            this.showAlert(error.message || 'Error saving contribution', 'danger');
        } finally {
            this.setLoadingState(false);
        }
    }

    async approveContribution() {
        if (!this.currentContributionId) return;

        try {
            this.setLoadingState(true);

            const response = await fetch(`${this.apiBase}/research-contributions/${this.currentContributionId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: 'approved',
                    review_notes: $('#contributionReviewNotes').val()
                })
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to approve contribution');
            }

            this.showAlert('Contribution approved successfully!', 'success');
            $('#contributionModal').modal('hide');
            this.loadContributions();
            this.loadStats();
        } catch (error) {
            console.error('Error approving contribution:', error);
            this.showAlert(error.message || 'Error approving contribution', 'danger');
        } finally {
            this.setLoadingState(false);
        }
    }

    async rejectContribution() {
        if (!this.currentContributionId) return;

        try {
            this.setLoadingState(true);

            const response = await fetch(`${this.apiBase}/research-contributions/${this.currentContributionId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: 'rejected',
                    review_notes: $('#contributionReviewNotes').val()
                })
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to reject contribution');
            }

            this.showAlert('Contribution rejected successfully!', 'success');
            $('#contributionModal').modal('hide');
            this.loadContributions();
            this.loadStats();
        } catch (error) {
            console.error('Error rejecting contribution:', error);
            this.showAlert(error.message || 'Error rejecting contribution', 'danger');
        } finally {
            this.setLoadingState(false);
        }
    }

    async quickApprove(id) {
        if (!confirm('Are you sure you want to approve this contribution?')) return;

        try {
            const response = await fetch(`${this.apiBase}/research-contributions/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: 'approved',
                    review_notes: 'Quick approved by admin'
                })
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to approve contribution');
            }

            this.showAlert('Contribution approved successfully!', 'success');
            this.loadContributions();
            this.loadStats();
        } catch (error) {
            console.error('Error approving contribution:', error);
            this.showAlert(error.message || 'Error approving contribution', 'danger');
        }
    }

    getFormData() {
        const form = document.getElementById('contributionForm');
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            if (value !== '') {
                data[key] = value;
            }
        }

        return data;
    }

    confirmDelete(id, title) {
        this.currentContributionId = id;
        $('#deleteContributionTitle').text(title);
        $('#deleteContributionModal').modal('show');
    }

    async deleteContribution() {
        try {
            this.setDeleteLoadingState(true);

            const response = await fetch(`${this.apiBase}/research-contributions/${this.currentContributionId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to delete contribution');
            }

            this.showAlert('Contribution deleted successfully!', 'success');
            $('#deleteContributionModal').modal('hide');
            this.loadContributions();
            this.loadStats();
        } catch (error) {
            console.error('Error deleting contribution:', error);
            this.showAlert(error.message || 'Error deleting contribution', 'danger');
        } finally {
            this.setDeleteLoadingState(false);
        }
    }

    async loadStats() {
        try {
            const response = await fetch(`${this.apiBase}/research-contributions/stats`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                $('#totalContributions').text(stats.data ? stats.data.total : 0);
                $('#pendingContributions').text(stats.data ? stats.data.pending : 0);
                $('#approvedContributions').text(stats.data ? stats.data.approved : 0);
                $('#rejectedContributions').text(stats.data ? stats.data.rejected : 0);
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    applyFilters() {
        const status = $('#statusFilter').val();
        const type = $('#typeFilter').val();
        const dateFrom = $('#dateFromFilter').val();
        const dateTo = $('#dateToFilter').val();

        // Apply filters to DataTable
        this.contributionsTable.draw();
    }

    clearFilters() {
        $('#statusFilter').val('');
        $('#typeFilter').val('');
        $('#dateFromFilter').val('');
        $('#dateToFilter').val('');
        this.contributionsTable.draw();
    }

    async exportContributions() {
        try {
            const response = await fetch(`${this.apiBase}/research-contributions/export`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `contributions_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                throw new Error('Failed to export contributions');
            }
        } catch (error) {
            console.error('Error exporting contributions:', error);
            this.showAlert('Error exporting contributions', 'danger');
        }
    }

    resetForm() {
        document.getElementById('contributionForm').reset();
        this.currentContributionId = null;
        $('#contributionId').val('');
        $('#contributionFileDownload').hide();
    }

    setLoadingState(loading) {
        const btn = $('#saveContributionBtn');
        const spinner = btn.find('.spinner-border');
        const text = btn.contents().filter(function() { return this.nodeType === 3; }).first();
        
        if (loading) {
            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            text[0].textContent = ' Saving...';
        } else {
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            text[0].textContent = ' Save Changes';
        }
    }

    setDeleteLoadingState(loading) {
        const btn = $('#confirmDeleteContributionBtn');
        const spinner = btn.find('.spinner-border');
        const text = btn.contents().filter(function() { return this.nodeType === 3; }).first();
        
        if (loading) {
            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            text[0].textContent = ' Deleting...';
        } else {
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            text[0].textContent = ' Delete Contribution';
        }
    }

    formatDate(dateString) {
        if (!dateString) return 'N/A';
        
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    formatDateTime(dateString) {
        if (!dateString) return 'N/A';
        
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
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
    window.contributionsManager = new ContributionsManager();
}); 