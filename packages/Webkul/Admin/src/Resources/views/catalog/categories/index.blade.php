@php($locale = core()->getRequestedLocaleCode())

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
        $(document).ready(function(){
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
                    type : 'POST',
                    url : '{{ route("admin.catalog.categories.product.count") }}',
                    data : {
                        _token: '{{csrf_token()}}',
                        indexes: indexes
                    },
                    success:function(data) {
                        $("input[type='checkbox']").attr('disabled', false);
                        if (data.product_count > 0) {
                            let message = "{{ trans('ui::app.datagrid.massaction.delete-category-product') }}";

                            if (type == 'delete') {
                                doAction(e, message);
                            } else {
                                $('form').attr('onsubmit', 'return confirm("'+message+'")');
                            }
                        } else {
                            let message = "{{ __('ui::app.datagrid.click_on_action') }}";

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

        /**
         * Do action function. Not directly calling the datagrid components.
         * Instead taking a copy and using in this scope.
         */
        function doAction (e, message, type) {
            let element = e.currentTarget;

            if (message) {
                element = e.target.parentElement;
            }

            message = message || '{{ __('ui::app.datagrid.massaction.delete') }}';

            if (confirm(message)) {
                axios.post(element.getAttribute('data-action'), {
                    _token: element.getAttribute('data-token'),
                    _method: element.getAttribute('data-method')
                }).then(function (response) {
                    this.result = response;

                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    } else {
                        location.reload();
                    }
                }).catch(function (error) {
                    location.reload();
                });

                e.preventDefault();
            } else {
                e.preventDefault();
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