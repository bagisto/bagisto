@extends('saas::companies.layouts.master')

@section('page_title')
    Companies
@stop

@section('content-wrapper')
    <div class="content mt-50">
        <div class="page-header">
            <div class="page-title">
                <h1>Companies</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('companies', 'Webkul\SAASCustomizer\DataGrids\CompaniesDataGrid')
            {!! $companies->render() !!}
        </div>
    </div>
@stop