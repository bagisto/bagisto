@extends('admin::layouts.content')

@section('page_title')
    {{ __('customerdocument::app.admin.documents.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('customerdocument::app.admin.documents.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.documents.create') }}" class="btn btn-lg btn-primary">
                    {{ __('customerdocument::app.admin.documents.add-document') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('customerDocumentGrid','Webkul\CustomerDocument\DataGrids\CustomerDocumentDataGrid')

            {!! $customerDocumentGrid->render() !!}
        </div>
    </div>

@stop

