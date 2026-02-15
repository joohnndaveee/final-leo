@extends('layouts.seller')

@section('title', 'Chat with Admin - Seller Center')

@push('styles')
<style>
    .chat-section { padding: 2rem; max-width: 900px; margin: 0 auto; }
    .chat-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 650px;
    }
    .chat-header {
        background: linear-gradient(135deg, #1a3009, #2d5016);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    .chat-header h1 { font-size: 2.2rem; margin: 0 0 0.5rem; }
    .chat-header p { font-size: 1.4rem; opacity: 0.9; margin: 0; }
    .chat-messages {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
        background: #f8faf9;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .message-wrapper { display: flex; align-items: flex-end; gap: 1rem; }
    .message-wrapper.seller { justify-content: flex-end; }
    .message-wrapper.admin { justify-content: flex-start; }
    .chat-messages .chat-bubble {
        max-width: 70%;
        padding: 1.2rem 1.6rem;
        border-radius: 1.2rem;
        font-size: 1.45rem;
        line-height: 1.5;
    }
    .message-wrapper.seller .chat-bubble {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border-bottom-right-radius: 0.3rem;
    }
    .message-wrapper.admin .chat-bubble {
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 0.3rem;
    }
    .message-time { font-size: 1.2rem; opacity: 0.8; margin-top: 0.5rem; }
    .admin-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: #1a3009;
        display: flex; align-items: center; justify-content: center;
        color: white; flex-shrink: 0;
    }
    .chat-input-container {
        padding: 1.5rem;
        background: white;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    .chat-input {
        flex: 1;
        padding: 1rem 1.5rem;
        font-size: 1.45rem;
        border: 1px solid #d1d5db;
        border-radius: 12px;
    }
    .chat-input:focus { outline: none; border-color: #22c55e; }
    .send-btn {
        width: 50px; height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .send-btn:hover { opacity: 0.9; }
    .empty-chat { text-align: center; padding: 4rem 2rem; color: #6b7280; }
    .empty-chat i { font-size: 5rem; margin-bottom: 1.5rem; opacity: 0.5; }
</style>
@endpush

@section('content')
<section class="chat-section">
    <div class="chat-container">
        <div class="chat-header">
            <h1><i class="fas fa-comments"></i> Chat with Admin</h1>
            <p>Contact support for account, subscription, or violation appeals</p>
        </div>
        <div class="chat-messages" id="chatMessages">
            @if($messages->count() > 0)
                @foreach($messages as $message)
                    <div class="message-wrapper {{ $message->sender_type }}">
                        @if($message->sender_type === 'admin')
                            <div class="admin-avatar"><i class="fas fa-user-shield"></i></div>
                        @endif
                        <div>
                            <div class="chat-bubble">{{ $message->message }}</div>
                            <div class="message-time">{{ $message->created_at->format('M d, h:i A') }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-chat">
                    <i class="fas fa-comments"></i>
                    <h3>Start a conversation</h3>
                    <p>Send a message to admin. We'll respond as soon as possible.</p>
                </div>
            @endif
        </div>
        <div class="chat-input-container">
            <input type="text" id="messageInput" class="chat-input" placeholder="Type your message..." maxlength="1000">
            <button type="button" id="sendBtn" class="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const chatMessages = document.getElementById('chatMessages');

    function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
    scrollToBottom();

    function escapeHtml(text) {
        const map = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function renderMessages(messages) {
        if (!messages.length) {
            chatMessages.innerHTML = '<div class="empty-chat"><i class="fas fa-comments"></i><h3>Start a conversation</h3><p>Send a message to admin. We\'ll respond as soon as possible.</p></div>';
            return;
        }
        chatMessages.innerHTML = messages.map(msg => {
            const d = new Date(msg.created_at);
            const dateStr = d.toLocaleDateString('en-US', {month:'short',day:'numeric',hour:'numeric',minute:'2-digit',hour12:true});
            const bubble = '<div class="chat-bubble">'+escapeHtml(msg.message)+'</div><div class="message-time">'+dateStr+'</div>';
            if (msg.sender_type === 'seller') {
                return '<div class="message-wrapper seller"><div>'+bubble+'</div></div>';
            } else {
                return '<div class="message-wrapper admin"><div class="admin-avatar"><i class="fas fa-user-shield"></i></div><div>'+bubble+'</div></div>';
            }
        }).join('');
        scrollToBottom();
    }

    function sendMessage() {
        const msg = messageInput.value.trim();
        if (!msg) return;
        sendBtn.disabled = true;
        fetch('{{ route("seller.chat.send") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
            body: JSON.stringify({message: msg})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { messageInput.value = ''; loadMessages(); }
        })
        .finally(() => { sendBtn.disabled = false; messageInput.focus(); });
    }

    function loadMessages() {
        fetch('{{ route("seller.chat.messages") }}', {headers: {'Accept':'application/json'}})
            .then(r => r.json())
            .then(data => { if (data.success) renderMessages(data.messages); });
    }

    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
    setInterval(loadMessages, 3000);
});
</script>
@endsection
