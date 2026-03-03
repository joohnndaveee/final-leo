@extends('layouts.admin')

@section('title', 'Users Management - Admin Panel')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    :root {
        --panel-bg: #ffffff;
        --page-bg: #f6f7f9;
        --text: #111827;
        --muted: #6b7280;
        --border: #e5e7eb;
        --shadow: 0 1px 2px rgba(17, 24, 39, 0.06);
        --radius: 10px;
        --radius-sm: 8px;
    }

    .users-container {
        padding: 1.6rem;
        background: var(--panel-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.6rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid var(--border);
    }

    .page-header h1 {
        margin: 0;
        font-size: 2.2rem;
        color: var(--text);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        line-height: 1.2;
    }

    .page-header h1 i {
        color: var(--text);
        opacity: 0.9;
    }

    .users-count {
        font-size: 1.3rem;
        color: var(--muted);
        background: #f9fafb;
        border: 1px solid var(--border);
        padding: 0.7rem 1.1rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        white-space: nowrap;
    }

    .users-table-wrapper {
        background: var(--panel-bg);
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table thead {
        background: #f3f4f6;
    }

    .users-table thead th {
        padding: 1.2rem 1.4rem;
        text-align: left;
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        text-transform: none;
        letter-spacing: 0.02em;
        border-bottom: 1px solid var(--border);
    }

    .users-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s ease;
    }

    .users-table tbody tr:hover {
        background: #f9fafb;
    }

    .users-table tbody td {
        padding: 1.2rem 1.4rem;
        font-size: 1.4rem;
        color: #374151;
        vertical-align: middle;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 0;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #111827;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 700;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        color: #111827;
        font-size: 1.4rem;
        line-height: 1.25;
    }

    .user-email {
        color: #6b7280;
        font-size: 1.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .user-contact {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-contact i {
        color: #6b7280;
        font-size: 1.2rem;
    }

    .user-address {
        max-width: 360px;
        line-height: 1.45;
        color: #4b5563;
    }

    .user-date {
        color: #6b7280;
        font-size: 1.3rem;
        line-height: 1.35;
    }

    .badge-missing {
        display: inline-block;
        padding: 0.35rem 0.7rem;
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        font-size: 1.2rem;
        font-style: normal;
    }

    .empty-users {
        text-align: center;
        padding: 5rem 2rem;
        background: #fff;
        border-radius: var(--radius);
        border: 1px dashed #d1d5db;
    }

    .empty-users i {
        font-size: 6rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }

    .empty-users h2 {
        font-size: 2rem;
        color: #111827;
        margin-bottom: 0.6rem;
    }

    .empty-users p {
        font-size: 1.4rem;
        color: #6b7280;
    }

    @media (max-width: 1100px) {
        .users-table-wrapper {
            overflow-x: auto;
        }
        .users-table {
            min-width: 880px;
        }
    }

    @media (max-width: 768px) {
        .users-container {
            padding: 1.2rem;
        }
        .page-header {
            flex-direction: column;
            align-items: stretch;
            gap: .8rem;
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
