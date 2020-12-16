@extends('admin::layouts.content')

@section('page_title')
    {{ __('communication::app.newsletter-queue.newsletter-queue') }}
@stop

@section('content')

    <div class="content full-page dashboard">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('communication::app.newsletter-queue.newsletter-queue') }}</h1>
            </div>

            <div class="page-action"></div>
        </div>

        <div class="page-content">
            @inject('newsletterQueueDataGrid','Webkul\Communication\Datagrids\NewsletterQueueDataGrid')

            {!! $newsletterQueueDataGrid->render() !!}
        </div>
    </div>

@stop