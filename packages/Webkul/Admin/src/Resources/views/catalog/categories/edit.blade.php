@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.edit-title') }}
@stop

@push('css')
    <style>
       @media only screen and (max-width: 728px){
            .content-container .content .page-header .page-title{
                width: 100%;
            }

            .content-container .content .page-header .page-title .control-group {
                margin-top: 20px!important;
                width: 100%!important;
                margin-left: 0!important;
            }

            .content-container .content .page-header .page-action {
                margin-top: 10px!important;
                float: left;
            }
       }
    </style>
@endpush

@section('content')
    <div class="content">
        @php
            $locale = core()->getRequestedLocaleCode();
        @endphp

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.catalog.categories.index') }}'"></i>

                        {{ __('admin::app.catalog.categories.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ route('admin.catalog.categories.update', $category->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>

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

                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.before', ['category' => $category]) !!}

                    <accordian title="{{ __('admin::app.catalog.categories.general') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.controls.before', ['category' => $category]) !!}

                            <div class="control-group" :class="[errors.has('{{$locale}}[name]') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.catalog.categories.name') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="{{$locale}}[name]" value="{{ old($locale)['name'] ?? ($category->translate($locale)['name'] ?? '') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.name') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[name]')">@{{ errors.first('{!!$locale!!}[name]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.catalog.categories.visible-in-menu') }}</label>
                                <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.catalog.categories.visible-in-menu') }}&quot;">
                                    <option value="1" {{ $category->status ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.categories.yes') }}
                                    </option>
                                    <option value="0" {{ $category->status ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.categories.no') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position" class="required">{{ __('admin::app.catalog.categories.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') ?: $category->position }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.position') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.controls.after', ['category' => $category]) !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.after', ['category' => $category]) !!}

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.before', ['category' => $category]) !!}

                    <accordian title="{{ __('admin::app.catalog.categories.description-and-images') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.controls.before', ['category' => $category]) !!}

                            <div class="control-group" :class="[errors.has('display_mode') ? 'has-error' : '']">
                                <label for="display_mode" class="required">{{ __('admin::app.catalog.categories.display-mode') }}</label>
                                <select class="control" v-validate="'required'" id="display_mode" name="display_mode" data-vv-as="&quot;{{ __('admin::app.catalog.categories.display-mode') }}&quot;">
                                    <option value="products_and_description" {{ $category->display_mode == 'products_and_description' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.categories.products-and-description') }}
                                    </option>
                                    <option value="products_only" {{ $category->display_mode == 'products_only' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.categories.products-only') }}
                                    </option>
                                    <option value="description_only" {{ $category->display_mode == 'description_only' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.categories.description-only') }}
                                    </option>
                                </select>
                                <span class="control-error" v-if="errors.has('display_mode')">@{{ errors.first('display_mode') }}</span>
                            </div>

                            <description></description>

                            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                <label>{{ __('admin::app.catalog.categories.image') }}</label>

                                <image-wrapper button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}" input-name="image" :multiple="false"  :images='"{{ $category->image_url }}"'></image-wrapper>

                                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.controls.after', ['category' => $category]) !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.after', ['category' => $category]) !!}

                    @if ($categories->count())
                        {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.before', ['category' => $category]) !!}

                        <accordian title="{{ __('admin::app.catalog.categories.parent-category') }}" :active="true">
                            <div slot="body">

                                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.controls.before', ['category' => $category]) !!}

                                <tree-view value-field="id" name-field="parent_id" input-type="radio" items='@json($categories)' value='@json($category->parent_id)' fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>

                                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.controls.before', ['category' => $category]) !!}

                            </div>
                        </accordian>

                        {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.after', ['category' => $category]) !!}
                    @endif

                    <accordian title="{{ __('admin::app.catalog.categories.filterable-attributes') }}" :active="true">
                        <div slot="body">
                            @php $selectedaAtributes = old('attributes') ?? $category->filterableAttributes->pluck('id')->toArray() @endphp

                            <div class="control-group multi-select" :class="[errors.has('attributes[]') ? 'has-error' : '']">
                                <label for="attributes" class="required">{{ __('admin::app.catalog.categories.attributes') }}</label>
                                <select class="control" name="attributes[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.catalog.categories.attributes') }}&quot;" multiple>

                                    @foreach ($attributes as $attribute)
                                        <option value="{{ $attribute->id }}" {{ in_array($attribute->id, $selectedaAtributes) ? 'selected' : ''}}>
                                            {{ $attribute->name ? $attribute->name : $attribute->admin_name }}
                                        </option>
                                    @endforeach

                                </select>
                                <span class="control-error" v-if="errors.has('attributes[]')">
                                    @{{ errors.first('attributes[]') }}
                                </span>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.before', ['category' => $category]) !!}

                    <accordian title="{{ __('admin::app.catalog.categories.seo') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.controls.before', ['category' => $category]) !!}

                            <div class="control-group">
                                <label for="meta_title">{{ __('admin::app.catalog.categories.meta_title') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <input type="text" class="control" id="meta_title" name="{{$locale}}[meta_title]" value="{{ old($locale)['meta_title'] ?? ($category->translate($locale)['meta_title'] ?? '') }}"/>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[slug]') ? 'has-error' : '']">
                                <label for="slug" class="required">{{ __('admin::app.catalog.categories.slug') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <input type="text" v-validate="'required'" class="control" id="slug" name="{{$locale}}[slug]" value="{{ old($locale)['slug'] ?? ($category->translate($locale)['slug'] ?? '') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.slug') }}&quot;" v-slugify/>
                                <span class="control-error" v-if="errors.has('{{$locale}}[slug]')">@{{ errors.first('{!!$locale!!}[slug]') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('admin::app.catalog.categories.meta_description') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <textarea class="control" id="meta_description" name="{{$locale}}[meta_description]">{{ old($locale)['meta_description'] ?? ($category->translate($locale)['meta_description'] ?? '') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('admin::app.catalog.categories.meta_keywords') }}
                                    <span class="locale">[{{ $locale }}]</span>
                                </label>
                                <textarea class="control" id="meta_keywords" name="{{$locale}}[meta_keywords]">{{ old($locale)['meta_keywords'] ?? ($category->translate($locale)['meta_keywords'] ?? '') }}</textarea>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.controls.after', ['category' => $category]) !!}
                        </div>
                    </accordian>
                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.after', ['category' => $category]) !!}
                </div>
            </div>
        </form>
        <div class="page-content">
            <accordian title="{{ __('admin::app.catalog.categories.products') }}" :active="true">
                <div slot="body">
                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.products.controls.before', ['category' => $category]) !!}

                    <datagrid-plus src="{{ route('admin.catalog.categories.products', $category->id) }}"></datagrid-plus>

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.products.controls.before', ['category' => $category]) !!}
                </div>
            </accordian>
        </div>
    </div>
@stop

@push('scripts')
    @include('admin::layouts.tinymce')

    <script type="text/x-template" id="description-template">
        <div class="control-group" :class="[errors.has('{{$locale}}[description]') ? 'has-error' : '']">
            <label for="description" :class="isRequired ? 'required' : ''">{{ __('admin::app.catalog.categories.description') }}
                <span class="locale">[{{ $locale }}]</span>
            </label>
            <textarea v-validate="isRequired ? 'required' : ''" class="control" id="description" name="{{$locale}}[description]" data-vv-as="&quot;{{ __('admin::app.catalog.categories.description') }}&quot;">{{ old($locale)['description'] ?? ($category->translate($locale)['description'] ?? '') }}</textarea>
            <span class="control-error" v-if="errors.has('{{$locale}}[description]')">@{{ errors.first('{!!$locale!!}[description]') }}</span>
        </div>
    </script>

    <script>
        Vue.component('description', {
            template: '#description-template',

            inject: ['$validator'],

            data: function() {
                return {
                    isRequired: true,
                }
            },

            created: function () {
                let self = this;

                $(document).ready(function () {
                    $('#display_mode').on('change', function (e) {
                        if ($('#display_mode').val() != 'products_only') {
                            self.isRequired = true;
                        } else {
                            self.isRequired = false;
                        }
                    })

                    if ($('#display_mode').val() != 'products_only') {
                        self.isRequired = true;
                    } else {
                        self.isRequired = false;
                    }

                    tinyMCEHelper.initTinyMCE({
                        selector: 'textarea#description',
                        height: 200,
                        width: "100%",
                        plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code | table',
                    });
                });
            }
        });
    </script>
@endpush