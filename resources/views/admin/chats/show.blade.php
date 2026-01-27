@extends('layouts.admin')

@section('title', 'Chat with ' . $user->name . ' - Admin Panel')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .chat-container {
        padding: 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem 2rem;
        background: rgba(255, 255, 255, 0.9);
        color: var(--black);
        text-decoration: none;
        border-radius: 1rem;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .back-button:hover {
        background: var(--main-color);
        color: white;
        border-color: var(--main-color);
        transform: translateX(-5px);
    }

    .chat-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 700px;
    }

    .chat-header {
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
    }

    .user-info {
        flex: 1;
    }

    .user-info h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }

    .user-info p {
        font-size: 1.3rem;
        opacity: 0.9;
    }

    .chat-messages {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .message-wrapper {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
    }

    .message-wrapper.user {
        justify-content: flex-start;
    }

    .message-wrapper.admin {
        justify-content: flex-end;
    }

    /* Chat message bubble - scoped to avoid conflicts */
    .chat-messages .chat-bubble {
        max-width: 70%;
        padding: 1.2rem 1.8rem;
        border-radius: 1.5rem;
        font-size: 1.5rem;
        line-height: 1.6;
        position: relative;
        word-wrap: break-word;
    }

    .message-wrapper.user .chat-bubble {
        background: white;
        color: #2c3e50;
        border: 2px solid #e0e0e0;
        border-bottom-left-radius: 0.3rem;
    }

    .message-wrapper.admin .chat-bubble {
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        color: white;
        border-bottom-right-radius: 0.3rem;
    }

    .message-time {
        font-size: 1.2rem;
        opacity: 0.7;
        margin-top: 0.5rem;
        color: #666;
    }

    .message-wrapper.user .message-time {
        text-align: left;
    }

    .message-wrapper.admin .message-time {
        text-align: right;
    }

    .user-message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 1.6rem;
        flex-shrink: 0;
    }

    .chat-input-container {
        padding: 2rem;
        background: white;
        border-top: 2px solid #e0e0e0;
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .chat-input {
        flex: 1;
        padding: 1.2rem 1.5rem;
        font-size: 1.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .chat-input:focus {
        outline: none;
        border-color: var(--main-color);
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
    }

    .send-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .send-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 20px rgba(39, 174, 96, 0.4);
    }

    .send-btn:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        transform: none;
    }

    /* Scrollbar styling */
    .chat-messages::-webkit-scrollbar {
        width: 8px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: var(--main-color);
        border-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .chat-box {
            height: calc(100vh - 200px);
        }

        .message {
            max-width: 85%;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-container">
    {{-- Back Button --}}
    <a href="{{ route('admin.chats.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Conversations
    </a>

    {{-- Chat Box --}}
    <div class="chat-box">
        {{-- Chat Header --}}
        <div class="chat-header">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
            </div>
        </div>

        {{-- Chat Messages --}}
        <div class="chat-messages" id="chatMessages">
            @foreach($messages as $message)
                <div class="message-wrapper {{ $message->sender_type }}">
                    @if($message->sender_type == 'user')
                        <div class="user-message-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div>
                        <div class="chat-bubble">
                            {{ $message->message }}
                        </div>
                        <div class="message-time">
                            {{ $message->created_at->format('M d, Y - h:i A') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Chat Input --}}
        <div class="chat-input-container">
            <input type="text" id="messageInput" class="chat-input" placeholder="Type your reply..." maxlength="1000">
            <button type="button" id="sendBtn" class="send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const chatMessages = document.getElementById('chatMessages');
    const userId = {{ $user->id }};

    // Scroll to bottom of messages
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Initial scroll
    scrollToBottom();

    // Send message
    function sendMessage() {
        const message = messageInput.value.trim();
        
        if (!message) {
            return;
        }

        // Disable input while sending
        sendBtn.disabled = true;
        messageInput.disabled = true;

        fetch(`/admin/chats/${userId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear input
                messageInput.value = '';
                
                // Load messages
                loadMessages();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to send message',
                    confirmButtonColor: '#3ac72d'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong',
                confirmButtonColor: '#3ac72d'
            });
        })
        .finally(() => {
            sendBtn.disabled = false;
            messageInput.disabled = false;
            messageInput.focus();
        });
    }

    // Load messages
    function loadMessages() {
        fetch(`/admin/chats/${userId}/messages`, {
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderMessages(data.messages);
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
    }

    // Render messages
    function renderMessages(messages) {
        chatMessages.innerHTML = messages.map(msg => {
            const date = new Date(msg.created_at);
            const formattedDate = date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });

            if (msg.sender_type === 'user') {
                return `
                    <div class="message-wrapper user">
                        <div class="user-message-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="chat-bubble">${escapeHtml(msg.message)}</div>
                            <div class="message-time">${formattedDate}</div>
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="message-wrapper admin">
                        <div>
                            <div class="chat-bubble">${escapeHtml(msg.message)}</div>
                            <div class="message-time">${formattedDate}</div>
                        </div>
                    </div>
                `;
            }
        }).join('');

        scrollToBottom();
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Event listeners
    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Poll for new messages every 3 seconds
    setInterval(loadMessages, 3000);
</script>
@endpush
