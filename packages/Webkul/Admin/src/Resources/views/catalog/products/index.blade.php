@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.title') }}
@stop

@section('content')
    <div class="content" style="height: 100%;">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.products.title') }}</h1>
            </div>

            <div class="page-action">
                <div class="export-import" @click="showModal('downloadDataGrid')">
                    <i class="export-icon"></i>

                    <span>
                        {{ __('admin::app.export.export') }}
                    </span>
                </div>
                
                {!! view_render_event('bagisto.admin.catalog.products.create.before') !!}

                @if (bouncer()->hasPermission('catalog.products.create'))
                    <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.add-product-btn-title') }}
                    </a>
                @endif

                {!! view_render_event('bagisto.admin.catalog.products.create.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.catalog.products.index') }}"></datagrid-plus>
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
    @include('admin::export.export', ['gridName' => app('Webkul\Admin\DataGrids\ProductDataGrid')])

    <script>
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);

            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }

        function showEditQuantityForm(productId) {
            $(`#product-${productId}-quantity`).hide();

            $(`#edit-product-${productId}-quantity-form-block`).show();
        }

        function cancelEditQuantityForm(productId) {
            $(`#edit-product-${productId}-quantity-form-block`).hide();

            $(`#product-${productId}-quantity`).show();
        }

        function saveEditQuantityForm(updateSource, productId) {
            let quantityFormData = $(`#edit-product-${productId}-quantity-form`).serialize();

            axios
                .post(updateSource, quantityFormData)
                .then(function (response) {
                    let data = response.data;

                    $(`#inventoryErrors${productId}`).text('');

                    $(`#edit-product-${productId}-quantity-form-block`).hide();

                    $(`#product-${productId}-quantity-anchor`).text(data.updatedTotal);

                    $(`#product-${productId}-quantity`).show();
                })
                .catch(function ({ response }) {
                    let { data } = response;

                    $(`#inventoryErrors${productId}`).text(data.message);
                });
        }
    </script>
@endpush
