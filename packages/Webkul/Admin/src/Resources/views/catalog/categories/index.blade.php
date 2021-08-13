@php( $locale = core()->getRequestedLocaleCode())

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
                <a href="{{ route('admin.catalog.categories.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.catalog.categories.add-title') }}
                </a>
            </div>
            <div class="control-group">
                <select class="control" id="locale-switcher" name="locale"
                        onchange="reloadPage('locale', this.value)">
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

        {!! view_render_event('bagisto.admin.catalog.categories.list.before') !!}

        <div class="page-content">
            @inject('categories', 'Webkul\Admin\DataGrids\CategoryDataGrid')
            {!! $categories->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.after') !!}
    </div>
@stop

@push('scripts')
    <script>
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }

        $(document).ready(function(){
            $("input[type='checkbox']").change(deleteFunction);
        });

        var deleteFunction = function(e,type) {
            if (type == 'delete') {
                var indexes = $(e.target).parent().attr('id');
            } else {
                $("input[type='checkbox']").attr('disabled', true);

                var formData = {};
                $.each($('form').serializeArray(), function(i, field) {
                    formData[field.name] = field.value;
                });

                var indexes = formData.indexes;
            }

            if (indexes) {
                $.ajax({
                    type : 'POST',
                    url : '{{route("admin.catalog.categories.product.count")}}',
                    data : {
                        _token: '{{csrf_token()}}',
                        indexes: indexes
                    },
                    success:function(data) {
                        $("input[type='checkbox']").attr('disabled', false);
                        if (data.product_count > 0) {
                            var message = "{{trans('ui::app.datagrid.massaction.delete-category-product')}}";
                            if (type == 'delete') {
                                doAction(e, message);
                            } else {
                                $('form').attr('onsubmit', 'return confirm("'+message+'")');
                            }
                        } else {
                            var message = "{{ __('ui::app.datagrid.click_on_action') }}";
                            if (type == 'delete') {
                                doAction(e, message);
                            } else {
                                $('form').attr('onsubmit', 'return confirm("'+message+'")');
                            }
                        }
                    }
                });
            } else {
                $("input[type='checkbox']").attr('disabled', false);
            }
        }
    </script>
@endpush