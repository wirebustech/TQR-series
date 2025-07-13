// Users Management JavaScript
class UsersManager {
    constructor() {
        this.apiBase = '/api';
        this.usersTable = null;
        this.currentUserId = null;
        this.init();
    }

    init() {
        this.initDataTable();
        this.loadUsers();
        this.bindEvents();
        this.loadStats();
    }

    initDataTable() {
        this.usersTable = $('#usersTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: "Search users:",
                lengthMenu: "Show _MENU_ users per page",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                emptyTable: "No users found"
            },
            columnDefs: [
                { targets: [0], width: '60px' },
                { targets: [1], width: '200px' },
                { targets: [2], width: '250px' },
                { targets: [3], width: '100px' },
                { targets: [4], width: '100px' },
                { targets: [5], width: '120px' },
                { targets: [6], width: '120px', orderable: false }
            ]
        });
    }

    bindEvents() {
        // Add new user button
        $('#addUserBtn').on('click', () => {
            this.resetForm();
            $('#userModalLabel').text('Add New User');
            $('#userModal').modal('show');
        });

        // Form submission
        $('#userForm').on('submit', (e) => {
            e.preventDefault();
            this.saveUser();
        });

        // Delete confirmation
        $('#confirmDeleteUserBtn').on('click', () => {
            this.deleteUser();
        });

        // Modal events
        $('#userModal').on('hidden.bs.modal', () => {
            this.resetForm();
        });

        // Password confirmation validation
        $('#userPasswordConfirm').on('input', () => {
            this.validatePassword();
        });

        $('#userPassword').on('input', () => {
            this.validatePassword();
        });

        // Role change handler
        $('#userRole').on('change', () => {
            this.handleRoleChange();
        });
    }

    async loadUsers() {
        try {
            const response = await fetch(`${this.apiBase}/users`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load users');
            }

            const users = await response.json();
            this.populateTable(users.data || users);
        } catch (error) {
            console.error('Error loading users:', error);
            this.showAlert('Error loading users', 'danger');
        }
    }

    populateTable(users) {
        this.usersTable.clear();

        users.forEach(user => {
            const row = [
                user.id,
                `<strong>${this.escapeHtml(user.name)}</strong>`,
                this.escapeHtml(user.email),
                this.getRoleBadge(user.role, user.is_admin),
                this.getStatusBadge(user.status || 'active'),
                this.formatDate(user.created_at),
                this.getActionButtons(user.id, user.name)
            ];

            this.usersTable.row.add(row);
        });

        this.usersTable.draw();
    }

    getRoleBadge(role, isAdmin) {
        if (isAdmin) {
            return '<span class="badge bg-danger">Admin</span>';
        }
        
        const badges = {
            'user': '<span class="badge bg-secondary">User</span>',
            'admin': '<span class="badge bg-danger">Admin</span>',
            'moderator': '<span class="badge bg-warning">Moderator</span>',
            'researcher': '<span class="badge bg-info">Researcher</span>'
        };
        return badges[role] || '<span class="badge bg-secondary">User</span>';
    }

    getStatusBadge(status) {
        const badges = {
            'active': '<span class="badge bg-success">Active</span>',
            'inactive': '<span class="badge bg-secondary">Inactive</span>',
            'suspended': '<span class="badge bg-danger">Suspended</span>'
        };
        return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
    }

    getActionButtons(id, name) {
        return `
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary" onclick="usersManager.editUser(${id})" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-outline-info" onclick="usersManager.viewUser(${id})" title="View">
                    <i class="bi bi-eye"></i>
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="usersManager.confirmDelete(${id}, '${this.escapeHtml(name)}')" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
    }

    async loadUser(id) {
        try {
            const response = await fetch(`${this.apiBase}/users/${id}`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load user');
            }

            const user = await response.json();
            return user.data || user;
        } catch (error) {
            console.error('Error loading user:', error);
            this.showAlert('Error loading user details', 'danger');
            return null;
        }
    }

    async editUser(id) {
        const user = await this.loadUser(id);
        if (!user) return;

        this.currentUserId = id;
        this.populateForm(user);
        $('#userModalLabel').text('Edit User');
        $('#userModal').modal('show');
    }

    async viewUser(id) {
        const user = await this.loadUser(id);
        if (!user) return;

        // Create a view-only modal or redirect to user profile
        this.showUserDetails(user);
    }

    showUserDetails(user) {
        const modal = `
            <div class="modal fade" id="userDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">User Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> ${user.name}</p>
                                    <p><strong>Email:</strong> ${user.email}</p>
                                    <p><strong>Role:</strong> ${this.getRoleBadge(user.role, user.is_admin)}</p>
                                    <p><strong>Status:</strong> ${this.getStatusBadge(user.status || 'active')}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Organization:</strong> ${user.organization || 'N/A'}</p>
                                    <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                                    <p><strong>Joined:</strong> ${this.formatDate(user.created_at)}</p>
                                    <p><strong>Email Verified:</strong> ${user.email_verified_at ? 'Yes' : 'No'}</p>
                                </div>
                            </div>
                            ${user.bio ? `<div class="mt-3"><strong>Bio:</strong><br>${user.bio}</div>` : ''}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="usersManager.editUser(${user.id})">Edit User</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        $('#userDetailsModal').remove();
        
        // Add new modal to body
        $('body').append(modal);
        
        // Show modal
        const userDetailsModal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
        userDetailsModal.show();
    }

    populateForm(user) {
        $('#userId').val(user.id);
        $('#userName').val(user.name);
        $('#userEmail').val(user.email);
        $('#userPassword').val('');
        $('#userPasswordConfirm').val('');
        $('#userRole').val(user.role || 'user');
        $('#userStatus').val(user.status || 'active');
        $('#userOrganization').val(user.organization || '');
        $('#userPhone').val(user.phone || '');
        $('#userBio').val(user.bio || '');
        $('#userIsAdmin').prop('checked', user.is_admin || false);
        $('#userEmailVerified').prop('checked', !!user.email_verified_at);
        
        this.handleRoleChange();
    }

    async saveUser() {
        const formData = this.getFormData();
        const isEdit = this.currentUserId !== null;

        // Validate password confirmation
        if (!this.validatePassword()) {
            return;
        }

        try {
            this.setLoadingState(true);

            const url = isEdit ? `${this.apiBase}/users/${this.currentUserId}` : `${this.apiBase}/users`;
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
                throw new Error(error.message || 'Failed to save user');
            }

            const result = await response.json();
            this.showAlert(`User ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            
            $('#userModal').modal('hide');
            this.loadUsers();
            this.loadStats();
        } catch (error) {
            console.error('Error saving user:', error);
            this.showAlert(error.message || 'Error saving user', 'danger');
        } finally {
            this.setLoadingState(false);
        }
    }

    getFormData() {
        const form = document.getElementById('userForm');
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            if (key === 'is_admin' || key === 'email_verified') {
                data[key] = value === 'on';
            } else if (value !== '') {
                data[key] = value;
            }
        }

        // Handle password (only include if provided)
        if (!data.password) {
            delete data.password;
            delete data.password_confirmation;
        }

        return data;
    }

    validatePassword() {
        const password = $('#userPassword').val();
        const confirmPassword = $('#userPasswordConfirm').val();
        
        if (password && confirmPassword && password !== confirmPassword) {
            $('#userPasswordConfirm').addClass('is-invalid');
            this.showAlert('Passwords do not match', 'danger');
            return false;
        } else {
            $('#userPasswordConfirm').removeClass('is-invalid');
            return true;
        }
    }

    handleRoleChange() {
        const role = $('#userRole').val();
        const isAdminCheckbox = $('#userIsAdmin');
        
        if (role === 'admin') {
            isAdminCheckbox.prop('checked', true);
        } else {
            isAdminCheckbox.prop('checked', false);
        }
    }

    confirmDelete(id, name) {
        this.currentUserId = id;
        $('#deleteUserName').text(name);
        $('#deleteUserModal').modal('show');
    }

    async deleteUser() {
        try {
            this.setDeleteLoadingState(true);

            const response = await fetch(`${this.apiBase}/users/${this.currentUserId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to delete user');
            }

            this.showAlert('User deleted successfully!', 'success');
            $('#deleteUserModal').modal('hide');
            this.loadUsers();
            this.loadStats();
        } catch (error) {
            console.error('Error deleting user:', error);
            this.showAlert(error.message || 'Error deleting user', 'danger');
        } finally {
            this.setDeleteLoadingState(false);
        }
    }

    async loadStats() {
        try {
            const response = await fetch(`${this.apiBase}/users/stats`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                $('#totalUsers').text(stats.data ? stats.data.total : 0);
                $('#activeUsers').text(stats.data ? stats.data.active : 0);
                $('#adminUsers').text(stats.data ? stats.data.admin : 0);
                $('#newUsers').text(stats.data ? stats.data.new_this_month : 0);
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    resetForm() {
        document.getElementById('userForm').reset();
        this.currentUserId = null;
        $('#userId').val('');
        $('#userPassword').val('');
        $('#userPasswordConfirm').val('');
        $('#userRole').val('user');
        $('#userStatus').val('active');
        $('#userIsAdmin').prop('checked', false);
        $('#userEmailVerified').prop('checked', false);
        $('#userPasswordConfirm').removeClass('is-invalid');
    }

    setLoadingState(loading) {
        const btn = $('#saveUserBtn');
        const spinner = btn.find('.spinner-border');
        const text = btn.contents().filter(function() { return this.nodeType === 3; }).first();
        
        if (loading) {
            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            text[0].textContent = ' Saving...';
        } else {
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            text[0].textContent = ' Save User';
        }
    }

    setDeleteLoadingState(loading) {
        const btn = $('#confirmDeleteUserBtn');
        const spinner = btn.find('.spinner-border');
        const text = btn.contents().filter(function() { return this.nodeType === 3; }).first();
        
        if (loading) {
            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            text[0].textContent = ' Deleting...';
        } else {
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            text[0].textContent = ' Delete User';
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
    window.usersManager = new UsersManager();
}); 