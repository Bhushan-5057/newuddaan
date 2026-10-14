<style>
    .black-marquee-wrapper {
        overflow: hidden;
        width: 100%;
        padding: 10px 0;
        position: relative;
        z-index: 2;
    }

    .black-marquee-group {
        display: flex;
        gap: 3.5rem;
        padding-right: 3.5rem;
    }

    .black-marquee-item {
        padding: 6px 20px;
        font-size: 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .black-marquee-inner {
        display: flex;
        width: max-content;
        animation: scrollLeft 25s linear infinite;
    }

    @keyframes scrollLeft {
        0% {
            transform: translateX(0%);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    /* Optional: pause on hover */
    .black-marquee-wrapper:hover .black-marquee-inner {
        animation-play-state: paused;
    }
</style>


<div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(90deg, #0d0d0d, #1a1a1a);">
    <div class="p-3 black-marquee-wrapper position-relative">
        {{-- Fixed Icon on Left --}}
        <div class="d-flex align-items-center mb-2">
            <i class="fa-solid fa-bullhorn text-warning fs-4 me-2"></i>
            <h6 class="text-light fw-semibold mb-0">Latest Announcements</h6>
        </div>

        {{-- Marquee Animation --}}
        <div class="black-marquee-inner d-flex align-items-center">
            <div class="black-marquee-group">
                @foreach ($marqueeNotices as $notice)
                    <div class="black-marquee-item">
                        <span class="notice-title">{{ $notice->title ?? 'Announcement' }}:</span>
                        <span class="notice-desc">{!! strip_tags($notice->description) !!}</span>
                    </div>
                @endforeach
            </div>

            {{-- Duplicate for continuous loop --}}
            <div class="black-marquee-group">
                @foreach ($marqueeNotices as $notice)
                    <div class="black-marquee-item">
                        <span class="notice-title">{{ $notice->title ?? 'Announcement' }}:</span>
                        <span class="notice-desc">{!! strip_tags($notice->description) !!}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
