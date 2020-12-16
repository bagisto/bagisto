@extends('admin::layouts.content')

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
                    <div class="control-group" :class="[errors.has('queue_date') ? 'has-error' : '']">
                        <label for="queue-date">{{ __('communication::app.newsletter-queue.queue-form.queue-date') }}</label>
                        <input type="date" class="control" id="queue-date" name="queue_date" v-validate="" value="{{ old('queue_date') }}" data-vv-as="&quot;{{ __('communication::app.newsletter-queue.queue-form.queue-date') }}&quot;">
                        <span class="control-error" v-if="errors.has('queue_date')">@{{ errors.first('queue_date') }}</span>
                    </div>

                    {{-- template name --}}
                    <div class="control-group" :class="[errors.has('template_name') ? 'has-error' : '']">
                        <label for="template-name" class="required">{{ __('communication::app.newsletter-templates.template-form.template-name') }}</label>
                        <input type="text" class="control" id="template-name" name="template_name" v-validate="'required'" value="{{ old('template_name') ?: $newsletterTemplate->template_name }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.template-form.template-name') }}&quot;">
                        <span class="control-error" v-if="errors.has('template_name')">@{{ errors.first('template_name') }}</span>
                    </div>

                    {{-- template subject --}}
                    <div class="control-group" :class="[errors.has('template_subject') ? 'has-error' : '']">
                        <label for="template-subject" class="required">{{ __('communication::app.newsletter-templates.template-form.template-subject') }}</label>
                        <input type="text" class="control" id="template-subject" name="template_subject" v-validate="'required'" value="{{ old('template_subject') ?: $newsletterTemplate->template_subject }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.template-form.template-subject') }}&quot;">
                        <span class="control-error" v-if="errors.has('template_subject')">@{{ errors.first('template_subject') }}</span>
                    </div>

                    {{-- sender name --}}
                    <div class="control-group" :class="[errors.has('sender_name') ? 'has-error' : '']">
                        <label for="sender-name" class="required">{{ __('communication::app.newsletter-templates.template-form.sender-name') }}</label>
                        <input type="text" class="control" id="sender-name" name="sender_name" v-validate="'required'" value="{{ old('sender_name') ?: $newsletterTemplate->sender_name }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.template-form.sender-name') }}&quot;">
                        <span class="control-error" v-if="errors.has('sender_name')">@{{ errors.first('sender_name') }}</span>
                    </div>

                    {{-- sender email --}}
                    <div class="control-group" :class="[errors.has('sender_email') ? 'has-error' : '']">
                        <label for="sender-email" class="required">{{ __('communication::app.newsletter-templates.template-form.sender-email') }}</label>
                        <input type="text" class="control" id="sender-email" name="sender_email" v-validate="'required'" value="{{ old('sender_email') ?: $newsletterTemplate->sender_email }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.template-form.sender-email') }}&quot;">
                        <span class="control-error" v-if="errors.has('sender_email')">@{{ errors.first('sender_email') }}</span>
                    </div>

                    {{-- template content --}}
                    <div class="control-group" :class="[errors.has('template_content') ? 'has-error' : '']">
                        <label for="template-content" class="required">{{ __('communication::app.newsletter-templates.template-form.template-content') }}</label>
                        <textarea type="text" class="control" id="template-content" name="template_content" v-validate="'required'" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.template-form.template-content') }}&quot;">{{ old('template_content') ?: $newsletterTemplate->template_content }}</textarea>
                        <span class="control-error" v-if="errors.has('template_content')">@{{ errors.first('template_content') }}</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop