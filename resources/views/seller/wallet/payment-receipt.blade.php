@extends('layouts.seller')

@section('title', 'Payment Receipt')

@push('styles')
<style>
    .receipt-container {
        max-width: 700px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    .receipt-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .receipt-header {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
    }
    
    .success-icon {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        display: block;
    }
    
    .receipt-header h2 {
        margin: 0;
        font-size: 2rem;
    }
    
    .receipt-header p {
        margin: 0.5rem 0 0 0;
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .receipt-body {
        padding: 2.5rem;
    }
    
    .receipt-section {
        margin-bottom: 2.5rem;
    }
    
    .receipt-section:last-of-type {
        margin-bottom: 0;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
        font-size: 1.3rem;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .detail-value {
        color: #1f2937;
        font-weight: 600;
    }
    
    .amount-box {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 2px solid #22c55e;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        text-align: center;
    }
    
    .amount-label {
        color: #6b7280;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }
    
    .amount-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #22c55e;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #d1fae5;
        color: #065f46;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .info-box {
        background: #dbeafe;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 2rem 0;
        color: #0c4a6e;
    }
    
    .info-box h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1.3rem;
        color: #0c4a6e;
    }
    
    .info-box p {
        margin: 0;
        font-size: 1.2rem;
        line-height: 1.6;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }
    
    .btn {
        flex: 1;
        min-width: 180px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 1.4rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(34, 197, 94, 0.3);
    }
    
    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 2px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        border-color: #9ca3af;
        background: #f9fafb;
    }
    
    .receipt-line {
        border: 1px solid #e5e7eb;
        margin: 2rem 0;
    }
    
    .thank-you {
        text-align: center;
        color: #6b7280;
        font-size: 1.2rem;
        margin: 2rem 0;
    }
    
    .thank-you strong {
        color: #1f2937;
    }
    
    @media (max-width: 768px) {
        .receipt-container {
            padding: 0 1rem;
        }
        
        .receipt-header {
            padding: 2rem;
        }
        
        .success-icon {
            font-size: 2.5rem;
        }
        
        .receipt-header h2 {
            font-size: 1.6rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            min-width: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="receipt-container">
    <div class="receipt-card">
        <!-- Success Header -->
        <div class="receipt-header">
            <span class="success-icon">✓</span>
            <h2>Payment Successful!</h2>
            <p>Your subscription has been renewed</p>
        </div>
        
        <div class="receipt-body">
            <!-- Payment Summary -->
            <div class="receipt-section">
                <div class="section-title">
                    <i class="fas fa-receipt"></i> Payment Receipt
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value">{{ $payment->reference_number }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Shop Name</span>
                    <span class="detail-value">{{ $seller->shop_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Status</span>
                    <span class="detail-value">
                        <span class="status-badge">
                            <i class="fas fa-check-circle"></i>Completed
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Paid At</span>
                    <span class="detail-value">{{ $payment->paid_at->format('M d, Y g:i A') }}</span>
                </div>
                
                <div class="amount-box">
                    <div class="amount-label">Amount Paid</div>
                    <div class="amount-value">${{ number_format($payment->amount, 2) }}</div>
                </div>
            </div>
            
            <div class="receipt-line"></div>
            
            <!-- Subscription Details -->
            <div class="receipt-section">
                <div class="section-title">
                    <i class="fas fa-credit-card"></i> Subscription Details
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Subscription Type</span>
                    <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $subscription->subscription_type)) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Monthly Cost</span>
                    <span class="detail-value">${{ number_format($subscription->amount, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Billing Cycle</span>
                    <span class="detail-value">Monthly (Renewable)</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Current Status</span>
                    <span class="detail-value">
                        <span class="status-badge">
                            <i class="fas fa-check-circle"></i>Active
                        </span>
                    </span>
                </div>
            </div>
            
            <div class="receipt-line"></div>
            
            <!-- Renewal Information -->
            <div class="receipt-section">
                <div class="section-title">
                    <i class="fas fa-calendar"></i> Renewal Information
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Subscription Renewed</span>
                    <span class="detail-value">{{ now()->format('M d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Active Until</span>
                    <span class="detail-value">{{ $subscription->end_date->format('M d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Days Remaining</span>
                    <span class="detail-value">
                        <strong>{{ now()->diffInDays($subscription->end_date) }}</strong> days
                    </span>
                </div>
            </div>
            
            <!-- Important Notice -->
            <div class="info-box">
                <h4><i class="fas fa-bell"></i> Next Payment Due</h4>
                <p>Your next rent payment will be due on <strong>{{ $subscription->end_date->format('M d, Y') }}</strong>. You'll receive a notification reminder before the due date.</p>
            </div>
            
            <!-- Thank You -->
            <div class="thank-you">
                <p>Thank you for choosing <strong>{{ env('APP_NAME', 'U-KAY HUB') }}</strong>!</p>
                <p style="font-size: 1.1rem; margin-top: 0.5rem;">Your shop is now active and ready for sales.</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('seller.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-th-large"></i>Back to Dashboard
                </a>
                <a href="{{ route('seller.wallet.index') }}" class="btn btn-secondary">
                    <i class="fas fa-wallet"></i>View Wallet
                </a>
            </div>
        </div>
    </div>
    
    <!-- Print/Download Notice -->
    <div style="text-align: center; margin-top: 2rem; color: #6b7280; font-size: 1.2rem;">
        <p>💡 You can take a screenshot or print this page for your records</p>
    </div>
</div>
@endsection
