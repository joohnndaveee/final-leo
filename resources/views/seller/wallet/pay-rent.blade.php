@extends('layouts.seller')

@section('title', 'Pay Monthly Rent')

@push('styles')
<style>
    .rent-container {
        max-width: 650px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    .rent-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .rent-header {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 1.5rem;
    }
    
    .rent-header h5 {
        margin: 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .rent-body {
        padding: 2rem;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
    }
    
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    .alert-info {
        background: #dbeafe;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }
    
    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }
    
    .alert-light {
        background: #f9fafb;
        color: #374151;
        border: 1px solid #e5e7eb;
    }
    
    .alert-heading {
        font-size: 1.6rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .payment-summary {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }
    
    .summary-row:last-of-type {
        margin-bottom: 0;
    }
    
    .summary-row .label {
        color: #6b7280;
    }
    
    .summary-row .value {
        font-weight: 700;
    }
    
    .value.text-danger {
        color: #ef4444;
    }
    
    .value.text-success {
        color: #22c55e;
    }
    
    .value.text-info {
        color: #3b82f6;
    }
    
    .divider {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 1rem 0;
    }
    
    .btn {
        padding: 0.8rem 1.5rem;
        font-size: 1.4rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        text-decoration: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        font-size: 1.6rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        font-size: 1.4rem;
        padding: 0.7rem 1.2rem;
        margin-top: 0.5rem;
        width: auto;
    }
    
    .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
    }
    
    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .badge.bg-success {
        background: #22c55e;
        color: white;
    }
    
    .badge.bg-danger {
        background: #ef4444;
        color: white;
    }
    
    .badge.bg-secondary {
        background: #6b7280;
        color: white;
    }
    
    .details-section {
        margin-top: 2rem;
    }
    
    .details-section h6 {
        font-size: 1.6rem;
        margin-bottom: 1rem;
        color: #1f2937;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        font-size: 1.3rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .detail-row .label {
        color: #6b7280;
    }
    
    .detail-row .value {
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .info-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .info-header {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .info-header h6 {
        margin: 0;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-body {
        padding: 1.5rem;
    }
    
    .faq-item {
        margin-bottom: 1.5rem;
    }
    
    .faq-item:last-child {
        margin-bottom: 0;
    }
    
    .faq-question {
        font-weight: 600;
        color: #374151;
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }
    
    .faq-answer {
        color: #6b7280;
        font-size: 1.2rem;
        line-height: 1.6;
    }
    
    hr {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 2rem 0;
    }
    
    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        animation: fadeIn 0.2s ease-in;
    }
    
    .modal-overlay.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-dialog {
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease-out;
    }
    
    .modal-header {
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h4 {
        margin: 0;
        font-size: 1.6rem;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.8rem;
        color: #6b7280;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .modal-close:hover {
        color: #1f2937;
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-summary {
        background: #f9fafb;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #3b82f6;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        font-size: 1.3rem;
    }
    
    .summary-item .label {
        color: #6b7280;
    }
    
    .summary-item .value {
        font-weight: 600;
        color: #1f2937;
    }
    
    .summary-item.total {
        border-top: 1px solid #e5e7eb;
        padding-top: 1rem;
        margin-top: 1rem;
        font-size: 1.5rem;
    }
    
    .summary-item.total .value {
        color: #22c55e;
        font-size: 1.8rem;
    }
    
    .modal-warning {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        color: #92400e;
        font-size: 1.2rem;
        display: flex;
        gap: 0.8rem;
    }
    
    .modal-footer {
        padding: 0 2rem 2rem;
        display: flex;
        gap: 1rem;
    }
    
    .modal-btn {
        flex: 1;
        padding: 1rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 1.4rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .modal-btn-confirm {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .modal-btn-confirm:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
    }
    
    .modal-btn-confirm:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .modal-btn-cancel {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }
    
    .modal-btn-cancel:hover:not(:disabled) {
        background: #f9fafb;
        border-color: #9ca3af;
    }
    
    .spinner {
        display: inline-block;
        width: 1.2rem;
        height: 1.2rem;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @media (max-width: 768px) {
        .modal-dialog {
            width: 95%;
        }
        
        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1.5rem;
        }
        
        .modal-footer {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="rent-container">
    @if ($isOverdue)
        <div class="alert alert-danger">
            <h4 class="alert-heading"><i class="fas fa-exclamation-circle"></i>Payment Overdue!</h4>
            <p style="margin: 0.5rem 0 1rem 0;">Your monthly rent payment is overdue. Please pay immediately to prevent account suspension.</p>
            <hr style="margin: 1rem 0; border-color: rgba(153, 27, 27, 0.2);">
            <small>Overdue Amount: <strong>₱{{ number_format($subscription->amount, 2) }}</strong></small>
        </div>
    @else
        <div class="alert alert-info">
            <h5 class="alert-heading"><i class="fas fa-calendar-alt"></i>Upcoming Payment</h5>
            <small>Your next payment is due on {{ $subscription->end_date->format('M d, Y') }}</small>
        </div>
    @endif

    <div class="rent-card">
        <div class="rent-header">
            <h5><i class="fas fa-credit-card"></i>Pay Monthly Shop Rent</h5>
        </div>
        <div class="rent-body">
            <!-- Summary -->
            <div class="payment-summary">
                <div class="summary-row">
                    <span class="label">Monthly Rent:</span>
                    <span class="value text-danger">₱{{ number_format($subscription->amount, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">Wallet Balance:</span>
                    <span class="value text-success">₱{{ number_format($wallet->balance, 2) }}</span>
                </div>
                <hr class="divider">
                <div class="summary-row">
                    <span class="label">Balance After Payment:</span>
                    <span class="value text-info">₱{{ number_format(max(0, $wallet->balance - $subscription->amount), 2) }}</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Balance Check Alert -->
            @if ($wallet->balance < $subscription->amount)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Insufficient Balance!</strong><br>
                    <span style="display: block; margin-top: 0.5rem;">You need ₱{{ number_format($subscription->amount - $wallet->balance, 2) }} more to pay the rent.</span>
                    <a href="{{ route('seller.wallet.deposit.form') }}" class="btn btn-warning">
                        Add Funds Now
                    </a>
                </div>
            @else
                <form action="{{ route('seller.wallet.pay-rent') }}" method="POST" id="paymentForm">
                    @csrf

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> After payment, your subscription will be renewed for another month, and your balance will be updated.
                    </div>

                    <button type="button" class="btn btn-primary" id="confirmPaymentBtn">
                        <i class="fas fa-check"></i>Confirm Payment
                    </button>
                    <a href="{{ route('seller.wallet.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </form>
            @endif

            <!-- Subscription Details -->
            <div class="details-section">
                <h6>Subscription Details</h6>
                <div class="detail-row">
                    <span class="label">Type:</span>
                    <span class="value">{{ $subscription->subscription_type }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Current Status:</span>
                    <span class="value">
                        @if ($subscription->isActive())
                            <span class="badge bg-success">Active</span>
                        @elseif ($subscription->isExpired())
                            <span class="badge bg-danger">Expired</span>
                        @else
                            <span class="badge bg-secondary">{{ $subscription->status }}</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row" style="border-bottom: none;">
                    <span class="label">Expires:</span>
                    <span class="value">{{ $subscription->end_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="info-card">
        <div class="info-header">
            <h6><i class="fas fa-question-circle"></i>Payment & Billing</h6>
        </div>
        <div class="info-body">
            <div class="faq-item">
                <div class="faq-question">What's happening?</div>
                <div class="faq-answer">You're paying your monthly subscription fee to keep your shop active and continue selling.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">When will it renew?</div>
                <div class="faq-answer">Your subscription will automatically renew for another month after payment.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">What if I don't pay?</div>
                <div class="faq-answer">If payment is not made before the expiration date, your account will be suspended and you won't be able to sell.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Can I refund?</div>
                <div class="faq-answer">Contact support to discuss refund options. Refunds are handled on a case-by-case basis.</div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div class="modal-overlay" id="confirmationModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h4><i class="fas fa-lock"></i> Confirm Payment</h4>
            <button type="button" class="modal-close" onclick="closeModal()">×</button>
        </div>
        
        <div class="modal-body">
            <p style="font-size: 1.3rem; margin-bottom: 1.5rem; color: #6b7280;">
                Please review the payment details below before confirming.
            </p>
            
            <div class="modal-summary">
                <div class="summary-item">
                    <span class="label">Monthly Rent Amount:</span>
                    <span class="value">₱{{ number_format($subscription->amount, 2) }}</span>
                </div>
                
                <div class="summary-item">
                    <span class="label">Current Wallet Balance:</span>
                    <span class="value">₱{{ number_format($wallet->balance, 2) }}</span>
                </div>
                
                <div class="summary-item total">
                    <span class="label">Balance After Payment:</span>
                    <span class="value">₱{{ number_format(max(0, $wallet->balance - $subscription->amount), 2) }}</span>
                </div>
            </div>
            
            <div class="modal-warning">
                <span style="font-size: 1.5rem;">⚠️</span>
                <span>This action cannot be undone. Your subscription will be renewed for another month.</span>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal()">
                Cancel
            </button>
            <button type="button" class="modal-btn modal-btn-confirm" id="submitPaymentBtn" onclick="submitPayment()">
                <i class="fas fa-check"></i>Confirm & Pay
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
    const submitPaymentBtn = document.getElementById('submitPaymentBtn');
    const confirmationModal = document.getElementById('confirmationModal');
    const paymentForm = document.getElementById('paymentForm');
    
    // Open modal when clicking confirm button
    confirmPaymentBtn.addEventListener('click', function() {
        confirmationModal.classList.add('show');
    });
    
    // Close modal
    function closeModal() {
        confirmationModal.classList.remove('show');
    }
    
    // Submit payment form
    function submitPayment() {
        submitPaymentBtn.disabled = true;
        submitPaymentBtn.innerHTML = '<span class="spinner"></span>Processing...';
        paymentForm.submit();
    }
    
    // Close modal when clicking outside
    confirmationModal.addEventListener('click', function(e) {
        if (e.target === confirmationModal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && confirmationModal.classList.contains('show')) {
            closeModal();
        }
    });
</script>
@endpush

@endsection
