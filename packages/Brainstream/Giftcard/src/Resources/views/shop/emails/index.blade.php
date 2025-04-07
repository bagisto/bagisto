<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('giftcard::app.giftcard.giftcard-details')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 8px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .body {
            padding: 20px;
            line-height: 1.6;
        }
        .details-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .details-table th, .details-table td {
            padding: 10px;
            border: 1px solid #e4e4e4;
            text-align: left;
        }
        .details-table th {
            background-color: #f9f9f9;
        }
        .details-table td {
            background-color: #ffffff;
        }
        .footer {
            background-color: #007bff;
            color: #ffffff;
            padding: 6px;
            text-align: center;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .occasion-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            margin-top: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>@lang('giftcard::app.giftcard.giftcard-details')</h1>
        </div>
        <div class="body">
            <p>Hey,</p>
            <p>Congratulations! You have received a gift card from {{ $name }}</p>
            <p>Thank you for using our service. Below are your gift card details:</p>

            <p><strong>Gift Card Number:</strong> {{ $giftcard_number }}</p>
            <p><strong>Sender Name:</strong> {{ $sendername }}</p>
            <p><strong>Message:</strong> {{ $personal_message }}</p>

            <img src="{{ $message->embed(public_path().'/vendor/giftcard/assets/images/'.$occasion_image) }}" alt="Occasion Image" class="occasion-image">
            
            <p>{{ $occasionGreeting }}</p>

            <table class="details-table">
                <tr>
                    <th>Balance</th>
                    <th>Expires on</th>
                </tr>
                <tr>
                    <td>${{ $amount }}</td>
                    <td>1 YEAR FROM TODAY</td>
                </tr>
            </table>
            
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>