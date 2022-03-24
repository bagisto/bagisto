@php
    $isRendered = false;
    $advertisementOne = null;
@endphp

@if ($velocityMetaData && $velocityMetaData->advertisement)
    @php
        $advertisement = json_decode($velocityMetaData->advertisement, true);
        if (isset($advertisement[1])) {
            $advertisementOne = $advertisement[1];
        }
    @endphp

    @if ($advertisementOne)
        @php
            $isRendered = true;
        @endphp

        <div class="container-fluid advertisement-one">
            <div class="row">
            <div
                class="col offers-lt-panel bg-image"
                style="background-image: url('{{ Storage::url($advertisementOne['image_1']) }}')">
            </div>

                <div class="col offers-ct-panel">

                    <div class="row pb10">
                        <div
                            class="col-12 offers-ct-top"
                            style="background-image: url('{{ Storage::url($advertisementOne['image_2']) }}')">
                        </div>
                    </div>

                    <div class="row">
                        <div
                            class="col-12 offers-ct-bottom"
                            style="background-image: url('{{ Storage::url($advertisementOne['image_3']) }}')">
                        </div>
                    </div>

                </div>

                <div
                    class="col offers-rt-panel"
                    style="background-image: url('{{ Storage::url($advertisementOne['image_4']) }}')">
                </div>
            </div>
        </div>
    @endif
@endif

@if (! $isRendered)
    <div class="container-fluid advertisement-one">
        <div class="row">
            <div class="col offers-lt-panel bg-image"></div>

            <div class="col offers-ct-panel">

                <div class="row pb10">
                    <div class="col-12 offers-ct-top"></div>
                </div>

                <div class="row">
                    <div class="col-12 offers-ct-bottom"></div>
                </div>

            </div>

            <div class="col offers-rt-panel"></div>
        </div>
    </div>
@endif