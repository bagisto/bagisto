@extends('admin::layouts.content')

@section('page_title')
    {{ __('velocity::app.admin.contents.edit-title') }}
@stop

@section('content')
    <div class="content">
        @php
            $locale = core()->getRequestedLocaleCode();
            $translation = $content->translations->where('locale', $locale)->first();
        @endphp

        <form
            method="POST"
            @submit.prevent="onSubmit"
            enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = history.length > 1 ? document.referrer : '{{ route('admin.dashboard.index') }}'"></i>

                        {{ __('velocity::app.admin.contents.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ route('velocity.admin.content.update', $content->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('velocity::app.admin.contents.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()
                <input name="_method" type="hidden" value="PUT">

                <input type="hidden" name="locale" value="{{ $locale }}"/>

                {!! view_render_event('bagisto.admin.content.edit_form_accordian.page.before', ['content' => $content]) !!}

                <accordian :title="'{{ __('velocity::app.admin.contents.tab.page') }}'" :active="true">
                    <div slot="body">

                        {!! view_render_event('bagisto.admin.content.edit_form_accordian.page.controls.before', ['content' => $content]) !!}

                        <div class="control-group" :class="[errors.has('{{$locale}}[title]') ? 'has-error' : '']">
                            <label for="title" class="required">
                                {{ __('velocity::app.admin.contents.page.title') }}
                                <span class="locale">[{{ $locale }}]</span>
                            </label>
                            <input type="text" v-validate="'required|max:100'" class="control" id="title" name="{{$locale}}[title]" value="{{ old($locale)['title'] ?? ($translation->title ?? '') }}" data-vv-as="&quot;{{ __('velocity::app.admin.contents.page.title') }}&quot;"/>

                            <span class="control-error" v-if="errors.has('{{$locale}}[title]')" v-text="errors.first('{!!$locale!!}[title]')"></span>
                        </div>

                        <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                            <label for="position" class="required">
                                {{ __('velocity::app.admin.contents.page.position') }}</span>
                            </label>
                            <input type="text" v-validate="'required|numeric|max:2'" class="control" id="position" name="position" value="{{ old('position') ?? $content->position }}" data-vv-as="&quot;{{ __('velocity::app.admin.contents.page.position') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('position')" v-text="errors.first('position')"></span>
                        </div>

                        <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                            <label for="status" class="required">{{ __('velocity::app.admin.contents.page.status') }}</label>
                            <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('velocity::app.admin.contents.page.status') }}&quot;">
                                <option value="0" {{ !$content->status ? 'selected' : '' }}>
                                    {{ __('velocity::app.admin.contents.inactive') }}
                                </option>
                                <option value="1" {{ $content->status ? 'selected' : '' }}>
                                    {{ __('velocity::app.admin.contents.active') }}
                                </option>
                            </select>
                            <span class="control-error" v-if="errors.has('status')" v-text="errors.first('status')"></span>
                        </div>

                        {!! view_render_event('bagisto.admin.content.edit_form_accordian.page.controls.after', ['content' => $content]) !!}

                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.content.edit_form_accordian.page.after', ['content' => $content]) !!}

                {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.before', ['content' => $content]) !!}

                <accordian :title="'{{ __('velocity::app.admin.contents.tab.content') }}'" :active="true">
                    <div slot="body">

                        {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.controls.before', ['content' => $content]) !!}

                            <content-type></content-type>

                        {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.controls.after', ['content' => $content]) !!}

                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.after', ['content' => $content]) !!}

            </div>

        </form>
    </div>
@stop


@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/x-template" id="content-type-template">
        <div>
            {{-- <div :class="`control-group ${errors.has('{{$locale}}[custom_title]') ? 'has-error' : ''}`">
                <label for="custom_title">
                    {{ __('velocity::app.admin.contents.content.custom-title') }}
                    <span class="locale">[{{ $locale }}]</span>
                </label>

                <input
                    type="text"
                    class="control"
                    id="custom_title"
                    v-validate="'max:100'"
                    name="{{$locale}}[custom_title]"
                    value="{{ old($locale)['custom_title'] ?? ($content->translate($locale) ? $content->translate($locale)['custom_title'] : '' ?? '') }}"
                    data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.custom-title') }}&quot;" />

                <span
                    class="control-error"
                    v-if="errors.has('{{$locale}}[custom_title]')"
                    v-text="errors.first('{!!$locale!!}[custom_title]')">
                </span>
            </div>

            <div :class="`control-group ${errors.has('{{$locale}}[custom_heading]') ? 'has-error' : ''}`">
                <label for="custom_heading">
                    {{ __('velocity::app.admin.contents.content.custom-heading') }}
                    <span class="locale">[{{ $locale }}]</span>
                </label>

                <input
                    type="text"
                    class="control"
                    id="custom_heading"
                    v-validate="'max:100'"
                    name="{{$locale}}[custom_heading]"
                    value="{{ old($locale)['custom_heading'] ?? $content->translate($locale) ? $content->translate($locale)['custom_title'] : '' }}" data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.custom-heading') }}&quot;" />

                <span
                    class="control-error"
                    v-if="errors.has('{{$locale}}[custom_heading]')"
                    v-text="errors.first('{!!$locale!!}[custom_heading]')">
                </span>
            </div> --}}

            <div :class="`control-group ${errors.has('content_type') ? 'has-error' : ''}`">
                <label
                    for="content_type"
                    class="required">
                    {{ __('velocity::app.admin.contents.content.content-type') }}
                </label>

                @php
                    $contentType = $content->content_type;
                @endphp

                <select
                    class="control"
                    id="content_type"
                    name="content_type"
                    v-model="content_type"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.content-type') }}&quot;" @change="loadFields($event)">

                    <option value="">
                        {{ __('velocity::app.admin.contents.select') }}
                    </option>

                    @foreach (velocity()->getContentType() as $key => $content_type)
                        <option
                            value="{{ $key }}"
                            {{ $contentType == $key ? 'selected' : '' }}>
                            {{ $content_type }}
                        </option>
                    @endforeach
                </select>

                <span
                    class="control-error"
                    v-if="errors.has('content_type')"
                    v-text="errors.first('content_type')">
                </span>
            </div>

            <div v-if="content_type == 'link'">
                {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.link.before', ['content' => $content]) !!}

                <div :class="`control-group ${errors.has('{{$locale}}[page_link]') ? 'has-error' : ''}`">

                    <label for="page_link" class="required">
                        {{ __('velocity::app.admin.contents.content.page-link') }}
                    </label>

                    <input
                        type="text"
                        id="page_link"
                        class="control"
                        name="{{$locale}}[page_link]"
                        v-validate="'required|max:150'"
                        value="{{ old($locale)['page_link'] ?? $content->translate($locale) ? $content->translate($locale)['page_link'] : '' }}"
                        data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.page-link') }}&quot;" />

                    <span
                        class="control-error"
                        v-if="errors.has('{{$locale}}[page_link]')"
                        v-text="errors.first('{!!$locale!!}[page_link]')">
                    </span>
                </div>

                <div class="control-group">
                    <label for="link_target">
                        {{ __('velocity::app.admin.contents.content.link-target') }}
                    </label>

                    <select
                        class="control"
                        id="link_target"
                        name="link_target">
                        <option value="0" {{ !$content->link_target ? 'selected' : '' }}>
                            {{ __('velocity::app.admin.contents.self') }}
                        </option>
                        <option value="1" {{ $content->link_target ? 'selected' : '' }}>
                            {{ __('velocity::app.admin.contents.new-tab') }}
                        </option>
                    </select>
                </div>

                {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.link.after', ['content' => $content]) !!}
            </div>

            <div v-else-if="content_type == 'product'">
                @include ('velocity::admin.content.content-type.edit-product')
            </div>

            <div v-else-if="content_type == 'static'">
                {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.static.before', ['content' => $content]) !!}

                <div :class="`control-group ${errors.has('{{$locale}}[description]') ? 'has-error' : ''}`">
                    <label for="description" class="required">{{ __('velocity::app.admin.contents.content.static-description') }}</label>

                    <textarea
                        class="control"
                        id="description"
                        v-validate="'required'"
                        name="{{$locale}}[description]"
                        data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.static-description') }}&quot;">
                        {{ old($locale)['description'] ?? $content->translate($locale) ? $content->translate($locale)['description'] : '' }}
                    </textarea>

                    <span
                        class="control-error"
                        v-if="errors.has('{{$locale}}[description]')"
                        v-text="errors.first('{!!$locale!!}[description]')">
                    </span>
                </div>

                {!! view_render_event('bagisto.admin.content.edit_form_accordian.content.static.after', ['content' => $content]) !!}
            </div>

            <div v-else-if="content_type == 'category'">
                @include ('velocity::admin.content.content-type.category')
            </div>
        </div>
    </script>

    <script type="text/javascript">
        Vue.component('content-type', {
            template: '#content-type-template',
            inject: ['$validator'],

            data() {
                return {
                    content_type: @json($contentType),
                }
            },

            created() {
                if (this.content_type == 'static') {
                    $(document).ready(function () {
                        tinymce.init({
                            selector: 'textarea#description',
                            height: 200,
                            width: "100%",
                            plugins: 'image imagetools media wordcount save fullscreen code',
                            toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code',
                            image_advtab: true
                        });
                    });
                }
            },

            methods: {
                loadFields(event) {
                    var thisthis = this;
                    thisthis.content_type = event.target.value;

                    if ( thisthis.content_type == 'static') {
                        $(document).ready(function () {
                            tinymce.init({
                                selector: 'textarea#description',
                                height: 200,
                                width: "100%",
                                plugins: 'image imagetools media wordcount save fullscreen code',
                                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code',
                                image_advtab: true
                            });
                        });
                    } else {
                        tinymce.remove('#description');
                    }
                }
            }
        });
    </script>
@endpush
