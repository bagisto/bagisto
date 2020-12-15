@extends('admin::layouts.content')

@section('page_title')
    {{ __('communication::app.newsletter-templates.newsletter-templates') }}
@stop

@section('content')

    <div class="content full-page dashboard">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('communication::app.newsletter-templates.newsletter-templates') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('communication.newsletter-templates.create') }}" class="btn btn-lg btn-primary">
                    {{ __('communication::app.newsletter-templates.add-new-template') }}
                </a>
            </div>
        </div>

        <div class="page-content">
        </div>
    </div>

@stop