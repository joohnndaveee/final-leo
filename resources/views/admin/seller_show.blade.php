@extends('layouts.admin')

@section('title', 'Seller Application Details - Admin Panel')

@section('content')
<div class="sellers-container" style="padding:2rem;">
    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;border-bottom:2px solid var(--main-color);padding-bottom:1rem;">
        <div>
            <h1 style="font-size:2.4rem;margin:0;display:flex;align-items:center;gap:0.8rem;">
                <i class="fas fa-file-alt" style="color:var(--main-color);"></i>
                Seller Application
            </h1>
            <p style="margin-top:0.5rem;font-size:1.4rem;color:#6b7280;">
                Review the information submitted by this seller before approving or rejecting.
            </p>
        </div>
        <div>
            <span id="seller-status-badge" class="badge-status {{ $seller->seller_status ?? 'pending' }}" style="display:inline-block;padding:0.6rem 1.2rem;border-radius:999px;font-size:1.3rem;text-transform:capitalize;">
                {{ ucfirst($seller->seller_status ?? 'pending') }}
            </span>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:2rem;margin-bottom:2rem;">
        <div style="background:#fff;border-radius:1.2rem;padding:1.8rem;box-shadow:0 4px 12px rgba(0,0,0,0.05);">
            <h2 style="font-size:1.8rem;margin-bottom:1.2rem;border-bottom:1px solid #e5e7eb;padding-bottom:0.8rem;">
                Account Information
            </h2>
            <p><strong>Name:</strong> {{ $seller->name }}</p>
            <p><strong>Email:</strong> {{ $seller->email }}</p>
            <p><strong>Registered At:</strong>
                @if($seller->created_at)
                    {{ $seller->created_at->format('M d, Y H:i') }}
                @else
                    —
                @endif
            </p>
            <p><strong>Current Role:</strong> {{ ucfirst($seller->role ?? 'seller') }}</p>
        </div>

        <div style="background:#fff;border-radius:1.2rem;padding:1.8rem;box-shadow:0 4px 12px rgba(0,0,0,0.05);">
            <h2 style="font-size:1.8rem;margin-bottom:1.2rem;border-bottom:1px solid #e5e7eb;padding-bottom:0.8rem;">
                Business Details
            </h2>
            <p><strong>Shop Name:</strong> {{ $seller->shop_name ?? '—' }}</p>
            <p><strong>Business Address:</strong> {{ $seller->business_address ?? '—' }}</p>
            <p><strong>Business ID Number:</strong> {{ $seller->business_id_number ?? '—' }}</p>
            <p><strong>Additional Notes:</strong></p>
            <div style="border:1px solid #e5e7eb;border-radius:0.8rem;padding:1rem;font-size:1.4rem;min-height:60px;background:#f9fafb;white-space:pre-wrap;">
                {{ $seller->business_notes ?? 'No additional notes provided.' }}
            </div>
        </div>

        <div style="background:#fff;border-radius:1.2rem;padding:1.8rem;box-shadow:0 4px 12px rgba(0,0,0,0.05);">
            <h2 style="font-size:1.8rem;margin-bottom:1.2rem;border-bottom:1px solid #e5e7eb;padding-bottom:0.8rem;">
                Review &amp; Actions
            </h2>
            <p style="font-size:1.4rem;color:#6b7280;margin-bottom:1rem;">
                Set the seller's application status. When you mark as <strong>Approved</strong>, their role will automatically become <strong>seller</strong>. Otherwise they remain a normal buyer.
            </p>
            <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                <label for="seller-status-select" style="font-size:1.4rem;color:#374151;">Seller status</label>
                <select id="seller-status-select" style="padding:0.6rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1.4rem;">
                    <option value="pending" @selected($seller->seller_status === 'pending')>Pending</option>
                    <option value="approved" @selected($seller->seller_status === 'approved')>Approved</option>
                    <option value="rejected" @selected($seller->seller_status === 'rejected')>Rejected</option>
                </select>
                <button type="button" onclick="updateSellerStatus()" style="padding:0.6rem 1.4rem;background:var(--main-color);color:#fff;border:none;border-radius:0.5rem;font-size:1.4rem;cursor:pointer;">
                    Update status
                </button>
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem;">
        <a href="{{ route('admin.sellers') }}" style="font-size:1.4rem;color:var(--main-color);text-decoration:none;">
            &larr; Back to sellers list
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateSellerStatus() {
        const select = document.getElementById('seller-status-select');
        const sellerStatus = select.value;
        const url = "{{ url('admin/users/'.$seller->id.'/role') }}";

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ seller_status: sellerStatus })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                const badge = document.getElementById('seller-status-badge');
                if (badge) {
                    badge.textContent = sellerStatus.charAt(0).toUpperCase() + sellerStatus.slice(1);
                    badge.classList.remove('pending', 'approved', 'rejected');
                    badge.classList.add(sellerStatus);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Seller status updated',
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });
    }
</script>
@endpush

