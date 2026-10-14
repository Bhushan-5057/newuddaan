@extends('main.layouts.app')

@section('title', 'FAQ - Frequently Asked Questions - ' . company_info('company_name'))
@section('content')

<!--=====================================
    BANNER PART START
=======================================-->
<section class="inner-section single-banner" style="background: url('{{ asset('images/single-banner.jpg') }}') no-repeat center; background-size: cover;">
    <div class="container">
        <h2>Frequently Asked Questions</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">FAQ</li>
        </ol>
    </div>
</section>
<!--=====================================
    BANNER PART END
=======================================-->

<!--=====================================
    FAQ PART START
=======================================-->
<section class="inner-section faq-part">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                
                <!-- FAQ Category Tabs (Optional) -->
                <div class="faq-tabs mb-4">
                    <ul class="nav nav-tabs justify-content-center" id="faqTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders & Payments</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">Shipping & Delivery</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#returns" type="button" role="tab">Returns & Refunds</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">Account & Security</button>
                        </li>
                    </ul>
                </div>

                <!-- FAQ Content Tabs -->
                <div class="tab-content" id="faqTabContent">
                    
                    <!-- GENERAL FAQ SECTION -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="faq-parent">
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>What is {{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }}?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>{{ company_info('company_name') ?? 'Trevolis Technologies Private Limited' }} is a trusted wellness and healthcare products provider committed to delivering high-quality, natural, and effective wellness solutions across India. We strive to enhance the well-being of our customers through carefully curated products.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>Are your products FDA approved?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Our products are manufactured in facilities that comply with GMP (Good Manufacturing Practices) standards. While specific product certifications may vary, all our products meet the required safety and quality standards as per applicable regulations. Please check individual product pages for specific certifications.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How can I contact customer service?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>You can contact our customer service team through multiple channels:</p>
                                    <ul>
                                        <li><strong>Email:</strong> <a href="mailto:{{ company_info('company_email') ?? 'support@trevolistechnology.com' }}">{{ company_info('company_email') ?? 'support@trevolistechnology.com' }}</a></li>
                                        <li><strong>Phone:</strong> {{ company_info('company_mobile') ?? 'Not Available' }}</li>
                                        <li><strong>WhatsApp:</strong> Same number for quick support</li>
                                        <li><strong>Contact Form:</strong> Available on our Contact Us page</li>
                                    </ul>
                                    <p>Our support hours are Monday to Saturday, 10:00 AM – 7:00 PM IST.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>Do you have a physical store?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Currently, we operate primarily as an online e-commerce platform. Our registered office address is: {{ company_info('company_address') ?? 'India' }}. We are planning to open experience centers in major cities soon. Stay tuned for updates!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ORDERS & PAYMENTS FAQ SECTION -->
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div class="faq-parent">
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How do I place an order?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Placing an order is simple:</p>
                                    <ol>
                                        <li>Browse our products and add items to your cart</li>
                                        <li>Click on the cart icon and proceed to checkout</li>
                                        <li>Enter your shipping address and contact details</li>
                                        <li>Choose your payment method (Credit/Debit Card, UPI, Net Banking, COD, etc.)</li>
                                        <li>Review your order and click "Place Order"</li>
                                        <li>You will receive an order confirmation via email and SMS</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>What payment methods do you accept?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>We accept various payment methods:</p>
                                    <ul>
                                        <li>Credit Cards (Visa, MasterCard, RuPay, American Express)</li>
                                        <li>Debit Cards</li>
                                        <li>Net Banking (All major banks)</li>
                                        <li>UPI (Google Pay, PhonePe, Paytm, BHIM)</li>
                                        <li>Digital Wallets</li>
                                        <li>Cash on Delivery (COD) - Available in select locations</li>
                                        <li>EMI Options (Available on select cards)</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>Is it safe to use my credit/debit card on your website?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Absolutely! We use industry-standard SSL encryption to protect your payment information. All transactions are processed through PCI-DSS compliant payment gateways. We do not store your card details on our servers, ensuring maximum security for your financial data.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How do I apply a coupon or discount code?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>You can apply a coupon code during checkout. Look for the "Coupon Code" or "Promo Code" box on the checkout page, enter your code, and click "Apply". The discount will be reflected in your order total immediately. Please note that some codes have minimum purchase requirements or product restrictions.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>Can I cancel my order after placing it?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Yes, you can cancel your order before it is dispatched. To cancel:</p>
                                    <ul>
                                        <li>Go to "My Account" → "My Orders"</li>
                                        <li>Select the order you want to cancel</li>
                                        <li>Click on "Cancel Order" button</li>
                                        <li>Select the reason for cancellation and confirm</li>
                                    </ul>
                                    <p>Once cancelled, refunds will be processed within 7-10 business days. Orders that have already been shipped cannot be cancelled but can be returned after delivery (subject to our return policy).</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SHIPPING & DELIVERY FAQ SECTION -->
                    <div class="tab-pane fade" id="shipping" role="tabpanel">
                        <div class="faq-parent">
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How long does delivery take?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Orders are usually processed within 1–3 business days. Delivery timelines vary by location:</p>
                                    <ul>
                                        <li><strong>Metro Cities:</strong> 2–4 business days after dispatch</li>
                                        <li><strong>Major Cities:</strong> 3–6 business days after dispatch</li>
                                        <li><strong>Remote Areas:</strong> 7–10 business days after dispatch</li>
                                    </ul>
                                    <p>You will receive tracking details via email/SMS after dispatch.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How can I track my order?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Once your order is dispatched, you will receive:</p>
                                    <ul>
                                        <li><strong>SMS:</strong> Tracking link sent to your registered mobile number</li>
                                        <li><strong>Email:</strong> Detailed tracking information with courier partner details</li>
                                        <li><strong>Website:</strong> Track your order from "My Account" → "My Orders"</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>What are the shipping charges?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Shipping charges, if applicable, are clearly displayed during checkout. We offer:</p>
                                    <ul>
                                        <li><strong>Free Shipping:</strong> On orders above a certain value (check website for current offer)</li>
                                        <li><strong>Standard Shipping:</strong> Flat rate or weight-based charges</li>
                                        <li><strong>COD Fee:</strong> Additional convenience fee for Cash on Delivery orders</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>Do you ship internationally?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Currently, we deliver only within India. We are working on expanding our shipping network to international destinations. Stay updated by subscribing to our newsletter!</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>What if my order is delayed?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>While we strive for on-time delivery, delays can occur due to courier partner issues, natural events, or unforeseen circumstances. If your order is significantly delayed:</p>
                                    <ul>
                                        <li>Track your order using the tracking link provided</li>
                                        <li>Contact our customer support with your order ID</li>
                                        <li>We will investigate and provide you with an updated delivery estimate</li>
                                    </ul>
                                    <p>We appreciate your patience and understanding in such situations.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RETURNS & REFUNDS FAQ SECTION -->
                    <div class="tab-pane fade" id="returns" role="tabpanel">
                        <div class="faq-parent">
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>What is your return policy?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Due to the nature of wellness and consumable products, opened or used products are non-returnable. However, you can return products in the following cases:</p>
                                    <ul>
                                        <li>Damaged products received</li>
                                        <li>Wrong/incorrect products received</li>
                                        <li>Expired products received</li>
                                        <li>Missing items in the order</li>
                                    </ul>
                                    <p>Returns must be reported within 48 hours of delivery with proper images/videos as evidence.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How do I return a damaged product?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>To return a damaged or incorrect product:</p>
                                    <ol>
                                        <li>Email us at <a href="mailto:{{ company_info('company_email') ?? 'support@trevolistechnology.com' }}">{{ company_info('company_email') ?? 'support@trevolistechnology.com' }}</a> within 48 hours of delivery</li>
                                        <li>Provide your Order ID and clear photos/videos of the damaged product</li>
                                        <li>Our team will verify and approve the return within 2-3 business days</li>
                                        <li>Once approved, we will arrange for pickup or guide you on the return process</li>
                                        <li>Refund will be processed after quality check</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How long does a refund take?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Once your return is approved, refunds are processed within 7–10 business days. The refund will be credited to your original payment method:</p>
                                    <ul>
                                        <li><strong>Cards/Net Banking/UPI:</strong> 7-10 business days</li>
                                        <li><strong>Digital Wallets:</strong> 5-7 business days</li>
                                        <li><strong>COD Orders:</strong> Bank transfer within 10-12 business days</li>
                                    </ul>
                                    <p>You will receive email notifications at each stage of the refund process.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>Are shipping charges refundable?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Shipping charges are generally non-refundable. However, if you received a completely wrong order or a damaged product, the entire amount including shipping charges will be refunded.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ACCOUNT & SECURITY FAQ SECTION -->
                    <div class="tab-pane fade" id="account" role="tabpanel">
                        <div class="faq-parent">
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How do I create an account?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Creating an account is quick and easy:</p>
                                    <ul>
                                        <li>Click on the "Account" or "Login" icon at the top of the page</li>
                                        <li>Click on "Create New Account" or "Register"</li>
                                        <li>Fill in your name, email address, and mobile number</li>
                                        <li>Create a secure password</li>
                                        <li>Click "Register" to complete the process</li>
                                    </ul>
                                    <p>You can also create an account automatically when you place your first order.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>I forgot my password. How can I reset it?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>To reset your password:</p>
                                    <ol>
                                        <li>Click on "Login" or "Account"</li>
                                        <li>Click on "Forgot Password?" link</li>
                                        <li>Enter your registered email address or mobile number</li>
                                        <li>You will receive an OTP/password reset link via email/SMS</li>
                                        <li>Follow the instructions to create a new password</li>
                                    </ol>
                                    <p>For any issues, contact our customer support team for assistance.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>Is my personal information secure?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>Yes! We take data security seriously. We use:</p>
                                    <ul>
                                        <li>SSL encryption for all data transmission</li>
                                        <li>Secure servers protected by firewalls</li>
                                        <li>Restricted access to personal data within our organization</li>
                                        <li>Regular security audits and vulnerability scanning</li>
                                    </ul>
                                    <p>We never share your personal information with third parties without your consent. Please refer to our <a href="{{ route('privacy.policy') }}">Privacy Policy</a> for more details.</p>
                                </div>
                            </div>
                            
                            <div class="faq-child">
                                <div class="faq-que">
                                    <button>How do I update my profile information?</button>
                                </div>
                                <div class="faq-ans">
                                    <p>To update your profile:</p>
                                    <ol>
                                        <li>Log in to your account</li>
                                        <li>Go to "My Account" → "Profile Settings"</li>
                                        <li>Update your name, email, phone number, or address</li>
                                        <li>Click "Save Changes"</li>
                                    </ol>
                                    <p>You can also update your password, add multiple addresses, and manage your newsletter preferences from your account dashboard.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Still Have Questions Section -->
                <div class="still-have-questions text-center mt-5 pt-4">
                    <div class="question-box" style="background: #f8f9fa; padding: 30px; border-radius: 10px;">
                        <i class="icofont-support" style="font-size: 48px; color: {{ company_info('primary_color') ?? '#0066c0' }};"></i>
                        <h4 class="mt-3 mb-3">Still Have Questions?</h4>
                        <p class="mb-3">Can't find the answer you're looking for? Please contact our support team.</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ url('/contact') }}" class="btn btn-primary" style="background: {{ company_info('primary_color') ?? '#0066c0' }}; border: none; padding: 10px 25px; border-radius: 5px; color: #fff; text-decoration: none;">
                                <i class="icofont-ui-email"></i> Contact Support
                            </a>
                            <a href="tel:{{ company_info('company_mobile') ?? '' }}" class="btn btn-outline-secondary" style="padding: 10px 25px; border-radius: 5px; text-decoration: none;">
                                <i class="icofont-ui-touch-phone"></i> Call Us
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!--=====================================
    FAQ PART END
