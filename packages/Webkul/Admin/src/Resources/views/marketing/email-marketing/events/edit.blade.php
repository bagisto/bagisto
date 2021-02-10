@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.marketing.events.edit-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.events.update', $event->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = history.length > 1 ? document.referrer : '{{ route('admin.dashboard.index') }}'"></i>

                        {{ __('admin::app.marketing.events.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.marketing.events.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    {!! view_render_event('bagisto.admin.marketing.events.create.before') !!}

                    <accordian :title="'{{ __('admin::app.marketing.events.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.marketing.events.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') ?: $event->name }}" data-vv-as="&quot;{{ __('admin::app.marketing.events.name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                <label for="description" class="required">{{ __('admin::app.marketing.events.description') }}</label>
                                <textarea v-validate="'required'" class="control" id="description" name="description" data-vv-as="&quot;{{ __('admin::app.marketing.events.description') }}&quot;">{{ old('description') ?: $event->description }}</textarea>
                                <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                            </div>

                            <div class="control-group date" :class="[errors.has('date') ? 'has-error' : '']">
                                <label for="date" class="required">{{ __('admin::app.marketing.events.date') }}</label>
                                <date>
                                    <input type="text" name="date" class="control" v-validate="'required'" value="{{ old('date') ?: $event->date }}" data-vv-as="&quot;{{ __('admin::app.marketing.events.date') }}&quot;">
                                </date>
                                <span class="control-error" v-if="errors.has('date')">@{{ errors.first('date') }}</span>
                            </div>

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.marketing.events.create.after') !!}

                </div>
            </div>
        </form>
    </div>
@stop