{{-- Shopee-style floating chat panel --}}
<style>
/* ─── Floating trigger button ─────────────────── */
.cw-trigger {
    position: fixed;
    bottom: 0;
    right: 24px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: .55rem;
    background: #16a34a;
    color: #fff;
    border: none;
    border-radius: 6px 6px 0 0;
    padding: .8rem 1.6rem;
    font-size: 1.45rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 -2px 14px rgba(22,163,74,.3);
    font-family: 'DM Sans','Segoe UI',sans-serif;
    transition: background .18s;
    line-height: 1;
}
.cw-trigger:hover { background: #15803d; }
.cw-trigger i { font-size: 1.55rem; }
.cw-trigger-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ee4d2d;
    color: #fff;
    font-size: 1.05rem;
    font-weight: 700;
    border-radius: 999px;
    min-width: 20px;
    height: 20px;
    padding: 0 .4rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    border: 2px solid #fff;
}

/* ─── Panel ───────────────────────────────────── */
.cw-panel {
    position: fixed;
    bottom: 0;
    right: 24px;
    z-index: 9998;
    width: 620px;
    height: 540px;
    background: #fff;
    border-radius: 8px 8px 0 0;
    box-shadow: 0 -4px 30px rgba(0,0,0,.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: cwUp .22s ease;
    font-family: 'DM Sans','Segoe UI',sans-serif;
}
.cw-panel.open { display: flex; }
@keyframes cwUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ─── Panel header bar ────────────────────────── */
.cw-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.4rem 1rem;
    border-bottom: 1px solid #f0f0f0;
    flex-shrink: 0;
    background: #fff;
}
.cw-panel-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #111;
    display: flex;
    align-items: baseline;
    gap: .4rem;
}
.cw-panel-title .cw-count {
    font-size: 1.55rem;
    font-weight: 500;
    color: #555;
}
.cw-head-btns { display: flex; align-items: center; gap: .3rem; }
.cw-head-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #666;
    font-size: 1.5rem;
    cursor: pointer;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .15s, color .15s;
}
.cw-head-btn:hover { background: #f5f5f5; color: #111; }

/* ─── Body (left + right) ─────────────────────── */
.cw-body {
    display: flex;
    flex: 1;
    min-height: 0;
}

/* ─── Left: conversation list ─────────────────── */
.cw-left {
    width: 300px;
    flex-shrink: 0;
    border-right: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
}
.cw-left-toolbar {
    padding: .8rem 1.1rem;
    border-bottom: 1px solid #f5f5f5;
    flex-shrink: 0;
}
.cw-search-row {
    display: flex;
    align-items: center;
    gap: .5rem;
}
.cw-search-box {
    flex: 1;
    display: flex;
    align-items: center;
    gap: .45rem;
    background: #f5f5f5;
    border-radius: 4px;
    padding: .55rem .9rem;
}
.cw-search-box i { color: #aaa; font-size: 1.2rem; flex-shrink: 0; }
.cw-search-box input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 1.28rem;
    color: #333;
    flex: 1;
    font-family: inherit;
    width: 0;
    min-width: 0;
}
.cw-filter-btn {
    display: flex;
    align-items: center;
    gap: .3rem;
    padding: .5rem .75rem;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 1.25rem;
    color: #555;
    background: #fff;
    cursor: pointer;
    font-family: inherit;
    white-space: nowrap;
    transition: border-color .15s;
    flex-shrink: 0;
}
.cw-filter-btn:hover { border-color: #16a34a; }

.cw-conv-list {
    flex: 1;
    overflow-y: auto;
}
.cw-conv-list::-webkit-scrollbar { width: 4px; }
.cw-conv-list::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 4px; }

.cw-conv-item {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: .95rem 1.2rem;
    cursor: pointer;
    transition: background .15s;
    border-bottom: 1px solid #fafafa;
}
.cw-conv-item:hover  { background: #f9f9f9; }
.cw-conv-item.active { background: #f0fdf4; }

.cw-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    flex-shrink: 0;
    background: #16a34a;
    color: #fff;
    font-size: 1.6rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}
.cw-avatar.admin-bg  { background: #1e3a5f; }
.cw-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; position:absolute; inset:0; }

.cw-conv-info { flex: 1; min-width: 0; }
.cw-conv-top  {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: .4rem;
    margin-bottom: .2rem;
}
.cw-conv-name {
    font-size: 1.32rem;
    font-weight: 600;
    color: #111;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
    min-width: 0;
}
.cw-conv-time { font-size: 1.1rem; color: #aaa; flex-shrink: 0; }
.cw-conv-bot  { display: flex; align-items: center; justify-content: space-between; gap: .4rem; }
.cw-conv-preview {
    font-size: 1.2rem;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}
.cw-conv-preview.fw { color: #111; font-weight: 600; }
.cw-unread-dot {
    background: #ee4d2d;
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    border-radius: 999px;
    min-width: 18px;
    height: 18px;
    padding: 0 .35rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* loading state */
.cw-list-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem;
    color: #aaa;
    font-size: 1.3rem;
    gap: .6rem;
}
.cw-spinner {
    width: 16px; height: 16px;
    border: 2px solid #e5e7eb;
    border-top-color: #16a34a;
    border-radius: 50%;
    animation: cwSpin .7s linear infinite;
}
@keyframes cwSpin { to { transform: rotate(360deg); } }

/* ─── Right: chat pane ────────────────────────── */
.cw-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background: #f5f5f5;
}

/* Welcome state */
.cw-welcome {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: #f5f5f5;
    text-align: center;
}
.cw-welcome-icon {
    position: relative;
    width: 120px;
    height: 90px;
    margin-bottom: 1.4rem;
}
.cw-welcome-icon .laptop {
    width: 100px;
    height: 70px;
    background: #e5e7eb;
    border-radius: 8px 8px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.cw-welcome-icon .laptop-inner {
    width: 80px;
    height: 52px;
    background: #d1d5db;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 8px;
}
.cw-msg-bubble-icon {
    background: #60a5fa;
    border-radius: 4px;
    height: 100%;
    flex: 1;
}
.cw-chat-bubble-icon {
    background: #ee4d2d;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    position: absolute;
    bottom: 10px;
    right: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cw-chat-bubble-icon::before,
.cw-chat-bubble-icon::after {
    content: '';
    position: absolute;
    background: #fff;
    border-radius: 2px;
}
.cw-chat-bubble-icon::before { width: 12px; height: 2px; top: 10px; }
.cw-chat-bubble-icon::after  { width: 8px;  height: 2px; top: 14px; }
.cw-welcome h4 { font-size: 1.65rem; font-weight: 700; color: #333; margin: 0 0 .4rem; }
.cw-welcome p  { font-size: 1.28rem; color: #888; }

/* Active chat */
.cw-chat-head {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: 1rem 1.2rem;
    background: #fff;
    border-bottom: 1px solid #f0f0f0;
    flex-shrink: 0;
}
.cw-chat-head-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: #16a34a; color: #fff;
    font-size: 1.4rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden; position: relative;
}
.cw-chat-head-avatar.admin-bg { background: #1e3a5f; }
.cw-chat-head-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; position:absolute; inset:0; }
.cw-chat-head-name { font-size: 1.38rem; font-weight: 700; color: #111; }
.cw-chat-head-sub  { font-size: 1.15rem; color: #888; }

/* Safety tip banner */
.cw-safety-tip {
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    padding: .75rem 1.2rem;
    background: #fffbeb;
    border-bottom: 1px solid #fde68a;
    font-size: 1.18rem;
    color: #78350f;
    line-height: 1.5;
    flex-shrink: 0;
}
.cw-safety-tip strong { font-weight: 700; }

.cw-msgs {
    flex: 1;
    overflow-y: auto;
    padding: 1.2rem;
    display: flex;
    flex-direction: column;
    gap: .65rem;
    background: #f5f5f5;
}
.cw-msgs::-webkit-scrollbar { width: 4px; }
.cw-msgs::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

.cw-m { display: flex; flex-direction: column; max-width: 75%; }
.cw-m.mine   { align-self: flex-end;   align-items: flex-end; }
.cw-m.theirs { align-self: flex-start; align-items: flex-start; }
.cw-bbl {
    padding: .65rem 1rem;
    border-radius: 14px;
    font-size: 1.3rem;
    line-height: 1.5;
    word-break: break-word;
}
.cw-m.mine   .cw-bbl { background: #16a34a; color: #fff; border-bottom-right-radius: 3px; }
.cw-m.theirs .cw-bbl { background: #fff; color: #333; border: 1px solid #e5e7eb; border-bottom-left-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
.cw-m-time { font-size: 1.05rem; color: #aaa; margin-top: .2rem; }

.cw-input-bar {
    display: flex;
    align-items: flex-end;
    gap: .6rem;
    padding: .85rem 1.1rem;
    background: #fff;
    border-top: 1px solid #f0f0f0;
    flex-shrink: 0;
}
.cw-tarea {
    flex: 1;
    border: 1.5px solid #e5e7eb;
    border-radius: 18px;
    padding: .6rem 1rem;
    font-size: 1.3rem;
    font-family: inherit;
    outline: none;
    resize: none;
    max-height: 88px;
    line-height: 1.5;
    transition: border-color .18s;
    color: #333;
    background: #fafafa;
}
.cw-tarea:focus { border-color: #16a34a; background: #fff; }
.cw-send {
    width: 38px; height: 38px;
    background: #16a34a; color: #fff;
    border: none; border-radius: 50%;
    cursor: pointer; font-size: 1.35rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background .18s, transform .15s;
}
.cw-send:hover    { background: #15803d; transform: scale(1.07); }
.cw-send:disabled { background: #d1d5db; cursor: not-allowed; transform: none; }

/* ─── Responsive ──────────────────────────────── */
@media (max-width: 680px) {
    .cw-panel { width: calc(100vw - 16px); right: 8px; bottom: 0; height: 480px; }
    .cw-left  { width: 220px; }
    .cw-trigger { right: 8px; }
}
@media (max-width: 520px) {
    .cw-panel { width: 100vw; right: 0; height: calc(100vh - 48px); border-radius: 0; }
    .cw-left  { width: 100%; position: absolute; inset: 0; z-index: 2; background: #fff; display: none; }
    .cw-left.show-mobile { display: flex; }
    .cw-trigger { right: 0; border-radius: 6px 6px 0 0; }
}
</style>

{{-- Floating trigger --}}
<button class="cw-trigger" id="cwTrigger" onclick="cwToggle()" style="position:fixed;">
    <i class="fas fa-comment-dots"></i>
    Chat
    <span class="cw-trigger-badge" id="cwBadge" style="display:none;">0</span>
    <span id="cwTriggerCount" style="display:none;font-weight:500;font-size:1.3rem;opacity:.9;">(<span id="cwTriggerCountVal">0</span>)</span>
</button>

{{-- Chat panel --}}
<div class="cw-panel" id="cwPanel">

    {{-- Header bar --}}
    <div class="cw-panel-head">
        <div class="cw-panel-title">
            Chat
            <span class="cw-count" id="cwCountLabel"></span>
        </div>
        <div class="cw-head-btns">
            <button class="cw-head-btn" title="Open full page" onclick="cwOpenFull()">
                <i class="fas fa-external-link-alt" style="font-size:1.3rem;"></i>
            </button>
            <button class="cw-head-btn" title="Minimize" onclick="cwToggle()">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>

    {{-- Body --}}
    <div class="cw-body">

        {{-- Left: conversation list --}}
        <div class="cw-left" id="cwLeft">
            <div class="cw-left-toolbar">
                <div class="cw-search-row">
                    <div class="cw-search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="cwSearch" placeholder="Search name"
                               oninput="cwFilter(this.value)">
                    </div>
                    <button class="cw-filter-btn">
                        All <i class="fas fa-chevron-down" style="font-size:1rem;"></i>
                    </button>
                </div>
            </div>
            <div class="cw-conv-list" id="cwConvList">
                <div class="cw-list-loading"><div class="cw-spinner"></div> Loading…</div>
            </div>
        </div>

        {{-- Right --}}
        <div class="cw-right" id="cwRight">

            {{-- Welcome (default) --}}
            <div class="cw-welcome" id="cwWelcome">
                <div class="cw-welcome-icon">
                    <div class="laptop">
                        <div class="laptop-inner">
                            <div class="cw-msg-bubble-icon"></div>
                        </div>
                    </div>
                    <div class="cw-chat-bubble-icon"></div>
                </div>
                <h4>Welcome to U-KAY HUB Chat</h4>
                <p>Start chatting with our sellers now!</p>
            </div>

            {{-- Active chat (hidden until selected) --}}
            <div id="cwChatArea" style="display:none;flex-direction:column;flex:1;min-height:0;">
                <div class="cw-chat-head" id="cwChatHead"></div>
                {{-- Safety tip --}}
                <div class="cw-safety-tip">
                    <i class="fas fa-exclamation-circle" style="color:#d97706;flex-shrink:0;margin-top:.1rem;"></i>
                    <span><strong>Safety Tip:</strong> Stay safe! Only transact within the U-KAY HUB platform. Avoid sellers who ask you to deal or make payments outside the app.</span>
                </div>
                <div class="cw-msgs" id="cwMsgs"></div>
                <div class="cw-input-bar">
                    <textarea class="cw-tarea" id="cwInput" rows="1"
                              placeholder="Type a message…"></textarea>
                    <button class="cw-send" id="cwSendBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>

        </div>{{-- /.cw-right --}}
    </div>{{-- /.cw-body --}}
</div>{{-- /.cw-panel --}}

<script>
(function () {
    const csrf       = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const convsUrl   = '{{ route("chat.widget.conversations") }}';
    const adminPoll  = '{{ route("chat.getMessages") }}';

    let panelOpen  = false;
    let allConvs   = [];
    let active     = null;   // { id, type, send_url, poll_url, name, avatar, initials }
    let lastCount  = 0;
    let pollTimer  = null;
    let convTimer  = null;

    /* ── Open / close ── */
    window.cwToggle = function () {
        panelOpen = !panelOpen;
        document.getElementById('cwPanel').classList.toggle('open', panelOpen);
        document.getElementById('cwTrigger').style.display = panelOpen ? 'none' : '';
        if (panelOpen) { fetchConvs(); startPolling(); }
        else           { stopPolling(); }
    };

    window.cwOpenFull = function () {
        window.location.href = '{{ route("user.seller.chats") }}';
    };

    /* ── Conversations ── */
    async function fetchConvs() {
        try {
            const r = await fetch(convsUrl, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const d = await r.json();
            allConvs = d.conversations || [];
            renderConvs(allConvs);
            updateBadge(d.total_unread || 0);
        } catch(_) {}
    }

    function renderConvs(convs) {
        const total = convs.reduce((s,c) => s + (c.unread||0), 0);
        document.getElementById('cwCountLabel').textContent = total > 0 ? `(${total})` : '';

        const list = document.getElementById('cwConvList');
        if (!convs.length) {
            list.innerHTML = '<div class="cw-list-loading" style="color:#aaa;flex-direction:column;gap:.3rem;"><i class="fas fa-comment-slash" style="font-size:2.2rem;opacity:.3;"></i>No conversations yet</div>';
            return;
        }

        list.innerHTML = convs.map(c => {
            const isActive = active && String(active.id) === String(c.id) && active.type === c.type;
            const av = c.avatar
                ? `<div class="cw-avatar ${c.type==='admin'?'admin-bg':''}"><img src="${c.avatar}" alt="" onerror="this.style.display='none'"><span>${esc(c.initials)}</span></div>`
                : `<div class="cw-avatar ${c.type==='admin'?'admin-bg':''}">${esc(c.initials)}</div>`;

            return `<div class="cw-conv-item ${isActive?'active':''}"
                        onclick="cwOpen('${c.type}','${c.id}')">
                ${av}
                <div class="cw-conv-info">
                    <div class="cw-conv-top">
                        <span class="cw-conv-name">${esc(c.name)}</span>
                        <span class="cw-conv-time">${esc(c.last_time||'')}</span>
                    </div>
                    <div class="cw-conv-bot">
                        <span class="cw-conv-preview ${c.unread?'fw':''}">${esc(c.last_msg||'')}</span>
                        ${c.unread?`<span class="cw-unread-dot">${c.unread}</span>`:''}
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    window.cwFilter = function(q) {
        const f = q.toLowerCase();
        renderConvs(f ? allConvs.filter(c => c.name.toLowerCase().includes(f)) : allConvs);
    };

    /* ── Open a conversation ── */
    window.cwOpen = async function (type, id) {
        const conv = allConvs.find(c => c.type === type && String(c.id) === String(id));
        if (!conv) return;

        active    = conv;
        lastCount = 0;

        // Highlight active
        document.querySelectorAll('.cw-conv-item').forEach(el => el.classList.remove('active'));
        document.querySelectorAll(`.cw-conv-item[onclick*="${type}"][onclick*="${id}"]`)
            .forEach(el => el.classList.add('active'));

        // Show chat area
        document.getElementById('cwWelcome').style.display  = 'none';
        const area = document.getElementById('cwChatArea');
        area.style.display = 'flex';

        // Render header
        const avHtml = conv.avatar
            ? `<div class="cw-chat-head-avatar ${type==='admin'?'admin-bg':''}"><img src="${conv.avatar}" alt="" onerror="this.style.display='none'"><span>${esc(conv.initials)}</span></div>`
            : `<div class="cw-chat-head-avatar ${type==='admin'?'admin-bg':''}">${esc(conv.initials)}</div>`;
        document.getElementById('cwChatHead').innerHTML = `
            ${avHtml}
            <div>
                <div class="cw-chat-head-name">${esc(conv.name)}</div>
                <div class="cw-chat-head-sub">Active recently</div>
            </div>`;

        document.getElementById('cwInput').focus();
        await loadMsgs();
    };

    /* ── Messages ── */
    async function loadMsgs() {
        if (!active) return;
        try {
            const url = active.type === 'admin' ? adminPoll : active.poll_url;
            const r   = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const d   = await r.json();

            let msgs;
            if (active.type === 'admin') {
                msgs = (d.messages||[]).map(m => ({
                    message:     m.message,
                    sender_type: m.sender_type,
                    time: new Date(m.created_at).toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit',hour12:true}),
                }));
            } else {
                msgs = d.messages || [];
            }

            if (msgs.length !== lastCount) {
                lastCount = msgs.length;
                renderMsgs(msgs);
            }
            await fetchConvs();
        } catch(_) {}
    }

    function renderMsgs(msgs) {
        const el = document.getElementById('cwMsgs');
        if (!msgs.length) {
            el.innerHTML = `<div style="text-align:center;color:#aaa;font-size:1.3rem;padding:2rem;">No messages yet. Say hi! 👋</div>`;
            return;
        }
        const isAdmin = active?.type === 'admin';
        el.innerHTML = msgs.map(m => {
            const mine = m.sender_type === 'user';
            return `<div class="cw-m ${mine?'mine':'theirs'}">
                <div class="cw-bbl">${esc(m.message)}</div>
                <span class="cw-m-time">${m.time||''}</span>
            </div>`;
        }).join('');
        el.scrollTop = el.scrollHeight;
    }

    /* ── Send ── */
    async function send() {
        if (!active) return;
        const inp  = document.getElementById('cwInput');
        const text = inp.value.trim();
        if (!text) return;
        const btn = document.getElementById('cwSendBtn');
        btn.disabled = true;
        inp.value = '';
        inp.style.height = 'auto';
        try {
            await fetch(active.send_url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type':'application/json', 'Accept':'application/json' },
                body: JSON.stringify({ message: text }),
            });
            await loadMsgs();
        } catch(_) {} finally {
            btn.disabled = false;
            inp.focus();
        }
    }

    document.getElementById('cwSendBtn').addEventListener('click', send);
    document.getElementById('cwInput').addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
    });
    document.getElementById('cwInput').addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 88) + 'px';
    });

    /* ── Polling ── */
    function startPolling() {
        stopPolling();
        pollTimer = setInterval(loadMsgs,    3000);
        convTimer = setInterval(fetchConvs,  7000);
    }
    function stopPolling() {
        clearInterval(pollTimer);
        clearInterval(convTimer);
        pollTimer = convTimer = null;
    }

    /* ── Badge (closed state) ── */
    function updateBadge(n) {
        const b = document.getElementById('cwBadge');
        const tc = document.getElementById('cwTriggerCount');
        const tcv = document.getElementById('cwTriggerCountVal');
        if (!panelOpen && n > 0) {
            b.textContent = n > 99 ? '99+' : n;
            b.style.display = 'inline-flex';
            if (tc && tcv) { tcv.textContent = n > 99 ? '99+' : n; tc.style.display = 'inline'; }
        } else {
            b.style.display = 'none';
            if (tc) tc.style.display = 'none';
        }
    }
    async function pollBadge() {
        if (panelOpen) return;
        try {
            const r = await fetch(convsUrl, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const d = await r.json();
            allConvs = d.conversations || [];
            updateBadge(d.total_unread || 0);
        } catch(_) {}
    }
    setInterval(pollBadge, 5000);
    pollBadge();

    /* ── Escape HTML ── */
    function esc(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
})();
</script>
