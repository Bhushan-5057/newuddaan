@extends('main.layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('main/css/about.css') }}">

<!--=====================================
            BANNER PART START
=======================================-->
<section class="inner-section single-banner"
    style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
    url('{{ asset('main/images/about/about-banner.jpg') }}') no-repeat center/cover;">

    <div class="container text-center">
        <h2 class="text-white">About</h2>
        <p class="text-light mt-2">
            Wellness • Balance • You
        </p>

        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item active">
                About Us
            </li>
        </ol>
    </div>
</section>
<!--=====================================
            BANNER PART END
=======================================-->




        <!--=====================================
                     ABOUT PART START
        =======================================-->
        <section class="inner-section about-company">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-content">
                            <h2>Our Motive is to Provide Best for Those Who Deserve</h2>
                            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officiis exercitationem commodi aliquam necessitatibus vero reiciendis quaerat illo est fuga ea temporibus natus doloremque ipsum voluptas quod deserunt expedita reprehenderit pariatur quidem quisquam, recusandae animi non! Voluptas totam repudiandae rerum molestiae possimus quis numquam sapiente sunt architecto quisquam Aliquam odio optio</p>
                        </div>
                        <ul class="about-list">
                            <li>
                                <h3>34785</h3>
                                <h6>registered users</h6>
                            </li>
                            <li>
                                <h3>2623</h3>
                                <h6>per day visitors</h6>
                            </li>
                            <li>
                                <h3>189</h3>
                                <h6>total products</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-img">
                            <img src="{{ asset('main/images/about/01.jpg') }}" alt="about">
                            <img src="{{ asset('main/images/about/02.jpg') }}" alt="about">
                            <img src="{{ asset('main/images/about/03.jpg') }}" alt="about">
                            <img src="{{ asset('main/images/about/04.jpg') }}" alt="about">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--=====================================
                    ABOUT PART END
        =======================================-->




        <!--=====================================
                    CHOOSE PART START
        =======================================-->
        <section class="about-choose">
            <div class="container">
                <div class="row">
                    <div class="col-11 col-md-9 col-lg-7 col-xl-6 mx-auto">
                        <div class="section-heading">
                            <h2>Why People Choose Their Daily Organic Life With Us</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="choose-card">
                            <div class="choose-icon">
                                <i class="icofont-fruits"></i>
                            </div>
                            <div class="choose-text">
                                <h4>100% fresh organic food</h4>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing tempora pariatur provident animi error dignissimo cumque minus facere dolores cupiditate debitis</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="choose-card">
                            <div class="choose-icon">
                                <i class="icofont-vehicle-delivery-van"></i>
                            </div>
                            <div class="choose-text">
                                <h4>Delivery within one hour</h4>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing tempora pariatur provident animi error dignissimo cumque minus facere dolores cupiditate debitis</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="choose-card">
                            <div class="choose-icon">
                                <i class="icofont-loop"></i>
                            </div>
                            <div class="choose-text">
                                <h4>quickly return policy</h4>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing tempora pariatur provident animi error dignissimo cumque minus facere dolores cupiditate debitis</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="choose-card">
                            <div class="choose-icon">
                                <i class="icofont-support"></i>
                            </div>
                            <div class="choose-text">
                                <h4>instant support team</h4>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing tempora pariatur provident animi error dignissimo cumque minus facere dolores cupiditate debitis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--=====================================
                    CHOOSE PART END
        =======================================-->








<!--=====================================
            NEWSLETTER
=======================================-->
<section class="news-part"
    style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
    url('{{ asset('main/images/newsletter.jpg') }}') no-repeat center/cover;">

    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-7">
                <div class="news-text">
                    <h2 class="text-white">
                        Subscribe For Wellness Updates
                    </h2>

                    <p class="text-light">
                        Get latest offers, wellness tips, and product updates.
                    </p>
                </div>
            </div>

            <div class="col-lg-5">
                <form class="news-form">
                    <input type="email"
                        placeholder="Enter Your Email">

                    <button type="submit">
                        Subscribe
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection