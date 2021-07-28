@if ($velocityMetaData && $velocityMetaData->slider)
    <div class="slider-container">
        <slider-component
            direction="{{ core()->getCurrentLocale()->direction }}"
            default-banner="{{ asset('/themes/velocity/assets/images/banner.webp') }}"
            :banners="{{ json_encode($sliderData) }}">

            {{-- this is default content if js is not loaded --}}
            <img class="col-12 no-padding banner-icon" src="{{ asset('/themes/velocity/assets/images/banner.webp') }}" alt=""/>

        </slider-component>
    </div>
@endif
