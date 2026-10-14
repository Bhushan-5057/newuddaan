@extends('main.layouts.app')

@section('title', 'Privacy Policy - ' . company_info('company_name'))
@section('content')

<!--=====================================
    BREADCRUMB START
=======================================-->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h2 class="breadcrumb-title">Privacy Policy</h2>
                    <ul class="breadcrumb-list">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li>Privacy Policy</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--=====================================
    BREADCRUMB END
=======================================-->

<!--=====================================
    PRIVACY PART START
=======================================-->
<section class="inner-section privacy-part">
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <nav class="nav nav-pills flex-column" id="scrollspy">
                    <a class="nav-link" href="#item-1">1. Information We Collect</a>
                    <a class="nav-link" href="#item-2">2. Purpose of Collection</a>
                    <a class="nav-link" href="#item-3">3. Payment Security</a>
                    <a class="nav-link" href="#item-4">4. Data Protection</a>
                    <a class="nav-link" href="#item-5">5. Contact Us</a>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div data-bs-spy="scroll" data-bs-target="#scrollspy" data-bs-offset="0" tabindex="0">
                    
                    <!-- Section 1 -->
                    <div class="scrollspy-content" id="item-1">
                        <h3>1. Information We Collect</h3>
                        <p><strong>{{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }}</strong> respects your privacy and is committed to protecting your personal information. We collect the following information to provide and improve our services:</p>
                        <ul>
                            <li><strong>Name</strong> - For identification and order processing</li>
                            <li><strong>Mobile Number</strong> - For order updates and delivery coordination</li>
                            <li><strong>Email Address</strong> - For order confirmation and promotional communication</li>
                            <li><strong>Shipping Address</strong> - To deliver your orders accurately</li>
                            <li><strong>Billing Information</strong> - For invoice generation and payment verification</li>
                        </ul>
                    </div>

                    <!-- Section 2 -->
                    <div class="scrollspy-content" id="item-2">
                        <h3>2. Purpose of Collection</h3>
                        <p>Your data is collected and used exclusively for the following legitimate business purposes:</p>
                        <ul>
                            <li><strong>Order Processing</strong> - To manage, confirm, and process your orders.</li>
                            <li><strong>Customer Support</strong> - To respond to your queries and resolve issues.</li>
                            <li><strong>Delivery Services</strong> - To coordinate with our delivery partners for shipment.</li>
                            <li><strong>Promotional Communication</strong> - To send you offers, new arrivals, and updates (only with your consent).</li>
                            <li><strong>Website Improvement</strong> - To analyze usage patterns and enhance user experience.</li>
                        </ul>
                    </div>

                    <!-- Section 3 -->
                    <div class="scrollspy-content" id="item-3">
                        <h3>3. Payment Security</h3>
                        <p>We prioritize your financial security. All payments processed through our website are handled via trusted, PCI-DSS compliant payment gateway providers. <strong>We do not store your debit card, credit card, or UPI credentials on our servers.</strong> Your transaction is encrypted and processed directly by the payment gateway.</p>
                        <p>Accepted payment methods include Credit Cards, Debit Cards, Net Banking, UPI, and Digital Wallets.</p>
                    </div>

                    <!-- Section 4 -->
                    <div class="scrollspy-content" id="item-4">
                        <h3>4. Data Protection</h3>
                        <p>We implement industry-standard security practices, including:</p>
                        <ul>
                            <li>SSL Encryption (Secure Socket Layer) for data transmission.</li>
                            <li>Regular security audits and vulnerability scanning.</li>
                            <li>Restricted access to personal data within our organization.</li>
                            <li>Secure servers protected by firewalls.</li>
                        </ul>
                        <p>While no method of transmission over the internet is 100% secure, we strive to use commercially acceptable means to protect your information.</p>
                    </div>

                    <!-- Section 5 -->
                    <div class="scrollspy-content" id="item-5">
                        <h3>5. Contact Us</h3>
                        <p>If you have any questions about this Privacy Policy, wish to access, correct, or delete your personal data, please contact our Grievance Officer:</p>
                        
                        <div class="contact-info-box" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 15px;">
                            <p style="margin-bottom: 8px;">
                                <strong>📧 Email:</strong> 
                                <a href="mailto:{{ company_info('company_email') ?? 'support@trevolistechnology.com' }}">
                                    {{ company_info('company_email') ?? 'support@trevolistechnology.com' }}
                                </a>
                            </p>
                            <p style="margin-bottom: 8px;">
                                <strong>📞 Phone:</strong> 
                                <a href="tel:{{ company_info('company_mobile') ?? '' }}">
                                    {{ company_info('company_mobile') ?? 'Not Available' }}
                                </a>
                            </p>
                            <p>
                                <strong>🏢 Address:</strong> 
                                {{ company_info('company_address') ?? 'India' }}
                            </p>
                        </div>
                        
                      
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--=====================================
    PRIVACY PART END
=======================================-->
<style>
    .nav-pills .nav-link {
        color: #333;
        background: transparent;
        padding: 10px 15px;
        margin-bottom: 5px;
        border-radius: 5px;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .nav-pills .nav-link:hover {
        background: #f0f0f0;
        color: #000;
    }
    
    .nav-pills .nav-link.active {
        background: {{ company_info('primary_color') ?? '#0066c0' }};
        color: #fff;
    }
    
    .scrollspy-content {
        margin-bottom: 40px;
        scroll-margin-top: 20px;
    }
    
    .scrollspy-content h3 {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #222;
    }
    
    .scrollspy-content p {
        line-height: 1.7;
        margin-bottom: 15px;
        color: #555;
    }
    
    .scrollspy-content ul {
        margin-bottom: 15px;
        padding-left: 20px;
    }
    
    .scrollspy-content ul li {
        margin-bottom: 8px;
        line-height: 1.6;
        color: #555;
    }
    
    .contact-info-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
        border-left: 3px solid {{ company_info('primary_color') ?? '#0066c0' }};
    }
    
    .contact-info-box p {
        margin-bottom: 8px;
    }
    
    .contact-info-box a {
        color: {{ company_info('primary_color') ?? '#0066c0' }};
        text-decoration: none;
    }
    
    .contact-info-box a:hover {
        text-decoration: underline;
    }
    
    .breadcrumb-section {
        background: #f5f5f5;
        padding: 40px 0;
        margin-bottom: 20px;
    }
    
    .breadcrumb-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 10px 0;
    }
    
    .breadcrumb-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .breadcrumb-list li {
        display: inline-block;
        color: #666;
    }
    
    .breadcrumb-list li a {
        color: {{ company_info('primary_color') ?? '#0066c0' }};
        text-decoration: none;
    }
    
    .breadcrumb-list li:not(:last-child):after {
        content: ">";
        margin: 0 8px;
        color: #999;
    }
    
    @media (max-width: 768px) {
        .breadcrumb-section {
            padding: 25px 0;
        }
        
        .breadcrumb-title {
            font-size: 22px;
        }
        
        .scrollspy-content h3 {
            font-size: 20px;
        }
    }
</style>
@endsection