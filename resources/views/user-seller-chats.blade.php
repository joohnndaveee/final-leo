@extends('layouts.app')

@section('title', 'My Messages - U-KAY HUB')

@push('styles')
<style>
.usc-list-wrap {
    max-width: 760px;
    margin: 2.4rem auto;
    padding: 0 1.6rem 3rem;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
}
.usc-list-head {
    font-size: 2.2rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 1.6rem;
    display: flex;
    align-items: center;
    gap: .7rem;
}
.usc-list-head i { color: #16a34a; }
.usc-conv-card {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    padding: 1.4rem 1.6rem;
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    margin-bottom: .7rem;
    text-decoration: none;
    color: inherit;
    transition: box-shadow .18s, transform .18s;
}
.usc-conv-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); transform: translateY(-1px); }
.usc-conv-logo {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    background: #16a34a;
    flex-shrink: 0;
    border: 2px solid #f0fdf4;
}
.usc-conv-logo-placeholder {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #16a34a;
    color: #fff;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.usc-conv-body { flex: 1; min-width: 0; }
.usc-conv-name { font-size: 1.5rem; font-weight: 600; color: #111; margin-bottom: .3rem; }
.usc-conv-preview {
    font-size: 1.3rem;
    color: #6b7280;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.usc-conv-preview.unread { color: #111; font-weight: 600; }
.usc-conv-right { display: flex; flex-direction: column; align-items: flex-end; gap: .4rem; flex-shrink: 0; }
.usc-conv-time { font-size: 1.15rem; color: #9ca3af; }
.usc-unread-badge {
    background: #16a34a;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 700;
    border-radius: 999px;
    padding: .15rem .55rem;
    min-width: 20px;
    text-align: center;
}
.usc-empty-state {
    text-align: center;
    padding: 5rem 1rem;
    color: #9ca3af;
    font-size: 1.5rem;
}
.usc-empty-state i { font-size: 4rem; display: block; margin-bottom: 1rem; opacity: .4; }
</style>
@endpush

@section('content')
<div class="usc-list-wrap">
    <h1 class="usc-list-head"><i class="fas fa-comments"></i> My Messages</h1>

    @if($conversations->isEmpty())
        <div class="usc-empty-state">
            <i class="fas fa-comment-slash"></i>
            <p>No conversations yet.<br>Visit a seller's shop and start chatting!</p>
        </div>
    @else
        @foreach($conversations as $conv)
            @php
                $seller   = $conv->seller;
                $last     = $conv->last_message;
                $shopName = $seller->shop_name ?? 'Seller';
                $shopLogo = !empty($seller->shop_logo) ? asset('uploaded_img/' . $seller->shop_logo) : null;
                $unread   = (int) $conv->unread_count;
                $preview  = $last ? ($last->sender_type === 'user' ? 'You: ' : '') . Str::limit($last->message, 60) : '';
            @endphp
            <a href="{{ route('user.seller.chat.show', $seller->id) }}" class="usc-conv-card">
                @if($shopLogo)
                    <img src="{{ $shopLogo }}" alt="{{ $shopName }}" class="usc-conv-logo"
                         onerror="this.style.display='none'">
                @else
                    <div class="usc-conv-logo-placeholder">{{ strtoupper(substr($shopName, 0, 1)) }}</div>
                @endif
                <div class="usc-conv-body">
                    <div class="usc-conv-name">{{ $shopName }}</div>
                    <div class="usc-conv-preview {{ $unread ? 'unread' : '' }}">{{ $preview }}</div>
                </div>
                <div class="usc-conv-right">
                    @if($last)
                        <span class="usc-conv-time">{{ $last->created_at->diffForHumans(null, true) }}</span>
                    @endif
                    @if($unread)
                        <span class="usc-unread-badge">{{ $unread }}</span>
                    @endif
                </div>
            </a>
        @endforeach
    @endif
</div>
@endsection
