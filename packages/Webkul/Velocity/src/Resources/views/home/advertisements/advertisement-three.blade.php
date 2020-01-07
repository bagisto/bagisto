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
                <a @if (isset($one)) href="{{ $one }}" @endif class="col-6 no-padding">
                    <img src="{{ asset('/storage/' . $advertisementThree['image_1']) }}" class="full-width" />
                </a>

                <div class="col-6">
                    <a @if (isset($two)) href="{{ $two }}" @endif class="row top-container">
                        <img src="{{ asset('/storage/' . $advertisementThree['image_2']) }}" class="col-12 pr0" />
                    </a>
                    <a @if (isset($three)) href="{{ $three }}" @endif class="row bottom-container">
                        <img src="{{ asset('/storage/' . $advertisementThree['image_3']) }}" class="col-12 pr0" />
                    </a>
                </div>
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-three-container">
        <div class="row">
            <a @if (isset($one)) href="{{ $one }}" @endif class="col-6 no-padding">
                <img src="{{ asset('/themes/velocity/assets/images/headphones.png') }}" class="full-width" />
            </a>

            <div class="col-6">
                <a @if (isset($two)) href="{{ $two }}" @endif class="row top-container">
                    <img src="{{ asset('/themes/velocity/assets/images/watch.png') }}" class="col-12 pr0" />
                </a>
                <a @if (isset($three)) href="{{ $three }}" @endif class="row bottom-container">
                    <img src="{{ asset('/themes/velocity/assets/images/kids-2.png') }}" class="col-12 pr0" />
                </a>
            </div>
        </div>
    </div>
@endif