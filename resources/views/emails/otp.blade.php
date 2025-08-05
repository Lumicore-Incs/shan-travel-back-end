<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset | Shan Travels</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f7fafc;
        }
        
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .logo {
            max-width: 180px;
            height: auto;
        }
        
        .content {
            padding: 25px;
        }
        
        h1 {
            color: #2d3748;
            font-size: 24px;
            margin-top: 0;
        }
        
        .otp-display {
            background: #f8fafc;
            border: 1px dashed #cbd5e0;
            padding: 15px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 3px;
            color: #2d3748;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .note {
            background: #f0fdf4;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            margin: 20px 0;
            border-left: 4px solid #22c55e;
        }
        
        .warning {
            background: #fef2f2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            margin: 20px 0;
            border-left: 4px solid #ef4444;
        }
        
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #f59e0b;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 15px 0;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            
            .content {
                padding: 15px;
            }
            
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            
            <h1>Shan Star</h1>
        </div>
        
        <div class="content">
            <h1>Password Reset Request</h1>
            
            <p>Hello,</p>
            
            <p>We received a request to reset your password for your Shan Travels account. Please use the following OTP (One-Time Password) to proceed:</p>
            
            <div class="otp-display">
                {{ $otp }}
            </div>
            
            <div class="note">
                <strong>Please note:</strong> This OTP is valid for <strong>15 minutes</strong> only. Do not share this code with anyone.
            </div>
            
            <p>If you didn't request this password reset, please ignore this email or contact our support team immediately if you suspect any unauthorized activity.</p>
            
            <div class="warning">
                <strong>Security tip:</strong> Shan Travels will never ask you for your password or OTP via email, phone, or SMS.
            </div>
         </div>
        
    </div>
</body>
</html>