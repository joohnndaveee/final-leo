{{-- Floating Chat Widget --}}
<style>
    /* Chat Widget Container */
    .chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: 'Montserrat', sans-serif;
    }

    /* Chat Button (Minimized State) */
    .chat-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(58, 199, 45, 0.4);
        transition: all 0.3s ease;
        position: relative;
    }

    .chat-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 30px rgba(58, 199, 45, 0.6);
    }

    .chat-button.hidden {
        display: none;
    }

    /* Unread Badge */
    .chat-button .unread-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff4444;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        font-size: 1.2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    /* Chat Window (Maximized State) */
    .chat-window {
        width: 380px;
        height: 550px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s ease;
    }

    .chat-window.active {
        display: flex;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Chat Header */
    .chat-window-header {
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        color: white;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-window-header-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .chat-window-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .chat-window-header-text h3 {
        font-size: 1.6rem;
        font-weight: 600;
        margin: 0 0 0.2rem 0;
    }

    .chat-window-header-text p {
        font-size: 1.2rem;
        margin: 0;
        opacity: 0.9;
    }

    .chat-window-controls {
        display: flex;
        gap: 0.5rem;
    }

    .chat-window-controls button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .chat-window-controls button:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Chat Messages Area */
    .chat-window-messages {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .chat-window-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-window-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-window-messages::-webkit-scrollbar-thumb {
        background: var(--main-color);
        border-radius: 10px;
    }

    /* Message Bubbles */
    .chat-message {
        display: flex;
        align-items: flex-end;
        gap: 0.8rem;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chat-message.user {
        justify-content: flex-end;
    }

    .chat-message.admin {
        justify-content: flex-start;
    }

    .chat-message > div {
        max-width: 85%;
        display: flex;
        flex-direction: column;
    }

    .chat-message-bubble {
        padding: 1rem 1.2rem;
        border-radius: 1.2rem;
        font-size: 1.4rem;
        line-height: 1.5;
        word-wrap: break-word;
        width: fit-content;
        display: inline-block;
    }

    .chat-message.user .chat-message-bubble {
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        color: white;
        border-bottom-right-radius: 0.3rem;
    }

    .chat-message.admin .chat-message-bubble {
        background: white;
        color: #2c3e50;
        border: 1px solid #e0e0e0;
        border-bottom-left-radius: 0.3rem;
    }

    .chat-message-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .chat-message-time {
        font-size: 1.1rem;
        color: #999;
        margin-top: 0.3rem;
        text-align: center;
    }

    /* Empty State */
    .chat-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #999;
        text-align: center;
        padding: 2rem;
    }

    .chat-empty i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .chat-empty h4 {
        font-size: 1.6rem;
        margin-bottom: 0.5rem;
        color: #666;
    }

    .chat-empty p {
        font-size: 1.3rem;
    }

    /* Chat Input Area */
    .chat-window-input {
        padding: 1.2rem;
        background: white;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 0.8rem;
        align-items: center;
    }

    .chat-window-input input {
        flex: 1;
        padding: 1rem 1.2rem;
        border: 1px solid #e0e0e0;
        border-radius: 25px;
        font-size: 1.4rem;
        outline: none;
        transition: all 0.3s ease;
    }

    .chat-window-input input:focus {
        border-color: var(--main-color);
        box-shadow: 0 0 0 3px rgba(58, 199, 45, 0.1);
    }

    .chat-window-input button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .chat-window-input button:hover:not(:disabled) {
        transform: scale(1.1);
    }

    .chat-window-input button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .chat-window {
            width: calc(100vw - 20px);
            height: calc(100vh - 80px);
            right: 10px;
            bottom: 70px;
        }

        .chat-button {
            width: 55px;
            height: 55px;
            font-size: 2.2rem;
        }
    }
</style>

<div class="chat-widget">
    {{-- Chat Button (Minimized) --}}
    <button class="chat-button" id="chatButton" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
        <span class="unread-badge" id="unreadBadge" style="display: none;">0</span>
    </button>

    {{-- Chat Window (Maximized) --}}
    <div class="chat-window" id="chatWindow">
        {{-- Header --}}
        <div class="chat-window-header">
            <div class="chat-window-header-info">
                <div class="chat-window-header-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="chat-window-header-text">
                    <h3>Admin Support</h3>
                    <p>We're here to help!</p>
                </div>
            </div>
            <div class="chat-window-controls">
                <button onclick="toggleChat()" title="Minimize">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-window-messages" id="chatWidgetMessages">
            <div class="chat-empty">
                <i class="fas fa-comments"></i>
                <h4>Start a conversation</h4>
                <p>Send a message to get help from our team</p>
            </div>
        </div>

        {{-- Input --}}
        <div class="chat-window-input">
            <input 
                type="text" 
                id="chatWidgetInput" 
                placeholder="Type your message..." 
                maxlength="1000"
                onkeypress="if(event.key === 'Enter') sendChatMessage()"
            >
            <button id="chatWidgetSendBtn" onclick="sendChatMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Chat widget state
    let isChatOpen = false;
    let lastMessageCount = 0;
    let chatPollingInterval = null;
    let currentMessages = [];
    let isFirstLoad = true;

    // Toggle chat window
    function toggleChat() {
        isChatOpen = !isChatOpen;
        const chatWindow = document.getElementById('chatWindow');
        const chatButton = document.getElementById('chatButton');
        
        if (isChatOpen) {
            chatWindow.classList.add('active');
            chatButton.classList.add('hidden');
            loadChatMessages();
            startChatPolling();
        } else {
            chatWindow.classList.remove('active');
            chatButton.classList.remove('hidden');
            stopChatPolling();
        }
    }

    // Send message
    function sendChatMessage() {
        const input = document.getElementById('chatWidgetInput');
        const sendBtn = document.getElementById('chatWidgetSendBtn');
        const message = input.value.trim();
        
        if (!message) return;

        // Disable input
        input.disabled = true;
        sendBtn.disabled = true;

        fetch('{{ route("chat.send") }}', {
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
                input.value = '';
                loadChatMessages();
            } else {
                alert('Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong');
        })
        .finally(() => {
            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
        });
    }

    // Load messages
    function loadChatMessages() {
        fetch('{{ route("chat.getMessages") }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderChatMessages(data.messages);
                updateUnreadBadge(data.messages);
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
    }

    // Render messages (smart update - only add new messages)
    function renderChatMessages(messages) {
        const container = document.getElementById('chatWidgetMessages');
        
        if (messages.length === 0) {
            container.innerHTML = `
                <div class="chat-empty">
                    <i class="fas fa-comments"></i>
                    <h4>Start a conversation</h4>
                    <p>Send a message to get help from our team</p>
                </div>
            `;
            currentMessages = [];
            return;
        }

        // Check if we need full re-render (first load or message count decreased)
        if (isFirstLoad || messages.length < currentMessages.length) {
            // Full render
            container.innerHTML = messages.map(msg => createMessageHTML(msg)).join('');
            currentMessages = messages;
            isFirstLoad = false;
            scrollToBottom(container);
            return;
        }

        // Smart update - only add new messages
        const newMessages = messages.slice(currentMessages.length);
        if (newMessages.length > 0) {
            const fragment = document.createDocumentFragment();
            newMessages.forEach(msg => {
                const div = document.createElement('div');
                div.innerHTML = createMessageHTML(msg);
                fragment.appendChild(div.firstElementChild);
            });
            container.appendChild(fragment);
            currentMessages = messages;
            scrollToBottom(container, true); // Smooth scroll for new messages
        }
    }

    // Create message HTML
    function createMessageHTML(msg) {
        const date = new Date(msg.created_at);
        const time = date.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });

        if (msg.sender_type === 'user') {
            return `
                <div class="chat-message user" data-id="${msg.id}">
                    <div>
                        <div class="chat-message-bubble">${escapeHtml(msg.message)}</div>
                        <div class="chat-message-time">${time}</div>
                    </div>
                </div>
            `;
        } else {
            return `
                <div class="chat-message admin" data-id="${msg.id}">
                    <div class="chat-message-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="chat-message-bubble">${escapeHtml(msg.message)}</div>
                        <div class="chat-message-time">${time}</div>
                    </div>
                </div>
            `;
        }
    }

    // Scroll to bottom
    function scrollToBottom(container, smooth = false) {
        if (smooth) {
            container.scrollTo({
                top: container.scrollHeight,
                behavior: 'smooth'
            });
        } else {
            container.scrollTop = container.scrollHeight;
        }
    }

    // Update unread badge
    function updateUnreadBadge(messages) {
        if (!isChatOpen) {
            const unreadCount = messages.filter(m => 
                m.sender_type === 'admin' && !m.is_read
            ).length;
            
            const badge = document.getElementById('unreadBadge');
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        } else {
            document.getElementById('unreadBadge').style.display = 'none';
        }
    }

    // Start polling
    function startChatPolling() {
        if (!chatPollingInterval) {
            chatPollingInterval = setInterval(loadChatMessages, 3000);
        }
    }

    // Stop polling
    function stopChatPolling() {
        if (chatPollingInterval) {
            clearInterval(chatPollingInterval);
            chatPollingInterval = null;
        }
    }

    // Escape HTML
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

    // Initial load (check for unread messages)
    loadChatMessages();
    
    // Poll for new messages even when closed (to update badge)
    setInterval(() => {
        if (!isChatOpen) {
            loadChatMessages();
        }
    }, 5000);
</script>
