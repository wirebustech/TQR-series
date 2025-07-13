@extends('admin.layout')

@section('title', 'Donations Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Donations Management</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDonationModal">
                    <i class="fas fa-plus me-2"></i>Add Donation
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
                                Total Donations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_donations']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-heart fa-2x text-gray-300"></i>
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
                                Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_amount'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['this_month'], 2) }}</div>
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
                                Average Donation</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ $stats['total_donations'] > 0 ? number_format($stats['total_amount'] / $stats['total_donations'], 2) : '0.00' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Donations</h6>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="searchDonations" placeholder="Search donations...">
                <button class="btn btn-sm btn-outline-primary" onclick="exportDonations()">
                    <i class="fas fa-download me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="donationsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                        <tr>
                            <td>
                                @if($donation->is_anonymous)
                                    <span class="text-muted">Anonymous</span>
                                @else
                                    {{ $donation->donor_display_name }}
                                    @if($donation->user)
                                        <br><small class="text-muted">{{ $donation->user->email }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <strong>{{ $donation->formatted_amount }}</strong>
                                @if($donation->message)
                                    <br><small class="text-muted">{{ Str::limit($donation->message, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $donation->payment_method ?: 'N/A' }}</td>
                            <td>
                                @if($donation->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($donation->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($donation->status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($donation->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $donation->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <code>{{ $donation->transaction_id ?: 'N/A' }}</code>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info" onclick="viewDonation({{ $donation->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="editDonation({{ $donation->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteDonation({{ $donation->id }})">
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
                {{ $donations->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Donation Modal -->
<div class="modal fade" id="addDonationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Donation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="donationForm">
                    <div class="mb-3">
                        <label for="donor_name" class="form-label">Donor Name</label>
                        <input type="text" class="form-control" id="donor_name" name="donor_name">
                    </div>
                    <div class="mb-3">
                        <label for="donor_email" class="form-label">Donor Email</label>
                        <input type="email" class="form-control" id="donor_email" name="donor_email">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message (Optional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">
                            <label class="form-check-label" for="is_anonymous">
                                Anonymous Donation
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveDonation()">Save Donation</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Search functionality
document.getElementById('searchDonations').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#donationsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Donation functions
function saveDonation() {
    const form = document.getElementById('donationForm');
    const formData = new FormData(form);
    
    // Here you would typically send to your backend
    alert('Donation saving functionality would be implemented here');
}

function viewDonation(id) {
    // View donation details
    console.log('View donation:', id);
}

function editDonation(id) {
    // Edit donation
    console.log('Edit donation:', id);
}

function deleteDonation(id) {
    if (confirm('Are you sure you want to delete this donation? This action cannot be undone.')) {
        // API call to delete
        console.log('Delete donation:', id);
    }
}

function exportDonations() {
    // Export functionality
    alert('Export functionality would be implemented here');
}
</script>
@endpush 