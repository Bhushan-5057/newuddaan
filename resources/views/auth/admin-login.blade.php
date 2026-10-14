<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ company_info('title') }} - Admin Login</title>
    <meta name="description" content="Secure Admin Login Panel with 2FA" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/' . company_info('favicon')) }}" />
    
    <!-- Tailwind CSS (for utility classes if needed) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts - Inter as used in original design -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Core CSS (keep for structure but we will override heavily) -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" />
    
    <!-- Custom iOS-inspired Glassmorphic Design Matching First Page -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Theme Variables - exact from first design */
        :root {
            /*--ios-primary: {{ company_info('theme_primary_color') ?? '#007AFF' }};*/
            --ios-secondary: {{ company_info('theme_secondary_color') ?? '#5856D6' }};
            --bg-color: #F2F2F7;
            --text-primary: #000000;
            --text-secondary: #3C3C43;
            --border-color: #C6C6C8;
        }

        /* Dark theme variables (default dark mode as per first design) */
        html.dark-theme {
            --bg-color: #000000;
            --text-primary: #FFFFFF;
            --text-secondary: #98989D;
            --border-color: #38383A;
        }

        /* Body styling - exact same background as first login page */
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

        /* iOS Glass Card - exact match from first design */
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

        /* Form Fields - Modern Glass Inputs */
        .ios-input-group {
            margin-bottom: 1.5rem;
        }

        .ios-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            font-size: 1rem;
            color: var(--text-primary);
            transition: all 0.3s ease;
            outline: none;
        }

        .ios-input:focus {
            border-color: var(--ios-primary);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
        }

        .ios-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #c3a200;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-secondary);
            transition: color 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--ios-primary);
        }

        /* Gradient Button - exact from first design */
        .ios-gradient-btn {
            background: linear-gradient(135deg, #ebb93f, #c97e0a);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            width: 100%;
            padding: 14px 20px;
            border-radius: 18px;
            font-weight: 600;
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
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

        /* Alert Messages - glassmorphic */
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

        /* Checkbox styling iOS style */
        .ios-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .ios-checkbox-wrapper input[type="checkbox"] {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            accent-color: var(--ios-primary);
            cursor: pointer;
        }

        .ios-checkbox-wrapper label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            cursor: pointer;
            margin: 0;
        }

        /* Loading spinner */
        .spinner-border-sm {
            width: 1.2rem;
            height: 1.2rem;
            border-width: 0.2em;
            border-color: white transparent white transparent;
            border-style: solid;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Invalid input style */
        .ios-input.is-invalid {
            border-color: #ff3b30;
            background: rgba(255, 59, 48, 0.05);
        }

        .invalid-feedback {
            color: #ff3b30;
            font-size: 0.75rem;
            margin-top: 6px;
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 520px) {
            .ios-glass {
                padding: 30px 25px !important;
                border-radius: 28px !important;
            }
            .ios-gradient-btn {
                height: 48px;
                font-size: 0.9rem;
            }
            .welcome-section h4 {
                font-size: 1.6rem !important;
            }
        }

        /* Logo and text styling */
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .logo-circle {
            width: 70px;
            height: 70px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            padding: 10px;
            backdrop-filter: blur(5px);
        }

        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-section h4 {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff, var(--ios-primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            opacity: 0.8;
        }

        /* Footer link styling */
        .footer-note {
            text-align: center;
            margin-top: 1.8rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Hover effects */
        .ios-input:hover {
            background: rgba(255, 255, 255, 0.12);
        }
    </style>
</head>
<body class="antialiased">
    <div class="container-xxl">
        <div class="authentication-wrapper">
            <div class="authentication-inner" style="max-width: 480px; width: 100%;">
                <!-- iOS Glass Card - Exact match to first design -->
                <div class="relative ios-glass rounded-[32px] p-[40px_35px] transition-all duration-300 hover:-translate-y-0.5" style="background: #000 !important;">
                    <div class="ios-glass-border absolute inset-0 rounded-[32px] pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <!-- Logo - exact style from first page -->
                        <div class="flex justify-center mb-6">
                            <a href="#" class="inline-block">
                                <img src="{{ asset('uploads/' . company_info('logodark')) }}" alt="Logo"
                                    style="width: {{ company_info('logo_width', '60px') }}; height: {{ company_info('logo_height', '60px') }};"
                                    class="drop-shadow-[0_0_20px_rgba(0,122,255,0.3)] rounded-[30%]">
                            </a>
                        </div>
                        
                        <!-- Title and Welcome -->
                        <h4 class="text-center text-3xl font-bold mb-2 bg-gradient-to-r from-white to-[var(--ios-primary)] bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, #ffffff, #ebb93f); -webkit-background-clip: text; background-clip: text; color: transparent;">
                            Admin Access
                        </h4>
                        <p class="text-center text-[var(--text-secondary)] mb-8 text-sm opacity-80">
                            Secure login to your control panel
                        </p>

                        <!-- Alert Messages - Glass styled -->
                        @if(session('success'))
                            <div class="alert-glass alert-success mb-4">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert-glass alert-danger mb-4">
                                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert-glass alert-danger mb-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form action="{{ route('admin.login') }}" method="POST" id="loginForm">
                            @csrf
                            
                            <!-- Email/Username Field -->
                            <div class="ios-input-group relative">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="text" 
                                       class="ios-input @error('email_username') is-invalid @enderror" 
                                       id="email_username" 
                                       name="email_username"
                                       placeholder="Email or Username" 
                                       value="{{ old('email_username') }}"
                                       required>
                                @error('email_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password Field -->
                            <div class="ios-input-group relative">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" 
                                       class="ios-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Password" 
                                       required>
                                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Submit Button - exact gradient style -->
                            <button type="submit" class="ios-gradient-btn" id="loginBtn">
                                <i class="fas fa-arrow-right-to-bracket"></i>
                                <span>Sign In</span>
                            </button>
                        </form>
                        
                        <!-- Footer note -->
                        <div class="footer-note">
                            <i class="fas fa-shield-alt mr-1"></i> Secure login with 2FA protection
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts - backend logic untouched, only UI enhancement -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/js/main.js"></script>
    
    <!-- Theme Management - exactly from first design -->
    <script>
        // Theme management (same as first page)
        function initializeTheme() {
            const companyTheme = "dark";
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

        // Run theme initialization
        initializeTheme();

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            const companyTheme = "dark";
            if (companyTheme === 'system') {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Password visibility toggle (enhanced but same logic)
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $(this);
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Form submission with loading state (preserved backend logic)
            $('#loginForm').on('submit', function(e) {
                const emailUsername = $('#email_username').val().trim();
                const password = $('#password').val();
                
                // Validation messages with glass style
                let hasError = false;
                
                if (!emailUsername) {
                    e.preventDefault();
                    showError('email_username', 'Please enter email or username');
                    hasError = true;
                }
                
                if (!password) {
                    e.preventDefault();
                    showError('password', 'Please enter password');
                    hasError = true;
                }
                
                if (hasError) {
                    return false;
                }
                
                // Show loading state on button (keeps backend intact)
                const btn = $('#loginBtn');
                const originalHtml = btn.html();
                btn.html('<div class="spinner-border-sm me-2"></div> Signing in...');
                btn.prop('disabled', true);
                
                // Allow form to submit normally, but if there's an error, restore button
                // We'll restore after a timeout in case of network issues, but form submit will reload page on success
                setTimeout(function() {
                    if (btn.prop('disabled')) {
                        btn.html(originalHtml);
                        btn.prop('disabled', false);
                    }
                }, 10000);
            });
            
            // Clear error on input with smooth UI
            $('#email_username, #password').on('input', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });
            
            function showError(field, message) {
                const input = $('#' + field);
                input.addClass('is-invalid');
                
                // Remove existing error message
                input.parent().find('.invalid-feedback').remove();
                
                // Add error message
                input.after('<div class="invalid-feedback" style="display: block; font-size: 12px; margin-top: 5px;">' + message + '</div>');
            }
            
            // Auto-focus on first field
            $('#email_username').focus();
        });
    </script>
    
    <!-- Additional styles to ensure exact match with first page's iOS theme -->
    <style>
        /* Dark mode fine-tuning */
        .dark .ios-input {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            color: #ffffff; 
        }
        
        .dark .ios-input:focus {
            border-color: #c3a200;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .light-theme .ios-glass {
            background: rgba(0, 0, 0, 0.85) !important;
        }
        
        .light-theme .ios-input {
            background: rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        
        .light-theme .ios-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }ios
        
        /* Ensure labels and icons visibility in light mode */
        .light-theme .input-icon,
        .light-theme .password-toggle {
            color: #007AFF;
        }
        
        .light-theme .welcome-section p,
        .light-theme .ios-checkbox-wrapper label,
        .light-theme .footer-note {
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--ios-primary);
            border-radius: 10px;
        }
        
        /* Spinner animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .spinner-border-sm {
            animation: spin 0.8s linear infinite;
        }
    </style>
</body>
</html>