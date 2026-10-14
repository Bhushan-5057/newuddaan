@extends('user.layouts.app')

@section('content')

{{-- Include Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" xintegrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* Royal Gold & Black Theme Variables */
:root {
    --primary-green: #057A55;    /* ડેશબોર્ડનો મેઈન ગ્રીન કલર */
    --light-green: #E6F4F0;      /* હળવો ગ્રીન બેકગ્રાઉન્ડ માટે */
    --bg-light: #F4F7F6;         /* પેજનું બેકગ્રાઉન્ડ કલર */
    --white: #FFFFFF;
    --text-dark: #333333;        /* ઘાટો ટેક્સ્ટ */
    --text-muted: #666666;       /* મ્યૂટેડ ટેક્સ્ટ */
    --border-color: #E0E6E9;     /* બોર્ડર કલર */
    --card-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

/* Base Styles Update */
body {
    background-color: var(--bg-light);
    color: var(--text-dark);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        line-height: 1.6;
    }

    .news-events-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 25px;
    }

    /* Hero Section */
    .news-events-hero {
        text-align: center;
        padding: 80px 0 60px;
       
     
        margin-bottom: 60px;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 20px 40px var(--card-shadow);
        position: relative;
        overflow: hidden;
       
        background: linear-gradient(135deg, #046345 0%, var(--primary-green) 100%);
    color: var(--white);
    border-bottom: none;
    }

    .news-events-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCUiIGhlaWdodD0iNTAlIj48ZGVmcz48cGF0dGVybiBpZD0icGF0dGVybiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIiBwYXR0ZXJuVHJhbnNmb3JtPSJyb3RhdGUoNDUpIj48cmVjdCB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9InJnYmEoMjEyLDE3NSw1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdG09IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjxzdmc+'); /* Subtle gold pattern */
        opacity: 0.1;
        z-index: 0;
    }

    .news-events-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 15px;
        font-weight: 800;
        position: relative;
        text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        color: var(--gold-primary); /* Gold title */
        z-index: 1;
        color: var(--white);
    }

    .news-events-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
        position: relative;
        color: var(--text-light);
        z-index: 1;
    }

    /* Section Header */
    .section-header {
        margin-bottom: 40px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center; /* Center align header content */
        flex-wrap: wrap; /* Allow wrapping on small screens */
    }

    .section-header h2 {
        font-size: 2.5rem;
        color: var(--text-light);
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
        font-weight: 700;
        margin-right: 20px; /* Space between title and decoration */
    }

    .header-decoration {
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
        border-radius: 4px;
        flex-grow: 0; /* Prevent it from growing too much */
        max-width: 300px;
    }

    /* News Timeline */
    .news-timeline {
        position: relative;
        padding-left: 50px;
        margin-left: 25px;
    }

    .news-timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 3px;
        background: linear-gradient(to bottom, var(--gold-primary), var(--gold-light));
        border-radius: 3px;
    }

    .news-item {
        position: relative;
        padding: 30px 0;
        display: flex;
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-item.visible {
        opacity: 1;
        transform: translateX(0);
            overflow: hidden;
    }

    .news-item::before {
        content: '';
        position: absolute;
        left: -53px;
        top: 40px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--black-dark); /* Dot background matches page background */
        border: 4px solid var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.3);
        z-index: 2;
    }

    .news-date {
        min-width: 100px;
        text-align: center;
        padding-right: 30px;
    }

    .news-date .day-month {
        display: block;
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--gold-primary);
        line-height: 1;
    }

    .news-date .year {
        font-size: 1rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 5px;
    }

    .news-content {
        flex: 1;
      
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 10px 30px var(--card-shadow);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      
       
    }
    
    .news-content {
    background: var(--white);
    color: var(--text-dark);
    border: 1px solid var(--border-color);
}

    .news-content:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px var(--hover-shadow);
    }

    .news-content h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: var(--gold-light); /* Gold title on card */
        font-size: 1.4rem;
        font-weight: 700;
    }

    .news-description {
        color: var(--text-muted); /* Muted text for description */
        line-height: 1.7;
        opacity: 0.9;
    }

    /* Events Grid */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 40px;
    }

    .event-card {
        background: var(--black-medium); /* Dark card background */
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px var(--card-shadow);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        opacity: 0;
        transform: translateY(20px);
        border: 1px solid var(--border-color);
    }

    .event-card.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .event-card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 20px 40px var(--hover-shadow);
    }

    .event-image-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .event-image-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.5)); /* Darker overlay for images */
    }

    .event-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .event-card:hover .event-image {
        transform: scale(1.1);
    }

    .event-date-badge {
        position: absolute;
        top: 20px; /* Changed to top-right for better visibility */
        right: 20px;
        background: rgba(0,0,0,0.7); /* Semi-transparent black background */
        padding: 12px 15px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 1;
        transition: all 0.3s ease;
        border: 1px solid var(--gold-light); /* Gold border */
    }

    .event-card:hover .event-date-badge {
        transform: scale(1.05);
        background: rgba(0,0,0,0.8);
    }

    .event-date-badge .day {
        display: block;
        font-size: 2rem;
        font-weight: 800;
        color: var(--gold-primary);
        line-height: 1;
    }

    .event-date-badge .month {
        font-size: 1rem;
        color: var(--text-light); /* Light text for month */
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .event-details {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        color: var(--text-light); /* Light text on dark card */
    }

    .event-details h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: var(--gold-light); /* Gold title on card */
        font-size: 1.5rem;
        font-weight: 700;
    }

    .event-description {
        color: var(--text-muted); /* Muted text for description */
        margin-bottom: 20px;
        line-height: 1.6;
        opacity: 0.9;
    }

    .event-button {
        display: inline-flex;
        align-items: center;
        padding: 12px 25px;
        background: linear-gradient(90deg, var(--gold-primary), var(--gold-light)); /* Gold gradient button */
        color: var(--black-dark); /* Dark text on gold button */
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .event-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        background: linear-gradient(90deg, var(--gold-light), var(--gold-primary));
    }

    .event-button i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .event-button:hover i {
        transform: translateX(5px);
    }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--black-medium); /* Dark empty state background */
        border-radius: 16px;
        box-shadow: 0 10px 30px var(--card-shadow);
        border: 1px dashed var(--border-color);
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3.5rem;
        color: var(--text-muted);
        margin-bottom: 20px;
        opacity: 0.7;
    }

    .empty-state p {
        color: var(--text-muted);
        font-size: 1.2rem;
        max-width: 500px;
        margin: 0 auto;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    .pagination .page-item .page-link {
        background-color: var(--black-medium);
        border: 1px solid var(--border-color);
        color: var(--gold-primary);
        margin: 0 5px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .pagination .page-item .page-link:hover {
        background-color: var(--gold-primary);
        border-color: var(--gold-primary);
        color: var(--black-dark);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--gold-primary);
        border-color: var(--gold-primary);
        color: var(--black-dark);
        box-shadow: 0 4px 10px rgba(212, 175, 55, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        background-color: var(--black-dark);
        border-color: var(--border-color);
        color: var(--text-muted);
        opacity: 0.6;
    }

    /* Floating Particles (if using particles.js) */
    .particles {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .particle {
        position: absolute;
        background: rgba(255,255,255,0.1); /* Muted white particles */
        border-radius: 50%;
        animation: float 15s infinite linear;
    }
    
    
    .news-content h3 {
    color: var(--primary-green);
}

/* Event Card Update */
.event-card {
    background: var(--white);
    border: 1px solid var(--border-color);
}

.event-details h3 {
    color: var(--primary-green);
}

.event-date-badge {
    background: var(--white);
    border: 1px solid var(--primary-green);
}

.event-date-badge .day {
    color: var(--primary-green);
}

.event-date-badge .month {
    color: var(--text-dark);
}

/* Button & Pagination Update */
.event-button {
    background: var(--primary-green);
    color: var(--white);
    box-shadow: 0 4px 10px rgba(5, 122, 85, 0.2);
}

.event-button:hover {
    background: #046345;
    box-shadow: 0 6px 15px rgba(5, 122, 85, 0.3);
}

.pagination .page-item.active .page-link {
    background-color: var(--primary-green);
    border-color: var(--primary-green);
}

.pagination .page-item .page-link {
    color: var(--primary-green);
}

/* Timeline Color */
.news-timeline::before {
    background: var(--primary-green);
}

.news-item::before {
    background: var(--white);
    border-color: var(--primary-green);
}

.header-decoration {
    background: var(--primary-green);
}

    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
        }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 1024px) {
        .events-grid {
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }
    }
    
    @media (max-width: 768px) {
        .news-events-hero {
            padding: 60px 0 40px;
        }
        
        .news-events-hero h1 {
            font-size: 2.2rem;
        }
        
        .news-item {
            flex-direction: column;
        }
        
        .news-date {
            text-align: left;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            padding-right: 0;
        }
        
        .news-date .day-month {
            display: inline;
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .news-date .year {
            display: inline;
            font-size: 1.1rem;
        }
        
        .news-timeline {
            padding-left: 30px;
            margin-left: 15px;
        }
        
        .news-item::before {
            left: -38px;
            top: 30px;
        }
        
        .section-header {
            flex-direction: column;
            align-items: center; /* Center align items in column */
            text-align: center;
        }
        
        .header-decoration {
            margin-left: 0;
            margin-top: 10px;
        }
        
        .events-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 480px) {
        .news-events-hero h1 {
            font-size: 1.8rem;
        }
        
        .news-events-hero p {
            font-size: 1rem;
        }
        
        .section-header h2 {
            font-size: 1.6rem;
        }
        .news-content {
            padding: 20px;
        }
        .event-details {
            padding: 20px;
        }
    }
</style>

@php
// These variables should be passed from your controller
// Example (if not already in controller):
$newsItems = \App\Models\NoticeBoard::where('type', 'marquee')
    ->where('status', 1)
    ->orderBy('added_on', 'desc')
    ->limit(5) // Limit news items for a concise list
    ->get();

$events = \App\Models\NoticeBoard::where('type', 'popup')
    ->where('status', 1)
    ->orderBy('added_on', 'desc')
    ->paginate(9); // Paginate events for a grid display
@endphp

<div class="news-events-container">
    <!-- Hero Section -->
    <div class="news-events-hero">
        <div class="particles" id="particles-js"></div>
        <h1>News & Events</h1>
        <p>Stay informed with our latest updates and upcoming activities</p>
    </div>

    <!-- News Section (Timeline) -->
    <section class="news-section mt-5 mb-5">
        <div class="section-header">
            <h2>Latest News</h2>
            <div class="header-decoration"></div>
        </div>

        @if($newsItems->count())
        <div class="news-timeline">
            @foreach($newsItems as $index => $news)
            <div class="news-item" data-index="{{ $index }}">
                <div class="news-date">
                    <span class="day-month">{{ \Carbon\Carbon::parse($news->added_on)->format('d M') }}</span>
                    <span class="year">{{ \Carbon\Carbon::parse($news->added_on)->format('Y') }}</span>
                </div>
                <div class="news-content">
                    <h3>{{ $news->title }}</h3>
                    <div class="news-description">
                        {!! $news->description !!}
                    </div>
                    {{-- Link to full news detail page --}}
                    <a href="{{ route('user.news_events.show', $news->id) }}" class="event-button mt-3">
                        Read More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-newspaper"></i>
            <p>No news available at the moment</p>
        </div>
        @endif
    </section>

    <!-- Events Section (Grid) -->
    <section class="events-section mt-5 mb-5">
        <div class="section-header">
            <h2>Upcoming Events</h2>
            <div class="header-decoration"></div>
        </div>

        @if($events->count())
        <div class="events-grid">
            @foreach($events as $index => $event)
            <div class="event-card" data-index="{{ $index }}">
                <div class="event-image-wrapper">
                    @if($event->img_path)
                        <img src="{{ asset('storage/' . $event->img_path) }}" alt="{{ $event->title }}" class="event-image">
                    @else
                        {{-- Placeholder image for events without an image --}}
                        <img src="https://placehold.co/600x400/1A1A1A/D4AF37?text=Event+Image" alt="No Event Image" class="event-image">
                    @endif
                    <div class="event-date-badge">
                        <span class="day">{{ \Carbon\Carbon::parse($event->added_on)->format('d') }}</span>
                        <span class="month">{{ \Carbon\Carbon::parse($event->added_on)->format('M') }}</span>
                    </div>
                </div>
                <div class="event-details">
                    <h3>{{ $event->title }}</h3>
                    <div class="event-description">
                        {!! Str::limit(strip_tags($event->description), 150) !!} {{-- Limit description for grid view --}}
                    </div>
                    {{-- Link to full event detail page --}}
                    <a href="{{ route('user.news_events.show', $event->id) }}" class="event-button">
                        View Details <i class="fas fa-calendar-check"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        {{-- Pagination for events --}}
        <div class="pagination-wrapper">
            {{ $events->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-alt"></i>
            <p>No upcoming events scheduled</p>
        </div>
        @endif
    </section>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize particles.js for the hero section
        if (document.getElementById('particles-js')) {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 40, // Increased particles
                        "density": {
                            "enable": true,
                            "value_area": 1000 // Larger area for density
                        }
                    },
                    "color": {
                        "value": "#ffffff" // Gold particles
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.3, // Slightly less opaque
                        "random": true,
                        "anim": {
                            "enable": false
                        }
                    },
                    "size": {
                        "value": 4, // Slightly larger particles
                        "random": true,
                        "anim": {
                            "enable": false
                        }
                    },
                    "line_linked": {
                        "enable": false
                    },
                    "move": {
                        "enable": true,
                        "speed": 1.5, // Slower movement
                        "direction": "top",
                        "random": true,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": false
                        },
                        "onclick": {
                            "enable": false
                        },
                        "resize": true
                    }
                },
                "retina_detect": true
            });
        }

        // Intersection Observer for scroll animations
        const animateOnScroll = (elements, className) => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const index = entry.target.getAttribute('data-index');
                        entry.target.classList.add(className);
                        entry.target.style.transitionDelay = `${index * 0.1}s`; // Staggered delay
                        observer.unobserve(entry.target); // Stop observing once animated
                    }
                });
            }, { threshold: 0.1 }); // Trigger when 10% of the item is visible
            
            elements.forEach(el => observer.observe(el));
        };

        // Animate news items
        const newsItems = document.querySelectorAll('.news-item');
        animateOnScroll(newsItems, 'visible');

        // Animate event cards
        const eventCards = document.querySelectorAll('.event-card');
        animateOnScroll(eventCards, 'visible');

        // Add hover effect to news items (for the inner content box)
        newsItems.forEach(item => {
            const newsContent = item.querySelector('.news-content');
            if (newsContent) {
                item.addEventListener('mouseenter', () => {
                    newsContent.style.transform = 'translateY(-5px)';
                    newsContent.style.boxShadow = '0 15px 40px var(--hover-shadow)';
                });
                
                item.addEventListener('mouseleave', () => {
                    newsContent.style.transform = '';
                    newsContent.style.boxShadow = '';
                });
            }
        });
    });
</script>
@endsection
