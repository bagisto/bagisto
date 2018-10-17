@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.tax-rates.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.configuration.tax-rates.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.tax-rates.show') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.configuration.tax-rates.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('taxrates', 'Webkul\Admin\DataGrids\TaxRateDataGrid')
            {!! $taxrates->render() !!}
        </div>
    </div>
@endsection