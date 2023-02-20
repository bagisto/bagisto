@php
$locale = core()->getRequestedLocaleCode();
@endphp

@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.categories.title') }}</h1>
            </div>

            <div class="page-action">
                {!! view_render_event('bagisto.admin.catalog.categories.create.before') !!}
                
                @if (bouncer()->hasPermission('catalog.categories.create'))
                    <a
                        href="{{ route('admin.catalog.categories.create') }}"
                        class="btn btn-lg btn-primary"
                    >
                        {{ __('admin::app.catalog.categories.add-title') }}
                    </a>
                @endif

                {!! view_render_event('bagisto.admin.catalog.categories.create.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.before') !!}

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.catalog.categories.index') }}"></datagrid-plus>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.after') !!}
    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function() {
            $("input[type='checkbox']").change(deleteCategory);
        });

        /**
         * Delete category function. This function name is present in category datagrid.
         * So outside scope function should be loaded `onclick` rather than `v-on`.
         */
        let deleteCategory = function(e, type) {
            let indexes;

            if (type == 'delete') {
                indexes = $(e.target).parent().attr('id');
            } else {
                $("input[type='checkbox']").attr('disabled', true);

                let formData = {};
                $.each($('form').serializeArray(), function(i, field) {
                    formData[field.name] = field.value;
                });

                indexes = formData.indexes;
            }

            if (indexes) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.catalog.categories.product.count') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        indexes: indexes
                    },
                    success: function(data) {
                        $("input[type='checkbox']").attr('disabled', false);
                        if (data.product_count > 0) {
                            let message = "{{ trans('ui::app.datagrid.mass-action.delete-category-product') }}";

                            if (type == 'delete') {
                                doAction(e, message);
                            } else {
                                $('form').attr('onsubmit', 'return confirm("' + message + '")');
                            }
                        } else {
                            let message = "{{ __('ui::app.datagrid.click_on_action') }}";

                            if (type == 'delete') {
                                doAction(e, message);
                            } else {
                                $('form').attr('onsubmit', 'return confirm("' + message + '")');
                            }
                        }
                    }
                });
            } else {
                $("input[type='checkbox']").attr('disabled', false);
            }
        }

        /**
         * Reload page.
         */
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);

            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }
    </script>
@endpush
