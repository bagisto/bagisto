@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cms.pages.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" id="page-form" action="{{ route('admin.cms.edit', $page->id) }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.cms.pages.pages') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="channel-switcher" name="channel">
                            @php
                                $locale = request()->get('locale') ?: app()->getLocale();

                                $channel = request()->get('channel') ?: core()->getDefaultChannelCode();
                            @endphp

                            @foreach (core()->getAllChannels() as $channelModel)

                                <option value="{{ $channelModel->code }}" {{ ($channelModel->code) == $channel ? 'selected' : '' }}>
                                    {{ $channelModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" name="locale">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action fixed-action">
                    <button id="preview" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cms.pages.preview') }}
                    </button>

                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cms.pages.edit-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <div class="control-group" :class="[errors.has('url_key') ? 'has-error' : '']">
                        <label for="url-key" class="required">{{ __('admin::app.cms.pages.url-key') }}</label>

                        <input type="text" class="control" name="url_key" v-validate="'required'" value="{{ $page->url_key ?? old('url_key') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.url-key') }}&quot;" v-slugify>

                        <span class="control-error" v-if="errors.has('url_key')">@{{ errors.first('url_key') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('layout') ? 'has-error' : '']">
                        <label for="layout" class="required">{{ __('admin::app.cms.pages.layout') }}</label>

                        @foreach(config('cms') as $key => $value)
                            <span class="radio">
                                <input type="radio" id="layout" name="layout" value="{{ $value }}" v-validate="'required'" @if($page->layout == $value) checked @endif>

                                <label class="radio-view" for="layout"></label>
                                {{ $key }}
                            </span>
                        @endforeach

                        <span class="control-error" v-if="errors.has('layout')">@{{ errors.first('layout') }}</span>
                    </div>

                    @php
                        $content = json_decode($page->content);
                    @endphp
                    <div class="control-group" :class="[errors.has('content') ? 'has-error' : '']">
                        <label for="content" class="required">{{ __('admin::app.cms.pages.content') }}</label>

                        <textarea type="text" class="control" id="content" name="content" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cms.pages.content') }}&quot;">{{ $content->html ?? old('content') }}</textarea>

                        <span class="control-error" v-if="errors.has('content')">@{{ errors.first('content') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('page_title') ? 'has-error' : '']">
                        <label for="page_title" class="required">{{ __('admin::app.cms.pages.page-title') }}</label>

                        <input type="text" class="control" name="page_title" v-validate="'required'" value="{{ $content->page_title ?? old('page_title') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.page-title') }}&quot;">

                        <span class="control-error" v-if="errors.has('page_title')">@{{ errors.first('page_title') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('meta_title') ? 'has-error' : '']">
                        <label for="meta_title" class="required">{{ __('admin::app.cms.pages.meta_title') }}</label>

                        <input type="text" class="control" name="meta_title" v-validate="'required'" value="{{ $content->meta_title ?? old('meta_title') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.meta_title') }}&quot;">

                        <span class="control-error" v-if="errors.has('meta_title')">@{{ errors.first('meta_title') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('meta_keywords') ? 'has-error' : '']">
                        <label for="meta_keywords" class="required">{{ __('admin::app.cms.pages.meta_keywords') }}</label>

                        <textarea type="text" class="control" name="meta_keywords" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cms.pages.meta_keywords') }}&quot;">{{ $content->meta_keywords ?? old('meta_keywords') }}</textarea>

                        <span class="control-error" v-if="errors.has('meta_keywords')">@{{ errors.first('meta_keywords') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('meta_description') ? 'has-error' : '']">
                        <label for="meta_description" class="required">{{ __('admin::app.cms.pages.meta_description') }}</label>

                        <textarea type="text" class="control" name="meta_description" data-vv-as="&quot;{{ __('admin::app.cms.pages.meta_description') }}&quot;">{{ $content->meta_description ?? old('meta_description') }}</textarea>

                        <span class="control-error" v-if="errors.has('meta_description')">@{{ errors.first('meta_description') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#preview').on('click', function(e) {
                var form = $('#page-form').serialize();
                var url = '{{ route('admin.cms.preview') }}' + '?' + form;

                window.open(url, '_blank').focus();

                return false;
            });

            $('#channel-switcher, #locale-switcher').on('change', function (e) {
                $('#channel-switcher').val()
                var query = '?channel=' + $('#channel-switcher').val() + '&locale=' + $('#locale-switcher').val();

                window.location.href = "{{ route('admin.cms.edit', $page->id)  }}" + query;
            });

            tinymce.init({
                selector: 'textarea#content',
                height: 200,
                width: "70%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]'
            });
        });
    </script>
@endpush