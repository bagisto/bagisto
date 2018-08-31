@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.configuration.tax.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.taxrule.show') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.configuration.tax.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <h2>Tax Rules DataGrid Will be here.</h2>
            <span>Render the tax rules datagrid here.</span>
        </div>
    </div>
@stop