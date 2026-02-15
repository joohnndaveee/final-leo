@extends('layouts.admin')

@section('title', 'Seller Chats - Admin Panel')

@push('styles')
<style>
    .chats-container { padding: 2rem; max-width: 1200px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid #e5e7eb; }
    .page-header h1 { font-size: 2.5rem; color: var(--black); font-weight: 700; display: flex; align-items: center; gap: 1rem; }
    .conversations-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.5rem; }
    .conversation-card {
        background: white; border: 1px solid #e5e7eb; border-radius: 1rem; padding: 1.5rem;
        transition: all 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        text-decoration: none; color: inherit; display: block;
    }
    .conversation-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-color: var(--main-color); }
    .conversation-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
    .user-avatar { width: 48px; height: 48px; border-radius: 50%; background: #1a3009; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; }
    .user-details h3 { font-size: 1.5rem; margin: 0 0 0.3rem; }
    .user-details p { font-size: 1.2rem; color: #6b7280; margin: 0; }
    .unread-badge { background: #ef4444; color: white; font-size: 1.1rem; padding: 0.2rem 0.6rem; border-radius: 999px; }
    .btn-view { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; background: var(--main-color); color: white; text-decoration: none; border-radius: 8px; font-size: 1.3rem; font-weight: 600; }
    .empty-conversations { text-align: center; padding: 6rem 2rem; color: #6b7280; }
</style>
@endpush

@section('content')
<div class="chats-container">
    <div class="page-header">
        <h1><i class="fas fa-store"></i> Seller Chats</h1>
        <span>{{ $conversations->count() }} {{ $conversations->count() === 1 ? 'Conversation' : 'Conversations' }}</span>
    </div>
    @if($conversations->count() > 0)
        <div class="conversations-grid">
            @foreach($conversations as $conversation)
                <a href="{{ route('admin.seller-chats.show', $conversation['seller']->id) }}" class="conversation-card">
                    <div class="conversation-header">
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <div class="user-avatar"><i class="fas fa-store"></i></div>
                            <div class="user-details">
                                <h3>{{ $conversation['seller']->shop_name ?? $conversation['seller']->name }}</h3>
                                <p>{{ $conversation['seller']->email }}</p>
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 1.2rem; color: #9ca3af;">{{ \Carbon\Carbon::parse($conversation['last_message_time'])->diffForHumans() }}</div>
                            @if($conversation['unread_count'] > 0)
                                <span class="unread-badge">{{ $conversation['unread_count'] }} new</span>
                            @endif
                        </div>
                    </div>
                    <span class="btn-view"><i class="fas fa-comments"></i> View Chat</span>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-conversations">
            <i class="fas fa-comments" style="font-size: 5rem; margin-bottom: 1rem;"></i>
            <h2>No Seller Chats Yet</h2>
            <p>Sellers can contact you via Chat with Admin from their dashboard.</p>
        </div>
    @endif
</div>
@endsection
