@extends('admin::layouts.content')

@section('page_title')
    {{ __('communication::app.newsletter-templates.new-template') }}
@stop

@section('content')

    <div class="content full-page dashboard">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('communication::app.newsletter-templates.new-template') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('communication.newsletter-templates.create') }}" class="btn btn-lg btn-primary">
                    {{ __('communication::app.newsletter-templates.new-template-form.save-template') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                <form method="POST" action="">
                    @csrf

                    {{-- template name --}}
                    <div class="control-group" :class="[errors.has('template_name') ? 'has-error' : '']">
                        <label for="template-name" class="required">{{ __('communication::app.newsletter-templates.new-template-form.template-name') }}</label>
                        <input type="text" class="control" id="template-name" name="template_name" v-validate="'required'" value="{{ old('template_name') }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.new-template-form.template-name') }}&quot;">
                        <span class="control-error" v-if="errors.has('template_name')">@{{ errors.first('template_name') }}</span>
                    </div>

                    {{-- template subject --}}
                    <div class="control-group" :class="[errors.has('template_subject') ? 'has-error' : '']">
                        <label for="template-subject" class="required">{{ __('communication::app.newsletter-templates.new-template-form.template-subject') }}</label>
                        <input type="text" class="control" id="template-subject" name="template_subject" v-validate="'required'" value="{{ old('template_subject') }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.new-template-form.template-subject') }}&quot;">
                        <span class="control-error" v-if="errors.has('template_subject')">@{{ errors.first('template_subject') }}</span>
                    </div>

                    {{-- sender name --}}
                    <div class="control-group" :class="[errors.has('sender_name') ? 'has-error' : '']">
                        <label for="sender-name" class="required">{{ __('communication::app.newsletter-templates.new-template-form.sender-name') }}</label>
                        <input type="text" class="control" id="sender-name" name="sender_name" v-validate="'required'" value="{{ old('sender_name') }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.new-template-form.sender-name') }}&quot;">
                        <span class="control-error" v-if="errors.has('sender_name')">@{{ errors.first('sender_name') }}</span>
                    </div>

                    {{-- sender email --}}
                    <div class="control-group" :class="[errors.has('sender_email') ? 'has-error' : '']">
                        <label for="sender-email" class="required">{{ __('communication::app.newsletter-templates.new-template-form.sender-email') }}</label>
                        <input type="text" class="control" id="sender-email" name="sender_email" v-validate="'required'" value="{{ old('sender_email') }}" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.new-template-form.sender-email') }}&quot;">
                        <span class="control-error" v-if="errors.has('sender_email')">@{{ errors.first('sender_email') }}</span>
                    </div>

                    {{-- template content --}}
                    <div class="control-group" :class="[errors.has('template_content') ? 'has-error' : '']">
                        <label for="template-content" class="required">{{ __('communication::app.newsletter-templates.new-template-form.template-content') }}</label>
                        <textarea type="text" class="control" id="template-content" name="template_content" v-validate="'required'" data-vv-as="&quot;{{ __('communication::app.newsletter-templates.new-template-form.template-content') }}&quot;">
                            {{ old('template_content') }}
                        </textarea>
                        <span class="control-error" v-if="errors.has('template_content')">@{{ errors.first('template_content') }}</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop