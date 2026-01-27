@extends('layouts.admin')

@section('title', 'Messages - Admin Panel')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .messages-container {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        padding-bottom: 1.5rem;
        border-bottom: 3px solid var(--main-color);
    }

    .page-header h1 {
        font-size: 3rem;
        color: var(--black);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header h1 i {
        color: var(--main-color);
    }

    .messages-count {
        font-size: 1.6rem;
        color: var(--light-color);
        background: rgba(58, 199, 45, 0.1);
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
    }

    /* Messages Grid */
    .messages-grid {
        display: grid;
        gap: 2rem;
    }

    /* Message Card */
    .message-card {
        background: var(--white);
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem;
        padding: 2.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .message-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .message-info {
        flex: 1;
    }

    .message-name {
        font-size: 2rem;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .message-name i {
        color: var(--main-color);
        font-size: 2.2rem;
    }

    .message-email {
        font-size: 1.5rem;
        color: var(--light-color);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .message-email i {
        color: var(--main-color);
    }

    .message-email a {
        color: var(--main-color);
        text-decoration: none;
    }

    .message-email a:hover {
        text-decoration: underline;
    }

    .message-date {
        font-size: 1.3rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .message-date i {
        font-size: 1.4rem;
    }

    .message-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 0.8rem;
        cursor: pointer;
        font-size: 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .message-subject {
        background: rgba(58, 199, 45, 0.1);
        border-left: 4px solid var(--main-color);
        padding: 1.5rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
    }

    .message-subject h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .message-subject h3 i {
        color: var(--main-color);
    }

    .message-subject p {
        font-size: 1.7rem;
        color: var(--black);
        font-weight: 600;
        margin: 0;
    }

    .message-content {
        padding: 1.5rem;
        background: #f9fafb;
        border-radius: 0.8rem;
    }

    .message-content h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .message-content h3 i {
        color: var(--main-color);
    }

    .message-content p {
        font-size: 1.6rem;
        color: #4b5563;
        line-height: 1.8;
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* Empty State */
    .empty-messages {
        text-align: center;
        padding: 8rem 2rem;
        background: var(--white);
        border-radius: 1.5rem;
        border: 2px dashed #e5e7eb;
    }

    .empty-messages i {
        font-size: 10rem;
        color: var(--light-color);
        margin-bottom: 2rem;
    }

    .empty-messages h2 {
        font-size: 2.5rem;
        color: var(--black);
        margin-bottom: 1rem;
    }

    .empty-messages p {
        font-size: 1.8rem;
        color: var(--light-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .message-header {
            flex-direction: column;
            gap: 1.5rem;
        }

        .message-actions {
            width: 100%;
        }

        .btn-delete {
            flex: 1;
            justify-content: center;
        }

        .message-card {
            padding: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="messages-container">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>
            <i class="fas fa-envelope"></i>
            Customer Messages
        </h1>
        <div class="messages-count">
            <i class="fas fa-comment-dots"></i>
            {{ $messages->count() }} {{ $messages->count() === 1 ? 'Message' : 'Messages' }}
        </div>
    </div>

    @if($messages->count() > 0)
        {{-- Messages Grid --}}
        <div class="messages-grid">
            @foreach($messages as $message)
                <div class="message-card" data-message-id="{{ $message->id }}">
                    {{-- Message Header --}}
                    <div class="message-header">
                        <div class="message-info">
                            <div class="message-name">
                                <i class="fas fa-user-circle"></i>
                                {{ $message->name }}
                            </div>
                            <div class="message-email">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                            </div>
                            <div class="message-date">
                                <i class="fas fa-clock"></i>
                                {{ $message->created_at ? $message->created_at->format('M d, Y - h:i A') : 'N/A' }}
                            </div>
                        </div>
                        <div class="message-actions">
                            <button class="btn-delete" onclick="deleteMessage({{ $message->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="message-subject">
                        <h3><i class="fas fa-tag"></i> Subject</h3>
                        <p>{{ $message->subject }}</p>
                    </div>

                    {{-- Message Content --}}
                    <div class="message-content">
                        <h3><i class="fas fa-comment-alt"></i> Message</h3>
                        <p>{{ $message->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-messages">
            <i class="fas fa-inbox"></i>
            <h2>No Messages Yet</h2>
            <p>Customer inquiries will appear here</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteMessage(messageId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to recover this message!",
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
                fetch(`/admin/messages/${messageId}`, {
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
                        // Remove the message card from DOM
                        const messageCard = document.querySelector(`[data-message-id="${messageId}"]`);
                        if (messageCard) {
                            messageCard.style.transition = 'all 0.3s ease';
                            messageCard.style.opacity = '0';
                            messageCard.style.transform = 'scale(0.9)';
                            
                            setTimeout(() => {
                                messageCard.remove();
                                
                                // Update messages count
                                const countElement = document.querySelector('.messages-count');
                                const remainingMessages = document.querySelectorAll('.message-card').length;
                                countElement.innerHTML = `<i class="fas fa-comment-dots"></i> ${remainingMessages} ${remainingMessages === 1 ? 'Message' : 'Messages'}`;
                                
                                // Show empty state if no messages left
                                if (remainingMessages === 0) {
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
</script>
@endpush
