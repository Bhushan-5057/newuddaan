<!DOCTYPE html>
<html lang="en" class="dark" dir="ltr" data-theme="dark-purple">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ company_info('title') }} - Wallet Login</title>
    <meta name="description" content="Login with your crypto wallet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    
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
            0%, 100% {
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
            max-width: 480px;
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
        
        .connect-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
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
            display: none;
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
        
        /* Signature Request Modal */
        .signature-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .signature-modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .signature-content {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2rem;
            width: 90%;
            max-width: 420px;
            box-shadow: var(--shadow-xl);
            text-align: center;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        
        .signature-modal.active .signature-content {
            transform: translateY(0);
        }
        
        .signature-icon {
            font-size: 3rem;
            color: var(--primary-purple);
            margin-bottom: 1.5rem;
        }
        
        .signature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .signature-message {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.5;
            font-size: 0.95rem;
        }
        
        .signature-info {
            background: rgba(124, 58, 237, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 3px solid var(--primary-purple);
        }
        
        .signature-info i {
            color: var(--primary-purple);
            margin-right: 0.5rem;
        }
        
        .signature-address {
            font-family: 'Monaco', 'Menlo', 'Consolas', monospace;
            font-size: 0.9rem;
            color: var(--text-primary);
            background: rgba(15, 23, 42, 0.7);
            padding: 0.75rem;
            border-radius: 8px;
            margin: 1rem 0;
            word-break: break-all;
        }
        
        .signature-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .signature-btn {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .signature-btn.cancel {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .signature-btn.cancel:hover {
            background: rgba(239, 68, 68, 0.2);
        }
        
        .signature-btn.confirm {
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
        }
        
        .signature-btn.confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(124, 58, 237, 0.4);
        }
        
        /* Login Button */
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--success) 0%, #0ca678 100%);
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
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            margin-top: 1.5rem;
            display: none;
        }
        
        .login-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }
        
        .login-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(124, 58, 237, 0.1);
            color: var(--text-secondary);
            font-size: 0.95rem;
        }
        
        .register-link a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Status Messages */
        .status-container {
            margin-bottom: 1.5rem;
        }
        
        .status-message {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }
        
        .status-message.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }
        
        .status-message.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        .status-message.info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
        }
        
        /* Loading Animation */
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
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }
            
            .brand-title {
                font-size: 1.75rem;
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
            
            .signature-content {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .signature-actions {
                flex-direction: column;
            }
        }
        
        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem 1rem;
            }
            
            .brand-title {
                font-size: 1.5rem;
            }
            
            .connect-btn, .login-btn {
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
    
    <!-- Signature Request Modal -->
    <div class="signature-modal" id="signatureModal">
        <div class="signature-content">
            <div class="signature-icon">
                <i class="fas fa-signature"></i>
            </div>
            <h2 class="signature-title">Signature Required</h2>
            <p class="signature-message">
                Please sign this message to verify ownership of your wallet address. 
                This is a secure way to prove you own this wallet without giving access to your funds.
            </p>
            
            <div class="signature-info">
                <i class="fas fa-shield-alt"></i>
                <span>This signature request does not cost any gas fees.</span>
            </div>
            
            <div class="wallet-label">
                <i class="fas fa-wallet"></i>
                <span>Signing Address:</span>
            </div>
            <div class="signature-address" id="signatureAddress"></div>
            
            <div class="signature-actions">
                <button class="signature-btn cancel" id="cancelSignature">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button class="signature-btn confirm" id="confirmSignature">
                    <i class="fas fa-check"></i>
                    Sign Message
                </button>
            </div>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo & Title -->
            <div class="brand-section">
                <div class="brand-logo">
                    <img src="{{ asset('uploads/' . company_info('logodark')) }}" alt="Logo">
                </div>
                <h1 class="brand-title">Wallet Login</h1>
                <p class="brand-subtitle">Connect your wallet to access your account</p>
            </div>
            
            <!-- Status Messages -->
            <div class="status-container" id="statusContainer"></div>
            
            <!-- Wallet Connection Section -->
            <div class="wallet-section">
                <!--<h2 class="section-title">-->
                <!--    <i class="fas fa-wallet"></i>-->
                <!--    <span>Step 1: Connect Wallet</span>-->
                <!--</h2>-->
                
                <!-- Connect Button -->
                <button id="connectBtn" class="connect-btn">
                   <i class="fab fa-binance"></i>

                    Connect Wallet
                </button>
                
                <!-- Connected State -->
                <div id="connectedState" class="connected-state">
                    <div class="wallet-header">
                        <div class="wallet-status">
                            <i class="fas fa-circle status-connected"></i>
                            <span>Wallet Connected</span>
                        </div>
                        <button id="disconnectBtn" class="action-btn disconnect-btn">
                            <i class="fas fa-plug"></i>
                            Disconnect
                        </button>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Login Button -->
            <button id="loginBtn" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>
                Login with Wallet
            </button>
            
            <!-- Register Link -->
            <div class="register-link">
                <span>Don't have an account? </span>
                <a href="{{ route('register') }}">
                    <i class="fas fa-user-plus"></i>
                    Register with Wallet
                </a>
            </div>
        </div>
    </div>

    <script>
        // State
        let currentAddress = '';
        let isConnected = false;
        let isSigning = false;
        
        // DOM Elements
        const connectBtn = document.getElementById('connectBtn');
        const disconnectBtn = document.getElementById('disconnectBtn');
        const connectedState = document.getElementById('connectedState');
        const walletAddressText = document.getElementById('walletAddressText');
        const copyBtn = document.getElementById('copyBtn');
        const loginBtn = document.getElementById('loginBtn');
        const signatureModal = document.getElementById('signatureModal');
        const signatureAddress = document.getElementById('signatureAddress');
        const cancelSignatureBtn = document.getElementById('cancelSignature');
        const confirmSignatureBtn = document.getElementById('confirmSignature');
        const statusContainer = document.getElementById('statusContainer');
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            checkExistingConnection();
            setupEventListeners();
        });
        
        // Check for existing connection
        async function checkExistingConnection() {
            if (typeof window.ethereum !== 'undefined') {
                try {
                    const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                    if (accounts.length > 0) {
                        currentAddress = accounts[0];
                        handleWalletConnected(currentAddress);
                    }
                } catch (error) {
                    console.log('No existing connection:', error);
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
            
            // Login button
            loginBtn.addEventListener('click', initiateLogin);
            
            // Signature modal
            cancelSignatureBtn.addEventListener('click', cancelSignature);
            confirmSignatureBtn.addEventListener('click', signAndLogin);
            
            // MetaMask events
            if (window.ethereum) {
                window.ethereum.on('accountsChanged', handleAccountsChanged);
                window.ethereum.on('chainChanged', () => window.location.reload());
            }
        }
        
        // Connect wallet
        async function connectWallet() {
            if (typeof window.ethereum === 'undefined') {
                showStatus('MetaMask Not Found', 'Please install MetaMask to connect your wallet.', 'error');
                return;
            }
            
            try {
                // Show loading
                connectBtn.innerHTML = '<span class="loading"></span> Connecting...';
                connectBtn.disabled = true;
                
                // Request connection
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                
                if (accounts.length > 0) {
                    currentAddress = accounts[0];
                    handleWalletConnected(currentAddress);
                    showStatus('Wallet Connected', 'Your wallet has been connected successfully.', 'success');
                }
            } catch (error) {
                if (error.code === 4001) {
                    showStatus('Connection Rejected', 'Please connect your wallet to continue.', 'error');
                } else {
                    showStatus('Connection Failed', error.message, 'error');
                }
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
            currentAddress = address.toLowerCase();
            
            // Update UI
            connectBtn.style.display = 'none';
            connectedState.style.display = 'block';
            walletAddressText.textContent = formatAddress(address);
            loginBtn.style.display = 'flex';
            
            showStatus('Ready to Login', 'Click "Login with Wallet" to continue.', 'info');
        }
        
        // Disconnect wallet
        function disconnectWallet() {
            isConnected = false;
            currentAddress = '';
            
            // Update UI
            connectBtn.style.display = 'block';
            connectedState.style.display = 'none';
            loginBtn.style.display = 'none';
            
            showStatus('Wallet Disconnected', 'You have been disconnected.', 'info');
        }
        
        // Handle account changes
        function handleAccountsChanged(accounts) {
            if (accounts.length === 0) {
                disconnectWallet();
                showStatus('Wallet Disconnected', 'Your wallet has been disconnected.', 'info');
            } else if (accounts[0].toLowerCase() !== currentAddress) {
                currentAddress = accounts[0].toLowerCase();
                handleWalletConnected(currentAddress);
                showStatus('Account Changed', `Switched to ${formatAddress(currentAddress)}`, 'info');
            }
        }
        
        // Initiate login process
        async function initiateLogin() {
            if (!isConnected || !currentAddress) {
                showStatus('Wallet Not Connected', 'Please connect your wallet first.', 'error');
                return;
            }
            
            // Show signature request modal
            signatureAddress.textContent = formatAddress(currentAddress);
            signatureModal.classList.add('active');
            isSigning = true;
        }
        
        // Cancel signature
        function cancelSignature() {
            signatureModal.classList.remove('active');
            isSigning = false;
            showStatus('Login Cancelled', 'Signature request was cancelled.', 'info');
        }
        
        // Sign message and login
        async function signAndLogin() {
            if (!isConnected || !currentAddress) {
                showStatus('Wallet Not Connected', 'Please connect your wallet first.', 'error');
                return;
            }
            
            try {
                // Update UI
                confirmSignatureBtn.innerHTML = '<span class="loading"></span> Signing...';
                confirmSignatureBtn.disabled = true;
                
                // Create a unique message to sign
                const message = `Login to {{ company_info('title') }} at ${new Date().toISOString()}`;
                
                // Sign the message
                const signature = await window.ethereum.request({
                    method: 'personal_sign',
                    params: [message, currentAddress],
                });
                
                // Prepare login request
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                // Close modal
                signatureModal.classList.remove('active');
                
                // Show loading on login button
                loginBtn.innerHTML = '<span class="loading"></span> Logging in...';
                loginBtn.disabled = true;
                
                // Send login request
                const response = await fetch('{{ route("logindapp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        user_address: currentAddress,
                        signature: signature,
                        message: message,
                        _token: csrfToken
                    }),
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    showStatus('Login Successful', 'Redirecting to dashboard...', 'success');
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("dashboard.user") }}';
                    }, 1500);
                } else {
                    showStatus('Login Failed', data.message || 'Invalid wallet address or signature.', 'error');
                    resetLoginButton();
                }
                
            } catch (error) {
                console.error('Login error:', error);
                
                if (error.code === 4001) {
                    showStatus('Signature Rejected', 'You declined the signature request.', 'error');
                } else {
                    showStatus('Login Failed', error.message || 'An error occurred during login.', 'error');
                }
                
                // Close modal
                signatureModal.classList.remove('active');
                resetLoginButton();
            } finally {
                // Reset signature button
                confirmSignatureBtn.innerHTML = '<i class="fas fa-check"></i> Sign Message';
                confirmSignatureBtn.disabled = false;
                isSigning = false;
            }
        }
        
        // Reset login button
        function resetLoginButton() {
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login with Wallet';
            loginBtn.disabled = false;
        }
        
        // Copy address
        function copyAddress() {
            navigator.clipboard.writeText(currentAddress).then(() => {
                // Visual feedback
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                copyBtn.classList.add('copied');
                
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="far fa-copy"></i> Copy';
                    copyBtn.classList.remove('copied');
                }, 2000);
                
                showStatus('Address Copied', 'Wallet address copied to clipboard.', 'success');
            }).catch(err => {
                console.error('Failed to copy:', err);
                showStatus('Copy Failed', 'Failed to copy address.', 'error');
            });
        }
        
        // Show status message
        function showStatus(title, message, type = 'info') {
            // Clear previous messages
            statusContainer.innerHTML = '';
            
            // Create message element
            const messageEl = document.createElement('div');
            messageEl.className = `status-message ${type}`;
            
            let icon = 'info-circle';
            if (type === 'error') icon = 'exclamation-circle';
            if (type === 'success') icon = 'check-circle';
            
            messageEl.innerHTML = `
                <i class="fas fa-${icon}"></i>
                <div>
                    <strong>${title}</strong>
                    <div style="font-size: 0.9rem; margin-top: 0.25rem;">${message}</div>
                </div>
            `;
            
            // Add to container
            statusContainer.appendChild(messageEl);
            
            // Auto-remove after 5 seconds (except for errors)
            if (type !== 'error') {
                setTimeout(() => {
                    if (messageEl.parentNode) {
                        messageEl.remove();
                    }
                }, 5000);
            }
        }
        
        // Utility functions
        function formatAddress(address) {
            return `${address.substring(0, 6)}...${address.substring(address.length - 4)}`;
        }
        
        // Handle escape key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && signatureModal.classList.contains('active')) {
                cancelSignature();
            }
        });
        
        // Handle click outside modal
        signatureModal.addEventListener('click', (e) => {
            if (e.target === signatureModal) {
                cancelSignature();
            }
        });
    </script>
</body>
</html>