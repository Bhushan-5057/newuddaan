@extends('main.layouts.app')

@section('title', 'Shipping & Delivery Policy - ' . company_info('company_name'))
@section('content')

<!--=====================================
    BREADCRUMB START
=======================================-->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h2 class="breadcrumb-title">Shipping & Delivery Policy</h2>
                    <ul class="breadcrumb-list">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li>Shipping & Delivery Policy</li>
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
    SHIPPING POLICY PART START
=======================================-->
<section class="inner-section privacy-part">
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <nav class="nav nav-pills flex-column" id="scrollspy">
                    <a class="nav-link" href="#item-1">1. Shipping Coverage</a>
                    <a class="nav-link" href="#item-2">2. Order Processing Time</a>
                    <a class="nav-link" href="#item-3">3. Delivery Timelines</a>
                    <a class="nav-link" href="#item-4">4. Order Tracking</a>
                    <a class="nav-link" href="#item-5">5. Shipping Charges</a>
                    <a class="nav-link" href="#item-6">6. Delivery Delays</a>
                    <a class="nav-link" href="#item-7">7. Contact Support</a>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div data-bs-spy="scroll" data-bs-target="#scrollspy" data-bs-offset="0" tabindex="0">
                    
                    <!-- Section 1: Shipping Coverage -->
                    <div class="scrollspy-content" id="item-1">
                        <h3>1. Shipping Coverage</h3>
                        <p><strong>{{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }}</strong> delivers products across <strong>all over India</strong>. We currently do not offer international shipping, but we are working on expanding our reach globally.</p>
                        <p>We ship to all states and union territories including major cities, towns, and most pin codes across the country. Some remote or restricted areas may have limited serviceability.</p>
                    </div>

                    <!-- Section 2: Order Processing Time -->
                    <div class="scrollspy-content" id="item-2">
                        <h3>2. Order Processing Time</h3>
                        <p>Orders are usually processed within <strong>1–3 business days</strong> from the date of order confirmation. Business days exclude Sundays and public holidays.</p>
                        <p><strong>Processing includes:</strong></p>
                        <ul>
                            <li>Order verification and confirmation</li>
                            <li>Quality check and packaging</li>
                            <li>Handover to the courier partner</li>
                        </ul>
                        <p>Orders placed after 6:00 PM IST or on holidays will be processed on the next business day.</p>
                    </div>

                    <!-- Section 3: Delivery Timelines -->
                    <div class="scrollspy-content" id="item-3">
                        <h3>3. Delivery Timelines</h3>
                        <p>Delivery timelines may vary depending on the shipping location and courier partner availability. Estimated delivery times after dispatch:</p>
                        <ul>
                            <li><strong>Metro Cities (Mumbai, Delhi, Bangalore, Chennai, Kolkata, Hyderabad, Pune, Ahmedabad):</strong> 2–4 business days</li>
                            <li><strong>Major Cities & Tier-2 Cities:</strong> 3–6 business days</li>
                            <li><strong>Tier-3 Cities & Towns:</strong> 5–8 business days</li>
                            <li><strong>Remote & Rural Areas:</strong> 7–10 business days</li>
                        </ul>
                        <p>Please note that these are estimated timelines and actual delivery may vary based on courier partner performance and other factors.</p>
                    </div>

                    <!-- Section 4: Order Tracking -->
                    <div class="scrollspy-content" id="item-4">
                        <h3>4. Order Tracking</h3>
                        <p>Customers will receive order tracking details after the order has been dispatched from our warehouse. You will receive:</p>
                        <ul>
                            <li><strong>SMS Alert:</strong> Tracking link sent to your registered mobile number</li>
                            <li><strong>Email Notification:</strong> Detailed tracking information sent to your email address</li>
                            <li><strong>WhatsApp Update:</strong> (If opted-in) Real-time delivery updates</li>
                        </ul>
                        <p>You can also track your order directly on our website by visiting <a href="{{ route('home') }}">My Account → Order History</a> or by contacting our customer support team.</p>
                    </div>

                    <!-- Section 5: Shipping Charges -->
                    <div class="scrollspy-content" id="item-5">
                        <h3>5. Shipping Charges</h3>
                        <p>Shipping charges, if applicable, will be clearly displayed during the checkout process before you make the payment.</p>
                        <p><strong>Shipping Fee Structure:</strong></p>
                        <ul>
                            <li><strong>Free Shipping:</strong> Available on orders above a certain value (as mentioned on our website/promotions)</li>
                            <li><strong>Standard Shipping:</strong> Flat rate or weight-based charges apply based on your location</li>
                            <li><strong>Cash on Delivery (COD):</strong> Additional COD convenience fee may apply (if available)</li>
                        </ul>
                        <p>All shipping charges are non-refundable except in cases of damaged, defective, or incorrect products received.</p>
                    </div>

                    <!-- Section 6: Delivery Delays -->
                    <div class="scrollspy-content" id="item-6">
                        <h3>6. Delivery Delays</h3>
                        <p>While we strive to deliver your orders on time, delays caused by circumstances beyond our control are possible. We are not liable for delays caused by:</p>
                        <ul>
                            <li><strong>Courier Partners:</strong> Operational issues, high volume, or logistical challenges</li>
                            <li><strong>Natural Events:</strong> Natural disasters, floods, earthquakes, or extreme weather conditions</li>
                            <li><strong>Unforeseen Circumstances:</strong> Strikes, lockdowns, political unrest, or pandemics</li>
                            <li><strong>Incorrect Address:</strong> Incorrect or incomplete shipping address provided by the customer</li>
                            <li><strong>Recipient Unavailability:</strong> Repeated failed delivery attempts due to recipient not being available</li>
                        </ul>
                        <p>In case of significant delays, our customer support team will proactively reach out to you via email or phone. You can also track your order status or contact us for assistance.</p>
                    </div>

                    <!-- Section 7: Contact Support -->
                    <div class="scrollspy-content" id="item-7">
                        <h3>7. Contact Support</h3>
                        <p>If you have any questions, concerns, or issues regarding shipping and delivery, please contact our support team:</p>
                        
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
    SHIPPING POLICY PART END
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