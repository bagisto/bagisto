{{-- @php
    $allLocales = app('Webkul\Core\Repositories\LocaleRepository')->all();
@endphp

@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.attributes.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form
            method="POST"
            action="{{ route('admin.catalog.attributes.update', $attribute->id) }}"
            @submit.prevent="onSubmit"
            enctype="multipart/form-data"
        >
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.catalog.attributes.index') }}'"></i>

                        {{ __('admin::app.catalog.attributes.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.attributes.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.general.before', ['attribute' => $attribute]) !!}

                    <accordian title="{{ __('admin::app.catalog.attributes.general') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.general.controls.before', ['attribute' => $attribute]) !!}

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">
                                    {{ __('admin::app.catalog.attributes.code') }}
                                </label>

                                <input
                                    type="text"
                                    name="code"
                                    class="control"
                                    id="code"
                                    value="{{ old('code') ?: $attribute->code }}"
                                    disabled="disabled"
                                    v-code
                                    v-validate="'required'"
                                    data-vv-as="&quot;{{ __('admin::app.catalog.attributes.code') }}&quot;"
                                />

                                <input type="hidden" name="code" value="{{ $attribute->code }}"/>

                                <span class="control-error" v-if="errors.has('code')" v-text="errors.first('code')"></span>
                            </div>

                            <div class="control-group">
                                @php
                                    $selectedOption = old('type') ?: $attribute->type;
                                @endphp

                                <label for="type">{{ __('admin::app.catalog.attributes.type') }}</label>

                                <select class="control" id="type" disabled="disabled">
                                    <option value="text" {{ $selectedOption == 'text' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.text') }}
                                    </option>

                                    <option value="textarea" {{ $selectedOption == 'textarea' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.textarea') }}
                                    </option>

                                    <option value="price" {{ $selectedOption == 'price' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.price') }}
                                    </option>

                                    <option value="boolean" {{ $selectedOption == 'boolean' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.boolean') }}
                                    </option>

                                    <option value="select" {{ $selectedOption == 'select' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.select') }}
                                    </option>

                                    <option value="multiselect" {{ $selectedOption == 'multiselect' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.multiselect') }}
                                    </option>

                                    <option value="datetime" {{ $selectedOption == 'datetime' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.datetime') }}
                                    </option>

                                    <option value="date" {{ $selectedOption == 'date' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.date') }}
                                    </option>

                                    <option value="image" {{ $selectedOption == 'image' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.image') }}
                                    </option>

                                    <option value="file" {{ $selectedOption == 'file' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.file') }}
                                    </option>

                                    <option value="checkbox" {{ $selectedOption == 'checkbox' ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.checkbox') }}
                                    </option>
                                </select>

                                <input type="hidden" name="type" value="{{ $attribute->type }}"/>
                            </div>

                            
                            @if ($attribute->type == 'textarea')
                                <div class="control-group">
                                    <label for="enable_wysiwyg">
                                        {{ __('admin::app.catalog.attributes.enable-wysiwyg') }}
                                    </label>

                                    <label class="switch">
                                        <input
                                            type="checkbox"
                                            name="enable_wysiwyg"
                                            id="enable_wysiwyg"
                                            value="1"
                                            {{ (old('enable_wysiwyg') ?: $attribute->enable_wysiwyg) ? 'checked' : '' }}
                                        />

                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            @endif

                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.general.controls.after', ['attribute' => $attribute]) !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.general.after', ['attribute' => $attribute]) !!}

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.attributes.before', ['attribute' => $attribute]) !!}

                    <accordian title="{{ __('admin::app.catalog.attributes.label') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.attributes.controls.before', ['attribute' => $attribute]) !!}

                            <div class="control-group" :class="[errors.has('admin_name') ? 'has-error' : '']">
                                <label for="admin_name" class="required">
                                    {{ __('admin::app.catalog.attributes.admin') }}
                                </label>

                                <input
                                    type="text"
                                    name="admin_name"
                                    class="control"
                                    id="admin_name"
                                    value="{{ old('admin_name') ?: $attribute->admin_name }}"
                                    v-validate="'required'"
                                    data-vv-as="&quot;{{ __('admin::app.catalog.attributes.admin_name') }}&quot;"
                                />

                                <span
                                    class="control-error"
                                    v-if="errors.has('admin_name')"
                                    v-text="errors.first('admin_name')"
                                ></span>
                            </div>

                            @foreach ($allLocales as $locale)
                                <div class="control-group">
                                    <label for="locale-{{ $locale->code }}">
                                        {{ $locale->name . ' (' . $locale->code . ')' }}
                                    </label>

                                    <input
                                        type="text"
                                        name="<?php echo $locale->code; ?>[name]"
                                        class="control"
                                        id="locale-{{ $locale->code }}"
                                        value="{{ old($locale->code)['name'] ?? ($attribute->translate($locale->code)->name ?? '') }}"
                                    />
                                </div>
                            @endforeach

                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.attributes.controls.after', ['attribute' => $attribute]) !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.attributes.after', ['attribute' => $attribute]) !!}

                    <div class="{{ in_array($attribute->type, ['select', 'multiselect', 'checkbox']) ?: 'hide' }}">
                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.options.before', ['attribute' => $attribute]) !!}

                        <accordian title="{{ __('admin::app.catalog.attributes.options') }}" :active="true" :id="'options'">
                            <div slot="body">

                                {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.options.controls.before', ['attribute' => $attribute]) !!}

                                <option-wrapper
                                    src="{{ route('admin.catalog.attributes.options', $attribute->id) }}"
                                    :all-locales="{{ $allLocales->toJson() }}"
                                ></option-wrapper>

                                {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.options.controls.after', ['attribute' => $attribute]) !!}

                            </div>
                        </accordian>

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.options.after', ['attribute' => $attribute]) !!}
                    </div>

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.validations.before', ['attribute' => $attribute]) !!}

                    <accordian title="{{ __('admin::app.catalog.attributes.validations') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.validations.controls.before', ['attribute' => $attribute]) !!}

                            <div class="control-group">
                                <label for="is_required">
                                    {{ __('admin::app.catalog.attributes.is_required') }}
                                </label>
                                
                                <select
                                    name="is_required"
                                    class="control"
                                    id="is_required"
                                    {{ ! $attribute->is_user_defined ? 'disabled' : '' }}
                                >
                                    <option value="0" {{ $attribute->is_required ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->is_required ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>

                                <input type="hidden" name="is_required" value="{{ $attribute->is_required }}"/>
                            </div>

                            <div class="control-group">
                                <label for="is_unique">
                                    {{ __('admin::app.catalog.attributes.is_unique') }}
                                </label>

                                <select name="is_unique" class="control" id="is_unique" disabled>
                                    <option value="0" {{ $attribute->is_unique ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->is_unique ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>

                                <input type="hidden" name="is_unique" value="{{ $attribute->is_unique }}"/>
                            </div>

                            @if ($attribute->type == 'text')
                                <div class="control-group">
                                    @php $selectedValidation = old('validation') ?: $attribute->validation @endphp

                                    <label for="validation">
                                        {{ __('admin::app.catalog.attributes.input_validation') }}
                                    </label>

                                    <select
                                        name="validation"
                                        class="control"
                                        id="validation"
                                        {{ ! $attribute->is_user_defined ? 'disabled' : '' }}
                                    >
                                        <option value=""></option>
                                        
                                        <option value="numeric" {{ $selectedValidation == 'numeric' ? 'selected' : '' }}>
                                            {{ __('admin::app.catalog.attributes.number') }}
                                        </option>

                                        <option value="decimal" {{ $selectedValidation == 'decimal' ? 'selected' : '' }}>
                                            {{ __('admin::app.catalog.attributes.decimal') }}
                                        </option>

                                        <option value="email" {{ $selectedValidation == 'email' ? 'selected' : '' }}>
                                            {{ __('admin::app.catalog.attributes.email') }}
                                        </option>

                                        <option value="url" {{ $selectedValidation == 'url' ? 'selected' : '' }}>
                                            {{ __('admin::app.catalog.attributes.url') }}
                                        </option>
                                    </select>
                                </div>
                            @endif

                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.validations.controls.after', ['attribute' => $attribute]) !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.validations.after', ['attribute' => $attribute]) !!}

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.configuration.before', ['attribute' => $attribute]) !!}

                    <accordian title="{{ __('admin::app.catalog.attributes.configuration') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.configuration.controls.before', ['attribute' => $attribute]) !!}

                            <div class="control-group">
                                <label for="value_per_locale">
                                    {{ __('admin::app.catalog.attributes.value_per_locale') }}
                                </label>

                                <select name="value_per_locale" class="control" id="value_per_locale" disabled>
                                    <option value="0" {{ $attribute->value_per_locale ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->value_per_locale ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>

                                <input type="hidden" name="value_per_locale" value="{{ $attribute->value_per_locale }}"/>
                            </div>

                            <div class="control-group">
                                <label for="value_per_channel">
                                    {{ __('admin::app.catalog.attributes.value_per_channel') }}
                                </label>

                                <select name="value_per_channel" class="control" id="value_per_channel" disabled>
                                    <option value="0" {{ $attribute->value_per_channel ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->value_per_channel ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>

                                <input type="hidden" name="value_per_channel" value="{{ $attribute->value_per_channel }}"/>
                            </div>

                            <div class="control-group">
                                <label for="is_filterable">
                                    {{ __('admin::app.catalog.attributes.is_filterable') }}
                                </label>

                                <select name="is_filterable" class="control" id="is_filterable">
                                    <option value="0" {{ $attribute->is_filterable ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->is_filterable ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_configurable">
                                    {{ __('admin::app.catalog.attributes.is_configurable') }}
                                </label>

                                <select name="is_configurable" class="control" id="is_configurable">
                                    <option value="0" {{ $attribute->is_configurable ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->is_configurable ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_visible_on_front">
                                    {{ __('admin::app.catalog.attributes.is_visible_on_front') }}
                                </label>

                                <select name="is_visible_on_front" class="control" id="is_visible_on_front">
                                    <option value="0" {{ $attribute->is_visible_on_front ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->is_visible_on_front ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_comparable">
                                    {{ __('admin::app.catalog.attributes.is_comparable') }}
                                </label>

                                <select name="is_comparable" class="control" id="is_comparable">
                                    <option value="0" {{ $attribute->is_comparable ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>

                                    <option value="1" {{ $attribute->is_comparable ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.configuration.controls.after', ['attribute' => $attribute]) !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.configuration.after', ['attribute' => $attribute]) !!}
                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script type="text/x-template" id="options-template">
        <div>
            <div class="control-group" v-if="show_swatch">
                <label for="swatch_type">
                    {{ __('admin::app.catalog.attributes.swatch_type') }}
                </label>

                <select
                    name="swatch_type"
                    class="control"
                    id="swatch_type"
                    v-model="swatch_type"
                >
                    <option value="dropdown">
                        {{ __('admin::app.catalog.attributes.dropdown') }}
                    </option>

                    <option value="color">
                        {{ __('admin::app.catalog.attributes.color-swatch') }}
                    </option>

                    <option value="image">
                        {{ __('admin::app.catalog.attributes.image-swatch') }}
                    </option>

                    <option value="text">
                        {{ __('admin::app.catalog.attributes.text-swatch') }}
                    </option>
                </select>
            </div>

            <div class="control-group">
                <span class="checkbox">
                    <input
                        type="checkbox"
                        name="default-null-option"
                        class="control"
                        id="default-null-option"
                        v-model="isNullOptionChecked"
                    />

                    <label class="checkbox-view" for="default-null-option"></label>

                    {{ __('admin::app.catalog.attributes.default_null_option') }}
                </span>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th v-if="show_swatch && (swatch_type == 'color' || swatch_type == 'image')">
                                {{ __('admin::app.catalog.attributes.swatch') }}
                            </th>

                            <th>
                                {{ __('admin::app.catalog.attributes.admin_name') }}
                            </th>

                            <th v-for="locale in allLocales" v-text="`${locale.name} (${locale.code})`"></th>

                            <th>
                                {{ __('admin::app.catalog.attributes.position') }}
                            </th>

                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- `v-show` used here, so that element remain inside the form. Don't use `v-if` here. --}}
                        <tr v-for="(row, index) in optionRows" :key="row.id" v-show="! row.isDelete">
                            <input type="hidden" :name="'options[' + row.id + '][isNew]'" :value="row.isNew">

                            <input type="hidden" :name="'options[' + row.id + '][isDelete]'" :value="row.isDelete">

                            <td v-if="show_swatch && swatch_type == 'color'">
                                <swatch-picker
                                    :input-name="'options[' + row.id + '][swatch_value]'"
                                    :color="row.swatch_value"
                                    colors="text-advanced"
                                    show-fallback
                                />
                            </td>

                            <td style="white-space: nowrap;" v-if="show_swatch && swatch_type == 'image'">
                                <div class="control-group" :class="[errors.has('options[' + row.id + '][swatch_value]') ? 'has-error' : '']">
                                    <img
                                        :src="row.swatch_value_url"
                                        style="width: 36px;height: 36px;vertical-align: middle;background: #F2F2F2;border-radius: 2px;margin-right: 10px;"
                                        v-if="row.swatch_value_url"
                                    />

                                    <input
                                        type="file"
                                        :name="'options[' + row.id + '][swatch_value]'"
                                        accept="image/*"
                                        v-validate="'size:600'"
                                    />

                                    <span
                                        class="control-error"
                                        v-text="'{{ __('admin::app.catalog.attributes.validation-messages.max-size') }}'"
                                        v-if="errors.has('options[' + row.id + '][swatch_value]')"
                                    ></span>
                                </div>
                            </td>

                            <td>
                                <div class="control-group" :class="[errors.has(adminName(row)) ? 'has-error' : '']">
                                    <input
                                        type="text"
                                        :name="adminName(row)"
                                        class="control"
                                        v-model="row['admin_name']"
                                        v-validate="getOptionValidation(row, '')"
                                        data-vv-as="&quot;{{ __('admin::app.catalog.attributes.admin_name') }}&quot;"
                                    />

                                    <span
                                        class="control-error"
                                        v-text="errors.first(adminName(row))"
                                        v-if="errors.has(adminName(row))"
                                    ></span>
                                </div>
                            </td>

                            <td v-for="locale in allLocales">
                                <div class="control-group" :class="[errors.has(localeInputName(row, locale.code)) ? 'has-error' : '']">
                                    <input
                                        type="text"
                                        :name="localeInputName(row, locale.code)"
                                        class="control"
                                        v-model="row['locales'][locale.code]"
                                        v-validate="getOptionValidation(row, locale.code)"
                                        :data-vv-as="`&quot;${locale.name} (${locale.code})&quot;`"
                                    />

                                    <span
                                        class="control-error"
                                        v-text="errors.first(localeInputName(row, locale.code))"
                                        v-if="errors.has(localeInputName(row, locale.code))"
                                    ></span>
                                </div>
                            </td>

                            <td>
                                <div class="control-group" :class="[errors.has(sortOrderName(row)) ? 'has-error' : '']">
                                    <input
                                        type="text"
                                        :name="sortOrderName(row)"
                                        class="control"
                                        v-model="row['sort_order']"
                                        v-validate="getOptionValidation(row, '')"
                                        data-vv-as="&quot;{{ __('admin::app.catalog.attributes.position') }}&quot;"
                                    />

                                    <span
                                        class="control-error"
                                        v-text="errors.first(sortOrderName(row))"
                                        v-if="errors.has(sortOrderName(row))"
                                    ></span>
                                </div>
                            </td>

                            <td class="actions">
                                <i class="icon trash-icon" @click="removeRow(row)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button
                type="button"
                class="btn btn-lg btn-primary"
                id="load-more-btm"
                style="margin-top: 20px"
                @click="loadMoreOptions()"
                v-if="loadMore"
            >
                {{ __('admin::app.catalog.attributes.load-more-options-btn-title') }}
            </button>

            <button
                type="button"
                class="btn btn-lg btn-primary"
                id="add-option-btn"
                style="margin-top: 20px"
                @click="addOptionRow()"
            >
                {{ __('admin::app.catalog.attributes.add-option-btn-title') }}
            </button>
        </div>
    </script>

    <script>
        Vue.component('option-wrapper', {
            template: '#options-template',

            inject: ['$validator'],

            props: [
                'src',
                'allLocales'
            ],

            data: function() {
                return {
                    appLocale: '{{ app()->getLocale() }}',

                    optionPage: 1,

                    optionRowCount: 0,

                    optionRows: [],

                    loadMore: true,

                    show_swatch: "{{ $attribute->type == 'select' ? true : false  }}",

                    swatch_type: "{{ $attribute->swatch_type == '' ? 'dropdown' : $attribute->swatch_type }}",

                    isNullOptionChecked: false,

                    idNullOption: null
                };
            },

            watch: {
                isNullOptionChecked: function (val) {
                    if (val) {
                        if (! this.idNullOption) {
                            this.addOptionRow(true);
                        }
                    } else if(this.idNullOption !== null && typeof this.idNullOption !== 'undefined') {
                        const row = this.optionRows.find(optionRow => optionRow.id === this.idNullOption);
                        this.removeRow(row);
                    }
                }
            },

            created: function () {
                this.getAttributeOptions();

                this.activateToggleSwatch();
            },

            methods: {
                activateToggleSwatch: function () {
                    let self = this;

                    $('#type').on('change', function (e) {
                        if (['select'].indexOf($(e.target).val()) === -1) {
                            self.show_swatch = false;
                        } else {
                            self.show_swatch = true;
                        }
                    });
                },

                getAttributeOptions: function () {
                    let self = this;

                    axios.get(`${this.src}?page=${this.optionPage}`).then(function (response) {
                        let options = response.data.data;

                        if (response.data.current_page === response.data.last_page) {
                            self.loadMore = false;
                        }

                        options.forEach((option) => {
                            self.optionRowCount++;

                            let row = {
                                'id': option.id,
                                'admin_name': option.admin_name,
                                'sort_order': option.sort_order,
                                'swatch_value': option.swatch_value,
                                'swatch_value_url': option.swatch_value_url,
                                'notRequired': '',
                                'locales': {},
                                'isNew': false,
                                'isDelete': false,
                            };

                            if (! option.label) {
                                self.isNullOptionChecked = true;
                                self.idNullOption = option.id;
                                row['notRequired'] = true;
                            } else {
                                row['notRequired'] = false;
                            }

                            option.translations.forEach((translation) => {
                                row['locales'][translation.locale] = translation.label ?? '';
                            });

                            self.optionRows.push(row);
                        });
                    });
                },

                loadMoreOptions: function () {
                    this.optionPage++;

                    this.getAttributeOptions();
                },

                addOptionRow: function (isNullOptionRow) {
                    const rowCount = this.optionRowCount++;
                    const id = 'option_' + rowCount;
                    let row = {'id': id, 'locales': {}, 'isNew': true, 'isDelete': false};

                    this.allLocales.forEach((locale) => {
                        row['locales'][locale.code] = '';
                    });

                    row['notRequired'] = '';

                    if (isNullOptionRow) {
                        this.idNullOption = id;
                        row['notRequired'] = true;
                    }

                    this.optionRows.push(row);
                },

                removeRow: function (row) {
                    if (row.id === this.idNullOption) {
                        this.idNullOption = null;
                        this.isNullOptionChecked = false;
                    }

                    const index = this.optionRows.indexOf(row);

                    if (this.optionRows[index].isNew) {
                        this.hardDeleteNewRow(index);
                    } else {
                        this.softDeleteExistingRow(index);
                    }
                },

                hardDeleteNewRow: function (rowIndex) {
                    Vue.delete(this.optionRows, rowIndex);
                },

                softDeleteExistingRow: function (rowIndex) {
                    let self = this;

                    this.optionRows[rowIndex].isDelete = true;

                    let requiredKeys = ['admin_name', 'sort_order'];

                    requiredKeys.forEach((key) => {
                        if (self.optionRows[rowIndex][key] === undefined || self.optionRows[rowIndex][key] === '') {
                            self.optionRows[rowIndex][key] = '0';
                        }
                    });
                },

                adminName: function (row) {
                    return 'options[' + row.id + '][admin_name]';
                },

                localeInputName: function (row, locale) {
                    return 'options[' + row.id + '][' + locale + '][label]';
                },

                sortOrderName: function (row) {
                    return 'options[' + row.id + '][sort_order]';
                },

                getOptionValidation: function (row, localeCode) {
                    if (row.notRequired === true) {
                        return '';
                    }
                    
                    return ('{{ app()->getLocale() }}' === localeCode) || localeCode == ""  ? 'required' : '';
                },
            },
        });
    </script>
@endpush --}}

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.catalog.attributes.create.title')
    </x-slot:title>

    {{-- Create Attributes Vue Components --}}
    <v-create-attributes></v-create-attributes>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-attributes-template"
        >
            <!-- Input Form -->
            <x-admin::form
                :action="route('admin.catalog.attributes.update', $attribute->id)"
                enctype="multipart/form-data"
                method="PUT"
            >
                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 font-bold">
                        @lang('admin::app.catalog.attributes.create.title')
                    </p>
        
                    <div class="flex gap-x-[10px] items-center">
                        <!-- Canvel Button -->
                        <a href="{{ route('admin.catalog.attributes.index') }}">
                            <span class="text-gray-600 leading-[24px]">
                                @lang('admin::app.catalog.attributes.create.cancel-btn')
                            </span>
                        </a>
        
                        <button
                            type="submit"
                            class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        >
                            @lang('admin::app.catalog.attributes.create.save-btn')
                        </button>
                    </div>
                </div>

                <!-- body content -->
                <div class="flex gap-[10px] mt-[14px]">
                    <!-- Left sub Component -->
                    <div class="flex flex-col gap-[8px] flex-1">

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.attributes.before', ['attribute' => $attribute]) !!}

                        <!-- Label -->
                        <div class="p-[16px] bg-white box-shadow rounded-[4px]">
                            <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                                @lang('admin::app.catalog.attributes.create.label')
                            </p>

                            <!-- Admin name -->
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.catalog.attributes.create.admin')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="admin_name"
                                    value="{{ old('admin_name') ?: $attribute->admin_name }}"
                                    rules="required"
                                    label="{{ trans('admin::app.catalog.attributes.create.admin') }}"
                                    placeholder="{{ trans('admin::app.catalog.attributes.create.admin') }}"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="admin_name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Locales Inputs -->
                            @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="{{ $locale->code }}[name]"
                                        value="{{ old($locale->code)['name'] ?? ($attribute->translate($locale->code)->name ?? '') }}"

                                        placeholder="{{ $locale->name }}"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="{{ $locale->code }}[name]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>    
                            @endforeach
                        </div>

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.attributes.after', ['attribute' => $attribute]) !!}

                        <!-- Options -->
                        <div 
                            class="p-[16px] bg-white box-shadow rounded-[4px]"
                            v-if="swatch_attribute && (attribute_type == 'select' || attribute_type == 'multiselect' || attribute_type == 'price' || attribute_type == 'checkbox')"
                        >
                            <div class="flex justify-between items-center mb-3">
                                <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.catalog.attributes.create.title')
                                </p>

                                <!-- Add Row Button -->
                                <div
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-[14px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.addOptionsRow.toggle()"
                                >
                                    @lang('admin::app.catalog.attributes.create.add-row')
                                </div>
                            </div>

                            <!-- For Attribute Options If Data Exist -->
                            <template v-if="this.options?.length">
                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.attributes.create.input-options')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="swatch_type"
                                            id="swatch_type"
                                            value="{{ old('swatch_type') }}"
                                            v-model="swatch_type"
                                            @change="show_swatch=true"
                                        >
                                            <option value="dropdown">
                                                @lang('admin::app.catalog.attributes.create.dropdown')
                                            </option>
                        
                                            <option value="color">
                                                @lang('admin::app.catalog.attributes.create.color-swatch')
                                            </option>
                        
                                            <option value="image">
                                                @lang('admin::app.catalog.attributes.create.image-swatch')
                                            </option>
                        
                                            <option value="text">
                                                @lang('admin::app.catalog.attributes.create.text-swatch')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            class="mt-3"
                                            control-name="admin"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <div class="w-full mb-[10px]">
                                        <!-- checkbox -->
                                        <x-admin::form.control-group.label class="invisible">
                                            @lang('admin::app.catalog.attributes.create.input-options')
                                        </x-admin::form.control-group.label>

                                        <label
                                            for="empty_option"
                                            class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                        >
                                            <input
                                                type="checkbox"
                                                name="empty_option"
                                                id="empty_option"
                                                class="hidden peer"
                                                value="1"
                                                v-model="isNullOptionChecked"
                                            >
                    
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                    
                                            <div class="text-[14px] text-gray-600 cursor-pointer">
                                                @lang('admin::app.catalog.attributes.create.create-empty-option')
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Table Information -->
                                <div class="mt-[15px] overflow-auto">    
                                    <x-admin::table class="w-full text-left">
                                        <x-admin::table.thead class="text-[14px] font-medium">
                                            <x-admin::table.tr>
                                                <x-admin::table.th class="!p-0"></x-admin::table.th>

                                                <!-- Swatch Select -->
                                                <x-admin::table.th v-if="show_swatch && (swatch_type == 'color' || swatch_type == 'image')">
                                                    @lang('admin::app.catalog.attributes.create.swatch')
                                                </x-admin::table.th>

                                                <!-- Admin tables heading -->
                                                <x-admin::table.th>
                                                    @lang('admin::app.catalog.attributes.create.admin_name')
                                                </x-admin::table.th>

                                                <!-- Loacles tables heading -->
                                                @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                                                    <x-admin::table.th>
                                                        {{ $locale->name . ' (' . $locale->code . ')' }}
                                                    </x-admin::table.th>
                                                @endforeach                                

                                                <!-- Positions tables heading -->
                                                <x-admin::table.th>
                                                    @lang('admin::app.catalog.attributes.create.position')
                                                </x-admin::table.th>

                                                <!-- Action tables heading -->
                                                <x-admin::table.th></x-admin::table.th>
                                            </x-admin::table.tr>
                                        </x-admin::table.thead>

                                        <!-- Draggable Component -->
                                        <draggable
                                            tag="tbody"
                                            ghost-class="draggable-ghost"
                                            :list="options"
                                            item-key="id"
                                        >
                                            <template #item="{ element, index }">
                                                <x-admin::table.tr>
                                                    <!-- Draggable Icon -->
                                                    <x-admin::table.td class="!px-0">
                                                        <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][position]'"
                                                            :value="index"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Swatch Type Image / Color -->
                                                    <x-admin::table.td v-if="show_swatch">
                                                        <!-- Swatch Image -->
                                                        <div v-if="swatch_type == 'image'">
                                                            @{{ element.params.swatch_value?.name }}

                                                            <input
                                                                type="hidden"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                v-model="element.params.swatch_value"
                                                            />    
                                                        </div>

                                                        <!-- Swatch Color -->
                                                        <div v-if="swatch_type == 'color'">
                                                            <div
                                                                class="w-[25px] h-[25px] mx-auto rounded-[5px]"
                                                                :style="{ background: element.params.swatch_value }"
                                                            >
                                                            </div>
                    
                                                            <input
                                                                type="hidden"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                v-model="element.params.swatch_value"
                                                            />
                                                        </div>
                                                    </x-admin::table.td>

                                                    <!-- Admin-->
                                                    <x-admin::table.td>
                                                        <p v-text="element.params.admin"></p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][admin]'"
                                                            v-model="element.params.admin"
                                                        />
                                                    </x-admin::table.td>
                    
                                                    <!-- English Loacle -->
                                                    <x-admin::table.td>
                                                        <p v-text="element.params.en"></p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][en]'"
                                                            v-model="element.params.en"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- French Loacle -->
                                                    <x-admin::table.td>
                                                        <p v-text="element.params.fr"></p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][fr]'"
                                                            v-model="element.params.fr"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Dutch Loacle -->
                                                    <x-admin::table.td>
                                                        <p v-text="element.params.nl"></p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][nl]'"
                                                            v-model="element.params.nl"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Turkce Loacle -->
                                                    <x-admin::table.td>
                                                        <p v-text="element.params.tr"></p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][tr]'"
                                                            v-model="element.params.tr"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Espanol Loacle -->
                                                    <x-admin::table.td>
                                                        <p v-text="element.params.es"></p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][es]'"
                                                            v-model="element.params.es"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Position Loacle -->
                                                    <x-admin::table.td>
                                                        <p v-text="element.params.sort_order"></p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][sort_order]'"
                                                            v-model="element.params.sort_order"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Actions Bustion -->
                                                    <x-admin::table.td class="!px-0">
                                                        <span
                                                            class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 max-sm:place-self-center"
                                                            @click="editModal(element.id)"
                                                        >
                                                        </span> 
                                                    </x-admin::table.td>
                                                </x-admin::table.tr>
                                            </template>
                                        </draggable>
                                    </x-admin::table>
                                </div>
                            </template>

                            <!-- For Empty Attribute Options -->
                            <template v-else>
                                <div class="grid gap-[14px] justify-items-center py-[40px] px-[10px]">
                                    <!-- Attribute Option Image -->
                                    <img 
                                        class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]" 
                                        src="{{ bagisto_asset('images/add-product-to-store.png') }}" 
                                        alt="{{ trans('admin::app.catalog.attributes.create.add-attribute-options') }}"
                                    >
                                    <!-- Add Attribute Options Information -->
                                    <div class="flex flex-col items-center">
                                        <p class="text-[16px] text-gray-400 font-semibold">
                                            @lang('admin::app.catalog.attributes.create.add-attribute-options')
                                        </p>

                                        <p class="text-gray-400">
                                            @lang('admin::app.catalog.attributes.create.add-options-info')
                                        </p>
                                    </div>

                                    <!-- Add Row Button -->
                                    <div 
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-[14px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        @click="$refs.addOptionsRow.toggle()"
                                    >
                                        @lang('admin::app.catalog.attributes.create.add-row')
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Right sub-component -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full">

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.general.before', ['attribute' => $attribute]) !!}

                        <!-- General -->
                        <div class="bg-white box-shadow rounded-[4px]">
                            <div class="flex justify-between items-center p-[6px]">
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.create.general')
                                </p>
                            </div>

                            <div class="px-[16px] pb-[16px]">
                                <!-- Attribute Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.general')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        value="{{ old('code') ?: $attribute->code }}"
                                        class="!w-[284px]"
                                        rules="required"
                                        disabled="disabled"
                                        label="{{ trans('admin::app.catalog.attributes.create.code') }}"
                                        placeholder="{{ trans('admin::app.catalog.attributes.create.code') }}"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="code"
                                        :value="$attribute->code"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Attribute Type -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.type')
                                    </x-admin::form.control-group.label>

                                    @php
                                        $selectedOption = old('type') ?: $attribute->type;
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        value="{{ old('type') }}"
                                        id="type"
                                        :value="$selectedOption"
                                        disabled="disabled"
                                        class="!w-[284px] cursor-pointer"
                                        label="{{ trans('admin::app.catalog.attributes.create.type') }}"
                                        v-model="attribute_type"
                                        @change="swatch_attribute=true"
                                    >
                                        <!-- Here! All Needed types are defined -->
                                        @foreach(['text', 'textarea', 'price', 'boolean', 'select', 'multiselect', 'datetime', 'date', 'image', 'file', 'checkbox'] as $type)
                                            <option
                                                value="{{ $type }}"
                                                {{ $selectedOption == $type ? 'selected' : '' }}
                                            >
                                                @lang('admin::app.catalog.attributes.create.'. $type)
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="type"
                                        :value="$attribute->type"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="type"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Textarea Switcher -->
                                @if($attribute->type == 'textarea')
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.catalog.attributes.create.enable-wysiwyg')
                                        </x-admin::form.control-group.label>

                                        @php $selectedOption = old('enable_wysiwyg') ?: $attribute->enable_wysiwyg; @endphp

                                        <x-admin::form.control-group.control
                                            type="switch"
                                            name="enable_wysiwyg"
                                            class="cursor-pointer"
                                            value="1"
                                            label="{{ trans('admin::app.catalog.attributes.create.enable-wysiwyg') }}"
                                            :checked="(boolean) $selectedOption"
                                        >
                                        </x-admin::form.control-group.control>
                                    </x-admin::form.control-group>
                                @endif
                            </div>
                        </div>

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.general.after', ['attribute' => $attribute]) !!}

                        <!-- Validations -->
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.create.validations')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <!-- Is Required -->
                                <label
                                    for="is_required"
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_required"
                                        id="is_required"
                                        for="is_required"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->is_user_defined"
                                        {{ ! $attribute->is_user_defined ? 'disabled' : '' }}
                                    >
                                    </x-admin::form.control-group.control>
            
                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.is_required')
                                    </div>
                                </label>

                                <input type="hidden" name="is_required" value="{{ $attribute->is_user_defined }}"/>

                                <!-- Is Unique -->
                                <label
                                    for="is_unique" 
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_unique"
                                        id="is_unique"
                                        for="is_unique"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->is_unique"
                                        disabled
                                    >
                                    </x-admin::form.control-group.control>

                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.is_unique')
                                    </div>
                                </label>

                                <input type="hidden" name="is_unique" value="{{ $attribute->is_unique }}"/>

                            </x-slot:content>
                        </x-admin::accordion>

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.validations.controls.after', ['attribute' => $attribute]) !!}

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.configuration.before', ['attribute' => $attribute]) !!}

                        <!-- Configurations -->
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.catalog.attributes.create.configuration')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <!-- value_per_locale -->
                                <label
                                    for="value_per_locale"
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="value_per_locale"
                                        id="value_per_locale"
                                        for="value_per_locale"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->value_per_locale"
                                        disabled
                                    >
                                    </x-admin::form.control-group.control>

                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.value_per_locale')
                                    </div>
                                </label>

                                <input type="hidden" name="value_per_locale" value="{{ $attribute->value_per_locale }}"/>

                                <!-- value_per_channel -->
                                <label
                                    for="value_per_channel"
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="value_per_channel"
                                        id="value_per_channel"
                                        for="value_per_channel"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->value_per_channel"
                                        disabled
                                    >
                                    </x-admin::form.control-group.control>
                                    
                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.value_per_channel')
                                    </div>
                                </label>

                                <input type="hidden" name="value_per_channel" value="{{ $attribute->value_per_channel }}"/>

                                <!-- Use in Layered -->
                                <label
                                    for="is_filterable" 
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_filterable"
                                        id="is_filterable"
                                        for="is_filterable"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->is_filterable"
                                        disabled
                                    >
                                    </x-admin::form.control-group.control>
            
                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.is_filterable')
                                    </div>
                                </label>

                                <!-- is_configurable -->
                                <label
                                    for="is_configurable"
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_configurable"
                                        id="is_configurable"
                                        for="is_configurable"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->value_per_channel"
                                    >
                                    </x-admin::form.control-group.control>
            
                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.is_configurable')
                                    </div>
                                </label>

                                <!-- is_visible_on_front -->
                                <label
                                    for="is_visible_on_front" 
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_visible_on_front"
                                        id="is_visible_on_front"
                                        for="is_visible_on_front"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->is_visible_on_front"
                                    >
                                    </x-admin::form.control-group.control>
                            
                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.is_visible_on_front')
                                    </div>
                                </label>

                                <!-- use_in_flat -->
                                <label
                                    for="use_in_flat" 
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="use_in_flat"
                                        id="use_in_flat"
                                        for="use_in_flat"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->use_in_flat"
                                    >
                                    </x-admin::form.control-group.control>

                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.use_in_flat')
                                    </div>
                                </label>

                                <!-- is_comparable -->
                                <label
                                    for="is_comparable"
                                    class="flex gap-[10px] items-center w-max p-[6px] cursor-pointer select-none"
                                >
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_comparable"
                                        id="is_comparable"
                                        for="is_comparable"
                                        value="1"
                                        class="hidden peer"
                                        :checked="(boolean) $attribute->is_comparable"
                                    >
                                    </x-admin::form.control-group.control>

                                    <div class="text-[14px] text-gray-600 cursor-pointer">
                                        @lang('admin::app.catalog.attributes.create.is_comparable')
                                    </div>
                                </label>
                            </x-slot:content>
                        </x-admin::accordion>

                        {!! view_render_event('bagisto.admin.catalog.attribute.edit_form_accordian.configuration.controls.after', ['attribute' => $attribute]) !!}

                    </div>
                </div>
            </x-admin::form>

            <!-- Add Options Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modelForm"
            >
                <form @submit.prevent="handleSubmit($event, storeOptions)" enctype="multipart/form-data">
                    <x-admin::modal ref="addOptionsRow">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.catalog.attributes.create.add-option')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="grid grid-cols-3 px-[16px] py-[10px]">
                                <!-- Image Input -->
                                <x-admin::form.control-group class="w-full" v-if="swatch_type == 'image'">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.image')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="swatch_value"
                                        placeholder="{{ trans('admin::app.catalog.attributes.create.image') }}"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="swatch_value"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Color Input -->
                                <x-admin::form.control-group class="w-full" v-if="swatch_type == 'color'">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.color')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="color"
                                        name="swatch_value"
                                        placeholder="{{ trans('admin::app.catalog.attributes.create.color') }}"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="swatch_value"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-3 gap-[16px] px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <!-- Hidden Id Input -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>
                                
                                <!-- Admin Input -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.admin')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="admin"
                                        rules="required"
                                        label="{{ trans('admin::app.catalog.attributes.create.admin') }}"
                                        placeholder="{{ trans('admin::app.catalog.attributes.create.admin') }}"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="admin"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Locales Input -->
                                @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="{{ $locale->code }}"
                                            rules="{{ core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : '' }}"
                                            label="{{ $locale->name }}"
                                            placeholder="{{ $locale->name }}"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="{{ $locale->code }}"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                @endforeach

                                <!-- Position Input -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.catalog.attributes.create.position')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="number"
                                        name="sort_order"
                                        rules="required"
                                        label="{{ trans('admin::app.catalog.attributes.create.position') }}"
                                        placeholder="{{ trans('admin::app.catalog.attributes.create.position') }}"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="sort_order"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
                        
                        <x-slot:footer>
                            <!-- Save Button -->
                            <button
                                type="submit" 
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.marketing.email-marketing.events.create.save')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        {{-- <script type="module">
            app.component('v-create-attributes', {
                template: '#v-create-attributes-template',

                data: function() {
                    return {
                        optionRowCount: 1,

                        show_swatch: false,

                        attribute_type: false,

                        swatch_attribute: false,

                        show_swatch: false,

                        isNullOptionChecked: false,

                        idNullOption: null,

                        options: [],
                    }
                },

                methods: {
                    storeOptions(params, {resetForm, setValues}) {
                        let foundIndex = this.options.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            let updatedObject = {
                                ...this.options[foundIndex],
                                params: {
                                    ...this.options[foundIndex].params,
                                    ...params,
                                }
                            };

                            this.options.splice(foundIndex, 1, updatedObject);
                        } else {
                            let rowCount = this.optionRowCount++;
                            let id = 'option_' + rowCount;
                            let row = {'id': id, params};
    
                            this.options.push(row);
                        }

                        this.$refs.addOptionsRow.toggle();

                        resetForm();
                    },

                    editModal(value) {
                        const foundData = this.options.find(item => item.id === value);
                        // For set value on edit form
                        this.$refs.modelForm.setValues(foundData.params);

                        this.$refs.modelForm.setValues(foundData);

                        this.$refs.addOptionsRow.toggle();
                    },
                },

                watch: {
                    isNullOptionChecked: function (val) {
                        /* 
                        *  Here else part code is useless 
                        *  Need to add code for When modal is closed after that input checkbox should unchecked
                        */ 
                        if (val) {
                            // For open existing model
                            if (! this.idNullOption) {
                                this.$refs.addOptionsRow.toggle();
                            }
                        } else if(this.idNullOption !== null && typeof this.idNullOption !== 'undefined') {
                            this.$refs.addOptionsRow.toggle()
                        }
                    },
                },
            });
        </script> --}}
    @endPushOnce
</x-admin::layouts>