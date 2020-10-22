@php
    $isRendered = false;
    $advertisementTwo = null;
@endphp

@if ($velocityMetaData && $velocityMetaData->advertisement)
    @php
        $advertisement = json_decode($velocityMetaData->advertisement, true);
        
        if (isset($advertisement[2]) && is_array($advertisement[2])) {
            $advertisementTwo = array_values(array_filter($advertisement[2]));
        }
    @endphp

    @if ($advertisementTwo)
        @php
            $isRendered = true;
        @endphp

        <div class="container-fluid advertisement-two-container">
            <div class="row">
                @if ( isset($advertisementTwo[0]))
                    <a class="col-lg-9 col-md-12 no-padding">
                        <img data-src="{{ asset('/storage/' . $advertisementTwo[0]) }}" class="lazyload" alt="" />
                    </a>
                @endif
                
                @if ( isset($advertisementTwo[1]))
                    <a class="col-lg-3 col-md-12 pr0">
                        <img data-src="{{ asset('/storage/' . $advertisementTwo[1]) }}" class="lazyload" alt="" />
                    </a>
                @endif
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-two-container">
        <div class="row">
            <a class="col-lg-9 col-md-12 no-padding">
                <img data-src="{{ asset('/themes/velocity/assets/images/toster.webp') }}" class="lazyload" alt="" />
            </a>

            <a class="col-lg-3 col-md-12 pr0">
                <img data-src="{{ asset('/themes/velocity/assets/images/trimmer.webp') }}" class="lazyload" alt="" />
            </a>
        </div>
    </div>
@endif