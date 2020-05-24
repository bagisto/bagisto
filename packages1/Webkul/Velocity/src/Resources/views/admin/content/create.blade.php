@extends('admin::layouts.content')

@section('page_title')
    {{ __('velocity::app.admin.contents.add-title') }}
@stop

@section('content')
    <div class="content">

        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('velocity::app.admin.contents.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('velocity::app.admin.contents.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()

                <input type="hidden" name="locale" value="all"/>

                {!! view_render_event('bagisto.admin.content.create_form_accordian.page.before') !!}

                <accordian :title="'{{ __('velocity::app.admin.contents.tab.page') }}'" :active="true">
                    <div slot="body">

                        {!! view_render_event('bagisto.admin.content.create_form_accordian.page.controls.before') !!}

                        <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                            <label for="title" class="required">
                                {{ __('velocity::app.admin.contents.page.title') }}
                            </label>
                            <input type="text" v-validate="'required|max:100'" class="control" id="title" name="title" value="" data-vv-as="&quot;{{ __('velocity::app.admin.contents.page.title') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                            <label for="position" class="required">
                                {{ __('velocity::app.admin.contents.page.position') }}</span>
                            </label>
                            <input type="text" v-validate="'required|numeric|max:2'" class="control" id="position" name="position" value="" data-vv-as="&quot;{{ __('velocity::app.admin.contents.page.position') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                            <label for="status" class="required">{{ __('velocity::app.admin.contents.page.status') }}</label>
                            <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('velocity::app.admin.contents.page.status') }}&quot;">
                                <option value="1">
                                    {{ __('velocity::app.admin.contents.active') }}
                                </option>
                                <option value="0">
                                    {{ __('velocity::app.admin.contents.inactive') }}
                                </option>
                            </select>
                            <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                        </div>

                        {!! view_render_event('bagisto.admin.content.create_form_accordian.page.controls.after') !!}

                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.content.create_form_accordian.page.after') !!}

                {!! view_render_event('bagisto.admin.content.create_form_accordian.content.before') !!}

                <accordian :title="'{{ __('velocity::app.admin.contents.tab.content') }}'" :active="true">
                    <div slot="body">

                        {!! view_render_event('bagisto.admin.content.create_form_accordian.content.controls.before') !!}

                            <content-type></content-type>

                        {!! view_render_event('bagisto.admin.content.create_form_accordian.content.controls.after') !!}

                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.content.create_form_accordian.content.after') !!}

            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/x-template" id="content-type-template">
        <div>
            {{-- <div class="control-group" :class="[errors.has('custom_title') ? 'has-error' : '']">
                <label for="custom_title">
                    {{ __('velocity::app.admin.contents.content.custom-title') }}
                </label>
                <input type="text" v-validate="'max:100'" class="control" id="custom_title" name="custom_title" value="" data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.custom-title') }}&quot;"/>

                <span class="control-error" v-if="errors.has('custom_title')">@{{ errors.first('custom_title') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('custom_heading') ? 'has-error' : '']">
                <label for="custom_heading">
                    {{ __('velocity::app.admin.contents.content.custom-heading') }}
                </label>
                <input type="text" v-validate="'max:100'" class="control" id="custom_heading" name="custom_heading" value="" data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.custom-heading') }}&quot;"/>

                <span class="control-error" v-if="errors.has('custom_heading')">@{{ errors.first('custom_heading') }}</span>
            </div> --}}

            <div class="control-group" :class="[errors.has('content_type') ? 'has-error' : '']">
                <label for="content_type" class="required">{{ __('velocity::app.admin.contents.content.content-type') }}</label>

                <select class="control" v-model="content_type" v-validate="'required'" id="content_type" name="content_type" data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.content-type') }}&quot;" @change="loadFields($event)">
                    <option value="">{{ __('velocity::app.admin.contents.select') }}</option>

                    @foreach (velocity()->getContentType() as $key => $content_type)
                        <option value="{{ $key }}">{{ $content_type }}</option>
                    @endforeach
                </select>

                <span class="control-error" v-if="errors.has('content_type')">@{{ errors.first('content_type') }}</span>
            </div>

            <div v-if="content_type == 'link'">
                @include ('velocity::admin.content.content-type.link')
            </div>
            <div v-else-if="content_type == 'product'">
                @include ('velocity::admin.content.content-type.product')
            </div>
            <div v-else-if="content_type == 'static'">
                @include ('velocity::admin.content.content-type.static')
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
                    content_type: '',
                }
            },
            methods: {
                loadFields(event) {
                    this.content_type = event.target.value;

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
                }
            }
        });
    </script>

@endpush