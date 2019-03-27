@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.catalog.categories.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.catalog.categories.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.categories.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input type="hidden" name="locale" value="all"/>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.categories.general') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.catalog.categories.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.catalog.categories.visible-in-menu') }}</label>
                                <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.catalog.categories.visible-in-menu') }}&quot;">
                                    <option value="1">
                                        {{ __('admin::app.catalog.categories.yes') }}
                                    </option>
                                    <option value="0">
                                        {{ __('admin::app.catalog.categories.no') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position" class="required">{{ __('admin::app.catalog.categories.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.position') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.after') !!}


                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.categories.description-and-images') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.controls.before') !!}

                            <div class="control-group" :class="[errors.has('display_mode') ? 'has-error' : '']">
                                <label for="display_mode" class="required">{{ __('admin::app.catalog.categories.display-mode') }}</label>
                                <select class="control" v-validate="'required'" id="display_mode" name="display_mode" data-vv-as="&quot;{{ __('admin::app.catalog.categories.display-mode') }}&quot;">
                                    <option value="products_and_description">
                                        {{ __('admin::app.catalog.categories.products-and-description') }}
                                    </option>
                                    <option value="products_only">
                                        {{ __('admin::app.catalog.categories.products-only') }}
                                    </option>
                                    <option value="description_only">
                                        {{ __('admin::app.catalog.categories.description-only') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('display_mode')">@{{ errors.first('display_mode') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                <label for="description">{{ __('admin::app.catalog.categories.description') }}</label>
                                <textarea  class="control" id="description" name="description" data-vv-as="&quot;{{ __('admin::app.catalog.categories.description') }}&quot;">{{ old('description') }}</textarea>
                                <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                            </div>

                            <div class="control-group">
                                <label>{{ __('admin::app.catalog.categories.image') }}

                                <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="image" :multiple="false"></image-wrapper>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.after') !!}


                    @if ($categories->count())

                        {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.parent_category.before') !!}

                        <accordian :title="'{{ __('admin::app.catalog.categories.parent-category') }}'" :active="true">
                            <div slot="body">

                                {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.parent_category.controls.before') !!}

                                <tree-view value-field="id" name-field="parent_id" input-type="radio" items='@json($categories)'></tree-view>

                                {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.parent_category.controls.after') !!}

                            </div>
                        </accordian>

                        {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.parent_category.after') !!}

                    @endif


                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.seo.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.categories.seo') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.seo.controls.before') !!}

                            <div class="control-group">
                                <label for="meta_title">{{ __('admin::app.catalog.categories.meta_title') }}</label>
                                <input type="text" class="control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"/>
                            </div>

                            <div class="control-group" :class="[errors.has('slug') ? 'has-error' : '']">
                                <label for="slug" class="required">{{ __('admin::app.catalog.categories.slug') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="slug" name="slug" value="{{ old('slug') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.slug') }}&quot;" v-slugify/>
                                <span class="control-error" v-if="errors.has('slug')">@{{ errors.first('slug') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('admin::app.catalog.categories.meta_description') }}</label>
                                <textarea class="control" id="meta_description" name="meta_description">{{ old('meta_description') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('admin::app.catalog.categories.meta_keywords') }}</label>
                                <textarea class="control" id="meta_keywords" name="meta_keywords">{{ old('meta_keywords') }}</textarea>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.seo.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.seo.after') !!}

                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
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
    </script>
@endpush