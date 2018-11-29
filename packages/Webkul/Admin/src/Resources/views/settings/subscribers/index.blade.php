@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.subscribers.title') }}
@stop

@section('content')


    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.subscribers.title') }}</h1>
            </div>

            {{-- <div class="page-action">
                <a href="{{ route('admin.subscribers.store') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.subscribers.add-title') }}
                </a>
            </div> --}}
        </div>

        <div class="page-content">
            @inject('subscribers','Webkul\Admin\DataGrids\NewsLetterDataGrid')
            {!! $subscribers->render() !!}
        </div>
    </div>
@stop