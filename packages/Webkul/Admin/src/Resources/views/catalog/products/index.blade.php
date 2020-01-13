@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.title') }}
@stop

@section('content')
    <div class="content" style="height: 100%;">
        <?php $locale = request()->get('locale') ?: null; ?>
        <?php $channel = request()->get('channel') ?: null; ?>
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.products.title') }}</h1>

                <div class="control-group">
                    <select class="control" id="channel-switcher" name="channel" onchange="reloadPage('channel', this.value)" >
                        <option value="all" {{ ! isset($channel) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-channels') }}
                        </option>

                        @foreach (core()->getAllChannels() as $channelModel)

                            <option
                                    value="{{ $channelModel->code }}" {{ (isset($channel) && ($channelModel->code) == $channel) ? 'selected' : '' }}>
                                {{ $channelModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="control-group">
                    <select class="control" id="locale-switcher" name="locale" onchange="reloadPage('locale', this.value)" >
                        <option value="all" {{ ! isset($locale) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-locales') }}
                        </option>

                        @foreach (core()->getAllLocales() as $localeModel)

                            <option
                                    value="{{ $localeModel->code }}" {{ (isset($locale) && ($localeModel->code) == $locale) ? 'selected' : '' }}>
                                {{ $localeModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>
            </div>

            <div class="page-action">
                <div class="export-import" @click="showModal('downloadDataGrid')">
                    <i class="export-icon"></i>
                    <span >
                        {{ __('admin::app.export.export') }}
                    </span>
                </div>

                <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.catalog.products.add-product-btn-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}

        <div class="page-content">
            @inject('products', 'Webkul\Admin\DataGrids\ProductDataGrid')
            {!! $products->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.products.list.after') !!}

    </div>

    <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
        <div slot="body">
            <export-form></export-form>
        </div>
    </modal>
@stop

@push('scripts')
    @include('admin::export.export', ['gridName' => $products])
    <script>
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            url.searchParams.set(getVar, getVal);
            window.location.href = url.href;
        }
    </script>
@endpush