<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login OTP</title>
    <style>
          body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    min-height: 100vh;
    
    /* 1. Exact color match from your image */
    background: linear-gradient(
        180deg, 
        #0084ff 0%,   /* Bright Azure */
        #002ead 40%,  /* Deep Blue */
        #000b3d 75%,  /* Midnight Navy */
        #000000 100%  /* Black */
    );

    /* 2. This keeps the background from scrolling */
    background-attachment: fixed;

    /* 3. Ensures the gradient covers the full screen even if content is short */
    background-size: cover; 
}
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .otp-code {
            font-size: 48px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 10px;
            color: #4e73df;
            background: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-family: monospace;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
        .footer {
            background: #f8f9fc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e3e6f0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4e73df;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .otp-code {
                font-size: 32px;
                letter-spacing: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $app_name ?? config('app.name') }}</h1>
            <p>Admin Login Verification</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>We received a request to log in to your admin account. Use the verification code below to complete your login:</p>
            
            <div class="otp-code">
                {{ $otp }}
            </div>
            
            <p>This code will expire in <strong>5 minutes</strong>.</p>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li>Never share this OTP with anyone</li>
                    <li>If you didn't request this login, please ignore this email</li>
                    <li>Admin support will never ask for your OTP</li>
                </ul>
            </div>
            
            <p style="text-align: center;">
                <strong>IP Address:</strong> {{ request()->ip() }}<br>
                <strong>Time:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
            </p>
            
            <p style="text-align: center;">
                <small>For security reasons, never forward this email to anyone.</small>
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>