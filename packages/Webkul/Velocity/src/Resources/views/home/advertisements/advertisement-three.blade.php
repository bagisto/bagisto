@php
    $isRendered = false;
    $advertisementThree = null;
@endphp

@if ($velocityMetaData->advertisement)
    @php
        $advertisement = json_decode($velocityMetaData->advertisement, true);
        if (isset($advertisement[3])) {
            $advertisementThree = $advertisement[3];
        }
    @endphp

    @if ($advertisementThree)
        @php
            $isRendered = true;
        @endphp

        <div class="container-fluid advertisement-three-container">

            <div class="row">
                <div class="col-8 ad-3-lt bg-image no-padding"></div>

                <div class="col-4 pr0">
                    <div class="ad-3-rt bg-image no-padding"></div>
                </div>
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-three-container">

        <div class="row">
            <div class="col-8 ad-3-lt bg-image no-padding"></div>

            <div class="col-4 pr0">
                <div class="ad-3-rt bg-image no-padding"></div>
            </div>
        </div>
    </div>
@endif