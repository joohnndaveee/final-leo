@extends('layouts.seller')

@section('title', 'Account Suspension - Violation Details')

@push('styles')
<style>
    .violations-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 0;
    }
    .violations-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #fee2e2;
    }
    .violations-header {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        padding: 2rem;
        text-align: center;
        border-bottom: 1px solid #fecaca;
    }
    .violations-header-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1rem;
        background: #dc2626;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.4rem;
    }
    .violations-header h1 {
        font-size: 2.2rem;
        color: #991b1b;
        margin: 0 0 0.5rem 0;
        font-weight: 700;
    }
    .violations-header p {
        font-size: 1.4rem;
        color: #7f1d1d;
        margin: 0;
    }
    .violations-body {
        padding: 2rem;
    }
    .violations-detail {
        margin-bottom: 2rem;
    }
    .violations-detail:last-of-type {
        margin-bottom: 0;
    }
    .violations-detail-label {
        display: block;
        font-size: 1.2rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    .violations-detail-value {
        font-size: 1.5rem;
        color: #1f2937;
    }
    .violations-notes {
        background: #fef2f2;
        border-left: 4px solid #dc2626;
        padding: 1.5rem;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: #7f1d1d;
        margin-top: 1rem;
    }
    .violations-footer {
        background: #f9fafb;
        padding: 2rem;
        border-top: 1px solid #e5e7eb;
    }
    .violations-footer h3 {
        font-size: 1.6rem;
        color: #374151;
        margin: 0 0 1rem 0;
        font-weight: 600;
    }
    .violations-footer p {
        font-size: 1.4rem;
        color: #6b7280;
        line-height: 1.6;
        margin: 0 0 1.5rem 0;
    }
    .violations-footer a {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: #dc2626;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 1.4rem;
        font-weight: 600;
        transition: background 0.2s;
    }
    .violations-footer a:hover {
        background: #b91c1c;
    }
</style>
@endpush

@section('content')
<div class="violations-container">
    <div class="violations-card">
        <div class="violations-header">
            <div class="violations-header-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1>Account Suspended</h1>
            <p>Your seller account has been suspended due to a violation</p>
        </div>

        <div class="violations-body">
            <div class="violations-detail">
                <span class="violations-detail-label">Suspension Reason</span>
                <span class="violations-detail-value">{{ $seller->suspension_reason ?? 'Administrative Action' }}</span>
            </div>

            @if($seller->suspended_at)
            <div class="violations-detail">
                <span class="violations-detail-label">Date Suspended</span>
                <span class="violations-detail-value">{{ $seller->suspended_at->format('F j, Y \a\t g:i A') }}</span>
            </div>
            @endif

            @if($seller->suspension_notes)
            <div class="violations-detail">
                <span class="violations-detail-label">Details</span>
                <div class="violations-notes">{{ $seller->suspension_notes }}</div>
            </div>
            @endif
        </div>

        <div class="violations-footer">
            <h3>What to do next</h3>
            <p>If you believe this suspension was made in error or would like to appeal, use the live chat below to message the administrator. Include your shop name and any relevant details.</p>
            <a href="{{ route('seller.chat') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 1.5rem; background: #22c55e; color: white; text-decoration: none; border-radius: 8px; font-size: 1.4rem; font-weight: 600; margin-bottom: 1.5rem;">
                <i class="fas fa-comments"></i> Chat with Admin (Live)
            </a>

            @if(session('success'))
                <div class="violations-success" style="background: #d1fae5; border: 1px solid #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #065f46;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('seller.support.send') }}" method="POST" class="violations-support-form">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label for="subject" style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 1.4rem;">Subject</label>
                    <input type="text" name="subject" id="subject" value="Appeal: Account Suspension - {{ $seller->shop_name ?? $seller->name }}" required
                        style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1.4rem;">
                    @error('subject')<span style="color: #dc2626; font-size: 1.2rem;">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="message" style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 1.4rem;">Message</label>
                    <textarea name="message" id="message" rows="4" required placeholder="Explain your situation and provide any relevant details..."
                        style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1.4rem;"></textarea>
                    @error('message')<span style="color: #dc2626; font-size: 1.2rem;">{{ $message }}</span>@enderror
                </div>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 1.5rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-size: 1.4rem; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-paper-plane"></i> Send Message to Admin
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
