<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            text-align: center;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            padding: 10px 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3 style="font-size: 26px;color: #7d879b;">ASP VDMS PORTAL</h3>
        </div>
        <div class="content">
            <p>Hello, {{  $data['name'] ?? "--"  }}</p>
            <h2>Your OTP Code</h2>
            <p>To complete your request, please use the following One-Time Password (OTP):</p>
            <div class="otp-code">
                {{  $data['token'] ?? "--"  }}
            </div>
            <p>This code is valid for 10 minutes. Please do not share this code with anyone.</p>
        </div>
        <div class="footer">
            <p>If you did not request this, please ignore this email or contact our support team.</p>
            <p>© {{ date('Y') }} ASP VDMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
