@extends('user.layouts.app')

@section('content')

{{-- Include Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" xintegrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* Royal Gold & Black Theme Variables (Duplicate for self-containment, or import via CSS file) */
    :root {
        --gold-primary: #D4AF37; /* Classic Gold */
        --gold-light: #E6C76C;   /* Lighter Gold for accents/hover */
        --black-dark: #1A1A1A;   /* Deep Black for backgrounds */
        --black-medium: #2C2C2C; /* Slightly lighter black for card backgrounds */
        --text-light: #F0F0F0;   /* Light text on dark backgrounds */
        --text-muted: #A0A0A0;   /* Muted text on dark backgrounds */
        --border-color: #444444; /* Subtle dark border */
        --card-shadow: rgba(0, 0, 0, 0.3); /* Darker shadow for depth */
        --hover-shadow: rgba(0, 0, 0, 0.5); /* Even darker hover shadow */
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: var(--black-dark);
        color: var(--text-light);
        line-height: 1.6;
    }

    .detail-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 25px;
    }

    .detail-card {
        background: var(--black-medium);
        border-radius: 16px;
        box-shadow: 0 15px 40px var(--card-shadow);
        overflow: hidden;
        border: 1px solid var(--border-color);
        padding-bottom: 30px; /* Padding for content below image */
    }

    .detail-header-image {
        position: relative;
        height: 400px; /* Larger image for details */
        overflow: hidden;
        border-bottom: 2px solid var(--gold-primary);
    }

    .detail-header-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .detail-header-image:hover img {
        transform: scale(1.05);
    }

    .detail-header-image::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 50%, rgba(0,0,0,0.7) 100%);
    }

    .detail-content {
        padding: 30px;
        position: relative;
        z-index: 1;
    }

    .detail-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--gold-primary);
        margin-bottom: 20px;
        text-align: center;
        line-height: 1.2;
    }

    .detail-meta {
        text-align: center;
        color: var(--text-muted);
        font-size: 1.1rem;
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
    }

    .detail-meta span {
        display: flex;
        align-items: center;
    }

    .detail-meta i {
        margin-right: 8px;
        color: var(--gold-light);
    }

    .detail-description {
        font-size: 1.1rem;
        color: var(--text-light);
        line-height: 1.8;
        padding: 0 15px; /* Inner padding for text */
    }

    .detail-description p {
        margin-bottom: 1.5em;
    }

    .back-button-wrapper {
        text-align: center;
        margin-top: 40px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        padding: 12px 30px;
        background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
        color: var(--black-dark);
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .back-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        background: linear-gradient(90deg, var(--gold-light), var(--gold-primary));
    }

    .back-button i {
        margin-right: 8px;
        transition: transform 0.3s ease;
    }

    .back-button:hover i {
        transform: translateX(-5px);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .detail-container {
            padding: 20px 15px;
        }
        .detail-header-image {
            height: 250px;
        }
        .detail-content {
            padding: 20px;
        }
        .detail-title {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        .detail-meta {
            font-size: 1rem;
            flex-direction: column;
            gap: 10px;
        }
        .detail-description {
            font-size: 1rem;
            padding: 0;
        }
        .back-button {
            padding: 10px 25px;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .detail-header-image {
            height: 200px;
        }
        .detail-title {
            font-size: 1.6rem;
        }
        .detail-meta {
            font-size: 0.9rem;
        }
    }
</style>

<div class="detail-container">
    @if($item)
    <div class="detail-card">
        @if($item->img_path)
        <div class="detail-header-image">
            <img src="{{ asset('storage/' . $item->img_path) }}" alt="{{ $item->title }}">
        </div>
        @else
        <div class="detail-header-image" style="background-color: var(--black-dark); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--text-muted);">
            <i class="fas fa-image" style="font-size: 3rem; color: var(--text-muted);"></i>
            <span style="margin-left: 15px;">No Image Available</span>
        </div>
        @endif

        <div class="detail-content">
            <h1 class="detail-title">{{ $item->title }}</h1>
            <div class="detail-meta">
                <span><i class="fas fa-calendar-alt"></i>{{ \Carbon\Carbon::parse($item->added_on)->format('d M Y') }}</span>
                <span><i class="fas fa-tag"></i>{{ ucfirst($item->type) }}</span>
            </div>
            <div class="detail-description">
                {!! $item->description !!}
            </div>
        </div>
    </div>

    <div class="back-button-wrapper">
        <a href="{{ route('user.newsevents.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to News & Events
        </a>
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-exclamation-triangle"></i>
        <h4>Content Not Found</h4>
        <p>The news or event you are looking for does not exist or has been removed.</p>
        <div class="back-button-wrapper">
            <a href="{{ route('user.news_events.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to News & Events
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
