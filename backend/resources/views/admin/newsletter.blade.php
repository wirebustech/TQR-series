@extends('admin.layout')

@section('title', 'Newsletter Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Newsletter Management</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendNewsletterModal">
                    <i class="fas fa-paper-plane me-2"></i>Send Newsletter
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Subscribers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_subscribers']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Subscribers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_subscribers']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                New This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['new_this_month']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unsubscribed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_subscribers'] - $stats['active_subscribers']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscribers Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Newsletter Subscribers</h6>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="searchSubscribers" placeholder="Search subscribers...">
                <button class="btn btn-sm btn-outline-primary" onclick="exportSubscribers()">
                    <i class="fas fa-download me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="subscribersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Subscribed</th>
                            <th>Source</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->email }}</td>
                            <td>{{ $subscription->full_name ?: 'N/A' }}</td>
                            <td>
                                @if($subscription->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $subscription->subscribed_at?->format('M d, Y') ?: 'N/A' }}</td>
                            <td>{{ $subscription->source ?: 'Website' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($subscription->is_active)
                                        <button class="btn btn-outline-warning" onclick="unsubscribeUser({{ $subscription->id }})">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-outline-success" onclick="resubscribeUser({{ $subscription->id }})">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-danger" onclick="deleteSubscriber({{ $subscription->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Send Newsletter Modal -->
<div class="modal fade" id="sendNewsletterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Newsletter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newsletterForm">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="recipients" class="form-label">Recipients</label>
                        <select class="form-select" id="recipients" name="recipients">
                            <option value="all">All Active Subscribers</option>
                            <option value="new">New Subscribers (This Month)</option>
                            <option value="inactive">Inactive Subscribers</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendNewsletter()">Send Newsletter</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Search functionality
document.getElementById('searchSubscribers').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#subscribersTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Newsletter functions
function sendNewsletter() {
    const form = document.getElementById('newsletterForm');
    const formData = new FormData(form);
    
    // Here you would typically send to your backend
    alert('Newsletter sending functionality would be implemented here');
}

function unsubscribeUser(id) {
    if (confirm('Are you sure you want to unsubscribe this user?')) {
        // API call to unsubscribe
        console.log('Unsubscribe user:', id);
    }
}

function resubscribeUser(id) {
    if (confirm('Are you sure you want to resubscribe this user?')) {
        // API call to resubscribe
        console.log('Resubscribe user:', id);
    }
}

function deleteSubscriber(id) {
    if (confirm('Are you sure you want to delete this subscriber? This action cannot be undone.')) {
        // API call to delete
        console.log('Delete subscriber:', id);
    }
}

function exportSubscribers() {
    // Export functionality
    alert('Export functionality would be implemented here');
}
</script>
@endpush 