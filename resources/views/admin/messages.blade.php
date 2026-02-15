@extends('layouts.admin')

@section('title', 'Contact Inquiries - Admin Panel')

@push('styles')
<style>
    .messages-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
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

    .messages-list {
        display: grid;
        gap: 1.5rem;
    }

    .message-card {
        background: #f9fff7;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.8rem 2rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .message-name-email {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .message-name {
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--main-color);
        text-transform: lowercase;
    }

    .message-email {
        font-size: 1.4rem;
        color: var(--light-color);
    }

    .message-email a {
        color: var(--main-color);
        text-decoration: none;
    }

    .message-email a:hover {
        text-decoration: underline;
    }

    .message-time {
        font-size: 1.3rem;
        color: #9ca3af;
        white-space: nowrap;
    }

    .message-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 0.8rem 0 1rem;
    }

    .message-row {
        font-size: 1.5rem;
        color: #4b5563;
        margin-bottom: 0.4rem;
    }

    .message-row strong {
        color: #374151;
        margin-right: 0.4rem;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    .empty-messages {
        text-align: center;
        padding: 6rem 2rem;
        background: var(--white);
        border-radius: 1rem;
        border: 1px dashed #e5e7eb;
    }

    .empty-messages i {
        font-size: 6rem;
        color: var(--light-color);
        margin-bottom: 1.5rem;
    }

    .empty-messages h2 {
        font-size: 2.2rem;
        color: var(--black);
        margin-bottom: 0.8rem;
    }

    .empty-messages p {
        font-size: 1.6rem;
        color: var(--light-color);
    }

    .messages-tab.active {
        background: var(--main-color) !important;
        color: white !important;
    }

    .message-source-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .message-source-badge.guest { background: #e5e7eb; color: #374151; }
    .message-source-badge.seller { background: #dbeafe; color: #1e40af; }

    @media (max-width: 640px) {
        .message-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .message-time {
            align-self: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="messages-container">
    <div class="page-header">
        <h1>
            <i class="fas fa-envelope"></i>
            @if(request('source') === 'seller')
                Seller Messages
            @else
                Guest Inquiries
            @endif
        </h1>
        <div class="messages-source-tabs" style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
            <a href="{{ route('admin.messages', array_merge(request()->except('source'), ['source' => 'guest'])) }}"
               class="messages-tab {{ !request('source') || request('source') === 'guest' ? 'active' : '' }}"
               style="padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.4rem; background: #e5e7eb; color: #374151;">
                <i class="fas fa-user-clock"></i> Guests
            </a>
            <a href="{{ route('admin.messages', array_merge(request()->except('source'), ['source' => 'seller'])) }}"
               class="messages-tab {{ request('source') === 'seller' ? 'active' : '' }}"
               style="padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.4rem; background: #e5e7eb; color: #374151;">
                <i class="fas fa-store"></i> Sellers
            </a>
        </div>
    </div>

    @if($messages->count() > 0)
        <div class="messages-list">
            @foreach($messages as $message)
                <div class="message-card">
                    <div class="message-header">
                        <div class="message-name-email">
                            <div class="message-name">{{ $message->name }}
                                <span class="message-source-badge {{ $message->source ?? 'guest' }}">{{ $message->source ?? 'guest' }}</span>
                            </div>
                            <div class="message-email">
                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                            </div>
                        </div>
                        <div class="message-time">
                            {{ $message->created_at ? $message->created_at->format('M d, Y - h:i A') : 'N/A' }}
                        </div>
                    </div>

                    <div class="message-divider"></div>

                    <div class="message-row">
                        <strong>Subject:</strong> {{ $message->subject }}
                    </div>
                    <div class="message-row">
                        <strong>Message:</strong> {{ $message->message }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-container">
            {{ $messages->links() }}
        </div>
    @else
        <div class="empty-messages">
            <i class="fas fa-inbox"></i>
            <h2>No Messages Found</h2>
            <p>
                @if(request('source') === 'seller')
                    Seller support messages will appear here when sellers contact admin.
                @else
                    Guest inquiries from the contact form will appear here.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection

