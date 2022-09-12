@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.sliders.title') }}
@stop

@section('content')
    <div class="content">
        @php
            $locale = core()->getRequestedLocaleCode('locale', false);

            $channel = core()->getRequestedChannelCode(false);
        @endphp

        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.sliders.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('settings.sliders.create'))
                    <a href="{{ route('admin.sliders.store') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.sliders.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.sliders.index') }}"></datagrid-plus>
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
