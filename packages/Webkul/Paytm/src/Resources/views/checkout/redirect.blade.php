<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ trans('paytm::app.checkout.onepage.payment.paytm.redirect') }} </title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #ffffff;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            width: 64px;
            height: 64px;
            border: 4px solid #bfdbfe;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .title {
            margin-top: 24px;
            font-size: 20px;
            font-weight: 600;
            color: #374151;
        }

        .subtitle {
            margin-top: 8px;
            font-size: 14px;
            color: #6b7280;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="spinner"></div>

    <h2 class="title">
        {{ trans('paytm::app.checkout.onepage.payment.paytm.redirecting') }}
    </h2>

    <p class="subtitle">
        {{ trans('paytm::app.checkout.onepage.payment.paytm.do-not-refresh') }}
    </p>

    <form action="{{ $paytmUrl }}" id="paytm_checkout" method="POST" class="hidden">
        <input type="submit">
        @foreach ($paytmFields as $name => $value)
            <input type="text" name="{{ $name }}" value="{{ $value }}" />
        @endforeach
    </form>
</div>

<script>
    setTimeout(function () {
        document.getElementById('paytm_checkout').submit();
    }, 500);
</script>

</body>
</html>
