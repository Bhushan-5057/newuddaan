<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OTP Verification - {{ company_info('title') }}</title>
    <meta name="description" content="Two-Factor Authentication for Admin Login" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/' . company_info('favicon')) }}" />
    
    <!-- Tailwind CSS for utilities -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts - Inter as used in original design -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Core CSS (keep for structure) -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" />
    
    <!-- iOS Glass-morphism Theme - Exact match to admin login page -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Theme Variables - exact from first design */
        :root {
            --ios-primary: {{ company_info('theme_primary_color') ?? '#007AFF' }};
            --ios-secondary: {{ company_info('theme_secondary_color') ?? '#5856D6' }};
            --bg-color: #F2F2F7;
            --text-primary: #000000;
            --text-secondary: #3C3C43;
            --border-color: #C6C6C8;
        }

        /* Dark theme variables */
        html.dark-theme {
            --bg-color: #000000;
            --text-primary: #FFFFFF;
            --text-secondary: #98989D;
            --border-color: #38383A;
        }

        /* Body styling - exact same background as login page */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: 
                radial-gradient(circle at 70% 20%, rgba(235, 185, 63, 0.15), transparent 65%),
                radial-gradient(circle at 30% 80%, rgba(110, 54, 1, 0.2), transparent 70%),
                linear-gradient(160deg, #000000 0%, #0a0a0f 100%);
            overflow-x: hidden;
            padding: 20px;
            margin: 0;
        }

        /* Mobile wallpaper support */
        @media (max-width: 768px) {
            body {
                background-image: url("{{ asset('uploads/' . company_info('mobile_wallpaper')) }}");
                background-size: cover;
            }
        }

        /* Container styling */
        .container-xxl {
            max-width: 100% !important;
            padding: 0 !important;
        }

        .authentication-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* iOS Glass Card - exact match from login page */
        .ios-glass {
            background: #000 !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25),
                        inset 1px 1px 0px rgba(255, 255, 255, 0.2),
                        inset -1px -1px 0px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .dark .ios-glass {
            background: #000 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6),
                        inset 1px 1px 0px rgba(255, 255, 255, 0.1),
                        inset -1px -1px 0px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(235, 185, 63, 0.7);
            box-shadow: 0 10px 30px rgba(235, 185, 63, 0.4);
            border-radius: 16px;
        }

        .ios-glass-border {
            position: absolute;
            inset: 0;
            border-radius: 32px;
            padding: 1px;
            background: linear-gradient(321deg, rgba(255, 255, 255, 0.45), rgba(0, 0, 0, 0.35) 40%, rgba(0, 0, 0, 0.2) 60%, rgba(255, 255, 255, 0.01));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* Animation for fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ios-glass {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* OTP Input Container - Glass style */
        .otp-input-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 35px 0;
        }
        
        .otp-digit {
            width: 65px;
            height: 75px;
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            color: var(--text-primary);
            transition: all 0.3s ease;
            font-family: 'Inter', monospace;
            outline: none;
        }
        
        .otp-digit:focus {
            border-color: #c3a200;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(195, 162, 0, 0.15);
            transform: scale(1.02);
        }
        
        .otp-digit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .otp-digit::-webkit-inner-spin-button,
        .otp-digit::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Timer Circle - Styled with iOS theme */
        .timer-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .timer-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ebb93f, #c97e0a);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 10px 25px -5px rgba(235, 185, 63, 0.3);
        }
        
        .timer-circle::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ebb93f, #c97e0a);
            z-index: -1;
            opacity: 0.5;
            animation: rotate 2s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .timer-text {
            color: white;
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            font-family: 'Inter', monospace;
        }

        /* Gradient Button - exact from login page */
        .ios-gradient-btn {
            background: linear-gradient(135deg, #ebb93f, #c97e0a);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            width: 100%;
            padding: 14px 20px;
            border-radius: 18px;
            font-weight: 700;
            font-size: 1rem;
            color: black;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .ios-gradient-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .ios-gradient-btn:hover::before {
            left: 100%;
        }
        
        .ios-gradient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(235, 185, 63, 0.4);
        }
        
        .ios-gradient-btn:active {
            transform: translateY(0);
        }
        
        .ios-gradient-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Resend Button - Secondary glass style */
        .btn-resend-glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 40px;
            padding: 12px 28px;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        
        .btn-resend-glass:hover:not(:disabled) {
            background: rgba(235, 185, 63, 0.2);
            border-color: #ebb93f;
            color: #ebb93f;
            transform: translateY(-2px);
        }
        
        .btn-resend-glass:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Alert Messages - Glassmorphic */
        .alert-glass {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 12px 16px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            animation: slideDown 0.4s ease;
            font-size: 0.875rem;
        }
        
        .alert-success {
            border-left-color: #34C759;
            color: #34C759;
            background: rgba(52, 199, 89, 0.1);
        }
        
        .alert-danger {
            border-left-color: #ff3b30;
            color: #ff3b30;
            background: rgba(255, 59, 48, 0.1);
        }
        
        .alert-warning {
            border-left-color: #f59e0b;
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Email Info Card - Glass style */
        .email-info-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 16px 20px;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .email-info-glass i {
            font-size: 28px;
            color: #ebb93f;
            margin-bottom: 8px;
            display: inline-block;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .email-info-glass p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }
        
        .email-info-glass strong {
            color: #ebb93f;
            font-weight: 600;
        }

        /* Expiry Text */
        .expiry-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 1rem 0;
            text-align: center;
        }
        
        .expiry-text i {
            margin-right: 6px;
        }
        
        .expiry-text span {
            font-weight: 700;
            color: #ebb93f;
        }
        
        .expiry-text .urgent {
            color: #ff3b30;
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.3s ease;
            margin-top: 1rem;
        }
        
        .back-link:hover {
            color: #ebb93f;
        }

        /* Loading spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            display: inline-block;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 520px) {
            .ios-glass {
                padding: 30px 25px !important;
                border-radius: 28px !important;
            }
            .otp-digit {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            .otp-input-container {
                gap: 4px;
            }
            .timer-circle {
                width: 80px;
                height: 80px;
            }
            .timer-text {
                font-size: 22px;
            }
        }

        /* Logo styling */
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        /* Title gradient */
        .gradient-title {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff, #ebb93f);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 1.5rem;
        }

        /* Dark mode specific overrides */
        .dark .otp-digit {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }
        
        .dark .otp-digit:focus {
            border-color: #ebb93f;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .light-theme .ios-glass {
            background: rgba(0, 0, 0, 0.85) !important;
        }
        
        .light-theme .otp-digit {
            background: rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        
        .light-theme .otp-digit::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="antialiased">
    <div class="container-xxl">
        <div class="authentication-wrapper">
            <div class="authentication-inner" style="max-width: 550px; width: 100%;">
                <!-- iOS Glass Card - Exact match to admin login -->
                <div class="relative ios-glass rounded-[32px] p-[40px_35px] transition-all duration-300 hover:-translate-y-0.5" style="background: #000 !important;">
                    <div class="ios-glass-border absolute inset-0 rounded-[32px] pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <!-- Logo - exact style from first page -->
                        <div class="flex justify-center mb-6">
                            <a href="#" class="inline-block">
                                <img src="{{ asset('uploads/' . company_info('logodark')) }}" alt="Logo"
                                    style="width: {{ company_info('logo_width', '60px') }}; height: {{ company_info('logo_height', '60px') }};"
                                    class="drop-shadow-[0_0_20px_rgba(235,185,63,0.3)] rounded-[30%]">
                            </a>
                        </div>
                        
                        <!-- Title -->
                        <div class="gradient-title">OTP Verification</div>
                        <div class="subtitle">Enter the 6-digit code sent to your email</div>
                        
                        <!-- Email Info - Glass style -->
                        <div class="email-info-glass">
                            <i class="fas fa-envelope-open-text"></i>
                            <p>We've sent a verification code to</p>
                            <strong>{{ session('otp_email', 'your email address') }}</strong>
                        </div>
                        
                        <!-- Alert Messages -->
                        @if(session('success'))
                            <div class="alert-glass alert-success">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert-glass alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert-glass alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- OTP Form - Logic unchanged -->
                        <form method="POST" action="{{ route('admin.otp.verify') }}" id="otpForm">
                            @csrf
                            <div class="otp-input-container">
                                <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric" id="digit1" autofocus>
                                <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric" id="digit2">
                                <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric" id="digit3">
                                <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric" id="digit4">
                                <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric" id="digit5">
                                <input type="text" class="otp-digit" maxlength="1" pattern="\d" inputmode="numeric" id="digit6">
                            </div>
                            <input type="hidden" name="otp" id="otpValue">
                            
                            <div class="expiry-text">
                                <i class="fas fa-clock"></i> OTP expires in 
                                <span id="expiryText" class="fw-bold">5 minutes</span>
                            </div>
                            
                            <div class="mb-3 mt-4">
                                <button class="ios-gradient-btn" type="submit" id="verifyBtn">
                                    <i class="fas fa-check-circle"></i> Verify OTP
                                </button>
                            </div>
                        </form>
                        
                        <!-- Resend Section -->
                        <div class="text-center">
                            <p style="color: var(--text-secondary); font-size: 0.8rem; margin-bottom: 12px;">
                                <i class="fas fa-question-circle me-1"></i> Didn't receive the code?
                            </p>
                            <button class="btn-resend-glass" id="resendBtn">
                                <i class="fas fa-sync-alt me-2"></i> Resend OTP
                            </button>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.login') }}" class="back-link">
                                    <i class="fas fa-arrow-left"></i> Back to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Core Scripts -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/js/main.js"></script>
    
    <!-- Theme Management - exactly from login page -->
    <script>
        function initializeTheme() {
            const companyTheme = "{{ company_info('theme_type') }}";
            let theme = companyTheme === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : companyTheme;
            applyTheme(theme);
        }

        function applyTheme(theme) {
            const html = document.documentElement;
            html.classList.remove('light-theme', 'dark-theme');
            if (theme === 'dark') {
                html.classList.add('dark-theme', 'dark');
                document.documentElement.style.colorScheme = 'dark';
            } else {
                html.classList.add('light-theme');
                html.classList.remove('dark');
                document.documentElement.style.colorScheme = 'light';
            }
        }

        initializeTheme();
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            const companyTheme = "{{ company_info('theme_type') }}";
            if (companyTheme === 'system') {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    </script>
    
    <!-- OTP Logic - Fully preserved, only UI references updated -->
    <script>
        // OTP auto-focus and handling - LOGIC UNCHANGED
        const digits = ['digit1', 'digit2', 'digit3', 'digit4', 'digit5', 'digit6'];
        
        digits.forEach((id, index) => {
            const input = document.getElementById(id);
            
            input.addEventListener('input', function(e) {
                if (this.value.length === 1 && index < digits.length - 1) {
                    document.getElementById(digits[index + 1]).focus();
                }
                updateOtpValue();
            });
            
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    document.getElementById(digits[index - 1]).focus();
                }
            });
            
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                if (paste && paste.length === 6 && /^\d+$/.test(paste)) {
                    paste.split('').forEach((char, i) => {
                        if (digits[i]) {
                            document.getElementById(digits[i]).value = char;
                        }
                    });
                    updateOtpValue();
                    document.getElementById(digits[5]).focus();
                }
            });
        });
        
        function updateOtpValue() {
            let otp = '';
            digits.forEach(id => {
                otp += document.getElementById(id).value;
            });
            document.getElementById('otpValue').value = otp;
            
            // Auto-submit when all digits are filled
            if (otp.length === 6) {
                const verifyBtn = document.getElementById('verifyBtn');
                verifyBtn.disabled = true;
                verifyBtn.innerHTML = '<div class="spinner-border-sm me-2"></div> Verifying...';
                setTimeout(() => {
                    document.getElementById('otpForm').submit();
                }, 500);
            }
        }
        
        // Countdown timer - LOGIC UNCHANGED
        let timeLeft = 300;
        const timerDisplay = document.getElementById('timerDisplay');
        const expiryText = document.getElementById('expiryText');
        const resendBtn = document.getElementById('resendBtn');
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timeString = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (timerDisplay) {
                timerDisplay.textContent = timeString;
            }
            
            if (expiryText) {
                if (timeLeft <= 60) {
                    expiryText.textContent = `${timeLeft} seconds`;
                    expiryText.classList.add('urgent');
                } else {
                    expiryText.textContent = `${minutes} minutes ${seconds} seconds`;
                    expiryText.classList.remove('urgent');
                }
            }
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                if (timerDisplay) timerDisplay.textContent = '00:00';
                if (expiryText) {
                    expiryText.textContent = 'EXPIRED';
                    expiryText.classList.add('urgent');
                }
                
                // Disable OTP inputs
                digits.forEach(id => {
                    document.getElementById(id).disabled = true;
                });
                
                // Show warning message
                const msgDiv = document.createElement('div');
                msgDiv.className = 'alert-glass alert-warning mt-3';
                msgDiv.innerHTML = '<i class="fas fa-hourglass-end me-2"></i> OTP has expired. Please request a new one.';
                const cardBody = document.querySelector('.ios-glass .relative.z-10');
                cardBody.insertBefore(msgDiv, document.querySelector('form'));
                
                if (resendBtn) {
                    resendBtn.disabled = false;
                }
            }
            timeLeft--;
        }
        
        const timerInterval = setInterval(updateTimer, 1000);
        
        if (resendBtn && timeLeft > 0) {
            resendBtn.disabled = true;
        }
        
        // Resend OTP functionality - LOGIC UNCHANGED
        if (resendBtn) {
            resendBtn.addEventListener('click', function() {
                if (this.disabled) return;
                
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<div class="spinner-border-sm me-2"></div> Sending...';
                
                fetch('{{ route("admin.resend.otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('success', data.message);
                        timeLeft = 300;
                        digits.forEach(id => {
                            document.getElementById(id).disabled = false;
                            document.getElementById(id).value = '';
                        });
                        document.getElementById('digit1').focus();
                        // Re-enable resend button disabled state (timer will re-disable after interval)
                        this.disabled = true;
                        // Reset timer interval by clearing and restarting
                        // Timer continues from new timeLeft value
                    } else {
                        showMessage('error', data.message);
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    showMessage('error', 'Failed to resend OTP. Please try again.');
                    this.disabled = false;
                    this.innerHTML = originalText;
                });
            });
        }
        
        function showMessage(type, message) {
            const existingAlerts = document.querySelectorAll('.alert-glass');
            existingAlerts.forEach(alert => alert.remove());
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert-glass alert-${type}`;
            alertDiv.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i> ${message}`;
            
            const cardContent = document.querySelector('.ios-glass .relative.z-10');
            const formElement = document.querySelector('form');
            if (formElement) {
                cardContent.insertBefore(alertDiv, formElement);
            } else {
                cardContent.insertBefore(alertDiv, cardContent.firstChild);
            }
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
        
        // Form submit loading state - LOGIC UNCHANGED
        document.getElementById('otpForm').addEventListener('submit', function() {
            const verifyBtn = document.getElementById('verifyBtn');
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<div class="spinner-border-sm me-2"></div> Verifying...';
        });
        
        setTimeout(() => {
            const firstDigit = document.getElementById('digit1');
            if (firstDigit) firstDigit.focus();
        }, 100);
    </script>
</body>
</html>