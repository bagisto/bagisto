<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ __('payu::app.redirect.redirecting') }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        h2 {
            color: #333;
            font-size: 24px;
            font-weight: 600;
            margin: 20px 0 10px;
        }
        
        p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin: 10px 0;
        }
        
        .secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            color: #166534;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin: 20px 0;
        }
        
        .secure-badge::before {
            content: "✓";
            display: inline-block;
            width: 18px;
            height: 18px;
            background: #22c55e;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 18px;
            font-size: 12px;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        {!! view_render_event('bagisto.shop.payu.redirect.before') !!}

        <div class="spinner"></div>

        <h2>{{ __('payu::app.redirect.redirecting-to-payment') }}</h2>
        
        <p>{{ __('payu::app.redirect.please-wait') }}</p>

        <div class="secure-badge">
            {{ __('payu::app.redirect.secure-payment') }}
        </div>

        <p style="color: #999; font-size: 12px;">
            {{ __('payu::app.redirect.redirect-message') }}
        </p>

        {!! view_render_event('bagisto.shop.payu.redirect.after') !!}
    </div>

    <form action="{{ $paymentUrl }}" id="payu_payment_form" method="POST" style="display: none;">
        @foreach ($paymentData as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
    </form>

    <script>
        setTimeout(function() {
            document.getElementById('payu_payment_form').submit();
        }, 100);
    </script>
</body>

</html>