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
    .message-content { display: flex; flex-direction: column; max-width: 70%; }
    .message-wrapper.seller .message-content { align-items: flex-end; }
    .message-wrapper.admin .message-content { align-items: flex-start; }
    .chat-messages .chat-bubble {
        max-width: 100%;
        padding: 1.2rem 1.6rem;
        border-radius: 1.2rem;
        font-size: 1.45rem;
        line-height: 1.5;
        white-space: pre-wrap;
        overflow-wrap: break-word;
        word-break: normal;
        display: inline-block;
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
    .typing-bubble {
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 0.3rem;
        padding: 1.2rem 1.6rem;
        border-radius: 1.2rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        max-width: 70%;
    }
    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #9ca3af;
        display: inline-block;
        animation: typingBounce 1.2s infinite ease-in-out;
    }
    .typing-dot:nth-child(2) { animation-delay: 0.15s; }
    .typing-dot:nth-child(3) { animation-delay: 0.3s; }
    @keyframes typingBounce {
        0%, 80%, 100% { transform: translateY(0); opacity: 0.5; }
        40% { transform: translateY(-4px); opacity: 1; }
    }
    .chat-input-container {
        padding: 1.5rem;
        background: white;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .attach-btn {
        width: 50px; height: 50px;
        border-radius: 50%;
        background: white;
        color: #1a3009;
        border: 1px solid #d1d5db;
        cursor: pointer;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .attach-btn:hover { background: #f9fafb; }
    .file-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        font-size: 1.2rem;
        color: #374151;
        background: #fff;
        margin-top: 0.5rem;
        max-width: 70%;
    }
    .file-chip .remove { cursor: pointer; font-weight: 700; }
    .file-list { margin-top: 0.6rem; display: flex; flex-direction: column; gap: 0.4rem; }
    .file-link { font-size: 1.25rem; text-decoration: underline; color: inherit; cursor: pointer; }
    .chat-input {
        flex: 1;
        min-width: 220px;
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

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 2rem;
    }
    .modal-overlay.open { display: flex; }
    .modal {
        width: min(920px, 95vw);
        max-height: 90vh;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(0,0,0,0.35);
        display: flex;
        flex-direction: column;
    }
    .modal-header {
        padding: 1.2rem 1.6rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e5e7eb;
    }
    .modal-title { font-size: 1.5rem; font-weight: 700; color: #111827; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .modal-actions { display: flex; gap: 0.8rem; align-items: center; }
    .modal-btn {
        padding: 0.8rem 1.2rem;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;
        cursor: pointer;
        font-size: 1.3rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        white-space: nowrap;
    }
    .modal-btn:hover { background: #f3f4f6; }
    .modal-close {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        border: 1px solid #d1d5db;
        background: #fff;
        cursor: pointer;
        font-size: 2rem;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .modal-close:hover { background: #f3f4f6; }
    .modal-body { padding: 1.2rem 1.6rem 1.6rem; overflow: auto; }
    .modal-preview img { max-width: 100%; height: auto; border-radius: 12px; border: 1px solid #e5e7eb; }
    .modal-preview iframe { width: 100%; height: 75vh; border: 1px solid #e5e7eb; border-radius: 12px; }
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
                        <div class="message-content">
                            <div class="chat-bubble">
                                @if(trim((string) $message->message) !== '')
                                    <div>{{ $message->message }}</div>
                                @endif
                                @if($message->files && $message->files->count() > 0)
                                    <div class="file-list">
                                        @foreach($message->files as $file)
                                            <a href="{{ route('seller.chat.files.view', $file) }}"
                                               class="file-link js-file-link"
                                               data-view="{{ route('seller.chat.files.view', $file) }}"
                                               data-download="{{ route('seller.chat.files.download', $file) }}"
                                               data-mime="{{ $file->mime }}"
                                               data-name="{{ $file->original_name }}">
                                                {{ $file->original_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
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
            <input type="file" id="attachmentInput" accept="image/*,.pdf" multiple style="display:none;">
            <input type="text" id="messageInput" class="chat-input" placeholder="Type your message..." maxlength="1000">
            <button type="button" id="attachBtn" class="attach-btn" title="Attach file"><i class="fas fa-paperclip"></i></button>
            <button type="button" id="sendBtn" class="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
        <div id="fileChipHost" style="padding: 0 1.5rem 1.2rem; background: white;"></div>
    </div>
</section>

<div class="modal-overlay" id="fileModal" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="fileModalTitle">
        <div class="modal-header">
            <div class="modal-title" id="fileModalTitle">Attachment</div>
            <div class="modal-actions">
                <a class="modal-btn" id="fileModalDownload" href="#" download>
                    <i class="fas fa-download"></i> Download
                </a>
                <button type="button" class="modal-close" id="fileModalClose" aria-label="Close">×</button>
            </div>
        </div>
        <div class="modal-body">
            <div class="modal-preview" id="fileModalPreview"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const chatMessages = document.getElementById('chatMessages');
    const attachBtn = document.getElementById('attachBtn');
    const attachmentInput = document.getElementById('attachmentInput');
    const fileChipHost = document.getElementById('fileChipHost');

    function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
    scrollToBottom();

    function escapeHtml(text) {
        const map = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    let selectedFiles = [];

    function renderFileChips() {
        if (!selectedFiles.length) {
            fileChipHost.innerHTML = '';
            return;
        }

        fileChipHost.innerHTML = selectedFiles.map((file, idx) => `
            <div class="file-chip" title="${escapeHtml(file.name)}">
                <i class="fas fa-paperclip"></i>
                <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width: 360px;">${escapeHtml(file.name)}</span>
                <span class="remove" data-remove-index="${idx}" aria-label="Remove">×</span>
            </div>
        `).join('');
    }

    function addFiles(list) {
        const incoming = Array.from(list || []);
        const spaceLeft = Math.max(0, 5 - selectedFiles.length);

        if (incoming.length > spaceLeft) {
            alert('You can attach up to 5 files only.');
        }

        for (const file of incoming.slice(0, spaceLeft)) {
            selectedFiles.push(file);
        }

        renderFileChips();
    }

    function removeFileAt(index) {
        selectedFiles = selectedFiles.filter((_, i) => i !== index);
        renderFileChips();
    }

    function openFileModal({ name, mime, viewUrl, downloadUrl }) {
        const modal = document.getElementById('fileModal');
        const title = document.getElementById('fileModalTitle');
        const preview = document.getElementById('fileModalPreview');
        const download = document.getElementById('fileModalDownload');

        title.textContent = name || 'Attachment';
        download.href = downloadUrl || viewUrl || '#';
        download.setAttribute('download', name || '');

        const m = String(mime || '');
        if (m.startsWith('image/')) {
            preview.innerHTML = `<img src="${viewUrl}" alt="${escapeHtml(name || 'Attachment')}">`;
        } else if (m === 'application/pdf') {
            preview.innerHTML = `<iframe src="${viewUrl}" title="${escapeHtml(name || 'PDF')}"></iframe>`;
        } else {
            preview.innerHTML = `<div style="font-size:1.4rem; color:#374151;">Preview not available. Please download the file.</div>`;
        }

        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeFileModal() {
        const modal = document.getElementById('fileModal');
        const preview = document.getElementById('fileModalPreview');
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        preview.innerHTML = '';
        document.body.style.overflow = '';
    }

    document.getElementById('fileModalClose').addEventListener('click', closeFileModal);
    document.getElementById('fileModal').addEventListener('click', (e) => {
        if (e.target && e.target.id === 'fileModal') closeFileModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeFileModal();
    });

    chatMessages.addEventListener('click', (e) => {
        const a = e.target.closest('.js-file-link');
        if (!a) return;
        e.preventDefault();
        openFileModal({
            name: a.dataset.name,
            mime: a.dataset.mime,
            viewUrl: a.dataset.view || a.getAttribute('href'),
            downloadUrl: a.dataset.download,
        });
    });

    const displayedIds = new Set();
    const pendingAdmin = new Map(); // id -> { msg, showAt }
    let lastServerMessages = [];

    function nowMs() { return Date.now(); }

    function fileListHtml(files) {
        const base = '{{ url('/seller/chat/files') }}';
        const list = Array.isArray(files) ? files : [];
        if (!list.length) return '';
        const links = list.map(f => {
            const name = escapeHtml(f.original_name || 'Attachment');
            const mime = escapeHtml(f.mime || '');
            const viewUrl = `${base}/${f.id}/view`;
            const downloadUrl = `${base}/${f.id}/download`;
            return `<a href="${viewUrl}" class="file-link js-file-link" data-view="${viewUrl}" data-download="${downloadUrl}" data-mime="${mime}" data-name="${name}">${name}</a>`;
        }).join('');
        return `<div class="file-list">${links}</div>`;
    }

    function renderMessages(messages, showTyping) {
        if (!messages.length && !showTyping) {
            chatMessages.innerHTML = '<div class="empty-chat"><i class="fas fa-comments"></i><h3>Start a conversation</h3><p>Send a message to admin. We\'ll respond as soon as possible.</p></div>';
            return;
        }

        const rows = messages.map(msg => {
            const d = new Date(msg.created_at);
            const dateStr = d.toLocaleDateString('en-US', {month:'short',day:'numeric',hour:'numeric',minute:'2-digit',hour12:true});
            const text = (msg.message || '').trim();
            const textHtml = text ? `<div>${escapeHtml(msg.message || '')}</div>` : '';
            const filesHtml = fileListHtml(msg.files);
            const bubble = '<div class="chat-bubble">'+(textHtml + filesHtml)+'</div>' + '<div class="message-time">'+dateStr+'</div>';

            if (msg.sender_type === 'seller') {
                return '<div class="message-wrapper seller"><div class="message-content">'+bubble+'</div></div>';
            }
            return '<div class="message-wrapper admin"><div class="admin-avatar"><i class="fas fa-user-shield"></i></div><div class="message-content">'+bubble+'</div></div>';
        });

        if (showTyping) {
            rows.push(
                '<div class="message-wrapper admin">' +
                    '<div class="admin-avatar"><i class="fas fa-user-shield"></i></div>' +
                    '<div class="message-content">' +
                        '<div class="typing-bubble" aria-label="Admin is typing">' +
                            '<span class="typing-dot"></span>' +
                            '<span class="typing-dot"></span>' +
                            '<span class="typing-dot"></span>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        }

        chatMessages.innerHTML = rows.join('');
        scrollToBottom();
    }

    function syncFromServer(serverMessages) {
        const isFirstLoad = lastServerMessages.length === 0;
        lastServerMessages = serverMessages || [];

        if (isFirstLoad) {
            for (const msg of lastServerMessages) displayedIds.add(msg.id);
            pendingAdmin.clear();
            renderMessages(lastServerMessages, false);
            return;
        }

        for (const msg of lastServerMessages) {
            if (displayedIds.has(msg.id) || pendingAdmin.has(msg.id)) continue;

            if (msg.sender_type === 'admin') {
                pendingAdmin.set(msg.id, { msg, showAt: nowMs() + 2500 }); // 2.5s delay
            } else {
                displayedIds.add(msg.id);
            }
        }

        renderFiltered();
    }

    function renderFiltered() {
        const t = nowMs();
        let showTyping = false;

        for (const [id, entry] of pendingAdmin.entries()) {
            if (t >= entry.showAt) {
                displayedIds.add(id);
                pendingAdmin.delete(id);
            } else {
                showTyping = true;
            }
        }

        const visible = lastServerMessages.filter(m => displayedIds.has(m.id));
        renderMessages(visible, showTyping);
    }

    function sendMessage() {
        const msg = messageInput.value.trim();
        if (!msg && selectedFiles.length === 0) return;

        sendBtn.disabled = true;
        const form = new FormData();
        form.append('message', msg);
        for (const file of selectedFiles) {
            form.append('attachments[]', file);
        }

        fetch('{{ route("seller.chat.send") }}', {
            method: 'POST',
            headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
            body: form
        })
        .then(r => r.json())
        .then(data => {
            if (!data || data.success !== true) {
                alert((data && data.message) ? data.message : 'Failed to send message.');
                return;
            }

            messageInput.value = '';
            selectedFiles = [];
            attachmentInput.value = '';
            renderFileChips();
            loadMessages();
        })
        .catch(() => {
            alert('Network/Server error while sending. Please try again.');
        })
        .then(() => {
            sendBtn.disabled = false;
            messageInput.focus();
        });
    }

    function loadMessages() {
        fetch('{{ route("seller.chat.messages") }}', {headers: {'Accept':'application/json'}})
            .then(r => r.json())
            .then(data => { if (data.success) syncFromServer(data.messages); });
    }

    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
    attachBtn.addEventListener('click', () => attachmentInput.click());
    attachmentInput.addEventListener('change', () => {
        addFiles(attachmentInput.files);
        attachmentInput.value = '';
    });
    fileChipHost.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-remove-index]');
        if (!btn) return;
        const idx = parseInt(btn.getAttribute('data-remove-index'), 10);
        if (Number.isFinite(idx)) removeFileAt(idx);
    });

    loadMessages();
    setInterval(loadMessages, 3000);
    setInterval(renderFiltered, 250);
});
</script>
@endsection
