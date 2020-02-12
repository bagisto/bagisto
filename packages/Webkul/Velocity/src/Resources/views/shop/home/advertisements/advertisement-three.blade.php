@php
    $isRendered = false;
    $advertisementThree = null;
@endphp

@if ($velocityMetaData && $velocityMetaData->advertisement)
    @php
        $advertisement = json_decode($velocityMetaData->advertisement, true);
        
        if (isset($advertisement[3]) && is_array($advertisement[3])) {
            $advertisementThree = array_values(array_filter($advertisement[3]));
        }
    @endphp

    @if ($advertisementThree)
        @php
            $isRendered = true;
        @endphp

        <div class="container-fluid advertisement-three-container">
            <div class="row">
                @if ( isset($advertisementThree[0]))
                    <a @if (isset($one)) href="{{ $one }}" @endif class="col-lg-6 col-md-12 no-padding">
                        <img src="{{ asset('/storage/' . $advertisementThree[0]) }}" class="full-width" />
                    </a>
                @endif

                <div class="col-lg-6 col-md-12 second-panel">
                    @if ( isset($advertisementThree[1]))
                        <a @if (isset($two)) href="{{ $two }}" @endif class="row top-container">
                            <img src="{{ asset('/storage/' . $advertisementThree[1]) }}" class="col-12 pr0" />
                        </a>
                    @endif
                    @if ( isset($advertisementThree[2]))
                        <a @if (isset($three)) href="{{ $three }}" @endif class="row bottom-container">
                            <img src="{{ asset('/storage/' . $advertisementThree[2]) }}" class="col-12 pr0" />
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-three-container">
        <div class="row">
            <a @if (isset($one)) href="{{ $one }}" @endif class="col-lg-6 col-md-12 no-padding">
                <img src="{{ asset('/themes/velocity/assets/images/headphones.png') }}" class="full-width" />
            </a>

            <div class="col-lg-6 col-md-12 second-panel">
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