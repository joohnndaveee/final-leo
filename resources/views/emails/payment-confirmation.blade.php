<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .header h2 {
            margin: 0;
            font-size: 1.8rem;
        }
        
        .header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 2rem;
        }
        
        .greeting {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .receipt-section {
            background: #f9fafb;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid #3b82f6;
        }
        
        .receipt-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .receipt-row:last-child {
            border-bottom: none;
        }
        
        .receipt-row .label {
            color: #6b7280;
        }
        
        .receipt-row .value {
            font-weight: 600;
            color: #1f2937;
        }
        
        .amount-row {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
        }
        
        .amount-row .label {
            color: #6b7280;
            font-size: 0.95rem;
        }
        
        .amount-row .value {
            font-size: 1.5rem;
            color: #22c55e;
            font-weight: 700;
        }
        
        .next-payment {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            color: #0c4a6e;
        }
        
        .next-payment h4 {
            margin: 0 0 0.5rem 0;
            color: #0c4a6e;
        }
        
        .next-payment p {
            margin: 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin: 1.5rem 0;
        }
        
        .footer {
            background: #f9fafb;
            padding: 1.5rem;
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer p {
            margin: 0.5rem 0;
        }
        
        .badge {
            display: inline-block;
            background: #22c55e;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✓ Payment Successful</h2>
            <p>Your monthly subscription rent has been paid</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <strong>{{ $seller->name }}</strong>,
            </div>
            
            <p>Thank you for your payment! Your subscription is now active and your shop is ready for business.</p>
            
            <!-- Payment Details -->
            <div class="receipt-section">
                <div class="receipt-title">📋 Payment Receipt</div>
                
                <div class="receipt-row">
                    <span class="label">Transaction ID</span>
                    <span class="value">{{ $payment->reference_number }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Shop Name</span>
                    <span class="value">{{ $seller->shop_name }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Payment Method</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Payment Status</span>
                    <span class="value">
                        Completed
                        <span class="badge">Paid</span>
                    </span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Paid At</span>
                    <span class="value">{{ $payment->paid_at->format('M d, Y h:i A') }}</span>
                </div>
                
                <div class="amount-row">
                    <div class="label">Amount Paid</div>
                    <div class="value">₱{{ number_format($payment->amount, 2) }}</div>
                </div>
            </div>
            
            <!-- Subscription Details -->
            <div class="receipt-section">
                <div class="receipt-title">📅 Subscription Details</div>
                
                <div class="receipt-row">
                    <span class="label">Subscription Type</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $subscription->subscription_type)) }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Monthly Cost</span>
                    <span class="value">₱{{ number_format($subscription->amount, 2) }}</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Billing Cycle</span>
                    <span class="value">Monthly</span>
                </div>
                
                <div class="receipt-row">
                    <span class="label">Current Status</span>
                    <span class="value">
                        Active
                        <span class="badge">✓</span>
                    </span>
                </div>
            </div>
            
            <!-- Next Payment -->
            <div class="next-payment">
                <h4>⏰ Next Payment Due</h4>
                <p>Your next payment will be due on <strong>{{ $subscription->end_date->format('M d, Y') }}</strong></p>
            </div>
            
            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ route('seller.wallet.index') }}" class="cta-button">
                    Go to Wallet
                </a>
            </div>
            
            <!-- Additional Info -->
            <p style="margin-top: 2rem; font-size: 0.95rem; color: #6b7280;">
                If you need any assistance or have questions about your subscription, please contact our support team. You can also view your payment history anytime in your seller account.
            </p>
        </div>
        
        <div class="footer">
            <p><strong>{{ env('APP_NAME', 'U-KAY HUB') }}</strong></p>
            <p>Thank you for your business!</p>
            <p style="margin-top: 1rem; font-size: 0.85rem; opacity: 0.7;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
