@extends('layouts.seller')

@section('title', 'Withdraw Funds')

@push('styles')
<style>
    .withdraw-container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    .withdraw-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.1);
        overflow: hidden;
    }
    
    .withdraw-header {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.95), rgba(217, 119, 6, 0.95));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: white;
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.2);
    }
    
    .withdraw-header h5 {
        margin: 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .withdraw-body {
        padding: 2rem;
    }
    
    .balance-box {
        background: rgba(219, 234, 254, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #1e40af;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border-left: 4px solid rgba(59, 130, 246, 0.6);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.3rem;
    }
    
    .balance-box .balance-amount {
        color: #10b981;
        font-weight: 700;
        font-size: 1.6rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
        font-size: 1.4rem;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-prefix {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 40px;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-right: none;
        border-radius: 6px 0 0 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6b7280;
    }
    
    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        font-size: 1.4rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .form-control.with-prefix {
        padding-left: 50px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: rgba(245, 158, 11, 0.5);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .form-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 1.2rem;
        color: #6b7280;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
    }
    
    .alert-danger {
        background: rgba(254, 226, 226, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #991b1b;
        border-left: 4px solid rgba(239, 68, 68, 0.6);
    }
    
    .alert-danger ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .alert-warning {
        background: rgba(254, 243, 199, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #92400e;
        border-left: 4px solid rgba(245, 158, 11, 0.6);
    }
    
    .alert-light {
        background: #f9fafb;
        color: #374151;
        border: 1px solid #e5e7eb;
        margin-top: 2rem;
    }
    
    .alert-heading {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-light ul {
        margin: 0.5rem 0 0 0;
        padding-left: 1.5rem;
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
    
    .btn-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.95), rgba(217, 119, 6, 0.95));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: white;
        font-size: 1.6rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
    }
    
    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
    }
    
    hr {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 2rem 0;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #6b7280;
        text-decoration: none;
        border-radius: 12px;
        border: 1px solid rgba(16, 185, 129, 0.1);
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.05);
    }
    
    .back-button:hover {
        background: rgba(240, 253, 244, 0.9);
        color: #059669;
        border-color: rgba(16, 185, 129, 0.3);
        transform: translateX(-3px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }
    
    @media (max-width: 768px) {
        .withdraw-container {
            padding: 0 1rem;
        }
        
        .withdraw-body {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="withdraw-container">
    <a href="{{ route('seller.wallet.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to Wallet
    </a>
    <div class="withdraw-card">
        <div class="withdraw-header">
            <h5><i class="fas fa-arrow-up"></i>Withdraw Funds</h5>
        </div>
        <div class="withdraw-body">
            <!-- Current Balance -->
            <div class="balance-box">
                <strong>Available Balance:</strong>
                <span class="balance-amount">₱{{ number_format($wallet->balance, 2) }}</span>
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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('seller.wallet.withdraw') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="amount" class="form-label">Withdrawal Amount *</label>
                    <div class="input-wrapper">
                        <span class="input-prefix">$</span>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            class="form-control with-prefix"
                            placeholder="0.00"
                            step="0.01"
                            min="0.01"
                            max="{{ $wallet->balance }}"
                            required
                            value="{{ old('amount') }}"
                        >
                    </div>
                    <small class="form-text">Maximum: ₱{{ number_format($wallet->balance, 2) }}</small>
                </div>

                <div class="form-group">
                    <label for="bank_name" class="form-label">Bank Name *</label>
                    <input 
                        type="text" 
                        id="bank_name" 
                        name="bank_name" 
                        class="form-control"
                        placeholder="e.g., First National Bank"
                        required
                        value="{{ old('bank_name') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="account_holder" class="form-label">Account Holder Name *</label>
                    <input 
                        type="text" 
                        id="account_holder" 
                        name="account_holder" 
                        class="form-control"
                        placeholder="Full name on the account"
                        required
                        value="{{ old('account_holder') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="bank_account" class="form-label">Bank Account Number *</label>
                    <input 
                        type="text" 
                        id="bank_account" 
                        name="bank_account" 
                        class="form-control"
                        placeholder="Your bank account number"
                        required
                        value="{{ old('bank_account') }}"
                    >
                    <small class="form-text">We keep this information secure</small>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Processing Time:</strong> Withdrawals are processed within 3-5 business days. A small processing fee may apply depending on your bank.
                </div>

                <hr>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-check"></i>Submit Withdrawal Request
                </button>
                <a href="{{ route('seller.wallet.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </form>

            <!-- Info Box -->
            <div class="alert alert-light">
                <h6 class="alert-heading"><i class="fas fa-info-circle"></i>Important</h6>
                <small>
                    <ul>
                        <li>Withdrawal requests are processed manually by our admin team</li>
                        <li>Please ensure your bank account information is correct</li>
                        <li>Processing usually takes 3-5 business days</li>
                        <li>You can only withdraw your available balance</li>
                    </ul>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
