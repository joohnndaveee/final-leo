@extends('layouts.app')

@section('title', 'Contact Us - U-KAY HUB')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .contact-section {
        padding: 3rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        min-height: calc(100vh - 200px);
    }

    .contact-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 4rem 3rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-header h1 {
        font-family: 'Nunito', sans-serif;
        font-size: 3rem;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .section-header p {
        font-family: 'Lato', sans-serif;
        font-size: 1.6rem;
        color: #7f8c8d;
    }

    /* Two Column Layout */
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        margin-top: 3rem;
    }

    /* Left Column - Contact Form */
    .contact-form-wrapper {
        padding-right: 2rem;
    }

    .contact-form-wrapper h2 {
        font-family: 'Nunito', sans-serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .contact-form-wrapper h2 i {
        color: var(--main-color);
        font-size: 2.5rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        font-family: 'Nunito', sans-serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.8rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 1.2rem 1.5rem;
        font-family: 'Lato', sans-serif;
        font-size: 1.5rem;
        color: #2c3e50;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        transition: all 0.3s ease;
        background: #fff;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--main-color);
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 150px;
    }

    .submit-btn {
        width: 100%;
        padding: 1.5rem 3rem;
        font-family: 'Nunito', sans-serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, var(--main-color), #27ae60);
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(39, 174, 96, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(39, 174, 96, 0.4);
    }

    .submit-btn:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        transform: none;
    }

    .submit-btn i {
        margin-right: 0.8rem;
    }

    /* Right Column - Shop Details */
    .shop-details-wrapper {
        padding-left: 2rem;
        border-left: 3px solid var(--main-color);
    }

    .shop-details-wrapper h2 {
        font-family: 'Nunito', sans-serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .shop-details-wrapper h2 i {
        color: var(--main-color);
        font-size: 2.5rem;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        padding: 1.5rem;
        background: rgba(39, 174, 96, 0.05);
        border-radius: 10px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        background: rgba(39, 174, 96, 0.1);
        transform: translateX(5px);
    }

    .detail-item i {
        font-size: 2.5rem;
        color: var(--main-color);
        min-width: 3rem;
    }

    .detail-content h3 {
        font-family: 'Nunito', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .detail-content p {
        font-family: 'Lato', sans-serif;
        font-size: 1.5rem;
        color: #555;
        line-height: 1.6;
        margin: 0;
    }

    .detail-content a {
        color: var(--main-color);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .detail-content a:hover {
        color: #27ae60;
        text-decoration: underline;
    }

    /* Google Map */
    .map-container {
        margin-top: 2rem;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        height: 350px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Responsive Design */
    @media (max-width: 968px) {
        .contact-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        .contact-form-wrapper {
            padding-right: 0;
        }

        .shop-details-wrapper {
            padding-left: 0;
            border-left: none;
            border-top: 3px solid var(--main-color);
            padding-top: 2rem;
        }
    }

    @media (max-width: 768px) {
        .contact-container {
            padding: 2rem 1.5rem;
        }

        .section-header h1 {
            font-size: 2.2rem;
        }

        .contact-form-wrapper h2,
        .shop-details-wrapper h2 {
            font-size: 1.9rem;
        }

        .map-container {
            height: 300px;
        }
    }
</style>
@endpush

@section('content')

<section class="contact-section">
    <div class="contact-container">
        {{-- Section Header --}}
        <div class="section-header">
            <h1>Get In Touch With Us</h1>
            <p>Have questions? We'd love to hear from you. Send us a message!</p>
        </div>

        {{-- Two Column Grid --}}
        <div class="contact-grid">
            {{-- Left Column - Contact Form --}}
            <div class="contact-form-wrapper">
                <h2><i class="fas fa-envelope"></i> Send Us a Message</h2>
                
                <form id="contactForm">
                    @csrf
                    
                    {{-- Name Field --}}
                    <div class="form-group">
                        <label for="name">Your Name <span style="color: red;">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" required>
                    </div>

                    {{-- Email Field --}}
                    <div class="form-group">
                        <label for="email">Your Email <span style="color: red;">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    {{-- Subject Field --}}
                    <div class="form-group">
                        <label for="subject">Subject <span style="color: red;">*</span></label>
                        <input type="text" id="subject" name="subject" placeholder="What is this about?" required>
                    </div>

                    {{-- Message Field --}}
                    <div class="form-group">
                        <label for="message">Message <span style="color: red;">*</span></label>
                        <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

            {{-- Right Column - Shop Details --}}
            <div class="shop-details-wrapper">
                <h2><i class="fas fa-store"></i> Shop Information</h2>

                {{-- Location --}}
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="detail-content">
                        <h3>Our Location</h3>
                        <p>P-6 Abilan, Buenavista<br>Agusan del Norte, Philippines</p>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="detail-item">
                    <i class="fas fa-phone-alt"></i>
                    <div class="detail-content">
                        <h3>Phone Number</h3>
                        <p><a href="tel:09304475164">0930 447 5164</a></p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="detail-item">
                    <i class="fas fa-envelope"></i>
                    <div class="detail-content">
                        <h3>Email Address</h3>
                        <p><a href="mailto:info@ukayhub.com">info@ukayhub.com</a></p>
                    </div>
                </div>

                {{-- Google Map --}}
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63339.67686827984!2d125.39726862167969!3d8.966840900000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3301e88e8db1c3dd%3A0x5d5f4f4f5f4f4f4f!2sBuenavista%2C%20Agusan%20del%20Norte!5e0!3m2!1sen!2sph!4v1643097600000!5m2!1sen!2sph" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('.submit-btn');
        const formData = new FormData(form);
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        // Send AJAX request
        fetch('{{ route("contact.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message with SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Message Sent Successfully!',
                    text: data.message,
                    confirmButtonColor: '#3ac72d',
                    confirmButtonText: 'Great!',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                
                // Reset form
                form.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#3ac72d'
            });
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
        });
    });
</script>
@endpush
