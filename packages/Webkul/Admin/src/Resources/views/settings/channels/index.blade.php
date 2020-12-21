@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.channels.title') }}</h1>

                <div class="control-group">
                    @php $locale = request()->get('locale') ?: app()->getLocale(); @endphp

                    <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                        @foreach (core()->getAllLocales() as $localeModel)

                            <option value="{{ route('admin.channels.index', ['locale' => $localeModel->code]) }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                {{ $localeModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.channels.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.channels.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('channels','Webkul\Admin\DataGrids\ChannelDataGrid')
            {!! $channels->render() !!}
        </div>
    </div>
@stop