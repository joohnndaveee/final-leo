@extends('layouts.seller')

@section('title', 'Buyer Messages - Seller Center')

@section('content')
<div style="font-family:'Inter',sans-serif;max-width:800px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
        <h1 style="font-size:2.2rem;font-weight:700;color:#0f172a;margin:0;display:flex;align-items:center;gap:.7rem;">
            <i class="fas fa-inbox" style="color:#16a34a;"></i> Buyer Messages
        </h1>
        @if($conversations->isNotEmpty())
            <span style="font-size:1.3rem;color:#6b7280;">{{ $conversations->count() }} conversation{{ $conversations->count() != 1 ? 's' : '' }}</span>
        @endif
    </div>

    @if($conversations->isEmpty())
        <div style="text-align:center;padding:5rem 1rem;color:#9ca3af;font-size:1.5rem;background:#fff;border-radius:12px;border:1px solid #e8e8e8;">
            <i class="fas fa-comment-slash" style="font-size:4rem;display:block;margin-bottom:1rem;opacity:.4;"></i>
            <p>No buyer messages yet.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:.6rem;">
            @foreach($conversations as $conv)
                @php
                    $buyer  = $conv->user;
                    $last   = $conv->last_message;
                    $unread = (int) $conv->unread_count;
                    $preview = $last ? ($last->sender_type === 'seller' ? 'You: ' : '') . \Illuminate\Support\Str::limit($last->message, 55) : '';
                @endphp
                <a href="{{ route('seller.buyer.chat.show', $buyer->id) }}"
                   style="display:flex;align-items:center;gap:1.2rem;padding:1.4rem 1.6rem;background:#fff;border:1px solid #e8e8e8;border-radius:10px;text-decoration:none;color:inherit;transition:box-shadow .18s,transform .18s;"
                   onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.08)';this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.boxShadow='';this.style.transform=''">

                    {{-- Avatar --}}
                    <div style="width:50px;height:50px;border-radius:50%;background:#16a34a;color:#fff;font-size:2rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        {{ strtoupper(substr($buyer->name ?? 'U', 0, 1)) }}
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:1.5rem;font-weight:600;color:#111;margin-bottom:.3rem;">
                            {{ $buyer->name ?? 'Unknown User' }}
                            @if($unread)
                                <span style="display:inline-block;background:#16a34a;color:#fff;font-size:1.05rem;font-weight:700;border-radius:999px;padding:.1rem .5rem;margin-left:.4rem;">{{ $unread }}</span>
                            @endif
                        </div>
                        <div style="font-size:1.28rem;color:{{ $unread ? '#111' : '#6b7280' }};font-weight:{{ $unread ? '600' : '400' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $preview }}
                        </div>
                    </div>

                    {{-- Time --}}
                    @if($last)
                    <div style="font-size:1.15rem;color:#9ca3af;flex-shrink:0;">
                        {{ $last->created_at->diffForHumans(null, true) }}
                    </div>
                    @endif

                    <i class="fas fa-chevron-right" style="color:#d1d5db;flex-shrink:0;"></i>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
