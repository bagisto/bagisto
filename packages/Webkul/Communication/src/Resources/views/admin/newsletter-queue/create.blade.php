@extends('communication::admin.layouts.master')

@section('page_title')
    {{ __('communication::app.newsletter-queue.queue-information') }}
@stop

@section('content')

    <div class="content full-page dashboard">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('communication::app.newsletter-queue.queue-information') }}</h1>
            </div>

            <div class="page-action">
                <a href="javascript:void(0);" class="btn btn-lg btn-primary" onclick="document.queue_form.submit();">
                    {{ __('communication::app.newsletter-queue.queue-form.save-queue') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                <form name="queue_form" method="POST" action="{{ route('communication.newsletter-queue.store', $newsletterTemplate->id) }}">
                    @csrf

                    {{-- queue date --}}
                    <div class="control-group" :class="[errors.has('queue_datetime') ? 'has-error' : '']">
                        <label for="queue-datetime" class="required">{{ __('communication::app.newsletter-queue.queue-form.queue-date') }}</label>
                        <input type="date" class="control" id="queue-datetime" name="queue_datetime" v-validate="" value="{{ old('queue_datetime') }}" data-vv-as="&quot;{{ __('communication::app.newsletter-queue.queue-form.queue-date') }}&quot;">
                        <span class="control-error" v-if="errors.has('queue_datetime')">@{{ errors.first('queue_datetime') }}</span>
                    </div>

                    {{-- subject --}}
                    <div class="control-group" :class="[errors.has('subject') ? 'has-error' : '']">
                        <label for="subject" class="required">{{ __('communication::app.newsletter-queue.queue-form.subject') }}</label>
                        <input type="text" class="control" id="subject" name="subject" v-validate="'required'" value="{{ old('subject') ?: $newsletterTemplate->template_subject }}" data-vv-as="&quot;{{ __('communication::app.newsletter-queue.queue-form.subject') }}&quot;">
                        <span class="control-error" v-if="errors.has('subject')">@{{ errors.first('subject') }}</span>
                    </div>

                    {{-- sender name --}}
                    <div class="control-group" :class="[errors.has('sender_name') ? 'has-error' : '']">
                        <label for="sender-name" class="required">{{ __('communication::app.newsletter-queue.queue-form.sender-name') }}</label>
                        <input type="text" class="control" id="sender-name" name="sender_name" v-validate="'required'" value="{{ old('sender_name') ?: $newsletterTemplate->sender_name }}" data-vv-as="&quot;{{ __('communication::app.newsletter-queue.queue-form.sender-name') }}&quot;">
                        <span class="control-error" v-if="errors.has('sender_name')">@{{ errors.first('sender_name') }}</span>
                    </div>

                    {{-- sender email --}}
                    <div class="control-group" :class="[errors.has('sender_email') ? 'has-error' : '']">
                        <label for="sender-email" class="required">{{ __('communication::app.newsletter-queue.queue-form.sender-email') }}</label>
                        <input type="text" class="control" id="sender-email" name="sender_email" v-validate="'required'" value="{{ old('sender_email') ?: $newsletterTemplate->sender_email }}" data-vv-as="&quot;{{ __('communication::app.newsletter-queue.queue-form.sender-email') }}&quot;">
                        <span class="control-error" v-if="errors.has('sender_email')">@{{ errors.first('sender_email') }}</span>
                    </div>

                    {{-- content --}}
                    <div class="control-group" :class="[errors.has('content') ? 'has-error' : '']">
                        <label for="content" class="required">{{ __('communication::app.newsletter-queue.queue-form.content') }}</label>
                        <textarea type="text" class="control" id="content" name="content" v-validate="'required'" data-vv-as="&quot;{{ __('communication::app.newsletter-queue.queue-form.content') }}&quot;">{{ old('content') ?: $newsletterTemplate->template_content }}</textarea>
                        <span class="control-error" v-if="errors.has('content')">@{{ errors.first('content') }}</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop