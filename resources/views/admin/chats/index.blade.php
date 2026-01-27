@extends('layouts.admin')

@section('title', 'Live Chats - Admin Panel')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .chats-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .page-header h1 {
        font-size: 2.5rem;
        color: var(--black);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header h1 i {
        color: var(--main-color);
        font-size: 2.2rem;
    }

    .chats-count {
        font-size: 1.4rem;
        color: var(--main-color);
        background: rgba(58, 199, 45, 0.08);
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
    }

    /* Conversations Grid - Compact Layout */
    .conversations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 1.5rem;
    }

    /* Conversation Card - Compact & Professional */
    .conversation-card {
        background: var(--white);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .conversation-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
        border-color: var(--main-color);
    }

    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.2rem;
        gap: 1rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        min-width: 0;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-details h3 {
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--black);
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-details p {
        font-size: 1.2rem;
        color: var(--light-color);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversation-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.4rem;
        flex-shrink: 0;
    }

    .conversation-time {
        font-size: 1.1rem;
        color: #9ca3af;
        white-space: nowrap;
    }

    .unread-badge {
        background: var(--main-color);
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
    }

    .conversation-actions {
        display: flex;
        gap: 0.8rem;
        margin-top: auto;
    }

    .btn-view {
        flex: 1;
        background: var(--main-color);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 0.6rem;
        cursor: pointer;
        font-size: 1.3rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-view:hover {
        background: #27ae60;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(58, 199, 45, 0.3);
    }

    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.8rem 1rem;
        border-radius: 0.6rem;
        cursor: pointer;
        font-size: 1.3rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(239, 68, 68, 0.3);
    }

    /* Empty State */
    .empty-conversations {
        text-align: center;
        padding: 8rem 2rem;
        background: var(--white);
        border-radius: 1.5rem;
        border: 2px dashed #e5e7eb;
    }

    .empty-conversations i {
        font-size: 10rem;
        color: var(--light-color);
        margin-bottom: 2rem;
    }

    .empty-conversations h2 {
        font-size: 2.5rem;
        color: var(--black);
        margin-bottom: 1rem;
    }

    .empty-conversations p {
        font-size: 1.8rem;
        color: var(--light-color);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .conversations-grid {
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .chats-container {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .conversations-grid {
            grid-template-columns: 1fr;
            gap: 1.2rem;
        }

        .conversation-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .conversation-meta {
            align-items: flex-start;
            flex-direction: row;
            gap: 0.8rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            font-size: 1.6rem;
        }

        .user-details h3 {
            font-size: 1.5rem;
        }

        .user-details p {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="chats-container">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>
            <i class="fas fa-comments"></i>
            Live Chats
        </h1>
        <div class="chats-count">
            <i class="fas fa-user"></i>
            {{ $conversations->count() }} {{ $conversations->count() === 1 ? 'Conversation' : 'Conversations' }}
        </div>
    </div>

    @if($conversations->count() > 0)
        {{-- Conversations Grid --}}
        <div class="conversations-grid">
            @foreach($conversations as $conversation)
                <div class="conversation-card" data-user-id="{{ $conversation['user']->id }}">
                    {{-- Conversation Header --}}
                    <div class="conversation-header">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <h3>{{ $conversation['user']->name }}</h3>
                                <p>{{ $conversation['user']->email }}</p>
                            </div>
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-time">
                                {{ \Carbon\Carbon::parse($conversation['last_message_time'])->diffForHumans() }}
                            </div>
                            @if($conversation['unread_count'] > 0)
                                <span class="unread-badge">
                                    {{ $conversation['unread_count'] }} new
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="conversation-actions">
                        <a href="{{ route('admin.chats.show', $conversation['user']->id) }}" class="btn-view">
                            <i class="fas fa-comments"></i> View Chat
                        </a>
                        <button class="btn-delete" onclick="deleteConversation(event, {{ $conversation['user']->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-conversations">
            <i class="fas fa-comments"></i>
            <h2>No Conversations Yet</h2>
            <p>User messages will appear here when they start chatting</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteConversation(event, userId) {
        event.preventDefault();
        event.stopPropagation();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the entire conversation with this user!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                fetch(`/admin/chats/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Remove the conversation card from DOM
                        const conversationCard = document.querySelector(`[data-user-id="${userId}"]`);
                        if (conversationCard) {
                            conversationCard.style.transition = 'all 0.3s ease';
                            conversationCard.style.opacity = '0';
                            conversationCard.style.transform = 'scale(0.9)';
                            
                            setTimeout(() => {
                                conversationCard.remove();
                                
                                // Update count
                                const countElement = document.querySelector('.chats-count');
                                const remainingConversations = document.querySelectorAll('.conversation-card').length;
                                countElement.innerHTML = `<i class="fas fa-user"></i> ${remainingConversations} ${remainingConversations === 1 ? 'Conversation' : 'Conversations'}`;
                                
                                // Show empty state if no conversations left
                                if (remainingConversations === 0) {
                                    location.reload();
                                }
                            }, 300);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            confirmButtonColor: '#3ac72d',
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            confirmButtonColor: '#3ac72d'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        confirmButtonColor: '#3ac72d'
                    });
                });
            }
        });
    }

    // Optional: Auto-refresh every 30 seconds (commented out to avoid disruption)
    // setInterval(() => {
    //     location.reload();
    // }, 30000);
</script>
@endpush
