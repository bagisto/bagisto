<div class="col-4 software-description">
    <div class="logo">
        <a href="{{ route('shop.home.index') }}">
            @if ($logo = core()->getCurrentChannel()->logo_url)
                <img
                    src="{{ $logo }}"
                    class="logo full-img" />
            @else
                <img
                    src="{{ asset('themes/velocity/assets/images/static/logo-text-white.png') }}"
                    class="logo full-img" />
            @endif

        </a>
    </div>

    <p class="clr-dark fs14">
        We love to craft softwares and solve the real world problems with the binaries. We are highly committed to our goals. We invest our resources to create world class easy to use softwares and applications for the enterprise business with the top notch, on the edge technology expertise.
    </p>
</div>