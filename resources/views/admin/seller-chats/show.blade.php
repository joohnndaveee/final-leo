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
    .message-content { display: flex; flex-direction: column; max-width: 70%; }
    .message-wrapper.seller .message-content { align-items: flex-start; margin-right: auto; }
    .message-wrapper.admin .message-content { align-items: flex-end; margin-left: auto; }
    .chat-messages .chat-bubble { max-width: 100%; padding: 1.2rem 1.6rem; border-radius: 1.2rem; font-size: 1.5rem; line-height: 1.5; white-space: pre-wrap; overflow-wrap: break-word; word-break: normal; display: inline-block; }
    .message-wrapper.seller .chat-bubble { background: white; border: 1px solid #e5e7eb; border-bottom-left-radius: 0.3rem; }
    .message-wrapper.admin .chat-bubble { background: linear-gradient(135deg, var(--main-color), #27ae60); color: white; border-bottom-right-radius: 0.3rem; }
    .message-time { font-size: 1.2rem; opacity: 0.8; margin-top: 0.5rem; color: #666; }
    .user-message-avatar { width: 40px; height: 40px; border-radius: 50%; background: #1a3009; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
    .chat-input-container { padding: 1.5rem; background: white; border-top: 1px solid #e5e7eb; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
    .quick-reply-select { width: 240px; padding: 1rem 1.2rem; font-size: 1.4rem; border: 1px solid #d1d5db; border-radius: 12px; background: #fff; }
    .quick-reply-btn { padding: 1rem 1.3rem; font-size: 1.35rem; border-radius: 12px; border: 1px solid #d1d5db; background: #fff; cursor: pointer; font-weight: 600; }
    .quick-reply-btn:hover { background: #f3f4f6; }
    .chat-input { flex: 1; min-width: 260px; padding: 1rem 1.5rem; font-size: 1.5rem; border: 1px solid #d1d5db; border-radius: 12px; resize: none; min-height: 50px; }
    .send-btn { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--main-color), #27ae60); color: white; border: none; cursor: pointer; font-size: 2rem; display: flex; align-items: center; justify-content: center; }
    .file-list { margin-top: 0.6rem; display: flex; flex-direction: column; gap: 0.4rem; }
    .file-link { font-size: 1.25rem; text-decoration: underline; color: inherit; cursor: pointer; }

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
                    <div class="message-content">
                        <div class="chat-bubble">
                            @if(trim((string) $message->message) !== '')
                                <div>{{ $message->message }}</div>
                            @endif
                            @if($message->files && $message->files->count() > 0)
                                <div class="file-list">
                                    @foreach($message->files as $file)
                                        <a href="{{ route('admin.seller-chats.files.view', [$seller->id, $file]) }}"
                                           class="file-link js-file-link"
                                           data-view="{{ route('admin.seller-chats.files.view', [$seller->id, $file]) }}"
                                           data-download="{{ route('admin.seller-chats.files.download', [$seller->id, $file]) }}"
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
        </div>
        <div class="chat-input-container">
            <select id="quickReplySelect" class="quick-reply-select" title="Quick reply templates">
                <option value="">Quick reply…</option>
                @foreach(($quickReplies ?? []) as $i => $reply)
                    <option value="{{ $i }}">{{ $reply['label'] }}</option>
                @endforeach
            </select>
            <button type="button" id="useTemplateBtn" class="quick-reply-btn" title="Insert selected template">
                Use
            </button>
            <textarea id="messageInput" class="chat-input" placeholder="Type your reply... (Ctrl+Enter to send)" maxlength="1000" rows="2"></textarea>
            <button type="button" id="sendBtn" class="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

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
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');
const chatMessages = document.getElementById('chatMessages');
const sellerId = {{ $seller->id }};
const quickReplySelect = document.getElementById('quickReplySelect');
const useTemplateBtn = document.getElementById('useTemplateBtn');
const quickReplies = @json($quickReplies ?? []);

function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }
scrollToBottom();

function escapeHtml(text) {
    const map = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
    return String(text).replace(/[&<>"']/g, m => map[m]);
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

function renderMessages(messages) {
    const base = `/admin/seller-chats/${sellerId}/files`;
    chatMessages.innerHTML = messages.map(msg => {
        const d = new Date(msg.created_at);
        const dateStr = d.toLocaleDateString('en-US', {month:'short',day:'numeric',hour:'numeric',minute:'2-digit',hour12:true});

        const text = (msg.message || '').trim();
        const textHtml = text ? `<div>${escapeHtml(msg.message || '')}</div>` : '';
        const files = Array.isArray(msg.files) ? msg.files : [];
        const filesHtml = files.length
            ? `<div class="file-list">` + files.map(f => {
                const name = escapeHtml(f.original_name || 'Attachment');
                const mime = escapeHtml(f.mime || '');
                const viewUrl = `${base}/${f.id}/view`;
                const downloadUrl = `${base}/${f.id}/download`;
                return `<a href="${viewUrl}" class="file-link js-file-link" data-view="${viewUrl}" data-download="${downloadUrl}" data-mime="${mime}" data-name="${name}">${name}</a>`;
            }).join('') + `</div>`
            : '';

        const bubble = '<div class="chat-bubble">'+(textHtml + filesHtml)+'</div>' + '<div class="message-time">'+dateStr+'</div>';
        if (msg.sender_type === 'seller') {
            return '<div class="message-wrapper seller"><div class="user-message-avatar"><i class="fas fa-store"></i></div><div class="message-content">'+bubble+'</div></div>';
        }
        return '<div class="message-wrapper admin"><div class="message-content">'+bubble+'</div></div>';
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
    .then(data => {
        if (!data || data.success !== true) {
            alert((data && data.message) ? data.message : 'Failed to send message.');
            return;
        }
        messageInput.value = '';
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

function insertTemplate() {
    const idx = quickReplySelect.value;
    if (idx === '' || !quickReplies[idx]) return;
    messageInput.value = quickReplies[idx].text;
    messageInput.focus();
}

quickReplySelect.addEventListener('change', insertTemplate);
useTemplateBtn.addEventListener('click', insertTemplate);

function loadMessages() {
    fetch(`/admin/seller-chats/${sellerId}/messages`, {headers: {'Accept':'application/json'}})
        .then(r => r.json())
        .then(data => { if (data.success) renderMessages(data.messages); });
}

sendBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
        e.preventDefault();
        sendMessage();
    }
});
setInterval(loadMessages, 3000);
</script>
@endsection
