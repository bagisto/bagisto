<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang("admin::app.errors.{$errorCode}.title") - RAM Plaza</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            min-height: 100vh;
            background: linear-gradient(180deg, #1E252B 0%, #262D34 50%, #1E252B 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .error-code {
            font-size: clamp(100px, 20vw, 200px);
            font-weight: 800;
            background: linear-gradient(135deg, #ff3e9a 0%, #ff66b6 50%, #ff3e9a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 40px rgba(255, 62, 154, 0.3));
            line-height: 1;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.75rem;
            text-align: center;
        }
        .error-desc {
            color: #9ca3af;
            font-size: 0.95rem;
            max-width: 400px;
            margin: 0 auto 2rem;
            text-align: center;
            line-height: 1.6;
        }
        .btn {
            background: linear-gradient(135deg, #ff3e9a 0%, #ff66b6 100%);
            color: #ffffff;
            padding: 0.875rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 62, 154, 0.35);
        }
        .footer {
            margin-top: 3rem;
            color: #4b5563;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="error-code">{{ $errorCode }}</div>
    <h1 class="error-title">@lang("admin::app.errors.{$errorCode}.title")</h1>
    <p class="error-desc">
        {{ $errorCode === 503 && core()->getCurrentChannel()->maintenance_mode_text != "" ? core()->getCurrentChannel()->maintenance_mode_text : trans("admin::app.errors.{$errorCode}.description") }}
    </p>
    <a href="{{ route('shop.home.index') }}" class="btn">@lang('shop::app.errors.go-to-home')</a>
    <div class="footer">RAM Plaza</div>
</body>
</html>
