<!DOCTYPE html>
<html lang="en" class="dark" dir="ltr" data-theme="dark-purple">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ company_info('title') }} - Wallet Registration</title>
    <meta name="description" content="Register with your crypto wallet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --primary-purple: #7c3aed;
            --primary-dark: #5b21b6;
            --primary-light: #8b5cf6;
            --gradient-start: #0f172a;
            --gradient-end: #1e1b4b;
            --card-bg: rgba(30, 41, 59, 0.95);
            --card-border: rgba(124, 58, 237, 0.2);
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-800: #1e293b;
            --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            --shadow-xl: 0 35px 60px -15px rgba(0, 0, 0, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body.dark {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background Effects */
        .bg-effects {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .bg-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(124, 58, 237, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124, 58, 237, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .bg-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--primary-purple) 0%, transparent 70%);
            filter: blur(80px);
            opacity: 0.15;
            animation: float-glow 20s infinite ease-in-out;
        }

        .bg-glow:nth-child(1) {
            top: -100px;
            left: -100px;
        }

        .bg-glow:nth-child(2) {
            bottom: -100px;
            right: -100px;
            animation-delay: -10s;
        }

        @keyframes float-glow {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(50px, -50px) scale(1.1);
            }

            50% {
                transform: translate(0, -100px) scale(1);
            }

            75% {
                transform: translate(-50px, -50px) scale(0.9);
            }
        }

        /* Main Container */
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        .auth-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-purple), var(--primary-light));
            border-radius: 24px 24px 0 0;
        }

        /* Logo Section */
        .brand-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .brand-logo img {
            height: 70px;
            width: auto;
            filter: drop-shadow(0 4px 12px rgba(124, 58, 237, 0.3));
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--primary-purple), var(--primary-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.9;
        }

        /* Referral Badge */
        .referral-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(91, 33, 182, 0.15));
            color: var(--primary-light);
            border: 1px solid rgba(124, 58, 237, 0.3);
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 1rem;
        }

        /* Wallet Connection Section */
        .wallet-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-purple);
            background: rgba(124, 58, 237, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Wallet Connection States */
        .wallet-state {
            transition: all 0.3s ease;
        }

        /* Connect Button */
        .connect-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 1.25rem;
            font-size: 1.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
            margin-bottom: 1.5rem;
        }

        .connect-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(124, 58, 237, 0.4);
        }

        .connect-btn:active {
            transform: translateY(0);
        }

        .connect-btn i {
            font-size: 1.5rem;
        }

        /* Connected State */
        .connected-state {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(124, 58, 237, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .wallet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .wallet-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .status-connected {
            color: var(--success);
        }

        .wallet-address-container {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid rgba(124, 58, 237, 0.2);
            margin-bottom: 1rem;
        }

        .wallet-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .wallet-address {
            font-family: 'Monaco', 'Menlo', 'Consolas', monospace;
            font-size: 0.95rem;
            color: var(--text-primary);
            word-break: break-all;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .address-text {
            flex: 1;
        }

        .wallet-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: rgba(124, 58, 237, 0.1);
            color: var(--primary-light);
            border: 1px solid rgba(124, 58, 237, 0.3);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            background: rgba(124, 58, 237, 0.2);
        }

        .action-btn.copy-btn.copied {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .disconnect-btn {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .disconnect-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* Registration Form */
        .registration-form {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .registration-form.visible {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .form-section {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(124, 58, 237, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-title i {
            color: var(--primary-purple);
        }



        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(124, 58, 237, 0.2);
            border-radius: 12px;
            padding: 0.875rem 1rem;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .sponsor-feedback {
            margin-top: 0.5rem;
            font-size: 0.95rem;
            min-height: 1.5rem;
        }

        .sponsor-valid {
            color: var(--success);
        }

        .sponsor-invalid {
            color: var(--danger);
        }

        /* Binary Position Selector */
        .binary-section {
            margin-top: 1rem;
        }

        .position-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .position-card {
            background: rgba(15, 23, 42, 0.7);
            border: 2px solid rgba(124, 58, 237, 0.2);
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .position-card:hover {
            border-color: var(--primary-purple);
            transform: translateY(-2px);
        }

        .position-card.selected {
            border-color: var(--primary-purple);
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(91, 33, 182, 0.15));
            box-shadow: 0 5px 20px rgba(124, 58, 237, 0.2);
        }

        .position-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .position-card.disabled:hover {
            border-color: rgba(124, 58, 237, 0.2);
            transform: none;
        }

        .position-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: var(--primary-purple);
        }

        .position-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .position-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .position-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--primary-purple);
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        .position-info {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: rgba(124, 58, 237, 0.1);
            border-radius: 8px;
            border-left: 3px solid var(--primary-purple);
        }

        .position-info i {
            color: var(--primary-purple);
            margin-right: 0.5rem;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 1.25rem;
            font-size: 1.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
            margin-top: 1.5rem;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(124, 58, 237, 0.4);
        }

        .submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(124, 58, 237, 0.1);
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .login-link a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Alerts */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }

        .alert {
            background: var(--card-bg);
            border-left: 4px solid var(--primary-purple);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideInRight 0.3s ease;
            transform: translateX(0);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alert i {
            font-size: 1.25rem;
            margin-top: 0.125rem;
        }

        .alert-success {
            border-left-color: var(--success);
        }

        .alert-success i {
            color: var(--success);
        }

        .alert-error {
            border-left-color: var(--danger);
        }

        .alert-error i {
            color: var(--danger);
        }

        .alert-info {
            border-left-color: var(--primary-purple);
        }

        .alert-info i {
            color: var(--primary-purple);
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .alert-message {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .alert-close {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.25rem;
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        .alert-close:hover {
            color: var(--text-primary);
        }

        /* Loading */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }

            .brand-title {
                font-size: 1.75rem;
            }

            .position-grid {
                grid-template-columns: 1fr;
            }

            .wallet-address {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .wallet-actions {
                width: 100%;
            }

            .action-btn {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem 1rem;
            }

            .brand-title {
                font-size: 1.5rem;
            }

            .connect-btn,
            .submit-btn {
                padding: 1rem;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body class="dark">
    <!-- Background Effects -->
    <div class="bg-effects">
        <div class="bg-grid"></div>
        <div class="bg-glow"></div>
        <div class="bg-glow"></div>
    </div>

    <!-- Alert Container -->
    <div class="alert-container" id="alertContainer"></div>

    <!-- Main Container -->
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo & Title -->
            <div class="brand-section">
                <div class="brand-logo">
                    <img src="{{ asset('uploads/' . company_info('logodark')) }}" alt="Logo">
                </div>
                <h1 class="brand-title">Wallet Registration</h1>
                <p class="brand-subtitle">Connect your wallet to join our network</p>

                @if ($referrer)
                    <div class="referral-badge">
                        <i class="fas fa-user-friends"></i>
                        <span>Referred by: {{ $referrer }}</span>
                    </div>
                @endif
            </div>

            <!-- Wallet Connection Section -->
            <div class="wallet-section">
                <!--<h2 class="section-title">-->
                <!--    <i class="fas fa-wallet"></i>-->
                <!--    <span>Step 1: Connect Wallet</span>-->
                <!--</h2>-->

                <!-- Connect Button (shown when not connected) -->
                <div id="connectState" class="wallet-state">
                    <button id="connectBtn" class="connect-btn">
                        <i class="fab fa-binance"></i>
                        Connect Wallet
                    </button>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.95rem;">
                        Connect your wallet to proceed with registration
                    </p>
                </div>

                <!-- Connected State (shown after connection) -->
                <div id="connectedState" class="connected-state" style="display: none;">
                    <div class="wallet-header">
                        <div class="wallet-status">
                            <i class="fas fa-circle status-connected"></i>
                            <span>Wallet Connected</span>
                        </div>
                    </div>

                    <div class="wallet-address-container">
                        <div class="wallet-label">
                            <i class="fas fa-address-card"></i>
                            <span>Connected Address</span>
                        </div>
                        <div class="wallet-address">
                            <span class="address-text" id="walletAddressText"></span>
                            <div class="wallet-actions">
                                <button id="copyBtn" class="action-btn copy-btn">
                                    <i class="far fa-copy"></i>
                                    Copy
                                </button>
                                <button id="disconnectBtn" class="action-btn disconnect-btn">
                                    <i class="fas fa-plug"></i>
                                    Disconnect
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form id="registrationForm" class="registration-form" action="{{ route('registerdapp') }}" method="POST">
                @csrf
                <input type="hidden" name="wallet_address" id="formWalletAddress">

                <!--<h2 class="section-title">-->
                <!--    <i class="fas fa-user-plus"></i>-->
                <!--    <span>Step 2: Registration Details</span>-->
                <!--</h2>-->

                <!-- Sponsor Information -->
                <div class="form-section">
                    <h3 class="form-title">
                        <i class="fas fa-users"></i>
                        <span>Sponsor Information</span>
                    </h3>

                    @php
                        $isSponsorRequired =
                            \App\Models\AdvanceInfo::where('label', 'is_sponsor_required')->value('value') ?? 'no';
                        $isSponsorRequired = strtolower($isSponsorRequired) === 'yes';
                        $showSponsorField = !$referrer || $isSponsorRequired;
                    @endphp

                    @if ($showSponsorField)
                        <div class="form-group">
                            <label for="sponsor_id" class="form-label">
                                Sponsor Username {{ $isSponsorRequired ? '*' : '' }}
                            </label>
                            <input type="text" id="sponsor_id" name="sponsor_display" class="form-control"
                                value="{{ $referrer ?? '' }}" {{ $isSponsorRequired ? 'required' : '' }}
                                autocomplete="off" placeholder="Enter sponsor's username">
                            @error('sponsor_display')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="sponsorFeedback" class="sponsor-feedback"></div>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="form-label">Sponsor (From Referral)</label>
                            <input type="text" class="form-control" value="{{ $referrer }}" readonly
                                style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3);">
                            <input type="hidden" name="sponsor_display" value="{{ $referrer }}">
                            <div class="sponsor-feedback sponsor-valid">
                                <i class="fas fa-check-circle"></i> Sponsor set from referral link
                            </div>
                        </div>
                        @error('sponsor_display')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    @endif
                </div>

                <!-- Binary Placement -->
                @if ($RegistrationType == 'binary')
                    <div class="form-section">
                        <!--<h3 class="form-title">-->
                        <!--    <i class="fas fa-sitemap"></i>-->
                        <!--    <span>Binary Placement</span>-->
                        <!--</h3>-->

                        @php
                            $hasParentFromReferral = !empty($refParent);
                            $hasSideFromReferral = !empty($refSide);
                            $hasSponsorDefaultSide = !empty($sponsorDefaultSide);
                            $canSelectPosition =
                                !$hasParentFromReferral && !$hasSideFromReferral && !$hasSponsorDefaultSide;
                        @endphp

                        <!-- Parent from referral -->
                        @if ($refParent)
                            <div class="form-group">
                                <label class="form-label">Parent Node</label>
                                <input type="text" class="form-control" value="{{ $refParent }}" readonly
                                    style="background: rgba(16, 185, 129, 0.1);">
                                <input type="hidden" name="parent" value="{{ $refParent }}">
                                @error('position')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="position-info">
                                    <i class="fas fa-info-circle"></i>
                                    Parent automatically set from referral link
                                </div>
                            </div>
                        @endif

                        <!-- Position Selection -->
                        <div class="form-group">
                            <label class="form-label">Select Position</label>

                            @if ($refSide)
                                <!-- Fixed from referral -->
                                <div class="position-grid">
                                    <div class="position-card selected disabled">
                                        <div class="position-icon">
                                            @if ($refSide == 'left')
                                                <i class="fas fa-arrow-left"></i>
                                            @else
                                                <i class="fas fa-arrow-right"></i>
                                            @endif
                                        </div>
                                        <div class="position-label">{{ ucfirst($refSide) }} Side</div>
                                        <div class="position-desc">Referral Link</div>
                                        <div class="position-badge">Auto</div>
                                    </div>
                                </div>
                                @error('position')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input type="hidden" name="side" value="{{ $refSide }}">
                                <div class="position-info">
                                    <i class="fas fa-info-circle"></i>
                                    Position automatically set from referral link
                                </div>
                            @elseif ($sponsorDefaultSide)
                                <!-- Sponsor default -->
                                <div class="position-grid">
                                    <div class="position-card selected disabled">
                                        <div class="position-icon">
                                            @if ($sponsorDefaultSide == 'left')
                                                <i class="fas fa-arrow-left"></i>
                                            @else
                                                <i class="fas fa-arrow-right"></i>
                                            @endif
                                        </div>
                                        <div class="position-label">{{ ucfirst($sponsorDefaultSide) }} Side</div>
                                        <div class="position-desc">Sponsor Default</div>
                                        <div class="position-badge">Auto</div>
                                    </div>
                                </div>
                                @error('position')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input type="hidden" name="side" value="{{ $sponsorDefaultSide }}">
                                <div class="position-info">
                                    <i class="fas fa-info-circle"></i>
                                    Position automatically set from sponsor's default side
                                </div>
                            @elseif($canSelectPosition)
                                <!-- Manual selection (only when coming directly) -->
                                <div class="position-grid">
                                    <div class="position-card" data-value="left">
                                        <div class="position-icon">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                        <div class="position-label">Left Side</div>
                                        <div class="position-desc">Left Leg</div>
                                    </div>

                                    <div class="position-card" data-value="right">
                                        <div class="position-icon">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        <div class="position-label">Right Side</div>
                                        <div class="position-desc">Right Leg</div>
                                    </div>

                                    <div class="position-card" data-value="auto">
                                        <div class="position-icon">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                        <div class="position-label">Auto</div>
                                        <div class="position-desc">Smart Placement</div>
                                    </div>
                                </div>
                                @error('position')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input type="hidden" name="side" id="selectedSide" value="auto">
                                <div class="position-info">
                                    <i class="fas fa-info-circle"></i>
                                    Select your preferred position or choose Auto for automatic placement
                                </div>
                            @else
                                <!-- No position selection needed -->
                                <div class="position-info">
                                    <i class="fas fa-info-circle"></i>
                                    Position will be automatically determined based on network structure
                                </div>
                                <input type="hidden" name="side" value="auto">
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="submit-btn" disabled>
                    <i class="fas fa-user-plus"></i>
                    Complete Registration
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <span>Already registered? </span>
                <a href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt"></i>
                    Connect Wallet to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        // State
        let walletAddress = '';
        let isConnected = false;
        let selectedPosition = '{{ $refSide ?? ($sponsorDefaultSide ?? 'auto') }}';

        // DOM Elements
        const connectState = document.getElementById('connectState');
        const connectedState = document.getElementById('connectedState');
        const connectBtn = document.getElementById('connectBtn');
        const disconnectBtn = document.getElementById('disconnectBtn');
        const walletAddressText = document.getElementById('walletAddressText');
        const copyBtn = document.getElementById('copyBtn');
        const formWalletAddress = document.getElementById('formWalletAddress');
        const registrationForm = document.getElementById('registrationForm');
        const sponsorIdInput = document.getElementById('sponsor_id');
        const sponsorFeedback = document.getElementById('sponsorFeedback');
        const submitBtn = document.getElementById('submitBtn');
        const alertContainer = document.getElementById('alertContainer');
        const positionCards = document.querySelectorAll('.position-card[data-value]');
        const selectedSideInput = document.getElementById('selectedSide');

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            checkExistingConnection();
            setupEventListeners();
            setupPositionSelection();

            // Auto-select default position
            if (selectedSideInput && !selectedPosition) {
                selectedSideInput.value = 'auto';
            }
        });

        // Check for existing connection
        async function checkExistingConnection() {
            if (typeof window.ethereum !== 'undefined') {
                try {
                    const accounts = await window.ethereum.request({
                        method: 'eth_accounts'
                    });
                    if (accounts.length > 0) {
                        walletAddress = accounts[0];
                        handleWalletConnected(walletAddress);
                    }
                } catch (error) {
                    console.log('No existing connection');
                }
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Connect wallet
            connectBtn.addEventListener('click', connectWallet);

            // Disconnect wallet
            disconnectBtn.addEventListener('click', disconnectWallet);

            // Copy address
            copyBtn.addEventListener('click', copyAddress);

            // Sponsor validation
            if (sponsorIdInput) {
                sponsorIdInput.addEventListener('input', debounce(validateSponsor, 500));
            }

            // Form submission
            registrationForm.addEventListener('submit', handleFormSubmit);

            // MetaMask events
            if (window.ethereum) {
                window.ethereum.on('accountsChanged', handleAccountsChanged);
                window.ethereum.on('chainChanged', () => window.location.reload());
            }
        }

        // Setup position selection
        function setupPositionSelection() {
            positionCards.forEach(card => {
                if (!card.classList.contains('disabled')) {
                    card.addEventListener('click', () => {
                        // Remove selected class from all cards
                        positionCards.forEach(c => {
                            if (!c.classList.contains('disabled')) {
                                c.classList.remove('selected');
                            }
                        });

                        // Add selected class to clicked card
                        card.classList.add('selected');

                        // Update selected position
                        selectedPosition = card.getAttribute('data-value');
                        if (selectedSideInput) {
                            selectedSideInput.value = selectedPosition;
                        }

                        showAlert('Position Selected',
                            `You selected: ${getPositionLabel(selectedPosition)}`, 'info');
                    });
                }
            });

            // Auto-select first position if none selected and cards are enabled
            if (positionCards.length > 0 && !selectedPosition) {
                const enabledCards = Array.from(positionCards).filter(card => !card.classList.contains('disabled'));
                if (enabledCards.length > 0) {
                    const autoCard = enabledCards.find(card => card.getAttribute('data-value') === 'auto') || enabledCards[
                        0];
                    autoCard.click();
                }
            }
        }

        // Get position label
        function getPositionLabel(position) {
            const labels = {
                'left': 'Left Side',
                'right': 'Right Side',
                'auto': 'Auto Placement'
            };
            return labels[position] || position;
        }

        // Connect wallet
        async function connectWallet() {
            if (typeof window.ethereum === 'undefined') {
                showAlert('MetaMask Not Found', 'Please install MetaMask to connect your wallet.', 'error');
                return;
            }

            try {
                // Show loading
                connectBtn.innerHTML = '<span class="loading"></span> Connecting...';
                connectBtn.disabled = true;

                // Request connection
                const accounts = await window.ethereum.request({
                    method: 'eth_requestAccounts'
                });

                if (accounts.length > 0) {
                    walletAddress = accounts[0];
                    handleWalletConnected(walletAddress);
                    showAlert('Wallet Connected', 'Your wallet has been connected successfully.', 'success');
                }
            } catch (error) {
                showAlert('Connection Failed', error.message, 'error');
                console.error('Wallet connection error:', error);
            } finally {
                // Reset button
                connectBtn.innerHTML = '<i class="fab fa-binance"></i> Connect Wallet';
                connectBtn.disabled = false;
            }
        }

        // Handle wallet connection
        function handleWalletConnected(address) {
            isConnected = true;
            walletAddress = address;

            // Update UI
            connectState.style.display = 'none';
            connectedState.style.display = 'block';
            walletAddressText.textContent = formatAddress(address);
            formWalletAddress.value = address;

            // Show registration form
            registrationForm.classList.add('visible');

            // Update submit button
            updateSubmitButton();
        }

        // Disconnect wallet
        function disconnectWallet() {
            isConnected = false;
            walletAddress = '';

            // Update UI
            connectState.style.display = 'block';
            connectedState.style.display = 'none';
            registrationForm.classList.remove('visible');
            formWalletAddress.value = '';

            // Reset form
            submitBtn.disabled = true;

            showAlert('Wallet Disconnected', 'You have been disconnected.', 'info');
        }

        // Handle account changes
        function handleAccountsChanged(accounts) {
            if (accounts.length === 0) {
                disconnectWallet();
            } else if (accounts[0] !== walletAddress) {
                walletAddress = accounts[0];
                handleWalletConnected(walletAddress);
                showAlert('Account Changed', `Switched to ${formatAddress(walletAddress)}`, 'info');
            }
        }

        // Validate sponsor
        async function validateSponsor() {
            const sponsorId = sponsorIdInput.value.trim();

            if (sponsorId.length < 2) {
                sponsorFeedback.textContent = '';
                sponsorFeedback.className = 'sponsor-feedback';
                updateSubmitButton();
                return;
            }

            try {
                const response = await fetch(
                    "{{ route('auth.getSponsorName') }}?sponsor_id=" + encodeURIComponent(sponsorId)
                );
                const data = await response.json();

                if (data.success) {
                    sponsorFeedback.innerHTML = `<i class="fas fa-check-circle"></i> Valid sponsor: ${data.name}`;
                    sponsorFeedback.className = 'sponsor-feedback sponsor-valid';
                } else {
                    sponsorFeedback.innerHTML = `<i class="fas fa-times-circle"></i> Sponsor not found`;
                    sponsorFeedback.className = 'sponsor-feedback sponsor-invalid';
                }
            } catch (error) {
                sponsorFeedback.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Error checking sponsor`;
                sponsorFeedback.className = 'sponsor-feedback sponsor-invalid';
            }

            updateSubmitButton();
        }

        // Update submit button state
        function updateSubmitButton() {
            let sponsorValid = true;

            // Check if sponsor field exists and is required
            if (sponsorIdInput) {
                const isRequired = sponsorIdInput.hasAttribute('required');
                const hasValue = sponsorIdInput.value.trim().length > 0;
                sponsorValid = !isRequired || hasValue;
            }

            submitBtn.disabled = !(isConnected && sponsorValid);
        }

        // Copy address
        function copyAddress() {
            navigator.clipboard.writeText(walletAddress).then(() => {
                // Visual feedback
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                copyBtn.classList.add('copied');

                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="far fa-copy"></i> Copy';
                    copyBtn.classList.remove('copied');
                }, 2000);

                showAlert('Address Copied', 'Wallet address copied to clipboard.', 'success');
            }).catch(err => {
                console.error('Failed to copy:', err);
                showAlert('Copy Failed', 'Failed to copy address.', 'error');
            });
        }

        // Handle form submission
        function handleFormSubmit(e) {
            if (!isConnected) {
                e.preventDefault();
                showAlert('Wallet Not Connected', 'Please connect your wallet first.', 'error');
                return;
            }

            // Show loading
            submitBtn.innerHTML = '<span class="loading"></span> Registering...';
            submitBtn.disabled = true;

            // Form will submit normally
        }

        // Show alert
        function showAlert(title, message, type = 'info') {
            const alertId = 'alert-' + Date.now();
            const icon = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                info: 'fas fa-info-circle'
            } [type];

            const alertEl = document.createElement('div');
            alertEl.id = alertId;
            alertEl.className = `alert alert-${type}`;
            alertEl.innerHTML = `
                <i class="${icon}"></i>
                <div class="alert-content">
                    <div class="alert-title">${title}</div>
                    <div class="alert-message">${message}</div>
                </div>
                <button class="alert-close" onclick="document.getElementById('${alertId}').remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            alertContainer.appendChild(alertEl);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) alert.remove();
            }, 5000);
        }

        // Utility functions
        function formatAddress(address) {
            return `${address.substring(0, 6)}...${address.substring(address.length - 4)}`;
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
</body>

</html>
