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
                <div class="col ad-2-lt"></div>

                <div class="col ad-2-rt">

                    <div class="row top-container">
                        <div class="col"></div>
                    </div>

                    <div class="row bottom-container">
                        <div class="col"></div>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-two-container">

        <div class="row">
            <div class="col ad-2-lt"></div>

            <div class="col ad-2-rt">

                <div class="row top-container">
                    <div class="col"></div>
                </div>

                <div class="row bottom-container">
                    <div class="col"></div>
                </div>

            </div>
        </div>
    </div>
@endif