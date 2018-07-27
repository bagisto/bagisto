@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.exchange_rates.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                
            </div>

            <div class="page-action">
                <a href="{{ route('admin.exchange_rates.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.exchange_rates.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">

        </div>
    </div>
@stop