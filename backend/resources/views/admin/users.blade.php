@extends('admin.layouts.app')

@section('title', 'Users Management - TQRS Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Users Management</h2>
    <div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> Add User
        </button>
        <button class="btn btn-outline-primary" id="exportUsersBtn">
            <i class="bi bi-download"></i> Export
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title" id="totalUsersCount">{{ $users->total() }}</h4>
                        <p class="card-text">Total Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title" id="activeUsersCount">{{ $users->where('status', 'active')->count() }}</h4>
                        <p class="card-text">Active Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title" id="verifiedUsersCount">{{ $users->whereNotNull('email_verified_at')->count() }}</h4>
                        <p class="card-text">Verified Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title" id="adminUsersCount">{{ $users->where('role', 'admin')->count() }}</h4>
                        <p class="card-text">Admin Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-shield-lock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form id="userFiltersForm" method="GET" action="{{ route('admin.users') }}">
            <div class="row">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Name, email, organization...">
                </div>
                <div class="col-md-2">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">All Roles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="researcher" {{ request('role') == 'researcher' ? 'selected' : '' }}>Researcher</option>
                        <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="verified" class="form-label">Verification</label>
                    <select class="form-select" id="verified" name="verified">
                        <option value="">All</option>
                        <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Users List</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-danger btn-sm" id="bulkDeleteBtn" disabled>
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm" id="bulkSuspendBtn" disabled>
                    <i class="bi bi-pause-circle"></i> Suspend Selected
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Organization</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-person-circle fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    @if($user->email_verified_at)
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> Verified
                                        </small>
                                    @else
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> Unverified
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role_badge }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status_badge }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->organization ?: '-' }}</td>
                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary editUserBtn" 
                                        data-user="{{ $user->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info viewUserBtn" 
                                        data-user="{{ $user->id }}" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($user->status === 'active')
                                    <button type="button" class="btn btn-outline-warning suspendUserBtn" 
                                            data-user="{{ $user->id }}" title="Suspend">
                                        <i class="bi bi-pause-circle"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-success activateUserBtn" 
                                            data-user="{{ $user->id }}" title="Activate">
                                        <i class="bi bi-play-circle"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-danger deleteUserBtn" 
                                        data-user="{{ $user->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-people fs-1"></i>
                            <p class="mt-2">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
            </div>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-muted">Leave blank to keep current password (when editing)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="user">User</option>
                                    <option value="researcher">Researcher</option>
                                    <option value="moderator">Moderator</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="organization" class="form-label">Organization</label>
                                <input type="text" class="form-control" id="organization" name="organization">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified">
                        <label class="form-check-label" for="email_verified">
                            Email Verified
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
class UsersManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateBulkActions();
    }

    bindEvents() {
        // Select all checkbox
        $('#selectAll').on('change', (e) => {
            $('.user-checkbox').prop('checked', e.target.checked);
            this.updateBulkActions();
        });

        // Individual checkboxes
        $(document).on('change', '.user-checkbox', () => {
            this.updateBulkActions();
        });

        // Add user button
        $('[data-bs-target="#addUserModal"]').on('click', () => {
            this.resetForm();
        });

        // Edit user buttons
        $(document).on('click', '.editUserBtn', (e) => {
            const userId = $(e.currentTarget).data('user');
            this.editUser(userId);
        });

        // View user buttons
        $(document).on('click', '.viewUserBtn', (e) => {
            const userId = $(e.currentTarget).data('user');
            this.viewUser(userId);
        });

        // Delete user buttons
        $(document).on('click', '.deleteUserBtn', (e) => {
            const userId = $(e.currentTarget).data('user');
            this.deleteUser(userId);
        });

        // Suspend/Activate user buttons
        $(document).on('click', '.suspendUserBtn, .activateUserBtn', (e) => {
            const userId = $(e.currentTarget).data('user');
            const action = $(e.currentTarget).hasClass('suspendUserBtn') ? 'suspend' : 'activate';
            this.toggleUserStatus(userId, action);
        });

        // Bulk actions
        $('#bulkDeleteBtn').on('click', () => this.bulkDelete());
        $('#bulkSuspendBtn').on('click', () => this.bulkSuspend());

        // Export users
        $('#exportUsersBtn').on('click', () => this.exportUsers());

        // Form submission
        $('#userForm').on('submit', (e) => {
            e.preventDefault();
            this.saveUser();
        });
    }

    updateBulkActions() {
        const checkedCount = $('.user-checkbox:checked').length;
        $('#bulkDeleteBtn, #bulkSuspendBtn').prop('disabled', checkedCount === 0);
    }

    resetForm() {
        $('#userForm')[0].reset();
        $('#userForm').attr('action', '{{ route("admin.users") }}');
        $('#userModalTitle').text('Add New User');
        $('#password, #password_confirmation').prop('required', true);
    }

    async editUser(userId) {
        try {
            const response = await fetch(`/api/users/${userId}`, {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            
            if (response.ok) {
                const user = await response.json();
                this.populateForm(user);
                $('#userForm').attr('action', `/api/users/${userId}`);
                $('#userModalTitle').text('Edit User');
                $('#password, #password_confirmation').prop('required', false);
                $('#addUserModal').modal('show');
            } else {
                this.showAlert('Error loading user data', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error loading user data', 'danger');
        }
    }

    populateForm(user) {
        $('#name').val(user.name);
        $('#email').val(user.email);
        $('#role').val(user.role);
        $('#status').val(user.status);
        $('#organization').val(user.organization || '');
        $('#phone').val(user.phone || '');
        $('#bio').val(user.bio || '');
        $('#email_verified').prop('checked', user.email_verified_at !== null);
    }

    async viewUser(userId) {
        try {
            const response = await fetch(`/api/users/${userId}`, {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            
            if (response.ok) {
                const user = await response.json();
                this.populateUserDetails(user);
                $('#viewUserModal').modal('show');
            } else {
                this.showAlert('Error loading user data', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error loading user data', 'danger');
        }
    }

    populateUserDetails(user) {
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Basic Information</h6>
                    <p><strong>Name:</strong> ${user.name}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Role:</strong> <span class="badge bg-${user.role_badge}">${user.role}</span></p>
                    <p><strong>Status:</strong> <span class="badge bg-${user.status_badge}">${user.status}</span></p>
                </div>
                <div class="col-md-6">
                    <h6>Additional Information</h6>
                    <p><strong>Organization:</strong> ${user.organization || 'N/A'}</p>
                    <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                    <p><strong>Email Verified:</strong> ${user.email_verified_at ? 'Yes' : 'No'}</p>
                    <p><strong>Joined:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                </div>
            </div>
            ${user.bio ? `<div class="mt-3"><h6>Bio</h6><p>${user.bio}</p></div>` : ''}
        `;
        $('#userDetailsContent').html(content);
    }

    async saveUser() {
        const form = $('#userForm')[0];
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');

        try {
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                $('#addUserModal').modal('hide');
                this.showAlert('User saved successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                const data = await response.json();
                this.showAlert(data.message || 'Error saving user', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error saving user', 'danger');
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    }

    async deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/api/users/${userId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                this.showAlert('User deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error deleting user', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error deleting user', 'danger');
        }
    }

    async toggleUserStatus(userId, action) {
        const actionText = action === 'suspend' ? 'suspend' : 'activate';
        if (!confirm(`Are you sure you want to ${actionText} this user?`)) {
            return;
        }

        try {
            const response = await fetch(`/api/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action })
            });

            if (response.ok) {
                this.showAlert(`User ${actionText}ed successfully`, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert(`Error ${actionText}ing user`, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert(`Error ${actionText}ing user`, 'danger');
        }
    }

    async bulkDelete() {
        const selectedIds = $('.user-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected users?`)) {
            return;
        }

        try {
            const response = await fetch('/api/users/bulk-action', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'delete',
                    ids: selectedIds
                })
            });

            if (response.ok) {
                this.showAlert('Users deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error deleting users', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error deleting users', 'danger');
        }
    }

    async bulkSuspend() {
        const selectedIds = $('.user-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        if (!confirm(`Are you sure you want to suspend ${selectedIds.length} selected users?`)) {
            return;
        }

        try {
            const response = await fetch('/api/users/bulk-action', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'suspend',
                    ids: selectedIds
                })
            });

            if (response.ok) {
                this.showAlert('Users suspended successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error suspending users', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error suspending users', 'danger');
        }
    }

    async exportUsers() {
        try {
            const response = await fetch('/api/users/export', {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `users_export_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                this.showAlert('Error exporting users', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error exporting users', 'danger');
        }
    }

    showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.alert').remove();
        $('.container-fluid').prepend(alertHtml);
        
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
}

// Initialize when document is ready
$(document).ready(function() {
    window.usersManager = new UsersManager();
});
</script>
@endpush 