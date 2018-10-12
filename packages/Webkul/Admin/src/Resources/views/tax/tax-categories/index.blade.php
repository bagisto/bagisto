@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.configuration.tax.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.tax-categories.show') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.configuration.tax.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('taxCategories','Webkul\Admin\DataGrids\TaxRuleDataGrid')
            {!! $taxCategories->render() !!}
        </div>
    </div>
@stop