@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.sliders.title') }}
@stop

@section('content')

    <div class="content">

      <?php $locale = request()->get('locale') ?: null; ?>
      <?php $channel = request()->get('channel') ?: null; ?>

        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.sliders.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.sliders.store') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.sliders.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('sliders','Webkul\Admin\DataGrids\SliderDataGrid')
            {!! $sliders->render() !!}
        </div>
    </div>
@stop


@push('scripts')
    <script>

        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }

    </script>
@endpush