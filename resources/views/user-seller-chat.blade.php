@extends('layouts.app')

@section('title', 'Chat with ' . ($seller->shop_name ?? 'Seller') . ' - U-KAY HUB')

@push('styles')
<style>
.usc-wrap {
    max-width: 860px;
    margin: 2rem auto;
    padding: 0 1.6rem 3rem;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
}
.usc-back {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-size: 1.3rem;
    color: #16a34a;
    text-decoration: none;
    margin-bottom: 1.2rem;
    font-weight: 500;
    transition: color .15s;
}
.usc-back:hover { color: #15803d; }
.usc-box {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 680px;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
}
/* Header */
.usc-head {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    padding: 1.4rem 1.8rem;
    background: linear-gradient(135deg, #14532d, #16a34a);
    color: #fff;
    flex-shrink: 0;
}
.usc-head-logo {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    background: #fff;
    border: 2px solid rgba(255,255,255,.4);
    flex-shrink: 0;
}
.usc-head-logo-placeholder {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    color: #fff;
    font-size: 1.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.usc-head-name { font-size: 1.7rem; font-weight: 700; line-height: 1.2; }
.usc-head-sub { font-size: 1.15rem; opacity: .8; margin-top: .15rem; }
.usc-head-actions { margin-left: auto; }
.usc-view-shop {
    font-size: 1.25rem;
    color: rgba(255,255,255,.85);
    text-decoration: none;
    border: 1px solid rgba(255,255,255,.4);
    padding: .4rem .9rem;
    border-radius: 4px;
    transition: background .15s;
    white-space: nowrap;
}
.usc-view-shop:hover { background: rgba(255,255,255,.15); }

/* Messages area */
.usc-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.6rem 1.8rem;
    display: flex;
    flex-direction: column;
    gap: .9rem;
    background: #f9fafb;
}
.usc-messages::-webkit-scrollbar { width: 5px; }
.usc-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

.usc-msg {
    display: flex;
    flex-direction: column;
    max-width: 70%;
}
.usc-msg.mine  { align-self: flex-end; align-items: flex-end; }
.usc-msg.theirs { align-self: flex-start; align-items: flex-start; }

.usc-bubble {
    padding: .75rem 1.1rem;
    border-radius: 16px;
    font-size: 1.38rem;
    line-height: 1.55;
    word-break: break-word;
}
.usc-msg.mine .usc-bubble {
    background: #16a34a;
    color: #fff;
    border-bottom-right-radius: 4px;
}
.usc-msg.theirs .usc-bubble {
    background: #fff;
    color: #333;
    border: 1px solid #e5e7eb;
    border-bottom-left-radius: 4px;
}
.usc-time {
    font-size: 1.1rem;
    color: #9ca3af;
    margin-top: .25rem;
    padding: 0 .3rem;
}

.usc-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 1.4rem;
    gap: .6rem;
}
.usc-empty i { font-size: 3.5rem; opacity: .4; }

/* Input area */
.usc-input-area {
    display: flex;
    align-items: flex-end;
    gap: .8rem;
    padding: 1.2rem 1.8rem;
    border-top: 1px solid #f0f0f0;
    background: #fff;
    flex-shrink: 0;
}
.usc-textarea {
    flex: 1;
    border: 1.5px solid #e5e7eb;
    border-radius: 20px;
    padding: .75rem 1.2rem;
    font-size: 1.38rem;
    font-family: inherit;
    outline: none;
    resize: none;
    max-height: 120px;
    line-height: 1.5;
    transition: border-color .18s;
    color: #333;
}
.usc-textarea:focus { border-color: #16a34a; }
.usc-send-btn {
    width: 44px;
    height: 44px;
    background: #16a34a;
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background .18s, transform .15s;
}
.usc-send-btn:hover { background: #15803d; transform: scale(1.05); }
.usc-send-btn:disabled { background: #d1d5db; cursor: not-allowed; transform: none; }
</style>
@endpush

@section('content')
@php
    $shopLogo = !empty($seller->shop_logo) ? asset('uploaded_img/' . $seller->shop_logo) : null;
    $shopName = $seller->shop_name ?? 'Seller';
@endphp

<div class="usc-wrap">
    <a href="{{ route('user.seller.chats') }}" class="usc-back">
        <i class="fas fa-arrow-left"></i> Back to Messages
    </a>

    <div class="usc-box">
        {{-- Header --}}
        <div class="usc-head">
            @if($shopLogo)
                <img src="{{ $shopLogo }}" alt="{{ $shopName }}" class="usc-head-logo"
                     onerror="this.style.display='none'">
            @else
                <div class="usc-head-logo-placeholder">{{ strtoupper(substr($shopName,0,1)) }}</div>
            @endif
            <div>
                <div class="usc-head-name">{{ $shopName }}</div>
                <div class="usc-head-sub">Seller · Active recently</div>
            </div>
            <div class="usc-head-actions">
                <a href="{{ route('seller.shop', $seller->id) }}" class="usc-view-shop">
                    <i class="fas fa-store"></i> View Shop
                </a>
            </div>
        </div>

        {{-- Messages --}}
        <div class="usc-messages" id="uscMessages">
            @if($messages->isEmpty())
                <div class="usc-empty">
                    <i class="fas fa-comment-dots"></i>
                    <span>No messages yet. Say hi to {{ $shopName }}!</span>
                </div>
            @else
                @foreach($messages as $msg)
                    <div class="usc-msg {{ $msg->sender_type === 'user' ? 'mine' : 'theirs' }}">
                        <div class="usc-bubble">{{ $msg->message }}</div>
                        <span class="usc-time">{{ $msg->created_at->format('h:i A') }}</span>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Input --}}
        <div class="usc-input-area">
            <textarea class="usc-textarea" id="uscInput"
                      placeholder="Type a message…"
                      rows="1"></textarea>
            <button type="button" class="usc-send-btn" id="uscSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const messagesEl = document.getElementById('uscMessages');
    const input      = document.getElementById('uscInput');
    const sendBtn    = document.getElementById('uscSend');
    const csrf       = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const sendUrl    = '{{ route("user.seller.chat.send", $seller->id) }}';
    const pollUrl    = '{{ route("user.seller.chat.messages", $seller->id) }}';
    let lastCount    = {{ $messages->count() }};

    function scrollBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function renderMessages(messages) {
        if (!messages.length) return;
        messagesEl.innerHTML = messages.map(m => `
            <div class="usc-msg ${m.sender_type === 'user' ? 'mine' : 'theirs'}">
                <div class="usc-bubble">${escHtml(m.message)}</div>
                <span class="usc-time">${m.time}</span>
            </div>
        `).join('');
        scrollBottom();
    }

    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
    }

    async function poll() {
        try {
            const res  = await fetch(pollUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (data.messages && data.messages.length !== lastCount) {
                lastCount = data.messages.length;
                renderMessages(data.messages);
            }
        } catch (_) {}
    }

    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;
        sendBtn.disabled = true;
        input.value = '';
        input.style.height = 'auto';

        try {
            await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text }),
            });
            await poll();
        } catch (_) {} finally {
            sendBtn.disabled = false;
            input.focus();
        }
    }

    sendBtn.addEventListener('click', sendMessage);

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Auto-resize textarea
    input.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    scrollBottom();
    setInterval(poll, 3000);
})();
</script>
@endpush
