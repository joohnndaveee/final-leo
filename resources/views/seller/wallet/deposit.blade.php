@extends('layouts.seller')

@section('title', 'Add Funds to Wallet')

@push('styles')
<style>
    .deposit-container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    .deposit-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .deposit-header {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        padding: 1.5rem;
    }
    
    .deposit-header h5 {
        margin: 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .deposit-body {
        padding: 2rem;
    }
    
    .balance-box {
        background: #dbeafe;
        color: #1e40af;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.3rem;
    }
    
    .balance-box .balance-amount {
        color: #22c55e;
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
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }
    
    .form-select {
        width: 100%;
        padding: 0.8rem 1rem;
        font-size: 1.4rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
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
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    .alert-danger ul {
        margin: 0;
        padding-left: 1.5rem;
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
    
    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        font-size: 1.6rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
    }
    
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
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
    
    @media (max-width: 768px) {
        .deposit-container {
            padding: 0 1rem;
        }
        
        .deposit-body {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="deposit-container">
    <div class="deposit-card">
        <div class="deposit-header">
            <h5><i class="fas fa-plus"></i>Add Funds to Wallet</h5>
        </div>
        <div class="deposit-body">
            <!-- Current Balance -->
            <div class="balance-box">
                <strong>Current Balance:</strong>
                <span class="balance-amount">${{ number_format($wallet->balance, 2) }}</span>
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

            <form action="{{ route('seller.wallet.deposit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="amount" class="form-label">Deposit Amount *</label>
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
                            max="1000000"
                            required
                            value="{{ old('amount') }}"
                        >
                    </div>
                    <small class="form-text">Minimum: $0.01 | Maximum: $1,000,000</small>
                </div>

                <div class="form-group">
                    <label for="payment_method" class="form-label">Payment Method *</label>
                    <select 
                        id="payment_method" 
                        name="payment_method" 
                        class="form-select"
                        required
                    >
                        <option value="">Select a payment method</option>
                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                            Bank Transfer
                        </option>
                        <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                            Credit/Debit Card
                        </option>
                        <option value="manual" {{ old('payment_method') === 'manual' ? 'selected' : '' }}>
                            Manual Payment
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="reference" class="form-label">Reference Number (Optional)</label>
                    <input 
                        type="text" 
                        id="reference" 
                        name="reference" 
                        class="form-control"
                        placeholder="Transaction ID, Check #, etc."
                        value="{{ old('reference') }}"
                    >
                    <small class="form-text">For tracking purposes</small>
                </div>

                <hr>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i>Confirm Deposit
                </button>
                <a href="{{ route('seller.wallet.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </form>

            <!-- Info Box -->
            <div class="alert alert-light">
                <h6 class="alert-heading"><i class="fas fa-info-circle"></i>Important</h6>
                <small>
                    Please ensure the amount matches the payment you're making. Once submitted, you'll need to complete the payment through your bank or payment provider. Your wallet will be credited after payment confirmation.
                </small>

        </div>
    </div>
</div>
@endsection
