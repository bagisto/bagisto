@php
    $isRendered = false;
    $advertisementThree = null;
    $isLazyLoad = ! isset($lazyload) ? true : ( $lazyload ? true : false );
@endphp

@if (
    $velocityMetaData
    && $velocityMetaData->advertisement
)
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
                        <img
                            class="full-width {{ $isLazyLoad ? 'lazyload' : '' }}"
                            @if (! $isLazyLoad) src="{{ Storage::url($advertisementThree[0]) }}" @endif
                            data-src="{{ Storage::url($advertisementThree[0]) }}" alt="" />
                    </a>
                @endif

                <div class="col-lg-6 col-md-12 second-panel">
                    @if ( isset($advertisementThree[1]))
                        <a @if (isset($two)) href="{{ $two }}" @endif class="row top-container">
                            <img
                                class="col-12 pr0 {{ $isLazyLoad ? 'lazyload' : '' }}"
                                @if (! $isLazyLoad) src="{{ Storage::url($advertisementThree[1]) }}" @endif
                                data-src="{{ Storage::url($advertisementThree[1]) }}" alt="" />
                        </a>
                    @endif
                    @if ( isset($advertisementThree[2]))
                        <a @if (isset($three)) href="{{ $three }}" @endif class="row bottom-container">
                            <img
                                class="col-12 pr0 {{ $isLazyLoad ? 'lazyload' : '' }}"
                                @if (! $isLazyLoad) src="{{ Storage::url($advertisementThree[2]) }}" @endif
                                data-src="{{ Storage::url($advertisementThree[2]) }}" alt="" />
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
                <img
                    class="full-width {{ $isLazyLoad ? 'lazyload' : '' }}"
                    @if (! $isLazyLoad) src="{{ asset('/themes/velocity/assets/images/headphones.webp') }}" @endif
                    data-src="{{ asset('/themes/velocity/assets/images/headphones.webp') }}" alt="" />
            </a>

            <div class="col-lg-6 col-md-12 second-panel">
                <a @if (isset($two)) href="{{ $two }}" @endif class="row top-container">
                    <img
                        class="col-12 pr0 {{ $isLazyLoad ? 'lazyload' : '' }}"
                        @if (! $isLazyLoad) src="{{ asset('/themes/velocity/assets/images/watch.webp') }}" @endif
                        data-src="{{ asset('/themes/velocity/assets/images/watch.webp') }}" alt="" />
                </a>
                <a @if (isset($three)) href="{{ $three }}" @endif class="row bottom-container">
                    <img
                        class="col-12 pr0 {{ $isLazyLoad ? 'lazyload' : '' }}"
                        @if (! $isLazyLoad) src="{{ asset('/themes/velocity/assets/images/kids-2.webp') }}" @endif
                        data-src="{{ asset('/themes/velocity/assets/images/kids-2.webp') }}" alt="" />
                </a>
            </div>
        </div>
    </div>
@endif