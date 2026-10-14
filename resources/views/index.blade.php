<!DOCTYPE html>
<html lang="en">
   
<!-- Mirrored from pixner.net/SkyHub/index-two.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Jan 2026 09:47:36 GMT -->
<head>
      <!-- required meta -->
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- #favicon -->
         <link type="image/x-icon" href="{{ asset('uploads/' . company_info('favicon')) }}" rel="icon">
      <!-- #title -->
      <title>{{ company_info('company_name') }}</title>
      <!-- #keywords -->
      <meta name="keywords" content="Artificial, Ai, Network, Html">
      <!-- #description -->
      <meta name="description" content="Artificial Neural Network AI HTML Template">
      <!-- ==== css dependencies start ==== -->
      <!-- bootstrap five css -->
      <link rel="stylesheet" href="{{ asset('maintheme/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
      <!-- bootstrap icons css -->
      <link rel="stylesheet" href=" {{ asset('maintheme/assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
      <!-- material icons css -->
      <link rel="stylesheet" href="{{ asset('maintheme/assets/vendor/material-icons/css/materialicons.css') }}">
      <!-- font awesome six css -->
      <link rel="stylesheet" href="{{ asset('maintheme/assets/vendor/font-awesome/css/all.css') }}">
      <!-- magnific popup css -->
      <link rel="stylesheet" href="{{ asset('maintheme/assets/vendor/magnific-popup/css/magnific-popup.css') }}">
      <!-- jquery ui css -->
      <link rel="stylesheet" href=" {{ asset('maintheme/assets/vendor/jquery-ui/css/jquery-ui.min.css') }}">
      <!-- slick css -->
      <link rel="stylesheet" href="{{ asset('maintheme/assets/vendor/slick/css/slick.css') }}">
      <!-- odometer css -->
      <link rel="stylesheet" href="{{ asset('maintheme/assets/vendor/odometer/css/odometer.css') }}">
      <!-- ==== / css dependencies end ==== -->
      <!-- main css -->
      <link rel="stylesheet" href="{{ asset('maintheme/assets/css/main.css') }}">
   </head>
   <body>
      <!--[if lte IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
      <![endif]-->
      <div class="my-app">
         <!-- ==== preloader start ==== -->
         <div id="preloader">
            <div id="ctn-preloader" class="ctn-preloader">
               <div class="animation-preloader">
                  <div class="spinner"></div>
                  <div class="txt-loading">
                     <span data-text-preloader="L" class="letters-loading">
                     S
                     </span>
                     <span data-text-preloader="O" class="letters-loading">
                     K
                     </span>
                     <span data-text-preloader="A" class="letters-loading">
                     Y
                     </span>
                     <span data-text-preloader="D" class="letters-loading">
                     H
                     </span>
                     <span data-text-preloader="I" class="letters-loading">
                     U
                     </span>
                     <span data-text-preloader="N" class="letters-loading">
                     B
                     </span>
                  </div>
               </div>
               <div class="loader-section section-left"></div>
               <div class="loader-section section-right"></div>
            </div>
         </div>
         <!-- ==== / preloader end ==== -->
         <!-- ==== mouse cursor drag start ==== -->
         <div class="mouseCursor cursor-outer"></div>
         <div class="mouseCursor cursor-inner">
            <span>Drag</span>
         </div>
         <!-- ==== / mouse cursor drag end ==== -->
         <!-- ==== header start ==== -->
         <header class="header">
            <div class="primary-navbar">
               <div class="container">
                  <div class="row">
                     <div class="col-12">
                        <nav class="navbar p-0">
                           <div class="navbar__logo">
                              <a href="{{ url('/') }}" aria-label="home page" title="logo">
                              <img src="{{ asset('uploads/' . company_info('logo')) }}" height="70" width="70" alt="Image">
                              </a>
                           </div>
                           <div class="navbar__menu">
                              <ul class="navbar__list">
                                 <li class="navbar__item navbar__item--has-children nav-fade">
                                    <a href="{{ url('/') }}">Home</a>
                                    
                                 </li>
                                 <li class="navbar__item nav-fade">
                                    <a href="#scrollPosition">About Us</a>
                                 </li>
                                 <li class="navbar__item navbar__item--has-children nav-fade">
                                    <a href="#Visionand">Vision & Mission</a>
                                    
                                 </li>
                                 <li class="navbar__item navbar__item--has-children nav-fade">
                                    <a href="#">Pages</a>
                                   
                                 </li>
                                 <li class="navbar__item navbar__item--has-children nav-fade">
                                    <a href="#">Blog</a>
                            
                                 </li>
                                 <li class="navbar__item nav-fade">
                                    <a href="#contact">Contact Us</a>
                                 </li>
                              </ul>
                           </div>
                           <!-- <div class="navbar__options">
                              <div class="navbar__mobile-options">
                                 <button class="open-cart" aria-label="selected products" title="see cart items">
                                 <i class="bi bi-bag-plus"></i>
                                 </button>
                                 <button class="open-search" aria-label="search products" title="open search box">
                                 <i class="bi bi-search"></i>
                                 </button>
                                 <button class="open-offcanvas" aria-label="open offcanvas" title="open offcanvas">
                                 <i class="bi bi-grid-3x3-gap"></i>
                                 </button>
                              </div>-->
                              <button class="open-mobile-menu d-flex d-xl-none" aria-label="toggle mobile menu" title="open mobile menu">
                              <i class="material-symbols-outlined">
                              menu_open
                              </i>
                              </button>
                           </div> 
                        </nav>
                     </div>
                  </div>
               </div>
               <!-- ==== mobile menu start ==== -->
               <div class="mobile-menu d-block d-xl-none">
                  <nav class="mobile-menu__wrapper">
                     <div class="mobile-menu__header nav-fade">
                        <div class="logo">
                           <a href="{{ url('/') }}" aria-label="home page" title="logo">
                           <img src="{{ asset('uploads/' . company_info('logo')) }}" height="70" width="70" alt="Image">
                           </a>
                        </div>
                        <button aria-label="close mobile menu" class="close-mobile-menu">
                        <i class="bi bi-x-lg"></i>
                        </button>
                     </div>
                     <div class="mobile-menu__list"></div>
                     <div class="mobile-menu__social social nav-fade">
                        <a
                           href="https://www.facebook.com/"
                           target="_blank"
                           aria-label="share us on facebook"
                           title="facebook"
                           >
                        <i class="bi bi-facebook"></i>
                        </a>
                        <a
                           href="https://www.twitter.com/"
                           target="_blank"
                           aria-label="share us on twitter"
                           title="twitter"
                           >
                        <i class="bi bi-twitter"></i>
                        </a>
                        <a
                           href="https://www.linkedin.com/"
                           target="_blank"
                           aria-label="share us on pinterest"
                           title="linkedin"
                           >
                        <i class="bi bi-linkedin"></i>
                        </a>
                        <a
                           href="https://www.instagram.com/"
                           target="_blank"
                           aria-label="share us on instagram"
                           title="instagram"
                           >
                        <i class="bi bi-instagram"></i>
                        </a>
                     </div>
                  </nav>
               </div>
               <div class="mobile-menu__backdrop"></div>
               <!-- ==== / mobile menu end ==== -->
            </div>
         </header>
         <!-- ==== / header end ==== -->
         <!-- ==== offcanvas cart start ==== -->
         <div class="sidebar-cart">
            <div class="sidebar-cart__inner">
               <button class="close-cart">
               <i class="bi bi-x-lg close-icon"></i>
               </button>
               <h2>
                  Shopping Bag
                  <span class="count-item"></span>
               </h2>
               <div class="cart-items">
                  <div class="cart-item-single">
                     <div class="cart-item-thumb">
                        <a href="">
                        <img src="{{ asset('maintheme/assets/images/cart.png') }}" alt="Image">
                        </a>
                     </div>
                     <div class="cart-item-content">
                        <h6 class="title-anim">
                           <a href="">Adv Prompts</a>
                        </h6>
                        <p class="price">
                           $
                           <span class="item-price">34.99</span>
                        </p>
                        <div class="measure">
                           <button aria-label="decrease item" class="quantity-decrease">
                           <i class="fa-solid fa-minus"></i>
                           </button>
                           <span class="item-quantity">0</span>
                           <button aria-label="add item" class="quantity-increase">
                           <i class="fa-solid fa-plus"></i>
                           </button>
                        </div>
                     </div>
                     <button aria-label="delete item" class="delete-item">
                     <i class="fa-solid fa-trash"></i>
                     </button>
                  </div>
                  <div class="cart-item-single">
                     <div class="cart-item-thumb">
                        <a href="">
                        <img src="{{ asset('maintheme/assets/images/cart-two.png') }}" alt="Image">
                        </a>
                     </div>
                     <div class="cart-item-content">
                        <h6 class="title-anim">
                           <a href="">Creative Design</a>
                        </h6>
                        <p class="price">
                           $
                           <span class="item-price">67.32</span>
                        </p>
                        <div class="measure">
                           <button aria-label="decrease item" class="quantity-decrease">
                           <i class="fa-solid fa-minus"></i>
                           </button>
                           <span class="item-quantity">0</span>
                           <button aria-label="add item" class="quantity-increase">
                           <i class="fa-solid fa-plus"></i>
                           </button>
                        </div>
                     </div>
                     <button aria-label="delete item" class="delete-item">
                     <i class="fa-solid fa-trash"></i>
                     </button>
                  </div>
                  <div class="cart-item-single">
                     <div class="cart-item-thumb">
                        <a href="">
                        <img src="{{ asset('maintheme/assets/images/cart.png') }}" alt="Image">
                        </a>
                     </div>
                     <div class="cart-item-content">
                        <h6 class="title-anim">
                           <a href="">Ai Prompts</a>
                        </h6>
                        <p class="price">
                           $
                           <span class="item-price">24.00</span>
                        </p>
                        <div class="measure">
                           <button aria-label="decrease item" class="quantity-decrease">
                           <i class="fa-solid fa-minus"></i>
                           </button>
                           <span class="item-quantity">0</span>
                           <button aria-label="add item" class="quantity-increase">
                           <i class="fa-solid fa-plus"></i>
                           </button>
                        </div>
                     </div>
                     <button aria-label="delete item" class="delete-item">
                     <i class="fa-solid fa-trash"></i>
                     </button>
                  </div>
               </div>
               <div class="empty-cart">
                  <h5>Your cart is empty</h5>
                  <a href="#">Add Products</a>
               </div>
               <div class="totals">
                  <div class="subtotal">
                     <span class="label">Subtotal:</span>
                     <span class="amount ">
                     $
                     <span class="total-price">0.00</span>
                     </span>
                  </div>
               </div>
               <div class="action-buttons">
                  <a class="view-cart-button" href="#" aria-label="go to cart">Cart</a>
                  <a class="checkout-button" href="#" aria-label="go to checkout">
                  Checkout
                  <i class="fa-solid fa-arrow-right-long"></i>
                  </a>
               </div>
            </div>
         </div>
         <div class="cart-backdrop"></div>
         <!-- ==== / offcanvas cart end ==== -->
         <!-- ==== search popup start ==== -->
         <div class="search-popup">
            <button class="close-search" aria-label="close search box" title="close search box">
            <i class="bi bi-x-lg"></i>
            </button>
            <form action="#" method="post">
               <div class="search-popup__group">
                  <input
                     type="text"
                     name="search-field"
                     id="searchField"
                     placeholder="Enter Product Name"
                     required
                     >
                  <button type="submit" aria-label="search products" title="search products">
                  <i class="bi bi-search"></i>
                  </button>
               </div>
            </form>
         </div>
         <!-- ==== / search popup end ==== -->
         <!-- ==== offcanvas info start ==== -->
         <div class="offcanvas-info">
            <div class="offcanvas-info__inner">
               <div class="offcanvas-info__intro">
                  <div class="offcanvas-info__logo">
                     <a href="{{ url('/') }}">
                     <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="Image">
                     </a>
                  </div>
                  <h4>Intelligent Conversations Made Simple</h4>
               </div>
               <div class="offcanvas-info__content">
                  <h5>Contact Us</h5>
                  <ul>
                     <li>
                        <a href="https://pixner.net/cdn-cgi/l/email-protection#d293bbb9b7a792b7aab3bfa2beb7fcb1bdbf"><span class="__cf_email__" data-cfemail="db9ab2b0beae9bbea3bab6abb7bef5b8b4b6">[email&#160;protected]</span></a>
                     </li>
                     <li>
                        <a href="tel:1880-480-555-0103">+1 880 (480) 555-0103</a>
                     </li>
                     <li>
                        <a href="https://www.google.com/maps/place/Kentucky,+USA/@37.8172108,-87.087054,8z/data=!3m1!4b1!4m6!3m5!1s0x8842734c8b1953c9:0x771f6f4ec5ccdffc!8m2!3d37.8393332!4d-84.2700179!16zL20vMDQ5OHk?entry=ttu" target="_blank">4517 Washington Ave. Manchester, Kentucky 39495</a>
                     </li>
                  </ul>
               </div>
               <div class="offcanvas-info__form">
                  <h5>Subscribe to newsletter</h5>
                  <form action="#" method="post" autocomplete="off">
                     <div class="subscribe-form">
                        <input
                           type="email"
                           name="subscribe-newsletter"
                           id="subscribeNewsletter"
                           placeholder="Email"
                           required
                           >
                        <button type="submit" aria-label="subscribe newsletter" title="subscribe newsletter">
                        <i class="material-symbols-outlined">
                        send
                        </i>
                        </button>
                     </div>
                  </form>
               </div>
               <div class="offcanvas-info__footer">
                  <p class="tertiary-text">Follow Us :</p>
                  <div class="social">
                     <a
                        href="https://www.facebook.com/"
                        target="_blank"
                        aria-label="share us on facebook"
                        title="facebook"
                        >
                     <i class="bi bi-facebook"></i>
                     </a>
                     <a
                        href="https://www.twitter.com/"
                        target="_blank"
                        aria-label="share us on twitter"
                        title="twitter"
                        >
                     <i class="bi bi-twitter"></i>
                     </a>
                     <a
                        href="https://www.linkedin.com/"
                        target="_blank"
                        aria-label="share us on pinterest"
                        title="linkedin"
                        >
                     <i class="bi bi-linkedin"></i>
                     </a>
                     <a
                        href="https://www.instagram.com/"
                        target="_blank"
                        aria-label="share us on instagram"
                        title="instagram"
                        >
                     <i class="bi bi-instagram"></i>
                     </a>
                  </div>
               </div>
            </div>
            <button class="close-offcanvas-info" aria-label="close offcanvas info">
            <i class="bi bi-x-lg"></i>
            </button>
         </div>
         <div class="offcanvas-info-backdrop"></div>
         <!-- ==== / offcanvas info end ==== -->
         <div id="smooth-wrapper">
            <div id="smooth-content">
               <!-- ==== main start ==== -->
               <main>
                  <!-- ==== banner two start ==== -->
                  <section class="banner-two" data-background="{{ asset('maintheme/assets/images/banner/banner-two-bg.png') }}">
                     <div class="container">
                        <div class="row">
                           <div class="col-12 col-md-10 col-lg-6">
                              <div class="banner-two__content">
                                 <h1 class="title-animation">
                                    <span>Crypto Mining</span>
                                    Hosting Made Simple
                                 </h1>
                                 <p class="primary-text">SkyHub provides secure, energy-efficient, and scalable mining hosting solutions. Focus on mining while we handle infrastructure, cooling, power, and uptime</p>
                                 <div class="section__content-cta">
                                    <a href="#" class="btn btn--primary">Get Started</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="banner-two__thumb">
                        <img src="{{ asset('maintheme/assets/images/banner/banner-two-thumb.png') }}" alt="Image">
                     </div>
                     <a class="scroll-position-btn" href="#scrollPosition">
                     <!-- <img src="assets/images/frame-three.png" alt="Image"> -->
                     <i class="bi bi-arrow-down"></i>
                     </a>
                  </section>
                  <!-- ==== / banner two end ==== -->
                  <!-- ==== text slider large start ==== -->
                  <section class="text-slider-large-wrapper section pb-0">
                     <div class="text-slider-large">
                        <div class="text-slider__single">
                           <h2 class="large-title">
                              <a href="">
                              
                              <span class="text-stroke" data-text="SkyHub"> SkyHub</span>
                              </a>
                           </h2>
                        </div>
                        <div class="text-slider__single">
                           <h2 class="large-title">
                              <a href="">
                              
                              <span class="text-stroke" data-text="SkyHub"> SkyHub</span>
                              </a>
                           </h2>
                        </div>
                        <div class="text-slider__single">
                           <h2 class="large-title">
                              <a href="">
                              
                              <span class="text-stroke" data-text="SkyHub"> SkyHub</span>
                              </a>
                           </h2>
                        </div>
                        <div class="text-slider__single">
                           <h2 class="large-title">
                              <a href="">
                              
                              <span class="text-stroke" data-text="SkyHub"> SkyHub</span>
                              </a>
                           </h2>
                        </div>
                     </div>
                  </section>
                  <!-- ==== / text slider large end ==== -->
                  <!-- ==== counter start ==== -->
                  <section class="counter section pb-0 fade-wrapper">
                     <div class="container">
                        <div class="row">
                           <div class="col-12">
                              <div class="counter__inner">
                                 <div class="counter__single fade-top">
                                    <h2 class="light-title">
                                       <span class="odometer" data-odometer-final="80"></span>
                                       <span class="prefix"> k</span>
                                    </h2>
                                    <p class="primary-text">Active User</p>
                                 </div>
                                 <div class="counter__single d-none d-xxl-block">
                                    <img src="{{ asset('maintheme/assets/images/star-two.png') }}" alt="Image">
                                 </div>
                                 <div class="counter__single fade-top">
                                    <h2 class="light-title">
                                       <span class="odometer" data-odometer-final="100"></span>
                                       <span class="prefix"> %</span>
                                    </h2>
                                    <p class="primary-text">Satisfaction</p>
                                 </div>
                                 <div class="counter__single d-none d-xxl-block">
                                    <img src="{{ asset('maintheme/assets/images/star-two.png') }}" alt="Image">
                                 </div>
                                 <div class="counter__single fade-top">
                                    <h2 class="light-title">
                                       <span class="odometer" data-odometer-final="2000"></span>
                                       <span class="prefix"> +</span>
                                    </h2>
                                    <p class="primary-text">Daily User</p>
                                 </div>
                                 <div class="counter__single d-none d-xxl-block">
                                    <img src="{{ asset('maintheme/assets/images/star-two.png') }}" alt="Image">
                                 </div>
                                 <div class="counter__single fade-top">
                                    <h2 class="light-title">
                                       <span class="odometer" data-odometer-final="05"></span>
                                       <span class="prefix"> k</span>
                                    </h2>
                                    <p class="primary-text">Years of Business</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <!-- ==== / counter end ==== -->
                  <!-- ==== gen start ==== -->
                  <section class="section gen pb-0" id="scrollPosition">
                     <div class="container">
                        <div class="row align-items-center gaper">
                           <div class="col-12 col-lg-5 col-xxl-5">
                              <div class="section__content">
                                 <span class="sub-title-two">
                                About Us
                                 </span>
                                 <h2 class="title title-animation">Building Reliable Infrastructure</h2>
                                 <p>SkyHub specializes in hosting solutions designed to support modern cryptocurrency mining operations. We offer secure facilities, consistent power supply, and efficient cooling systems that help miners achieve stable performance and long-term profitability.</p>
                                 <div class="section__content-cta">
                                    <a href="#" class="btn btn--primary">Get started</a>
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 col-lg-7 col-xxl-6 offset-xxl-1">
                              <div class="gen__thumb">
                                 <div class="reveal-img parallax-img">
                                    <img src="{{ asset('maintheme/assets/images/qqq.jpeg') }}" alt="Image" class="unset">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <!-- ==== / gen end ==== -->
                  <!-- ==== partner start ==== -->
                  <div class="partner section pb-0">
                     <div class="container">
                        <div class="row justify-content-center">
                           <div class="col-12 col-lg-5">
                              <div class="section__header text-center">
                                 <h2 class="title sub-title-two mt-0 title-animation">
                                    Our Partner's
                                 </h2>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <div class="partner__slider">
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/one.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/two.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/three.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/four.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/five.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/one.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/two.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/three.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/four.png') }}" alt="Image">
                                 </div>
                                 <div class="partner__slider-single">
                                    <img src="{{ asset('maintheme/assets/images/partner/five.png') }}" alt="Image">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- ==== / partner end ==== -->
                  <!-- ==== easy start ==== -->
<section class="section easy pb-0 position-relative">
   <div class="container">
      <div class="row gaper align-items-center">
         
         <!-- VIDEO COLUMN -->
         <div class="col-12 col-lg-6 col-xl-5">
            <div class="easy__thumb dir-rtl reveal-img d-block parallax-img">
               
               <video 
                  class="unset"
                  autoplay
                  loop
                  muted
                  playsinline
                  style="width:100%; height:auto; border-radius:12px;"
               >
                  <source src="{{ asset('maintheme/assets/videos/aabb.mp4') }}" type="video/mp4">
                  Your browser does not support the video tag.
               </video>

            </div>
         </div>

         <!-- CONTENT COLUMN -->
         <div class="col-12 col-lg-6 col-xl-5 offset-xl-2" id="Visionand">
            <div class="section__content">
               <span class="sub-title-two">
                  Vision & Mission
               </span>
               <h2 class="title title-animation">Powering the Future of Crypto Mining</h2>
               <p>
                 SkyHub’s vision is to build a secure, scalable, and energy-efficient crypto mining ecosystem where miners can grow with confidence. Through advanced infrastructure, cost-effective hosting solutions, and 24/7 monitoring, we ensure maximum uptime and high performance, enabling our clients to scale their mining operations reliably and efficiently.
               </p>
               <div class="section__content-cta">
                  <a href="#" class="btn btn--primary">Get started</a>
               </div>
            </div>
         </div>

      </div>
   </div>
</section>

                  <!-- ==== / easy end ==== -->
                  <!-- ==== overview two start ==== -->
                  <section class="section overview-two fade-wrapper pb-0">
                     <div class="container">
                        <div class="row align-items-center justify-content-center gaper">
                           <div class="col-12 col-sm-9 col-md-6 col-xl-4">
                              <div class="overview-two__single text-center fade-top">
                                 <div class="thumb">
                                    <img src="{{ asset('maintheme/assets/images/o-one.png') }}" alt="Image">
                                 </div>
                                 <div class="content">
                                    <h4>
                                       <a href="#">Analytics and Insights</a>
                                    </h4>
                                    <p class="tertiary-text">SkyHub can learn from user interactions and improve over time</p>
                                    <div class="section__content-cta">
                                       <a href="#">
                                       <span class="arrow"></span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 col-sm-9 col-md-6 col-xl-4">
                              <div class="overview-two__single text-center fade-top">
                                 <div class="thumb">
                                    <img src="{{ asset('maintheme/assets/images/o-two.png') }}" alt="Image">
                                 </div>
                                 <div class="content">
                                    <h4>
                                       <a href="#">Advance Programing</a>
                                    </h4>
                                    <p class="tertiary-text">SkyHub can learn from user interactions and improve over time</p>
                                    <div class="section__content-cta">
                                       <a href="#">
                                       <span class="arrow"></span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 col-sm-9 col-md-6 col-xl-4">
                              <div class="overview-two__single text-center fade-top">
                                 <div class="thumb">
                                    <img src="{{ asset('maintheme/assets/images/o-three.png') }}" alt="Image">
                                 </div>
                                 <div class="content">
                                    <h4>
                                       <a href="#">Cloud Ai Intergration</a>
                                    </h4>
                                    <p class="tertiary-text">SkyHub can learn from user interactions and improve over time</p>
                                    <div class="section__content-cta">
                                       <a href="#">
                                       <span class="arrow"></span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <!-- ==== / overview two end ==== -->
                  <!-- ==== easy two start ==== -->
                  <section class="section easy-two position-relative">
                     <div class="container">
                        <div class="row gaper align-items-center">
                           <div class="col-12 col-lg-6 col-xl-6">
                              <div class="easy__thumb reveal-img parallax-img">
                                 <img src="{{ asset('maintheme/assets/images/easy-two-thumb.png') }}" alt="Image">
                              </div>
                           </div>
                           <div class="col-12 col-lg-6 col-xl-5 offset-xl-1">
                              <div class="section__content">
                                 <span class="sub-title-two">
                                 Sky Hub
                                 </span>
                                 <h2 class="title title-animation">Simplify Crypto Mining with SkyHub </h2>
                                 <p>SkyHub provides reliable crypto mining hosting solutions designed to make mining easier and more efficient. With secure infrastructure, stable power, advanced cooling systems, and continuous monitoring, we help miners reduce operational complexity and focus on maximizing performance and profitability.</p>
                                 <div class="section__content-cta">
                                    <a href="#" class="btn btn--primary">Get started</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <!-- ==== / easy two end ==== -->
                  <!-- ==== review start ==== -->
                  <section class="section review">
                     <div class="container">
                        <div class="row justify-content-center">
                           <div class="col-12 col-lg-5">
                              <div class="section__header text-center">
                                 <span class="sub-title-two">Review</span>
                                 <h2 class="title title-animation">Our User Review</h2>
                                 <p>SkyHub can generate analytics and insights on user interactions,</p>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <div class="review__slider">
                                 <div class="review__slider-single">
                                    <p>I recently had the pleasure of experiencing the transformative power of s, and I must say, I am thoroughly impressed.</p>
                                    <div class="review__meta">
                                       <div class="thumb">
                                          <img src="{{ asset('maintheme/assets/images/t-one.png') }}" alt="Image">
                                       </div>
                                       <div class="content">
                                          <h5>Devon Lane</h5>
                                          <p class="tertiary-text">Nursing Assistant</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="review__slider-single">
                                    <p>I recently had the pleasure of experiencing the transformative power of s, and I must say, I am thoroughly impressed.</p>
                                    <div class="review__meta">
                                       <div class="thumb">
                                          <img src="{{ asset('maintheme/assets/images/t-two.png') }}" alt="Image">
                                       </div>
                                       <div class="content">
                                          <h5>Kathryn Murphy</h5>
                                          <p class="tertiary-text">Web Designer</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="review__slider-single">
                                    <p>I recently had the pleasure of experiencing the transformative power of s, and I must say, I am thoroughly impressed.</p>
                                    <div class="review__meta">
                                       <div class="thumb">
                                          <img src="{{ asset('maintheme/assets/images/t-three.png') }}" alt="Image">
                                       </div>
                                       <div class="content">
                                          <h5>Ralph Edwards</h5>
                                          <p class="tertiary-text">Marketing Coordinator</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="review__slider-single">
                                    <p>I recently had the pleasure of experiencing the transformative power of s, and I must say, I am thoroughly impressed.</p>
                                    <div class="review__meta">
                                       <div class="thumb">
                                          <img src="{{ asset('maintheme/assets/images/t-one.png') }}" alt="Image">
                                       </div>
                                       <div class="content">
                                          <h5>Devon Lane</h5>
                                          <p class="tertiary-text">Nursing Assistant</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="review__slider-single">
                                    <p>I recently had the pleasure of experiencing the transformative power of s, and I must say, I am thoroughly impressed.</p>
                                    <div class="review__meta">
                                       <div class="thumb">
                                          <img src="{{ asset('maintheme/assets/images/t-two.png') }}" alt="Image">
                                       </div>
                                       <div class="content">
                                          <h5>Kathryn Murphy</h5>
                                          <p class="tertiary-text">Web Designer</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="review__slider-single">
                                    <p>I recently had the pleasure of experiencing the transformative power of s, and I must say, I am thoroughly impressed.</p>
                                    <div class="review__meta">
                                       <div class="thumb">
                                          <img src="{{ asset('maintheme/assets/images/t-three.png') }}" alt="Image">
                                       </div>
                                       <div class="content">
                                          <h5>Ralph Edwards</h5>
                                          <p class="tertiary-text">Marketing Coordinator</p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <div class="section__cta">
                                 <div class="slider-pagination-group review-pagination d-flex justify-content-center"></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <!-- ==== / review end ==== -->
                  <!-- ==== easy start ==== -->
                  <section class="section easy easy--secondary position-relative">
                     <div class="container">
                        <div class="row gaper align-items-center">
                           <div class="col-12 col-lg-6 col-xl-5">
                              <div class="easy__thumb dir-rtl reveal-img d-block parallax-img">
                                 <img src="{{ asset('maintheme/assets/images/bot-three.png') }}" alt="Image" class="unset">
                              </div>
                           </div>
                           <div class="col-12 col-lg-6 col-xl-6 offset-xl-1">
                              <div class="section__content">
                                 <span class="sub-title-two">
                                 Sky Hub
                                 </span>
                                 <h2 class="title title-animation">Reliable Hosting for Smarter Crypto Mining</h2>
                                 <p>SkyHub offers professionally managed crypto mining hosting designed to reduce operational stress and increase efficiency From stable power and advanced cooling to secure facilities and continuous monitoring, we provide the foundation miners need to operate smoothly and scale with confidence.</p>
                                 <div class="section__content-cta">
                                    <div class="easy__cta">
                                       <div class="easy__cta-single" data-background="{{ asset('maintheme/assets/images/r-line.png') }}">
                                          <div class="content">
                                             <h4></h4>
                                             <p>Customization</p>
                                          </div>
                                          <div class="thumb">
                                             <img src="{{ asset('maintheme/assets/images/r-d.png') }}" alt="Image">
                                          </div>
                                       </div>
                                       <div class="easy__cta-single easy__cta-single-alt">
                                          <div class="thumber">
                                             <img src="{{ asset('maintheme/assets/images/wave.png') }}" alt="Image">
                                          </div>
                                          <div class="content">
                                             <h4>Voice</h4>
                                             <p>Recognition</p>
                                          </div>
                                          <div class="thumb">
                                             <img src="{{ asset('maintheme/assets/images/r-d-t.png') }}" alt="Image">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <!-- ==== / easy end ==== -->
               </main>
               <!-- ==== / main end ==== -->
               <!-- ==== footer start ==== -->
               <footer class="footer-cmn">
                  <div class="container" id="contact">
                     <div class="row">
                        <div class="col-12">
                           <div class="footer-cmn__cta text-center appear-down">
                              <h2 class="title fw-7 title-animation">Join To SkyHub</h2>
                              <div class="section__content-cta">
                                 <a href="#" class="btn btn--primary">Get Started</a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-12">
                           <div class="footer-cmn__inner section ">
                              <div class="row  gaper">
                                 <div class="col-12 col-lg-7 col-xl-8">
                                    <div class="footer-cmn__left text-center text-lg-start">
                                       <a href="{{ url('/') }}" class="logo">
                                       <img src="{{ asset('uploads/' . company_info('logo')) }}" height="70" width="70" alt="Image">
                                       </a>
                                       <div class="footer__nav-list">
                                          <ul class="justify-content-center justify-content-lg-start">
                                             <li>
                                                <a href="{{ url('/') }}">Home</a>
                                             </li>
                                             <li>
                                                <a href="#">About Us</a>
                                             </li>
                                             <li>
                                                <a href="#">Shop</a>
                                             </li>
                                             <li>
                                                <a href="#">Blog</a>
                                             </li>
                                             <li>
                                                <a href="#">Contact Us</a>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12 col-lg-5 col-xl-4">
                                    <div class="offcanvas-info__form">
                                       <h5 class="text-white text-center text-lg-start">Subscribe to newsletter</h5>
                                       <form action="#" method="post" autocomplete="off">
                                          <div class="subscribe-form">
                                             <input
                                                type="email"
                                                name="subscribe-newsletter"
                                                id="subscribeNewsletterTwo"
                                                placeholder="Email"
                                                required
                                                >
                                             <button type="submit" aria-label="subscribe newsletter" title="subscribe newsletter">
                                             <i class="material-symbols-outlined">
                                             send
                                             </i>
                                             </button>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="footer__copyright">
                     <div class="container">
                        <div class="row gaper">
                           <div class="col-12 col-lg-6">
                              <div class="footer__copyright-content text-center text-lg-start">
                                 <p>
                                    Copyright &copy;
                                    <span id="copyrightYear"></span>
                                    <a href="{{ url('/') }}">SkyHub</a>
                                    . All Rights Reserved
                                 </p>
                              </div>
                           </div>
                           <!-- <div class="col-12 col-lg-6">
                              <div class="footer__copyright-social justify-content-center justify-content-lg-end">
                                 <p class="tertiary-text d-none d-lg-block">Follow Us :</p>
                                 <div class="social">
                                    <a
                                       href="https://www.facebook.com/"
                                       target="_blank"
                                       aria-label="share us on facebook"
                                       title="facebook"
                                       >
                                    <i class="bi bi-facebook"></i>
                                    </a>
                                    <a
                                       href="https://www.twitter.com/"
                                       target="_blank"
                                       aria-label="share us on twitter"
                                       title="twitter"
                                       >
                                    <i class="bi bi-twitter"></i>
                                    </a>
                                    <a
                                       href="https://www.linkedin.com/"
                                       target="_blank"
                                       aria-label="share us on pinterest"
                                       title="linkedin"
                                       >
                                    <i class="bi bi-linkedin"></i>
                                    </a>
                                    <a
                                       href="https://www.instagram.com/"
                                       target="_blank"
                                       aria-label="share us on instagram"
                                       title="instagram"
                                       >
                                    <i class="bi bi-instagram"></i>
                                    </a>
                                 </div>
                              </div>
                           </div> -->
                        </div>
                     </div>
                  </div>
               </footer>
               <!-- ==== / footer end ==== -->
            </div>
         </div>
         <!-- ==== scroll to top start ==== -->
         <button class="progress-wrap" aria-label="scroll indicator" title="go to top">
            <span></span>
            <svg
               class="progress-circle svg-content"
               width="100%"
               height="100%"
               viewBox="-1 -1 102 102"
               >
               <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
            </svg>
         </button>
         <!-- ==== / scroll to top end ==== -->
      </div>
      <!-- ==== js dependencies start ==== -->
      <!-- jquery -->
      <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
      <script src="{{ asset('maintheme/assets/vendor/jquery/jquery-3.7.0.min.js') }}"></script>
      <!-- bootstrap five js -->
      <script src="{{ asset('maintheme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <!-- magnific popup js -->
      <script src="{{ asset('maintheme/assets/vendor/magnific-popup/js/jquery.magnific-popup.min.js') }}"></script>
      <!-- jquery ui js -->
      <script src="{{ asset('maintheme/assets/vendor/jquery-ui/js/jquery-ui.min.js') }}"></script>
      <!-- slick js -->
      <script src="{{ asset('maintheme/assets/vendor/slick/js/slick.min.js') }}"></script>
      <!-- odometer js -->
      <script src="{{ asset('maintheme/assets/vendor/odometer/js/odometer.min.js') }}"></script>
      <!-- image loaded js -->
      <script src="{{ asset('maintheme/assets/vendor/images-loaded/imagesloaded.pkgd.min.js') }}"></script>
      <!-- isotope js -->
      <script src="{{ asset('maintheme/assets/vendor/isotope/isotope.pkgd.min.js') }}"></script>
      <!-- packery js -->
      <script src="{{ asset('maintheme/assets/vendor/isotope/packery.pkgd.min.js') }}"></script>
      <!-- splittext js -->
      <script src="{{ asset('maintheme/assets/vendor/gsap/SplitText.min.js') }}"></script>
      <!-- scrollsmoother js -->
      <script src="{{ asset('maintheme/assets/vendor/gsap/ScrollSmoother.min.js') }}"></script>
      <!-- scrollto js -->
      <script src="{{ asset('maintheme/assets/vendor/gsap/ScrollToPlugin.min.js') }}"></script>
      <!-- scrolltrigger js -->
      <script src="{{ asset('maintheme/assets/vendor/gsap/ScrollTrigger.min.js') }}"></script>
      <!-- gsap js -->
      <script src="{{ asset('maintheme/assets/vendor/gsap/gsap.min.js') }}"></script>
      <!-- viewport js -->
      <script src="{{ asset('maintheme/assets/vendor/viewport/viewport.jquery.js') }}"></script>
      <!-- vanilla tilt js -->
      <script src="{{ asset('maintheme/assets/vendor/vanilla-tilt/tilt.jquery.js') }}"></script>
      <!-- particle js -->
      <script src="{{ asset('maintheme/assets/vendor/particle-js/particles.min.js') }}"></script>
      <!-- ==== / js dependencies end ==== -->
      <!-- plugins js -->
      <script src="{{ asset('maintheme/assets/js/plugins.js') }}"></script>
      <!-- main js -->
      <script src="{{ asset('maintheme/assets/js/main.js') }}"></script>
   <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"6e6444122ed94892b6dff27396bb5627","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>

<!-- Mirrored from pixner.net/SkyHub/index-two.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Jan 2026 09:47:58 GMT -->
</html>