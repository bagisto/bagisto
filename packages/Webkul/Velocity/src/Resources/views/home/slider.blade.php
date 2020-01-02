<div class="slides-container">
    <carousel-component
        :slides-count="{{ ! empty($sliderData) ? sizeof($sliderData) : 1 }}"
        slides-per-page="1"
        navigation-enabled="hide">

        @if (! empty($sliderData))
            @foreach ($sliderData as $index => $slider)

                <slide slot="slide-{{ $index }}">
                    <img
                        class="col-12 no-padding banner-icon"
                        src="{{ url()->to('/') . '/storage/' . $slider['path'] }}" />
                </slide>

            @endforeach
        @else
            <slide slot="slide-0">
                <img
                    class="col-12 no-padding banner-icon"
                    src="{{ asset('themes/velocity/assets/images/banner.png') }}" />
            </slide>
        @endif

    </carousel-component>
</div>