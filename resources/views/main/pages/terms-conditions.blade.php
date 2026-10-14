@extends('main.layouts.app')

@section('title', 'Terms & Conditions - ' . company_info('company_name'))
@section('content')

<!--=====================================
    BREADCRUMB START
=======================================-->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h2 class="breadcrumb-title">Terms & Conditions</h2>
                    <ul class="breadcrumb-list">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li>Terms & Conditions</li>
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
    TERMS & CONDITIONS PART START
=======================================-->
<section class="inner-section privacy-part">
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <nav class="nav nav-pills flex-column" id="scrollspy">
                    <a class="nav-link" href="#item-1">1. Acceptance of Terms</a>
                    <a class="nav-link" href="#item-2">2. Product Information & Disclaimer</a>
                    <a class="nav-link" href="#item-3">3. User Obligations</a>
                    <a class="nav-link" href="#item-4">4. Intellectual Property</a>
                    <a class="nav-link" href="#item-5">5. Limitation of Liability</a>
                    <a class="nav-link" href="#item-6">6. Governing Law & Jurisdiction</a>
                    <a class="nav-link" href="#item-7">7. Contact Us</a>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div data-bs-spy="scroll" data-bs-target="#scrollspy" data-bs-offset="0" tabindex="0">
                    
                    <!-- Section 1: Acceptance of Terms -->
                    <div class="scrollspy-content" id="item-1">
                        <h3>1. Acceptance of Terms</h3>
                        <p>By accessing and using this website, you agree to comply with and be bound by all terms, conditions, policies, and guidelines of <strong>{{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }}</strong>. If you do not agree with any of these terms, please do not use this website.</p>
                        <p>We reserve the right to modify, update, or change these Terms & Conditions at any time without prior notice. Your continued use of the website following any changes constitutes your acceptance of the revised terms.</p>
                    </div>

                    <!-- Section 2: Product Information & Disclaimer -->
                    <div class="scrollspy-content" id="item-2">
                        <h3>2. Product Information & Disclaimer</h3>
                        <p>All product information, including descriptions, images, specifications, and pricing, provided on this website is for general informational and wellness purposes only.</p>
                        <p><strong>Important Medical Disclaimer:</strong> The information provided on this website is not intended to diagnose, treat, cure, or prevent any disease. It should not be considered as medical advice, diagnosis, or prescription. Always consult with a qualified healthcare professional before starting any new wellness regimen or if you have any medical concerns.</p>
                        <p>While we strive to ensure the accuracy of all product information, we do not warrant that product descriptions, colors, or other content are complete, reliable, current, or error-free. The actual product may vary slightly from the images shown on the website.</p>
                    </div>

                    <!-- Section 3: User Obligations -->
                    <div class="scrollspy-content" id="item-3">
                        <h3>3. User Obligations</h3>
                        <p>As a user of this website, you agree to:</p>
                        <ul>
                            <li>Provide accurate, current, and complete information when creating an account or placing an order.</li>
                            <li>Maintain the confidentiality of your account credentials and be responsible for all activities under your account.</li>
                            <li>Notify us immediately of any unauthorized use of your account.</li>
                            <li>Not use the website for any unlawful purpose or in violation of any applicable laws.</li>
                            <li>Not attempt to gain unauthorized access to any portion of the website or interfere with its functionality.</li>
                            <li>Not upload or transmit any malicious code, viruses, or harmful content.</li>
                        </ul>
                    </div>

                    <!-- Section 4: Intellectual Property -->
                    <div class="scrollspy-content" id="item-4">
                        <h3>4. Intellectual Property</h3>
                        <p>All content on this website, including but not limited to text, graphics, logos, images, product designs, software, and trademarks, is the property of <strong>{{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }}</strong> or its content suppliers and is protected by Indian and international copyright laws.</p>
                        <p>You may not reproduce, distribute, modify, display, perform, transmit, or otherwise use any content without prior written permission from the company. You may download or print a copy of any content for personal, non-commercial use only, provided you do not remove any copyright or proprietary notices.</p>
                    </div>

                    <!-- Section 5: Limitation of Liability -->
                    <div class="scrollspy-content" id="item-5">
                        <h3>5. Limitation of Liability</h3>
                        <p>To the maximum extent permitted by law, <strong>{{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }}</strong> shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising from:</p>
                        <ul>
                            <li>Your use or inability to use the website.</li>
                            <li>Any errors, omissions, or inaccuracies in the content.</li>
                            <li>Unauthorized access to or alteration of your data.</li>
                            <li>Any interruption or cessation of website functionality.</li>
                            <li>Any products or services obtained through the website.</li>
                        </ul>
                        <p>Our total liability for any claim arising out of or relating to these terms or the website shall not exceed the amount paid by you, if any, for products purchased through the website.</p>
                    </div>

                    <!-- Section 6: Governing Law & Jurisdiction -->
                    <div class="scrollspy-content" id="item-6">
                        <h3>6. Governing Law & Jurisdiction</h3>
                        <p>These Terms & Conditions shall be governed by and construed in accordance with the laws of <strong>India</strong>.</p>
                        <p><strong>Jurisdiction:</strong> Any disputes, claims, or legal proceedings arising out of or relating to these terms or your use of this website shall be subject to the exclusive jurisdiction of the courts located in <strong>Gujarat, India</strong>. You hereby consent to the personal jurisdiction of such courts and waive any objection based on improper venue or forum non conveniens.</p>
                        <p>Any cause of action you may have must be filed within one (1) year after it arises, or it will be permanently barred.</p>
                    </div>

                    <!-- Section 7: Contact Us -->
                    <div class="scrollspy-content" id="item-7">
                        <h3>7. Contact Us</h3>
                        <p>If you have any questions, concerns, or requests regarding these Terms & Conditions, please contact us:</p>
                        
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
                                {{ company_info('company_address') ?? 'Gujarat, India' }}
                            </p>
                        </div>
                        
                      
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--=====================================
    TERMS & CONDITIONS PART END
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