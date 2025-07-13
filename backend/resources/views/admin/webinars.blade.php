@extends('admin.layouts.app')

@section('title', 'Webinars Management - TQRS Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-camera-video"></i> Webinars Management</h2>
    <div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addWebinarModal">
            <i class="bi bi-plus-circle"></i> Add Webinar
        </button>
        <button class="btn btn-outline-primary" id="exportWebinarsBtn">
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
                        <h4 class="card-title" id="totalWebinarsCount">{{ $webinars->total() }}</h4>
                        <p class="card-text">Total Webinars</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-camera-video fs-1"></i>
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
                        <h4 class="card-title" id="upcomingWebinarsCount">{{ $webinars->where('start_time', '>', now())->count() }}</h4>
                        <p class="card-text">Upcoming</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-event fs-1"></i>
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
                        <h4 class="card-title" id="liveWebinarsCount">{{ $webinars->where('status', 'live')->count() }}</h4>
                        <p class="card-text">Live Now</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-broadcast fs-1"></i>
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
                        <h4 class="card-title" id="totalRegistrationsCount">{{ $webinars->sum(function($webinar) { return $webinar->registrations->count(); }) }}</h4>
                        <p class="card-text">Total Registrations</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form id="webinarFiltersForm" method="GET" action="{{ route('admin.webinars') }}">
            <div class="row">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Title, description...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.webinars') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Webinars Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Webinars List</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-danger btn-sm" id="bulkDeleteBtn" disabled>
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm" id="bulkPublishBtn" disabled>
                    <i class="bi bi-check-circle"></i> Publish Selected
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
                        <th>Webinar</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Registrations</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($webinars as $webinar)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input webinar-checkbox" value="{{ $webinar->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @if($webinar->thumbnail)
                                        <img src="{{ $webinar->thumbnail }}" alt="Thumbnail" class="rounded" width="50" height="50">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-camera-video text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">{{ $webinar->title }}</div>
                                    <small class="text-muted">{{ Str::limit($webinar->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $webinar->start_time->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $webinar->start_time->format('g:i A') }} - {{ $webinar->end_time->format('g:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusBadges = [
                                    'draft' => 'secondary',
                                    'published' => 'success',
                                    'live' => 'warning',
                                    'completed' => 'info',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusBadges[$webinar->status] ?? 'secondary' }}">
                                {{ ucfirst($webinar->status) }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $webinar->registrations->count() }}</div>
                                <small class="text-muted">{{ $webinar->max_participants ? $webinar->max_participants . ' max' : 'No limit' }}</small>
                            </div>
                        </td>
                        <td>{{ $webinar->duration }} minutes</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary editWebinarBtn" 
                                        data-webinar="{{ $webinar->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info viewWebinarBtn" 
                                        data-webinar="{{ $webinar->id }}" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success registrationsBtn" 
                                        data-webinar="{{ $webinar->id }}" title="Registrations">
                                    <i class="bi bi-people"></i>
                                </button>
                                @if($webinar->status === 'draft')
                                    <button type="button" class="btn btn-outline-warning publishWebinarBtn" 
                                            data-webinar="{{ $webinar->id }}" title="Publish">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-danger deleteWebinarBtn" 
                                        data-webinar="{{ $webinar->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-camera-video fs-1"></i>
                            <p class="mt-2">No webinars found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($webinars->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $webinars->firstItem() }} to {{ $webinars->lastItem() }} of {{ $webinars->total() }} webinars
            </div>
            <div>
                {{ $webinars->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Add/Edit Webinar Modal -->
<div class="modal fade" id="addWebinarModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="webinarModalTitle">Add New Webinar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="webinarForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="live">Live</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Date & Time *</label>
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Date & Time *</label>
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration (minutes) *</label>
                                <input type="number" class="form-control" id="duration" name="duration" min="15" max="480" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_participants" class="form-label">Max Participants</label>
                                <input type="number" class="form-control" id="max_participants" name="max_participants" min="1">
                                <small class="text-muted">Leave empty for unlimited</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="meeting_url" class="form-label">Meeting URL</label>
                                <input type="url" class="form-control" id="meeting_url" name="meeting_url" placeholder="https://zoom.us/j/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="meeting_id" class="form-label">Meeting ID</label>
                                <input type="text" class="form-control" id="meeting_id" name="meeting_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="meeting_password" class="form-label">Meeting Password</label>
                                <input type="text" class="form-control" id="meeting_password" name="meeting_password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="thumbnail" class="form-label">Thumbnail URL</label>
                                <input type="url" class="form-control" id="thumbnail" name="thumbnail">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="research, methodology, qualitative">
                        <small class="text-muted">Comma-separated tags</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                        <label class="form-check-label" for="is_featured">
                            Featured Webinar
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Save Webinar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Webinar Modal -->
<div class="modal fade" id="viewWebinarModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Webinar Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="webinarDetailsContent">
                <!-- Webinar details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Registrations Modal -->
<div class="modal fade" id="registrationsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Webinar Registrations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="registrationsContent">
                <!-- Registrations will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
class WebinarsManager {
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
            $('.webinar-checkbox').prop('checked', e.target.checked);
            this.updateBulkActions();
        });

        // Individual checkboxes
        $(document).on('change', '.webinar-checkbox', () => {
            this.updateBulkActions();
        });

        // Add webinar button
        $('[data-bs-target="#addWebinarModal"]').on('click', () => {
            this.resetForm();
        });

        // Edit webinar buttons
        $(document).on('click', '.editWebinarBtn', (e) => {
            const webinarId = $(e.currentTarget).data('webinar');
            this.editWebinar(webinarId);
        });

        // View webinar buttons
        $(document).on('click', '.viewWebinarBtn', (e) => {
            const webinarId = $(e.currentTarget).data('webinar');
            this.viewWebinar(webinarId);
        });

        // Registrations buttons
        $(document).on('click', '.registrationsBtn', (e) => {
            const webinarId = $(e.currentTarget).data('webinar');
            this.viewRegistrations(webinarId);
        });

        // Delete webinar buttons
        $(document).on('click', '.deleteWebinarBtn', (e) => {
            const webinarId = $(e.currentTarget).data('webinar');
            this.deleteWebinar(webinarId);
        });

        // Publish webinar buttons
        $(document).on('click', '.publishWebinarBtn', (e) => {
            const webinarId = $(e.currentTarget).data('webinar');
            this.publishWebinar(webinarId);
        });

        // Bulk actions
        $('#bulkDeleteBtn').on('click', () => this.bulkDelete());
        $('#bulkPublishBtn').on('click', () => this.bulkPublish());

        // Export webinars
        $('#exportWebinarsBtn').on('click', () => this.exportWebinars());

        // Form submission
        $('#webinarForm').on('submit', (e) => {
            e.preventDefault();
            this.saveWebinar();
        });

        // Auto-calculate duration
        $('#start_time, #end_time').on('change', () => {
            this.calculateDuration();
        });
    }

    updateBulkActions() {
        const checkedCount = $('.webinar-checkbox:checked').length;
        $('#bulkDeleteBtn, #bulkPublishBtn').prop('disabled', checkedCount === 0);
    }

    resetForm() {
        $('#webinarForm')[0].reset();
        $('#webinarForm').attr('action', '{{ route("admin.webinars") }}');
        $('#webinarModalTitle').text('Add New Webinar');
    }

    calculateDuration() {
        const startTime = $('#start_time').val();
        const endTime = $('#end_time').val();
        
        if (startTime && endTime) {
            const start = new Date(startTime);
            const end = new Date(endTime);
            const duration = Math.round((end - start) / (1000 * 60)); // minutes
            
            if (duration > 0) {
                $('#duration').val(duration);
            }
        }
    }

    async editWebinar(webinarId) {
        try {
            const response = await fetch(`/api/webinars/${webinarId}`, {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            
            if (response.ok) {
                const webinar = await response.json();
                this.populateForm(webinar);
                $('#webinarForm').attr('action', `/api/webinars/${webinarId}`);
                $('#webinarModalTitle').text('Edit Webinar');
                $('#addWebinarModal').modal('show');
            } else {
                this.showAlert('Error loading webinar data', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error loading webinar data', 'danger');
        }
    }

    populateForm(webinar) {
        $('#title').val(webinar.title);
        $('#description').val(webinar.description);
        $('#status').val(webinar.status);
        $('#start_time').val(webinar.start_time.replace(' ', 'T'));
        $('#end_time').val(webinar.end_time.replace(' ', 'T'));
        $('#duration').val(webinar.duration);
        $('#max_participants').val(webinar.max_participants || '');
        $('#meeting_url').val(webinar.meeting_url || '');
        $('#meeting_id').val(webinar.meeting_id || '');
        $('#meeting_password').val(webinar.meeting_password || '');
        $('#thumbnail').val(webinar.thumbnail || '');
        $('#tags').val(webinar.tags || '');
        $('#is_featured').prop('checked', webinar.is_featured);
    }

    async viewWebinar(webinarId) {
        try {
            const response = await fetch(`/api/webinars/${webinarId}`, {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            
            if (response.ok) {
                const webinar = await response.json();
                this.populateWebinarDetails(webinar);
                $('#viewWebinarModal').modal('show');
            } else {
                this.showAlert('Error loading webinar data', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error loading webinar data', 'danger');
        }
    }

    populateWebinarDetails(webinar) {
        const content = `
            <div class="row">
                <div class="col-md-8">
                    <h4>${webinar.title}</h4>
                    <p class="text-muted">${webinar.description}</p>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6>Details</h6>
                            <p><strong>Status:</strong> <span class="badge bg-${this.getStatusBadge(webinar.status)}">${webinar.status}</span></p>
                            <p><strong>Date:</strong> ${new Date(webinar.start_time).toLocaleDateString()}</p>
                            <p><strong>Time:</strong> ${new Date(webinar.start_time).toLocaleTimeString()} - ${new Date(webinar.end_time).toLocaleTimeString()}</p>
                            <p><strong>Duration:</strong> ${webinar.duration} minutes</p>
                            <p><strong>Max Participants:</strong> ${webinar.max_participants || 'Unlimited'}</p>
                        </div>
                    </div>
                </div>
            </div>
            ${webinar.meeting_url ? `
            <div class="mt-3">
                <h6>Meeting Information</h6>
                <p><strong>URL:</strong> <a href="${webinar.meeting_url}" target="_blank">${webinar.meeting_url}</a></p>
                ${webinar.meeting_id ? `<p><strong>Meeting ID:</strong> ${webinar.meeting_id}</p>` : ''}
                ${webinar.meeting_password ? `<p><strong>Password:</strong> ${webinar.meeting_password}</p>` : ''}
            </div>
            ` : ''}
        `;
        $('#webinarDetailsContent').html(content);
    }

    getStatusBadge(status) {
        const badges = {
            'draft': 'secondary',
            'published': 'success',
            'live': 'warning',
            'completed': 'info',
            'cancelled': 'danger'
        };
        return badges[status] || 'secondary';
    }

    async viewRegistrations(webinarId) {
        try {
            const response = await fetch(`/api/webinars/${webinarId}/registrations`, {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.populateRegistrations(data.data, data.webinar);
                $('#registrationsModal').modal('show');
            } else {
                this.showAlert('Error loading registrations', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error loading registrations', 'danger');
        }
    }

    populateRegistrations(registrations, webinar) {
        const content = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Registrations for: ${webinar.title}</h6>
                <span class="badge bg-primary">${registrations.length} registrations</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Organization</th>
                            <th>Registered</th>
                            <th>Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${registrations.map(reg => `
                            <tr>
                                <td>${reg.user.name}</td>
                                <td>${reg.user.email}</td>
                                <td>${reg.user.organization || '-'}</td>
                                <td>${new Date(reg.created_at).toLocaleDateString()}</td>
                                <td>
                                    <span class="badge bg-${reg.attended ? 'success' : 'secondary'}">
                                        ${reg.attended ? 'Yes' : 'No'}
                                    </span>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        $('#registrationsContent').html(content);
    }

    async saveWebinar() {
        const form = $('#webinarForm')[0];
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
                $('#addWebinarModal').modal('hide');
                this.showAlert('Webinar saved successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                const data = await response.json();
                this.showAlert(data.message || 'Error saving webinar', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error saving webinar', 'danger');
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    }

    async deleteWebinar(webinarId) {
        if (!confirm('Are you sure you want to delete this webinar? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/api/webinars/${webinarId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                this.showAlert('Webinar deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error deleting webinar', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error deleting webinar', 'danger');
        }
    }

    async publishWebinar(webinarId) {
        if (!confirm('Are you sure you want to publish this webinar?')) {
            return;
        }

        try {
            const response = await fetch(`/api/webinars/${webinarId}/publish`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.showAlert('Webinar published successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error publishing webinar', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error publishing webinar', 'danger');
        }
    }

    async bulkDelete() {
        const selectedIds = $('.webinar-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected webinars?`)) {
            return;
        }

        try {
            const response = await fetch('/api/webinars/bulk-action', {
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
                this.showAlert('Webinars deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error deleting webinars', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error deleting webinars', 'danger');
        }
    }

    async bulkPublish() {
        const selectedIds = $('.webinar-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        if (!confirm(`Are you sure you want to publish ${selectedIds.length} selected webinars?`)) {
            return;
        }

        try {
            const response = await fetch('/api/webinars/bulk-action', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'publish',
                    ids: selectedIds
                })
            });

            if (response.ok) {
                this.showAlert('Webinars published successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error publishing webinars', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error publishing webinars', 'danger');
        }
    }

    async exportWebinars() {
        try {
            const response = await fetch('/api/webinars/export', {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `webinars_export_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                this.showAlert('Error exporting webinars', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error exporting webinars', 'danger');
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
    window.webinarsManager = new WebinarsManager();
});
</script>
@endpush 