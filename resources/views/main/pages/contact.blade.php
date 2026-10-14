@extends('main.layouts.app')
@section('content')

<style>
    /* Contact Cards Styling */
.contact-card-link {
    text-decoration: none;
    display: block;
    transition: transform 0.3s ease;
}

.contact-card-link:hover {
    transform: translateY(-5px);
}

.contact-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    cursor: pointer;
}

.contact-card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border-color: transparent;
}




.contact-card h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #222;
    text-transform: capitalize;
}

.contact-card p {
    font-size: 14px;
    color: #666;
    margin-bottom: 0;
    line-height: 1.5;
    word-break: break-word;
}

.contact-card .contact-phone-link,
.contact-card .contact-email-link {
    color: inherit;
    text-decoration: none;
}

.contact-card.active .contact-phone-link,
.contact-card.active .contact-email-link {
    color: #fff;
}

.contact-card .contact-phone-link:hover,
.contact-card .contact-email-link:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-card {
        padding: 20px 15px;
        margin-bottom: 15px;
    }
    
    .contact-card i {
        font-size: 32px;
    }
    
    .contact-card h4 {
        font-size: 16px;
    }
    
    .contact-card p {
        font-size: 13px;
    }
}
</style>
<section class="inner-section single-banner" style="background: url({{ asset('main/images/single-banner.jpg') }}) no-repeat center;">
    <div class="container">
        <h2>contact us</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Contact</li>
        </ol>
    </div>
</section>

<section class="inner-section contact-part">
    <div class="container">
<div class="row">
    <div class="col-md-6 col-lg-4">
        <a href="{{ company_info('map_link') }}" target="_blank" rel="noopener noreferrer" class="contact-card-link">
            <div class="contact-card">
                <i class="icofont-location-pin"></i>
                <h4>Head Office</h4>
                <p>{{ company_info('company_address') }}</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-6 col-lg-4">
        <a href="tel:{{ company_info('company_mobile') }}" class="contact-card-link">
            <div class="contact-card active">
                <i class="icofont-phone"></i>
                <h4>Phone Number</h4>
                <p>{{ company_info('company_mobile') }}</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-6 col-lg-4">
        <a href="mailto:{{ company_info('company_email') }}" class="contact-card-link">
            <div class="contact-card">
                <i class="icofont-email"></i>
                <h4>Support Mail</h4>
                <p>{{ company_info('company_email') }}</p>
            </div>
        </a>
    </div>
</div>
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-map">
                    {!! company_info('map_iframe') !!}
                </div>
            </div>
            <div class="col-lg-6">
                <form class="contact-form" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <h4>Drop Your Thoughts</h4>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="form-group">
                        <div class="form-input-group">
                            <input class="form-control" name="name" type="text" placeholder="Your Name" required>
                            <i class="icofont-user-alt-3"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-input-group">
                            <input class="form-control" name="email" type="email" placeholder="Your Email" required>
                            <i class="icofont-email"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-input-group">
                            <input class="form-control" name="subject" type="text" placeholder="Your Subject" required>
                            <i class="icofont-book-mark"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-input-group">
                            <textarea class="form-control" name="message" placeholder="Your Message" required></textarea>
                            <i class="icofont-paragraph"></i>
                        </div>
                    </div>
                    <button type="submit" class="form-btn-group">
                        <i class="fas fa-envelope"></i>
                        <span>send message</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection