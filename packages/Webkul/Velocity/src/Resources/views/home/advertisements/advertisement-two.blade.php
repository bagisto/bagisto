@php
    $isRendered = false;
    $advertisementTwo = null;
@endphp

@if ($velocityMetaData->advertisement)
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
                <a class="col-lg-9 col-md-12 no-padding">
                    <img src="{{ asset('/storage/' . $advertisementTwo['image_1']) }}" />
                </a>

                <a class="col-lg-3 col-md-12 pr0">
                    <img src="{{ asset('/storage/' . $advertisementTwo['image_2']) }}" />
                </a>
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