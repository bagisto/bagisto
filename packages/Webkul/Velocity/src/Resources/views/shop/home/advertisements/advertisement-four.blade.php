@php
    $isRendered = false;
    $advertisementFour = null;
@endphp

@if ($velocityMetaData && $velocityMetaData->advertisement)
    @php
        $advertisement = json_decode($velocityMetaData->advertisement, true);
        
        if (isset($advertisement[4]) && is_array($advertisement[4])) {
            $advertisementFour = array_values(array_filter($advertisement[4]));
        }
    @endphp

    @if ($advertisementFour)
        @php
            $isRendered = true;
        @endphp

        <div class="container-fluid advertisement-four-container">
            <div class="row">
                @if ( isset($advertisementFour[0]))
                    <a @if (isset($one)) href="{{ $one }}" @endif class="col-lg-4 col-12 no-padding" aria-label="Advertisement">
                        <img class="col-12 lazyload" data-src="{{ asset('/storage/' . $advertisementFour[0]) }}" alt="" />
                    </a>
                @endif

                <div class="col-lg-4 col-12 offers-ct-panel">
                    @if ( isset($advertisementFour[1]))
                        <a @if (isset($two)) href="{{ $two }}" @endif class="row col-12 remove-padding-margin" aria-label="Advertisement">
                            <img class="col-12 offers-ct-top lazyload" data-src="{{ asset('/storage/' . $advertisementFour[1]) }}" alt="" />
                        </a>
                    @endif

                    @if ( isset($advertisementFour[2]))
                        <a @if (isset($three)) href="{{ $three }}" @endif class="row col-12 remove-padding-margin" aria-label="Advertisement">
                            <img class="col-12 offers-ct-bottom lazyload" data-src="{{ asset('/storage/' . $advertisementFour[2]) }}" alt="" />
                        </a>
                    @endif
                </div>

                @if ( isset($advertisementFour[3]))
                    <a @if (isset($four)) href="{{ $four }}" @endif class="col-lg-4 col-12 no-padding" aria-label="Advertisement">
                        <img class="col-12 lazyload" data-src="{{ asset('/storage/' . $advertisementFour[3]) }}" alt="" />
                    </a>
                @endif
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-four-container">
        <div class="row">
            <a @if (isset($one)) href="{{ $one }}" @endif class="col-lg-4 col-12 no-padding" aria-label="Advertisement">
                <img class="col-12 lazyload" data-src="{{ asset('/themes/velocity/assets/images/big-sale-banner.webp') }}" alt="" />
            </a>

            <div class="col-lg-4 col-12 offers-ct-panel">
                <a @if (isset($two)) href="{{ $two }}" @endif class="row col-12 remove-padding-margin" aria-label="Advertisement">
                    <img class="col-12 offers-ct-top lazyload" data-src="{{ asset('/themes/velocity/assets/images/seasons.webp') }}" alt="" />
                </a>
                <a @if (isset($three)) href="{{ $three }}" @endif class="row col-12 remove-padding-margin" aria-label="Advertisement">
                    <img class="col-12 offers-ct-bottom lazyload" data-src="{{ asset('/themes/velocity/assets/images/deals.webp') }}" alt="" />
                </a>
            </div>

            <a @if (isset($four)) href="{{ $four }}" @endif class="col-lg-4 col-12 no-padding" aria-label="Advertisement">
                <img class="col-12 lazyload" data-src="{{ asset('/themes/velocity/assets/images/kids.webp') }}" alt="" />
            </a>
        </div>
    </div>
@endif