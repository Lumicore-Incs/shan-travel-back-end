<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Confirmation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .booking-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            font-weight: 500;
            color: #64748b;
            width: 150px;
            flex-shrink: 0;
        }
        
        .detail-value {
            color: #1e293b;
            font-weight: 400;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        
        .logo {
            font-size: 20px;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }
        
        .thank-you {
            font-size: 18px;
            color: #3b82f6;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .content {
                padding: 20px;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Travel Shan Star</div>
            <h1>New Booking Confirmation</h1>
        </div>
        
        <div class="content">
            <div class="thank-you">Thank you for your booking!</div>
            
            <div class="booking-details">
                <div class="detail-row">
                    <div class="detail-label">Booking Reference:</div>
                    <div class="detail-value">#{{ $booking->id }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Name:</div>
                    <div class="detail-value">{{ $booking->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Pickup Location:</div>
                    <div class="detail-value">{{ $booking->pickup_location }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Drop Location:</div>
                    <div class="detail-value">{{ $booking->drop_location }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Trip Dates:</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($booking->start_date)->format('M j, Y') }} to 
                        {{ \Carbon\Carbon::parse($booking->end_date)->format('M j, Y') }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Number of Persons:</div>
                    <div class="detail-value">{{ $booking->number_of_persons }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Contact:</div>
                    <div class="detail-value">{{ $booking->contact }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $booking->email }}</div>
                </div>
                @if($booking->notes)
                <div class="detail-row">
                    <div class="detail-label">Special Notes:</div>
                    <div class="detail-value">{{ $booking->notes }}</div>
                </div>
                @endif
                <div class="detail-row">
                    <div class="detail-label">Booking Date:</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M j, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
            
            <p style="margin-bottom: 0;">We've received your booking request and will process it shortly. You'll receive another email once your booking is confirmed.</p>
        </div>
    </div>
</body>
</html>