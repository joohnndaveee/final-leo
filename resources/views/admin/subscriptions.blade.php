<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seller Subscriptions | Admin Panel</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
   <style>
       .subscription-stats {
           display: grid;
           grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
           gap: 2rem;
           margin-bottom: 3rem;
       }
       .stat-card {
           background: var(--white);
           padding: 2rem;
           border-radius: 0.5rem;
           box-shadow: var(--box-shadow);
           text-align: center;
       }
       .stat-card h3 {
           font-size: 3rem;
           color: var(--black);
           margin-bottom: 0.5rem;
       }
       .stat-card p {
           font-size: 1.6rem;
           color: var(--light-color);
       }
       .stat-card.active { border-left: 5px solid #10b981; }
       .stat-card.expired { border-left: 5px solid #ef4444; }
       .stat-card.suspended { border-left: 5px solid #f59e0b; }
       .stat-card.expiring { border-left: 5px solid #f97316; }
       
       .filters {
           background: var(--white);
           padding: 2rem;
           border-radius: 0.5rem;
           box-shadow: var(--box-shadow);
           margin-bottom: 2rem;
       }
       .filters form {
           display: flex;
           gap: 1rem;
           flex-wrap: wrap;
           align-items: end;
       }
       .filter-group {
           flex: 1;
           min-width: 200px;
       }
       .filter-group label {
           display: block;
           font-size: 1.4rem;
           color: var(--black);
           margin-bottom: 0.5rem;
       }
       .filter-group select,
       .filter-group input {
           width: 100%;
           padding: 1rem;
           font-size: 1.4rem;
           border: var(--border);
           border-radius: 0.5rem;
       }
       
       .subscriptions-table {
           background: var(--white);
           border-radius: 0.5rem;
           box-shadow: var(--box-shadow);
           overflow-x: auto;
       }
       .subscriptions-table table {
           width: 100%;
           border-collapse: collapse;
       }
       .subscriptions-table th,
       .subscriptions-table td {
           padding: 1.5rem;
           text-align: left;
           border-bottom: var(--border);
       }
       .subscriptions-table th {
           background: var(--light-bg);
           font-size: 1.4rem;
           color: var(--black);
           font-weight: 600;
       }
       .subscriptions-table td {
           font-size: 1.4rem;
           color: var(--light-color);
       }
       
       .status-badge {
           display: inline-block;
           padding: 0.5rem 1rem;
           border-radius: 2rem;
           font-size: 1.2rem;
           font-weight: 600;
           text-transform: uppercase;
       }
       .status-badge.active {
           background: #d1fae5;
           color: #065f46;
       }
       .status-badge.expired {
           background: #fee2e2;
           color: #991b1b;
       }
       .status-badge.suspended {
           background: #fef3c7;
           color: #92400e;
       }
       .status-badge.inactive {
           background: #e5e7eb;
           color: #374151;
       }
       
       .days-left {
           font-weight: 600;
       }
       .days-left.critical {
           color: #dc2626;
       }
       .days-left.warning {
           color: #f59e0b;
       }
       .days-left.good {
           color: #10b981;
       }
       
       .action-buttons {
           display: flex;
           gap: 0.5rem;
           flex-wrap: wrap;
       }
       .action-buttons button,
       .action-buttons form {
           margin: 0;
       }
       .btn-sm {
           padding: 0.6rem 1.2rem;
           font-size: 1.2rem;
           border-radius: 0.4rem;
           cursor: pointer;
           border: none;
           color: white;
           transition: all 0.3s;
       }
       .btn-success {
           background: #10b981;
       }
       .btn-success:hover {
           background: #059669;
       }
       .btn-warning {
           background: #f59e0b;
       }
       .btn-warning:hover {
           background: #d97706;
       }
       .btn-danger {
           background: #ef4444;
       }
       .btn-danger:hover {
           background: #dc2626;
       }
       
       .empty-state {
           text-align: center;
           padding: 4rem 2rem;
           color: var(--light-color);
       }
       .empty-state i {
           font-size: 6rem;
           margin-bottom: 2rem;
           color: var(--light-color);
       }
   </style>
</head>
<body>

@include('admin.header')

<section class="dashboard">
   <h1 class="heading">Seller Subscriptions</h1>

   <!-- Statistics -->
   <div class="subscription-stats">
       <div class="stat-card active">
           <h3>{{ $stats['active'] }}</h3>
           <p>Active Subscriptions</p>
       </div>
       <div class="stat-card expired">
           <h3>{{ $stats['expired'] }}</h3>
           <p>Expired Subscriptions</p>
       </div>
       <div class="stat-card suspended">
           <h3>{{ $stats['suspended'] }}</h3>
           <p>Suspended Sellers</p>
       </div>
       <div class="stat-card expiring">
           <h3>{{ $stats['expiring_soon'] }}</h3>
           <p>Expiring Soon (7 days)</p>
       </div>
       <div class="stat-card">
           <h3>{{ $stats['total'] }}</h3>
           <p>Total Sellers</p>
       </div>
   </div>

   <!-- Filters -->
   <div class="filters">
       <form method="GET" action="{{ route('admin.subscriptions') }}">
           <div class="filter-group">
               <label for="search">Search</label>
               <input type="text" id="search" name="search" placeholder="Shop name or email..." value="{{ request('search') }}">
           </div>
           <div class="filter-group">
               <label for="subscription_status">Subscription Status</label>
               <select name="subscription_status" id="subscription_status">
                   <option value="">All</option>
                   <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>Active</option>
                   <option value="expired" {{ request('subscription_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                   <option value="suspended" {{ request('subscription_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                   <option value="inactive" {{ request('subscription_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
               </select>
           </div>
           <div class="filter-group">
               <label for="status">Seller Status</label>
               <select name="status" id="status">
                   <option value="">All</option>
                   <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                   <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                   <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                   <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
               </select>
           </div>
           <div class="filter-group">
               <button type="submit" class="btn">Filter</button>
           </div>
       </form>
   </div>

   <!-- Subscriptions Table -->
   <div class="subscriptions-table">
       @if($sellers->count() > 0)
       <table>
           <thead>
               <tr>
                   <th>Shop Name</th>
                   <th>Email</th>
                   <th>Seller Status</th>
                   <th>Subscription Status</th>
                   <th>Monthly Rent</th>
                   <th>End Date</th>
                   <th>Days Left</th>
                   <th>Last Payment</th>
                   <th>Actions</th>
               </tr>
           </thead>
           <tbody>
               @foreach($sellers as $seller)
               @php
                   $subscription = $seller->sellerSubscriptions->first();
                   $daysLeft = $seller->subscription_end_date ? now()->diffInDays($seller->subscription_end_date, false) : null;
                   $daysClass = $daysLeft !== null ? ($daysLeft < 0 ? 'critical' : ($daysLeft <= 7 ? 'warning' : 'good')) : '';
               @endphp
               <tr>
                   <td>
                       <strong>{{ $seller->shop_name }}</strong>
                   </td>
                   <td>{{ $seller->email }}</td>
                   <td>
                       <span class="status-badge {{ $seller->status }}">{{ ucfirst($seller->status) }}</span>
                   </td>
                   <td>
                       <span class="status-badge {{ $seller->subscription_status }}">
                           {{ ucfirst($seller->subscription_status) }}
                       </span>
                   </td>
                   <td>₱{{ number_format($seller->monthly_rent ?? 0, 2) }}</td>
                   <td>
                       @if($seller->subscription_end_date)
                           {{ $seller->subscription_end_date->format('M d, Y') }}
                       @else
                           <span style="color: #9ca3af;">Not set</span>
                       @endif
                   </td>
                   <td>
                       @if($daysLeft !== null)
                           <span class="days-left {{ $daysClass }}">
                               @if($daysLeft < 0)
                                   Expired {{ abs($daysLeft) }} days ago
                               @elseif($daysLeft == 0)
                                   Expires today
                               @else
                                   {{ $daysLeft }} days
                               @endif
                           </span>
                       @else
                           <span style="color: #9ca3af;">-</span>
                       @endif
                   </td>
                   <td>
                       @if($seller->last_payment_date)
                           {{ \Carbon\Carbon::parse($seller->last_payment_date)->format('M d, Y') }}
                       @else
                           <span style="color: #9ca3af;">Never</span>
                       @endif
                   </td>
                   <td>
                       <div class="action-buttons">
                           @if(in_array($seller->subscription_status, ['expired', 'inactive']))
                               <form action="{{ route('admin.seller.mark-paid', $seller->id) }}" method="POST" style="display: inline;">
                                   @csrf
                                   <button type="submit" class="btn-sm btn-success" onclick="return confirm('Mark subscription as paid and activate for 1 month?')">
                                       <i class="fas fa-check"></i> Mark Paid
                                   </button>
                               </form>
                           @endif
                           
                           @if($seller->subscription_status == 'active')
                               <form action="{{ route('admin.seller.notify', $seller->id) }}" method="POST" style="display: inline;">
                                   @csrf
                                   <button type="submit" class="btn-sm btn-warning">
                                       <i class="fas fa-bell"></i> Send Reminder
                                   </button>
                               </form>
                           @endif
                           
                           @if($seller->subscription_status != 'suspended')
                               <form id="suspend-form-{{ $seller->id }}" action="{{ route('admin.seller.disable', $seller->id) }}" method="POST" style="display: inline;">
                                   @csrf
                                   <input type="hidden" name="suspension_reason" id="reason-{{ $seller->id }}" value="">
                                   <input type="hidden" name="suspension_notes" id="notes-{{ $seller->id }}" value="">
                                   <button type="button" class="btn-sm btn-danger suspension-btn" data-seller-id="{{ $seller->id }}" data-shop-name="{{ $seller->shop_name ?? 'Seller' }}">
                                       <i class="fas fa-ban"></i> Suspend Subscription
                                   </button>
                               </form>
                           @else
                               <form action="{{ route('admin.seller.unsuspend', $seller->id) }}" method="POST" style="display: inline;">
                                   @csrf
                                   <button type="submit" class="btn-sm btn-success" onclick="return confirm('Reactivate this seller subscription?')">
                                       <i class="fas fa-check-circle"></i> Reactivate
                                   </button>
                               </form>
                           @endif
                           
                           <a href="{{ route('admin.sellers.show', $seller->id) }}" class="btn-sm" style="background: #6366f1;">
                               <i class="fas fa-eye"></i> View
                           </a>
                       </div>
                   </td>
               </tr>
               @endforeach
           </tbody>
       </table>
       @else
       <div class="empty-state">
           <i class="fas fa-inbox"></i>
           <h3>No sellers found</h3>
           <p>Try adjusting your filters</p>
       </div>
       @endif
   </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/admin_script.js') }}"></script>

@if(session('success'))
<script>
   Swal.fire({
       icon: 'success',
       title: 'Success',
       text: '{{ session('success') }}',
       timer: 3000,
       showConfirmButton: false
   });
</script>
@endif

@if(session('error'))
<script>
   Swal.fire({
       icon: 'error',
       title: 'Error',
       text: '{{ session('error') }}',
       timer: 3000,
       showConfirmButton: false
   });
</script>
@endif

<script>
document.querySelectorAll('.suspension-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const sellerId = this.dataset.sellerId;
        const shopName = this.dataset.shopName;

        Swal.fire({
            title: `Suspend Subscription: ${shopName}`,
            html: `
                <div style="text-align: left; font-size: 1.4rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Suspension Reason:</label>
                    <select id="swal-reason" class="swal2-input" style="width: 100%; margin: 0 0 1.5rem 0;">
                        <option value="Overdue Payment">Overdue Payment</option>
                        <option value="Policy Violation">Policy Violation</option>
                        <option value="Negative Reviews">Negative Reviews</option>
                        <option value="Quality Issues">Quality Issues</option>
                        <option value="Customer Complaints">Customer Complaints</option>
                        <option value="Fraudulent Activity">Fraudulent Activity</option>
                        <option value="Other">Other</option>
                    </select>

                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Additional Notes (Optional):</label>
                    <textarea id="swal-notes" class="swal2-textarea" style="width: 100%; margin: 0; min-height: 100px;" placeholder="Enter details here..."></textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Suspend Subscription',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
            preConfirm: () => {
                const reasonSelect = document.getElementById('swal-reason');
                const reason = reasonSelect.value;
                const notes = document.getElementById('swal-notes').value;

                if (!reason) {
                    Swal.showValidationMessage('Please select a reason');
                    return false;
                }

                return { reason, notes };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`reason-${sellerId}`).value = result.value.reason;
                document.getElementById(`notes-${sellerId}`).value = result.value.notes;
                document.getElementById(`suspend-form-${sellerId}`).submit();
            }
        });
    });
});
</script>

</body>
</html>

