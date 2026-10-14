@extends('main.layouts.app')

@section('title', 'Refund & Cancellation Policy - ' . company_info('company_name'))
@section('content')

<!--=====================================
    BREADCRUMB START
=======================================-->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h2 class="breadcrumb-title">Refund & Cancellation Policy</h2>
                    <ul class="breadcrumb-list">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li>Refund & Cancellation Policy</li>
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
    REFUND POLICY PART START
=======================================-->
<section class="inner-section privacy-part">
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <nav class="nav nav-pills flex-column" id="scrollspy">
                    <a class="nav-link" href="#item-1">1. No Refund Policy</a>
                    <a class="nav-link" href="#item-2">2. Cancellation Policy</a>
                    <a class="nav-link" href="#item-3">3. Non-Returnable Products</a>
                    <a class="nav-link" href="#item-4">4. Damaged or Incorrect Products</a>
                    <a class="nav-link" href="#item-5">5. Contact Us</a>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div data-bs-spy="scroll" data-bs-target="#scrollspy" data-bs-offset="0" tabindex="0">
                    
                    <!-- Section 1: No Refund Policy -->
                    <div class="scrollspy-content" id="item-1">
                        <h3>1. No Refund Policy</h3>
                        <p>At <strong>{{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }}</strong>, we do not offer any refunds on products once purchased. All sales are final.</p>
                        <p>Due to the nature of wellness and consumable products, we strictly follow a <strong>no refund policy</strong> to ensure the safety and hygiene of all products.</p>
                        <p>Please read all product descriptions, ingredients, and usage instructions carefully before making a purchase.</p>
                    </div>

                    <!-- Section 2: Cancellation Policy -->
                    <div class="scrollspy-content" id="item-2">
                        <h3>2. Cancellation Policy</h3>
                        <p><strong>Orders once placed can only be cancelled before dispatch.</strong></p>
                        <p><strong>Cancellation Process:</strong></p>
                        <ul>
                            <li>You can cancel your order directly from your account dashboard under "My Orders" section.</li>
                            <li>Alternatively, you can contact our customer support team via email or phone to request cancellation.</li>
                            <li>Cancellation requests will only be accepted if the order has not yet been processed or shipped from our warehouse.</li>
                            <li>Once an order has been dispatched, it <strong>cannot be cancelled</strong>.</li>
                        </ul>
                        <p><strong>No cancellation fees apply.</strong> The full order amount will be refunded to your original payment method within 7-10 business days (only for cancelled orders before dispatch).</p>
                    </div>

                    <!-- Section 3: Non-Returnable Products -->
                    <div class="scrollspy-content" id="item-3">
                        <h3>3. Non-Returnable Products</h3>
                        <p><strong>We do not accept returns on any products.</strong> Once a product is delivered and accepted by the customer, it cannot be returned for any of the following reasons:</p>
                        <ul>
                            <li>Change of mind or buyer's remorse</li>
                            <li>Product not suiting the customer</li>
                            <li>Product ordered by mistake</li>
                            <li>Better price available elsewhere</li>
                            <li>Any other personal reason</li>
                        </ul>
                        <p><strong>Important:</strong> Please ensure you have reviewed the product details, specifications, and terms before completing your purchase.</p>
                    </div>

                    <!-- Section 4: Damaged or Incorrect Products -->
                    <div class="scrollspy-content" id="item-4">
                        <h3>4. Damaged or Incorrect Products</h3>
                        <p>If you receive a <strong>damaged product</strong> or <strong>incorrect product</strong> (wrong item sent), please contact us immediately.</p>
                        <p><strong>Reporting Timeline:</strong> Damaged or incorrect products must be reported within <strong>48 hours of delivery</strong> with proper images and order details.</p>
                        <p><strong>Required Information:</strong></p>
                        <ul>
                            <li>Order ID number</li>
                            <li>Product name</li>
                            <li>Clear photographs or video of the damaged/incorrect product</li>
                            <li>Photographs of the outer packaging</li>
                            <li>Brief description of the issue</li>
                        </ul>
                        <p><strong>How to Report:</strong></p>
                        <ul>
                            <li><strong>Email:</strong> Send all details to <a href="mailto:{{ company_info('company_email') ?? 'support@trevolistechnology.com' }}">{{ company_info('company_email') ?? 'support@trevolistechnology.com' }}</a></li>
                            <li><strong>Phone:</strong> Call our customer support team during business hours</li>
                        </ul>
                        <p><strong>Resolution:</strong> Upon verification, we will provide a <strong>replacement</strong> (not refund) for damaged or incorrect products. No refunds will be issued.</p>
                        <p><strong>Note:</strong> Requests reported after 48 hours of delivery will not be entertained.</p>
                    </div>

                    <!-- Section 5: Contact Us -->
                    <div class="scrollspy-content" id="item-5">
                        <h3>5. Contact Us</h3>
                        <p>If you have any questions, concerns, or need assistance regarding cancellations or damaged products, please contact our support team:</p>
                        
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
                        
                        <p class="mt-3"><strong>Support Hours:</strong> Monday to Saturday, 10:00 AM – 7:00 PM IST (Closed on Sundays & Public Holidays)</p>
                       
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--=====================================
    REFUND POLICY PART END
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