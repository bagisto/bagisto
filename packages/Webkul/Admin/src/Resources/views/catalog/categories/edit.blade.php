{{-- @extends('admin::layouts.content')

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

                                <input 
                                    type="text" v-validate="'required'" 
                                    name="{{$locale}}[name]"
                                    value="{{ old($locale)['name'] ?? ($category->translate($locale)['name'] ?? '') }}"
                                    class="control" 
                                    id="name" 
                                    data-vv-as="&quot;{{ __('admin::app.catalog.categories.name') }}&quot;"
                                />

                                <span
                                    class="control-error" 
                                    v-text="errors.first('{!!$locale!!}[name]')"
                                    v-if="errors.has('{{$locale}}[name]')">
                                </span>
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

                                <span class="control-info mb-5">{{ __('admin::app.catalog.products.image-drop') }}</span>

                                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>

                                <label>{{ __('admin::app.catalog.categories.category_banner') }}</label>
                                <large-image-wrapper button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}" input-name="category_banner" :multiple="false" :images='"{{ $category->banner_url }}"'></large-image-wrapper>

                                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>

                                <span class="control-info">{{ __('admin::app.catalog.products.image-drop') }}</span>

                                <span class="control-info mt-10">{{ __('admin::app.catalog.categories.banner_size') }}</span>   
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
@endpush --}}

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalog.categories.create.add-new-category')
    </x-slot:title>

    @php
        $locale = core()->getRequestedLocaleCode();
    @endphp
    
    {{-- Input Form --}}
    <x-admin::form 
        :action="route('admin.catalog.categories.store')"
        enctype="multipart/form-data"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.catalog.categories.create.add-new-category')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a href="{{ route('admin.catalog.categories.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.catalog.categories.create.cancel')
                    </span>
                </a>

                <!-- Save Button -->
                <button 
                    type="submit" 
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.catalog.categories.create.create-btn')
                </button>
            </div>
        </div>

        {{-- Full Pannel --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left Section --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <!-- General -->
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.categories.create.general')
                    </p>

                    {{-- Locales --}}
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="locale"
                        value="all"
                    >
                    </x-admin::form.control-group.control>

                    {{-- Name --}}
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.catalog.categories.create.company-name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            value="{{ old($locale)['name'] ?? ($category->translate($locale)['name'] ?? '') }}"
                            class="w-full"
                            label="Category Name"
                            placeholder="Category Name"
                        >
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>

                    <div class="mb-[10px]">
                        {{-- Parent category --}}
                        <label
                            class="block mb-[10px] text-[12px] text-gray-800 font-medium leading-[24px]"
                            for="username"
                        >
                            @lang('admin::app.catalog.categories.create.select-parent-category')
                        </label>

                        {{-- Radio select button --}}
                        <div class="flex flex-col gap-[12px]">
                            @foreach ($categories as $category)
                                <label
                                    for="{{ $category->id }}"
                                    class="inline-flex items-center w-max px-[4px] text-gray-600 cursor-pointer select-none"
                                >
                                    <span class="icon-sort-right text-[24px]"></span>
                                    <input
                                        type="radio"
                                        name="parent_id"
                                        id="{{ $category->id }}"
                                        class="hidden peer"
                                        value="{{ old('parent_id')  ?? $category->id }}"
                                    >

                                    <span class="icon-radio-normal mr-[4px] text-[24px] rounded-[6px] cursor-pointer peer-checked:icon-radio-selected peer-checked:text-navyBlue"></span>

                                    <div class="text-[14px] cursor-pointer">
                                        {{ $category->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Description and images -->
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.categories.create.description-and-images')
                    </p>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.catalog.categories.create.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="tinymce"
                            name="description"
                            id="description"
                            class="description"
                            value="{{ old('description') }}"
                            rules="required"
                            label="{{ trans('admin::app.catalog.categories.create.description') }}"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="description"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <div class="flex gap-[50px]">
                        {{-- Add Logo --}}
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.logo')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.create.logo-size')
                            </p>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="image"
                                    name="logo_path[image_1]"
                                    class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                    :label="trans('admin::app.catalog.categories.create.add-logo')"
                                    :is-multiple="false"
                                    :src="isset($category) ? $category->logo_path : ''"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="image[]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>

                        {{-- Add Banner --}}
                        <div class="flex flex-col gap-[8px] w-[40%] mt-5">
                            <p class="text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.banner')
                            </p>

                            <p class="text-[12px] text-gray-500">
                                @lang('admin::app.catalog.categories.create.banner-size')
                            </p>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="image"
                                    name="banner_path[]"
                                    class="mb-0 !p-0 rounded-[12px] text-gray-700"
                                    :width="200"
                                    :height="110"
                                    :label="trans('admin::app.catalog.categories.create.add-banner')"
                                    :is-multiple="false"
                                    accepted-types="image/*"
                                    :src="isset($category) ? $category->banner_path : ''"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="category_icon_image[]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                    </div>
                </div>

                {{-- SEO Deatils --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.catalog.categories.create.seo-details')
                    </p>

                    <div class="flex flex-col gap-[3px]">
                        <p
                            class="text-[#161B9D]"
                            v-text="metaTitle ? metaTitle : '{{ $category->translate($locale)['meta_title'] ?? '' }}'"
                        >
                        </p>

                        {{-- Get Meta Title From v-model and convet in lower case also replace space with (-) --}}
                        <p
                            class="text-[#135F29]"
                            v-text="'{{ URL::to('/') }}/' + (metaTitle ? metaTitle.toLowerCase().replace(/\s+/g, '-') : '{{ $category->translate($locale)['meta_title'] ?? '' }}')"
                        >
                        </p>

                        {{-- Get Meta Description From v-model --}}
                        <p
                            class="text-gray-600"
                            v-text="metaDescription"
                        >
                        </p>
                    </div>

                    <div class="mt-[30px]">
                        {{-- Meta Title --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meta_title"
                                value="{{ old($locale)['meta_title'] ?? ($category->translate($locale)['meta_title'] ?? '') }}"
                                label="{{ trans('admin::app.catalog.categories.create.meta-title') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.meta-title') }}"
                                v-model="metaTitle"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Slug --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.slug')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="slug"
                                value="{{ old('slug') }}"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.create.slug') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.slug') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="slug"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Meta Keywords --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-keywords')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meta_keywords"
                                value="{{ old('meta_keywords') }}"
                                label="{{ trans('admin::app.catalog.categories.create.meta-keywords') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.meta-keywords') }}"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Meta Description --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.catalog.categories.create.meta-description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="meta_description"
                                value="{{ old('meta_description') }}"
                                label="{{ trans('admin::app.catalog.categories.create.meta-description') }}"
                                placeholder="{{ trans('admin::app.catalog.categories.create.meta-description') }}"
                                v-model="metaDescription"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>

            {{-- Right Section --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
                {{-- Settings --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.catalog.categories.create.settings')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                        {{-- Position --}}
                        <div class="mb-[10px]">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="!text-gray-800">
                                    @lang('admin::app.catalog.categories.create.position')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="position"
                                    :value="old('position') ?: $category->position"
                                    rules="required"
                                    label="{{ trans('admin::app.catalog.categories.create.position') }}"
                                    placeholder="{{ trans('admin::app.catalog.categories.create.enter-position') }}"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="position"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>

                        {{-- Display Mode  --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.display-mode')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('status') ?: $category->display_mode @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                name="display_mode"
                                class="cursor-pointer"
                                :value="$selectedValue"
                                rules="required"
                                label="{{ trans('admin::app.catalog.categories.create.display-mode') }}"
                            >
                                @foreach (['products_and_description', 'products_only', 'description_only'] as $item)
                                    <option
                                        value="{{ $item }}"
                                        {{ $selectedValue == $item ? 'selected' : '' }}
                                    >
                                        @lang('admin::app.catalog.categories.create.' . $item)
                                    </option>    
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="display_mode"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Visible in menu --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="!text-gray-800 font-medium">
                                @lang('admin::app.catalog.categories.create.visible-in-menu')
                            </x-admin::form.control-group.label>

                            @php $selectedValue = old('status') ?: $category->status @endphp

                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                class="cursor-pointer"
                                value="1"
                                label="{{ trans('admin::app.catalog.categories.create.visible-in-menu') }}"
                                :checked="(boolean) $selectedValue"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                {{-- Filterable Attributes --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                            @lang('admin::app.catalog.categories.create.filterable-attributes')
                        </p>
                    </x-slot:header>
                
                    @php $selectedaAtributes = old('attributes') ?: $category->filterableAttributes->pluck('id')->toArray() @endphp

                    <x-slot:content class="pointer-events-none">
                        @foreach ($attributes as $attribute)
                            <label
                                class="flex gap-[10px] w-max items-center p-[6px] cursor-pointer select-none"
                                for="{{ $attribute->name ?? $attribute->admin_name }}"
                            >
                                @php $selectedOption = in_array($attribute->id, $selectedaAtributes); @endphp

                                <input
                                    type="checkbox" 
                                    id="{{ $attribute->name ?? $attribute->admin_name }}"
                                    value="{{ $attribute->id }}"
                                    name="attributes[]" 
                                    class="hidden peer"
                                    checked="{{ (boolean) $selectedOption }}"
                                >

                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointe peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                                <div class="text-[14px] text-gray-600 font-semibold cursor-pointer">
                                    {{ $attribute->name ?? $attribute->admin_name }}
                                </div>
                            </label>

                            {{-- {{ in_array($attribute->id, $selectedaAtributes) ? 'selected' : ''}} --}}
                        @endforeach
                    </x-slot:content>
                </x-admin::accordion>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
