@extends('admin.layouts.app')

@section('title', 'Research Contributions - TQRS Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-earmark-text"></i> Research Contributions</h2>
    <div>
        <button class="btn btn-outline-primary" id="exportContributionsBtn">
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
                        <h4 class="card-title" id="totalContributionsCount">{{ $contributions->total() }}</h4>
                        <p class="card-text">Total Contributions</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-file-earmark-text fs-1"></i>
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
                        <h4 class="card-title" id="pendingContributionsCount">{{ $contributions->where('status', 'pending')->count() }}</h4>
                        <p class="card-text">Pending Review</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
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
                        <h4 class="card-title" id="approvedContributionsCount">{{ $contributions->where('status', 'approved')->count() }}</h4>
                        <p class="card-text">Approved</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title" id="rejectedContributionsCount">{{ $contributions->where('status', 'rejected')->count() }}</h4>
                    <p class="card-text">Rejected</p>
                </div>
                <div class="align-self-center">
                    <i class="bi bi-x-circle fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form id="contributionFiltersForm" method="GET" action="{{ route('admin.contributions') }}">
            <div class="row">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Title, author, keywords...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        <option value="research_paper" {{ request('type') == 'research_paper' ? 'selected' : '' }}>Research Paper</option>
                        <option value="case_study" {{ request('type') == 'case_study' ? 'selected' : '' }}>Case Study</option>
                        <option value="methodology" {{ request('type') == 'methodology' ? 'selected' : '' }}>Methodology</option>
                        <option value="review" {{ request('type') == 'review' ? 'selected' : '' }}>Review</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.contributions') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Contributions Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Contributions List</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-success btn-sm" id="bulkApproveBtn" disabled>
                    <i class="bi bi-check-circle"></i> Approve Selected
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" id="bulkRejectBtn" disabled>
                    <i class="bi bi-x-circle"></i> Reject Selected
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm" id="bulkDeleteBtn" disabled>
                    <i class="bi bi-trash"></i> Delete Selected
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
                        <th>Contribution</th>
                        <th>Author</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $contribution)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input contribution-checkbox" value="{{ $contribution->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-file-earmark-text text-muted"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">{{ $contribution->title }}</div>
                                    <small class="text-muted">{{ Str::limit($contribution->abstract, 60) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $contribution->user->name }}</div>
                                <small class="text-muted">{{ $contribution->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            @php
                                $typeBadges = [
                                    'research_paper' => 'primary',
                                    'case_study' => 'success',
                                    'methodology' => 'info',
                                    'review' => 'warning'
                                ];
                            @endphp
                            <span class="badge bg-{{ $typeBadges[$contribution->type] ?? 'secondary' }}">
                                {{ ucwords(str_replace('_', ' ', $contribution->type)) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusBadges = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'published' => 'info'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusBadges[$contribution->status] ?? 'secondary' }}">
                                {{ ucfirst($contribution->status) }}
                            </span>
                        </td>
                        <td>{{ $contribution->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary viewContributionBtn" 
                                        data-contribution="{{ $contribution->id }}" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($contribution->status === 'pending')
                                    <button type="button" class="btn btn-outline-success approveContributionBtn" 
                                            data-contribution="{{ $contribution->id }}" title="Approve">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger rejectContributionBtn" 
                                            data-contribution="{{ $contribution->id }}" title="Reject">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @endif
                                @if($contribution->status === 'approved')
                                    <button type="button" class="btn btn-outline-info publishContributionBtn" 
                                            data-contribution="{{ $contribution->id }}" title="Publish">
                                        <i class="bi bi-globe"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-warning editContributionBtn" 
                                        data-contribution="{{ $contribution->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger deleteContributionBtn" 
                                        data-contribution="{{ $contribution->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-file-earmark-text fs-1"></i>
                            <p class="mt-2">No contributions found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($contributions->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $contributions->firstItem() }} to {{ $contributions->lastItem() }} of {{ $contributions->total() }} contributions
            </div>
            <div>
                {{ $contributions->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- View Contribution Modal -->
<div class="modal fade" id="viewContributionModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contribution Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contributionDetailsContent">
                <!-- Contribution details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div id="contributionActionButtons">
                    <!-- Action buttons will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalTitle">Review Contribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reviewForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="review_decision" class="form-label">Decision *</label>
                        <select class="form-select" id="review_decision" name="decision" required>
                            <option value="">Select decision</option>
                            <option value="approve">Approve</option>
                            <option value="reject">Reject</option>
                            <option value="request_revision">Request Revision</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="review_comments" class="form-label">Review Comments *</label>
                        <textarea class="form-control" id="review_comments" name="comments" rows="4" required 
                                  placeholder="Provide detailed feedback and comments..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="review_score" class="form-label">Quality Score (1-10)</label>
                        <input type="number" class="form-control" id="review_score" name="score" 
                               min="1" max="10" placeholder="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
class ContributionsManager {
    constructor() {
        this.currentContributionId = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateBulkActions();
    }

    bindEvents() {
        // Select all checkbox
        $('#selectAll').on('change', (e) => {
            $('.contribution-checkbox').prop('checked', e.target.checked);
            this.updateBulkActions();
        });

        // Individual checkboxes
        $(document).on('change', '.contribution-checkbox', () => {
            this.updateBulkActions();
        });

        // View contribution buttons
        $(document).on('click', '.viewContributionBtn', (e) => {
            const contributionId = $(e.currentTarget).data('contribution');
            this.viewContribution(contributionId);
        });

        // Approve contribution buttons
        $(document).on('click', '.approveContributionBtn', (e) => {
            const contributionId = $(e.currentTarget).data('contribution');
            this.reviewContribution(contributionId, 'approve');
        });

        // Reject contribution buttons
        $(document).on('click', '.rejectContributionBtn', (e) => {
            const contributionId = $(e.currentTarget).data('contribution');
            this.reviewContribution(contributionId, 'reject');
        });

        // Publish contribution buttons
        $(document).on('click', '.publishContributionBtn', (e) => {
            const contributionId = $(e.currentTarget).data('contribution');
            this.publishContribution(contributionId);
        });

        // Edit contribution buttons
        $(document).on('click', '.editContributionBtn', (e) => {
            const contributionId = $(e.currentTarget).data('contribution');
            this.editContribution(contributionId);
        });

        // Delete contribution buttons
        $(document).on('click', '.deleteContributionBtn', (e) => {
            const contributionId = $(e.currentTarget).data('contribution');
            this.deleteContribution(contributionId);
        });

        // Bulk actions
        $('#bulkApproveBtn').on('click', () => this.bulkApprove());
        $('#bulkRejectBtn').on('click', () => this.bulkReject());
        $('#bulkDeleteBtn').on('click', () => this.bulkDelete());

        // Export contributions
        $('#exportContributionsBtn').on('click', () => this.exportContributions());

        // Review form submission
        $('#reviewForm').on('submit', (e) => {
            e.preventDefault();
            this.submitReview();
        });
    }

    updateBulkActions() {
        const checkedCount = $('.contribution-checkbox:checked').length;
        $('#bulkApproveBtn, #bulkRejectBtn, #bulkDeleteBtn').prop('disabled', checkedCount === 0);
    }

    async viewContribution(contributionId) {
        try {
            const response = await fetch(`/api/research-contributions/${contributionId}`, {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            
            if (response.ok) {
                const contribution = await response.json();
                this.populateContributionDetails(contribution);
                $('#viewContributionModal').modal('show');
            } else {
                this.showAlert('Error loading contribution data', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error loading contribution data', 'danger');
        }
    }

    populateContributionDetails(contribution) {
        const content = `
            <div class="row">
                <div class="col-md-8">
                    <h4>${contribution.title}</h4>
                    <p class="text-muted mb-3">
                        <strong>Type:</strong> 
                        <span class="badge bg-${this.getTypeBadge(contribution.type)}">
                            ${contribution.type.replace('_', ' ').toUpperCase()}
                        </span>
                        <strong class="ms-3">Status:</strong> 
                        <span class="badge bg-${this.getStatusBadge(contribution.status)}">
                            ${contribution.status.toUpperCase()}
                        </span>
                    </p>
                    <div class="mb-3">
                        <h6>Abstract</h6>
                        <p>${contribution.abstract}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Keywords</h6>
                        <p>${contribution.keywords || 'No keywords provided'}</p>
                    </div>
                    ${contribution.content ? `
                    <div class="mb-3">
                        <h6>Content</h6>
                        <div class="border rounded p-3 bg-light">
                            ${contribution.content}
                        </div>
                    </div>
                    ` : ''}
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6>Author Information</h6>
                            <p><strong>Name:</strong> ${contribution.user.name}</p>
                            <p><strong>Email:</strong> ${contribution.user.email}</p>
                            <p><strong>Organization:</strong> ${contribution.user.organization || 'N/A'}</p>
                            <p><strong>Submitted:</strong> ${new Date(contribution.created_at).toLocaleDateString()}</p>
                            ${contribution.updated_at !== contribution.created_at ? 
                                `<p><strong>Last Updated:</strong> ${new Date(contribution.updated_at).toLocaleDateString()}</p>` : ''}
                        </div>
                    </div>
                    ${contribution.file_url ? `
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6>Attached File</h6>
                            <a href="${contribution.file_url}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-download"></i> Download File
                            </a>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
        $('#contributionDetailsContent').html(content);

        // Add action buttons based on status
        let actionButtons = '';
        if (contribution.status === 'pending') {
            actionButtons = `
                <button type="button" class="btn btn-success approveContributionBtn" data-contribution="${contribution.id}">
                    <i class="bi bi-check-circle"></i> Approve
                </button>
                <button type="button" class="btn btn-danger rejectContributionBtn" data-contribution="${contribution.id}">
                    <i class="bi bi-x-circle"></i> Reject
                </button>
            `;
        } else if (contribution.status === 'approved') {
            actionButtons = `
                <button type="button" class="btn btn-info publishContributionBtn" data-contribution="${contribution.id}">
                    <i class="bi bi-globe"></i> Publish
                </button>
            `;
        }
        $('#contributionActionButtons').html(actionButtons);
    }

    getTypeBadge(type) {
        const badges = {
            'research_paper': 'primary',
            'case_study': 'success',
            'methodology': 'info',
            'review': 'warning'
        };
        return badges[type] || 'secondary';
    }

    getStatusBadge(status) {
        const badges = {
            'pending': 'warning',
            'approved': 'success',
            'rejected': 'danger',
            'published': 'info'
        };
        return badges[status] || 'secondary';
    }

    reviewContribution(contributionId, action) {
        this.currentContributionId = contributionId;
        $('#review_decision').val(action);
        $('#reviewModalTitle').text(action === 'approve' ? 'Approve Contribution' : 'Reject Contribution');
        $('#reviewModal').modal('show');
    }

    async submitReview() {
        const form = $('#reviewForm')[0];
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');

        try {
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            const response = await fetch(`/api/research-contributions/${this.currentContributionId}/review`, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                $('#reviewModal').modal('hide');
                $('#viewContributionModal').modal('hide');
                this.showAlert('Review submitted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                const data = await response.json();
                this.showAlert(data.message || 'Error submitting review', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error submitting review', 'danger');
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    }

    async publishContribution(contributionId) {
        if (!confirm('Are you sure you want to publish this contribution?')) {
            return;
        }

        try {
            const response = await fetch(`/api/research-contributions/${contributionId}/publish`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.showAlert('Contribution published successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error publishing contribution', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error publishing contribution', 'danger');
        }
    }

    async editContribution(contributionId) {
        // Redirect to edit page or show edit modal
        window.location.href = `/admin/contributions/${contributionId}/edit`;
    }

    async deleteContribution(contributionId) {
        if (!confirm('Are you sure you want to delete this contribution? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/api/research-contributions/${contributionId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                this.showAlert('Contribution deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error deleting contribution', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error deleting contribution', 'danger');
        }
    }

    async bulkApprove() {
        const selectedIds = $('.contribution-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        if (!confirm(`Are you sure you want to approve ${selectedIds.length} selected contributions?`)) {
            return;
        }

        try {
            const response = await fetch('/api/research-contributions/bulk-action', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'approve',
                    ids: selectedIds
                })
            });

            if (response.ok) {
                this.showAlert('Contributions approved successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error approving contributions', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error approving contributions', 'danger');
        }
    }

    async bulkReject() {
        const selectedIds = $('.contribution-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        if (!confirm(`Are you sure you want to reject ${selectedIds.length} selected contributions?`)) {
            return;
        }

        try {
            const response = await fetch('/api/research-contributions/bulk-action', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'reject',
                    ids: selectedIds
                })
            });

            if (response.ok) {
                this.showAlert('Contributions rejected successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error rejecting contributions', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error rejecting contributions', 'danger');
        }
    }

    async bulkDelete() {
        const selectedIds = $('.contribution-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected contributions?`)) {
            return;
        }

        try {
            const response = await fetch('/api/research-contributions/bulk-action', {
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
                this.showAlert('Contributions deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('Error deleting contributions', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error deleting contributions', 'danger');
        }
    }

    async exportContributions() {
        try {
            const response = await fetch('/api/research-contributions/export', {
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `contributions_export_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                this.showAlert('Error exporting contributions', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error exporting contributions', 'danger');
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
    window.contributionsManager = new ContributionsManager();
});
</script>
@endpush 