<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('cashfree::app.redirect.title')</title>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top-color: #4f46e5;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h1 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        p {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="spinner"></div>
        <h1>@lang('cashfree::app.redirect.connecting')</h1>
        <p>@lang('cashfree::app.redirect.do-not-refresh')</p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cashfree = Cashfree({
                mode: "{{ $sandbox ? 'sandbox' : 'production' }}"
            });

            cashfree.checkout({
                paymentSessionId: "{{ $paymentSessionId }}",
                redirectTarget: "_self"
            });
        });
    </script>
</body>
</html>