=======================================-->

<style>
    /* Banner Styling */
    .single-banner {
        min-height: 250px;
        display: flex;
        align-items: center;
        text-align: center;
        background-size: cover !important;
        position: relative;
    }
    
    .single-banner h2 {
        font-size: 36px;
        font-weight: 700;
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .single-banner .breadcrumb {
        background: transparent;
        justify-content: center;
        margin-bottom: 0;
    }
    
    .single-banner .breadcrumb-item a,
    .single-banner .breadcrumb-item {
        color: #fff;
        font-size: 16px;
    }
    
    .single-banner .breadcrumb-item.active {
        color: #ffd700;
    }
    
    /* FAQ Tabs Styling */
    .faq-tabs .nav-tabs {
        border-bottom: 2px solid #e0e0e0;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .faq-tabs .nav-tabs .nav-link {
        border: none;
        padding: 12px 24px;
        font-weight: 600;
        color: #555;
        background: transparent;
        border-radius: 30px;
        transition: all 0.3s;
        margin-bottom: 0;
    }
    
    .faq-tabs .nav-tabs .nav-link:hover {
        color: {{ company_info('primary_color') ?? '#0066c0' }};
        background: rgba(0,102,192,0.1);
    }
    
    .faq-tabs .nav-tabs .nav-link.active {
        color: #fff;
        background: {{ company_info('primary_color') ?? '#0066c0' }};
        border-radius: 30px;
    }
    
    /* FAQ Accordion Styling */
    .faq-parent {
        border: 1px solid #eaeaea;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .faq-child {
        border-bottom: 1px solid #eaeaea;
    }
    
    .faq-child:last-child {
        border-bottom: none;
    }
    
    .faq-que button {
        width: 100%;
        text-align: left;
        padding: 18px 20px;
        background: #fff;
        border: none;
        font-size: 16px;
        font-weight: 600;
        color: #222;
        cursor: pointer;
        position: relative;
        transition: all 0.3s;
    }
    
    .faq-que button:hover {
        background: #f9f9f9;
    }
    
    .faq-que button::after {
        content: '\f107';
        font-family: 'IcoFont';
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        transition: transform 0.3s;
    }
    
    .faq-child.active .faq-que button::after {
        transform: translateY(-50%) rotate(180deg);
    }
    
    .faq-ans {
        display: none;
        padding: 0 20px 20px 20px;
        background: #fafafa;
        border-top: 1px solid #eaeaea;
    }
    
    .faq-child.active .faq-ans {
        display: block;
    }
    
    .faq-ans p,
    .faq-ans ul,
    .faq-ans ol {
        margin-bottom: 12px;
        line-height: 1.7;
        color: #555;
    }
    
    .faq-ans ul li,
    .faq-ans ol li {
        margin-bottom: 8px;
    }
    
    .faq-ans a {
        color: {{ company_info('primary_color') ?? '#0066c0' }};
        text-decoration: none;
    }
    
    .faq-ans a:hover {
        text-decoration: underline;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .single-banner {
            min-height: 200px;
        }
        
        .single-banner h2 {
            font-size: 24px;
        }
        
        .faq-tabs .nav-tabs .nav-link {
            padding: 8px 16px;
            font-size: 14px;
        }
        
        .faq-que button {
            padding: 15px 35px 15px 15px;
            font-size: 14px;
        }
        
        .still-have-questions .question-box {
            padding: 20px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Accordion Functionality
        const faqButtons = document.querySelectorAll('.faq-que button');
        
        faqButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const faqChild = this.closest('.faq-child');
                const isActive = faqChild.classList.contains('active');
                
                // Optional: Close other open FAQs (uncomment for single open at a time)
                // document.querySelectorAll('.faq-child.active').forEach(activeFaq => {
                //     if (activeFaq !== faqChild) {
                //         activeFaq.classList.remove('active');
                //     }
                // });
                
                // Toggle current FAQ
                if (isActive) {
                    faqChild.classList.remove('active');
                } else {
                    faqChild.classList.add('active');
                }
            });
        });
        
        // Initialize Bootstrap tabs if needed (Bootstrap handles this automatically)
        // Just ensure Bootstrap JS is loaded
    });
</script>
@endsection