@extends('admin::layouts.content')

@section('page_title')
    {{ __('webfont::app.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('webfont::app.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.cms.webfont.add') }}" class="btn btn-lg btn-primary">
                    {{ __('webfont::app.add') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('webfontGrid', 'Webkul\Webfont\DataGrids\WebfontDataGrid')

            {!! $webfontGrid->render() !!}
        </div>
    </div>
@stop