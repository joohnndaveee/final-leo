@extends('layouts.seller')

@section('title', 'Chat with ' . ($buyer->name ?? 'Buyer') . ' - Seller Center')

@push('styles')
<style>
.sbc-wrap { max-width: 800px; margin: 0 auto; }
.sbc-back {
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
.sbc-back:hover { color: #15803d; }
.sbc-box {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 640px;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
}
.sbc-head {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.3rem 1.6rem;
    background: linear-gradient(135deg, #14532d, #16a34a);
    color: #fff;
    flex-shrink: 0;
}
.sbc-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    color: #fff;
    font-size: 1.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.sbc-head-name { font-size: 1.6rem; font-weight: 700; }
.sbc-head-sub { font-size: 1.15rem; opacity: .8; }
.sbc-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.4rem 1.6rem;
    display: flex;
    flex-direction: column;
    gap: .85rem;
    background: #f9fafb;
}
.sbc-messages::-webkit-scrollbar { width: 4px; }
.sbc-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
.sbc-msg { display: flex; flex-direction: column; max-width: 70%; }
.sbc-msg.mine   { align-self: flex-end;   align-items: flex-end; }
.sbc-msg.theirs { align-self: flex-start; align-items: flex-start; }
.sbc-bubble {
    padding: .7rem 1.05rem;
    border-radius: 16px;
    font-size: 1.35rem;
    line-height: 1.55;
    word-break: break-word;
}
.sbc-msg.mine .sbc-bubble   { background: #16a34a; color: #fff; border-bottom-right-radius: 4px; }
.sbc-msg.theirs .sbc-bubble { background: #fff; color: #333; border: 1px solid #e5e7eb; border-bottom-left-radius: 4px; }
.sbc-time { font-size: 1.05rem; color: #9ca3af; margin-top: .2rem; padding: 0 .2rem; }
.sbc-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 1.38rem;
    gap: .5rem;
}
.sbc-empty i { font-size: 3rem; opacity: .35; }
.sbc-input-area {
    display: flex;
    align-items: flex-end;
    gap: .7rem;
    padding: 1.1rem 1.6rem;
    border-top: 1px solid #f0f0f0;
    background: #fff;
    flex-shrink: 0;
}
.sbc-textarea {
    flex: 1;
    border: 1.5px solid #e5e7eb;
    border-radius: 20px;
    padding: .7rem 1.1rem;
    font-size: 1.35rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    resize: none;
    max-height: 110px;
    line-height: 1.5;
    transition: border-color .18s;
    color: #333;
}
.sbc-textarea:focus { border-color: #16a34a; }
.sbc-send {
    width: 42px;
    height: 42px;
    background: #16a34a;
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.45rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background .18s, transform .15s;
}
.sbc-send:hover  { background: #15803d; transform: scale(1.05); }
.sbc-send:disabled { background: #d1d5db; cursor: not-allowed; transform: none; }
</style>
@endpush

@section('content')
<div class="sbc-wrap">
    <a href="{{ route('seller.buyer.chats') }}" class="sbc-back">
        <i class="fas fa-arrow-left"></i> Back to Inbox
    </a>

    <div class="sbc-box">
        <div class="sbc-head">
            <div class="sbc-avatar">{{ strtoupper(substr($buyer->name ?? 'U', 0, 1)) }}</div>
            <div>
                <div class="sbc-head-name">{{ $buyer->name ?? 'Buyer' }}</div>
                <div class="sbc-head-sub">{{ $buyer->email }}</div>
            </div>
        </div>

        <div class="sbc-messages" id="sbcMessages">
            @if($messages->isEmpty())
                <div class="sbc-empty">
                    <i class="fas fa-comment-dots"></i>
                    <span>No messages yet.</span>
                </div>
            @else
                @foreach($messages as $msg)
                    <div class="sbc-msg {{ $msg->sender_type === 'seller' ? 'mine' : 'theirs' }}">
                        <div class="sbc-bubble">{{ $msg->message }}</div>
                        <span class="sbc-time">{{ $msg->created_at->format('h:i A') }}</span>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="sbc-input-area">
            <textarea class="sbc-textarea" id="sbcInput"
                      placeholder="Type a message…" rows="1"></textarea>
            <button type="button" class="sbc-send" id="sbcSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const el      = document.getElementById('sbcMessages');
    const input   = document.getElementById('sbcInput');
    const sendBtn = document.getElementById('sbcSend');
    const csrf    = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const sendUrl = '{{ route("seller.buyer.chat.send", $userId) }}';
    const pollUrl = '{{ route("seller.buyer.chat.messages", $userId) }}';
    let lastCount = {{ $messages->count() }};

    function scrollBottom() { el.scrollTop = el.scrollHeight; }

    function esc(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
    }

    function render(messages) {
        el.innerHTML = messages.map(m => `
            <div class="sbc-msg ${m.sender_type === 'seller' ? 'mine' : 'theirs'}">
                <div class="sbc-bubble">${esc(m.message)}</div>
                <span class="sbc-time">${m.time}</span>
            </div>`).join('');
        scrollBottom();
    }

    async function poll() {
        try {
            const res  = await fetch(pollUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (data.messages && data.messages.length !== lastCount) {
                lastCount = data.messages.length;
                render(data.messages);
            }
        } catch (_) {}
    }

    async function send() {
        const text = input.value.trim();
        if (!text) return;
        sendBtn.disabled = true;
        input.value = '';
        input.style.height = 'auto';
        try {
            await fetch(sendUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ message: text }),
            });
            await poll();
        } catch (_) {} finally {
            sendBtn.disabled = false;
            input.focus();
        }
    }

    sendBtn.addEventListener('click', send);
    input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); } });
    input.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 110) + 'px';
    });

    scrollBottom();
    setInterval(poll, 3000);
})();
</script>
@endpush
