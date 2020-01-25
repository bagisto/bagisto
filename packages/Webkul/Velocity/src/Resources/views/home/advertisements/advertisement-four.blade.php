@php
    $isRendered = false;
    $advertisementFour = null;
@endphp

@if ($velocityMetaData->advertisement)
    @php
        $advertisement = json_decode($velocityMetaData->advertisement, true);
        if (isset($advertisement[4])) {
            $advertisementFour = $advertisement[4];
        }
    @endphp

    @if ($advertisementFour)
        @php
            $isRendered = true;
        @endphp

        <div class="container-fluid advertisement-four-container">
            <div class="row">
                <a @if (isset($one)) href="{{ $one }}" @endif class="col-lg-4 col-12 no-padding">
                    <img class="col-12" src="{{ asset('/storage/' . $advertisementFour['image_1']) }}" />
                </a>

                <div class="col-lg-4 col-12 offers-ct-panel">
                    <a @if (isset($two)) href="{{ $two }}" @endif class="row col-12 remove-padding-margin">
                        <img class="col-12 offers-ct-top" src="{{ asset('/storage/' . $advertisementFour['image_2']) }}" />
                    </a>
                    <a @if (isset($three)) href="{{ $three }}" @endif class="row col-12 remove-padding-margin">
                        <img class="col-12 offers-ct-bottom" src="{{ asset('/storage/' . $advertisementFour['image_3']) }}" />
                    </a>
                </div>

                <a @if (isset($four)) href="{{ $four }}" @endif class="col-lg-4 col-12 no-padding">
                    <img class="col-12" src="{{ asset('/storage/' . $advertisementFour['image_4']) }}" />
                </a>
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-four-container">
        <div class="row">
            <a @if (isset($one)) href="{{ $one }}" @endif class="col-lg-4 col-12 no-padding">
                <img class="col-12" src="{{ asset('/themes/velocity/assets/images/big-sale-banner.png') }}" />
            </a>

            <div class="col-lg-4 col-12 offers-ct-panel">
                <a @if (isset($two)) href="{{ $two }}" @endif class="row col-12 remove-padding-margin">
                    <img class="col-12 offers-ct-top" src="{{ asset('/themes/velocity/assets/images/seasons.png') }}" />
                </a>
                <a @if (isset($three)) href="{{ $three }}" @endif class="row col-12 remove-padding-margin">
                    <img class="col-12 offers-ct-bottom" src="{{ asset('/themes/velocity/assets/images/deals.png') }}" />
                </a>
            </div>

            <a @if (isset($four)) href="{{ $four }}" @endif class="col-lg-4 col-12 no-padding">
                <img class="col-12" src="{{ asset('/themes/velocity/assets/images/kids.png') }}" />
            </a>
        </div>
    </div>
@endif