@extends('layouts.admin')

@section('title', 'Users Management - Admin Panel')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .users-container {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid var(--main-color);
    }

    .page-header h1 {
        font-size: 3rem;
        color: var(--black);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header h1 i {
        color: var(--main-color);
    }

    .users-count {
        font-size: 1.6rem;
        color: var(--light-color);
        background: rgba(58, 199, 45, 0.1);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
    }

    /* Users Table */
    .users-table-wrapper {
        background: var(--white);
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table thead {
        background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
    }

    .users-table thead th {
        padding: 2rem;
        text-align: left;
        font-size: 1.6rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .users-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .users-table tbody tr:hover {
        background: rgba(58, 199, 45, 0.05);
    }

    .users-table tbody td {
        padding: 2rem;
        font-size: 1.5rem;
        color: #4b5563;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .user-name {
        font-weight: 600;
        color: var(--black);
        font-size: 1.6rem;
    }

    .user-email {
        color: var(--light-color);
        font-size: 1.4rem;
    }

    .user-contact {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-contact i {
        color: var(--main-color);
        font-size: 1.4rem;
    }

    .user-address {
        max-width: 300px;
        line-height: 1.6;
    }

    .user-date {
        color: #9ca3af;
        font-size: 1.4rem;
    }

    .btn-delete-user {
        background: #ef4444;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 0.8rem;
        cursor: pointer;
        font-size: 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-delete-user:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Empty State */
    .empty-users {
        text-align: center;
        padding: 8rem 2rem;
        background: var(--white);
        border-radius: 1.5rem;
        border: 2px dashed #e5e7eb;
    }

    .empty-users i {
        font-size: 10rem;
        color: var(--light-color);
        margin-bottom: 2rem;
    }

    .empty-users h2 {
        font-size: 2.5rem;
        color: var(--black);
        margin-bottom: 1rem;
    }

    .empty-users p {
        font-size: 1.8rem;
        color: var(--light-color);
    }

    /* Badge for missing data */
    .badge-missing {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        color: #6b7280;
        border-radius: 0.5rem;
        font-size: 1.3rem;
        font-style: italic;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .users-table-wrapper {
            overflow-x: auto;
        }

        .users-table {
            min-width: 900px;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .users-container {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="users-container">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>
            <i class="fas fa-users"></i>
            Registered Users
        </h1>
        <div class="users-count">
            <i class="fas fa-user-check"></i>
            {{ $users->count() }} {{ $users->count() === 1 ? 'User' : 'Users' }}
        </div>
    </div>

    @if($users->count() > 0)
        {{-- Users Table --}}
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr data-user-id="{{ $user->id }}">
                            {{-- User Info --}}
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="user-details">
                                        <span class="user-name">{{ $user->name }}</span>
                                        <span class="user-email">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Phone --}}
                            <td>
                                @if($user->phone)
                                    <div class="user-contact">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $user->phone }}</span>
                                    </div>
                                @else
                                    <span class="badge-missing">Not provided</span>
                                @endif
                            </td>

                            {{-- Address --}}
                            <td>
                                <div class="user-address">
                                    @if($user->address)
                                        {{ $user->address }}
                                    @else
                                        <span class="badge-missing">Not provided</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Registered Date --}}
                            <td>
                                <div class="user-date">
                                    @if($user->created_at)
                                        <i class="fas fa-calendar-alt" style="margin-right: 0.5rem; color: var(--main-color);"></i>
                                        {{ $user->created_at->format('M d, Y') }}
                                        <br>
                                        <small style="color: #9ca3af;">{{ $user->created_at->format('h:i A') }}</small>
                                    @else
                                        <span class="badge-missing">N/A</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-users">
            <i class="fas fa-user-slash"></i>
            <h2>No Users Yet</h2>
            <p>Registered customers will appear here</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteUser(userId, userName) {
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete user <strong>${userName}</strong>.<br>This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete user!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Remove the user row from DOM
                        const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                        if (userRow) {
                            userRow.style.transition = 'all 0.3s ease';
                            userRow.style.opacity = '0';
                            userRow.style.transform = 'scale(0.9)';
                            
                            setTimeout(() => {
                                userRow.remove();
                                
                                // Update users count
                                const countElement = document.querySelector('.users-count');
                                const remainingUsers = document.querySelectorAll('tbody tr').length;
                                countElement.innerHTML = `<i class="fas fa-user-check"></i> ${remainingUsers} ${remainingUsers === 1 ? 'User' : 'Users'}`;
                                
                                // Show empty state if no users left
                                if (remainingUsers === 0) {
                                    location.reload();
                                }
                            }, 300);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            confirmButtonColor: '#4caf50',
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            confirmButtonColor: '#4caf50'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        confirmButtonColor: '#4caf50'
                    });
                });
            }
        });
    }

    function updateUserRole(userId, role, sellerStatus) {
        fetch(`/admin/users/${userId}/role`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ role, seller_status: sellerStatus })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Role updated', timer: 1200, showConfirmButton: false });
            }
        });
    }

    function updateUserSellerStatus(userId, sellerStatus, role) {
        fetch(`/admin/users/${userId}/role`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ role, seller_status: sellerStatus })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Seller status updated', timer: 1200, showConfirmButton: false });
            }
        });
    }
</script>
@endpush
