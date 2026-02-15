@extends('layouts.admin')

@section('title', 'Chat with ' . ($seller->shop_name ?? $seller->name) . ' - Admin Panel')

@push('styles')
<style>
    .chat-container { padding: 2rem; max-width: 1000px; margin: 0 auto; }
    .back-button { display: inline-flex; align-items: center; gap: 0.8rem; padding: 1rem 2rem; background: white; color: var(--black); text-decoration: none; border-radius: 1rem; font-size: 1.5rem; font-weight: 600; margin-bottom: 2rem; border: 2px solid #e5e7eb; }
    .back-button:hover { background: var(--main-color); color: white; border-color: var(--main-color); }
    .chat-box { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; display: flex; flex-direction: column; height: 650px; }
    .chat-header { background: linear-gradient(135deg, #1a3009, #2d5016); color: white; padding: 1.5rem 2rem; display: flex; align-items: center; gap: 1.2rem; }
    .user-avatar { width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 2rem; }
    .user-info h2 { font-size: 1.8rem; margin: 0 0 0.2rem; }
    .user-info p { font-size: 1.3rem; opacity: 0.9; margin: 0; }
    .chat-messages { flex: 1; padding: 2rem; overflow-y: auto; background: #f8faf9; display: flex; flex-direction: column; gap: 1.5rem; }
    .message-wrapper { display: flex; align-items: flex-end; gap: 1rem; }
    .message-wrapper.seller { justify-content: flex-start; }
    .message-wrapper.admin { justify-content: flex-end; }
    .chat-messages .chat-bubble { max-width: 70%; padding: 1.2rem 1.6rem; border-radius: 1.2rem; font-size: 1.5rem; line-height: 1.5; }
    .message-wrapper.seller .chat-bubble { background: white; border: 1px solid #e5e7eb; border-bottom-left-radius: 0.3rem; }
    .message-wrapper.admin .chat-bubble { background: linear-gradient(135deg, var(--main-color), #27ae60); color: white; border-bottom-right-radius: 0.3rem; }
    .message-time { font-size: 1.2rem; opacity: 0.8; margin-top: 0.5rem; color: #666; }
    .user-message-avatar { width: 40px; height: 40px; border-radius: 50%; background: #1a3009; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
    .chat-input-container { padding: 1.5rem; background: white; border-top: 1px solid #e5e7eb; display: flex; gap: 1rem; align-items: center; }
    .chat-input { flex: 1; padding: 1rem 1.5rem; font-size: 1.5rem; border: 1px solid #d1d5db; border-radius: 12px; }
    .send-btn { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--main-color), #27ae60); color: white; border: none; cursor: pointer; font-size: 2rem; display: flex; align-items: center; justify-content: center; }
</style>
@endpush

@section('content')
<div class="chat-container">
    <a href="{{ route('admin.seller-chats.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Seller Chats
    </a>
    <div class="chat-box">
        <div class="chat-header">
            <div class="user-avatar"><i class="fas fa-store"></i></div>
            <div class="user-info">
                <h2>{{ $seller->shop_name ?? $seller->name }}</h2>
                <p>{{ $seller->email }}</p>
            </div>
        </div>
        <div class="chat-messages" id="chatMessages">
            @foreach($messages as $message)
                <div class="message-wrapper {{ $message->sender_type }}">
                    @if($message->sender_type === 'seller')
                        <div class="user-message-avatar"><i class="fas fa-store"></i></div>
                    @endif
                    <div>
                        <div class="chat-bubble">{{ $message->message }}</div>
                        <div class="message-time">{{ $message->created_at->format('M d, h:i A') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="chat-input-container">
            <input type="text" id="messageInput" class="chat-input" placeholder="Type your reply..." maxlength="1000">
            <button type="button" id="sendBtn" class="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');
const chatMessages = document.getElementById('chatMessages');
const sellerId = {{ $seller->id }};

function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
scrollToBottom();

function escapeHtml(text) {
    const map = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

function renderMessages(messages) {
    chatMessages.innerHTML = messages.map(msg => {
        const d = new Date(msg.created_at);
        const dateStr = d.toLocaleDateString('en-US', {month:'short',day:'numeric',hour:'numeric',minute:'2-digit',hour12:true});
        const bubble = '<div class="chat-bubble">'+escapeHtml(msg.message)+'</div><div class="message-time">'+dateStr+'</div>';
        if (msg.sender_type === 'seller') {
            return '<div class="message-wrapper seller"><div class="user-message-avatar"><i class="fas fa-store"></i></div><div>'+bubble+'</div></div>';
        } else {
            return '<div class="message-wrapper admin"><div>'+bubble+'</div></div>';
        }
    }).join('');
    scrollToBottom();
}

function sendMessage() {
    const msg = messageInput.value.trim();
    if (!msg) return;
    sendBtn.disabled = true;
    fetch(`/admin/seller-chats/${sellerId}/reply`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
        body: JSON.stringify({message: msg})
    })
    .then(r => r.json())
    .then(data => { if (data.success) { messageInput.value = ''; loadMessages(); } })
    .finally(() => { sendBtn.disabled = false; messageInput.focus(); });
}

function loadMessages() {
    fetch(`/admin/seller-chats/${sellerId}/messages`, {headers: {'Accept':'application/json'}})
        .then(r => r.json())
        .then(data => { if (data.success) renderMessages(data.messages); });
}

sendBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
setInterval(loadMessages, 3000);
</script>
@endsection
