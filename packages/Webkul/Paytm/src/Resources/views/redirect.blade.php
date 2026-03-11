<body>
    {{ trans('paytm::app.shop.payment.redirecting') }}

    <form action="{{ $paytmUrl }}" id="paytm_checkout" method="POST">
        <input value="{{ trans('paytm::app.shop.payment.redirect-fallback') }}" type="submit">

        @foreach ($paytmFields as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
        @endforeach
    </form>

    <script type="text/javascript">
        document.getElementById('paytm_checkout').submit();
    </script>
</body>
