@php
    $isRendered = false;
    $advertisementTwo = null;
@endphp

@if ($velocityMetaData && $velocityMetaData->advertisement)
    @php
        $advertisement = json_decode($velocityMetaData->advertisement, true);
        if (isset($advertisement[2])) {
            $advertisementTwo = $advertisement[2];
        }
    @endphp

    @if ($advertisementTwo)
        @php
            $isRendered = true;
        @endphp

        <div class="container-fluid advertisement-two-container">
            <div class="row">
                @if ( isset($advertisementTwo[1]))
                    <a class="col-lg-9 col-md-12 no-padding">
                        <img src="{{ asset('/storage/' . $advertisementTwo[1]) }}" />
                    </a>
                @endif
                
                @if ( isset($advertisementTwo[2]))
                    <a class="col-lg-3 col-md-12 pr0">
                        <img src="{{ asset('/storage/' . $advertisementTwo[2]) }}" />
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
                <img src="{{ asset('/themes/velocity/assets/images/toster.png') }}" />
            </a>

            <a class="col-lg-3 col-md-12 pr0">
                <img src="{{ asset('/themes/velocity/assets/images/trimmer.png') }}" />
            </a>
        </div>
    </div>
@endif