@php
    $allLocales = app('Webkul\Core\Repositories\LocaleRepository')->all();
@endphp

@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.attributes.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.attributes.update', $attribute->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

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
                                <label for="code" class="required">{{ __('admin::app.catalog.attributes.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') ?: $attribute->code }}" disabled="disabled" data-vv-as="&quot;{{ __('admin::app.catalog.attributes.code') }}&quot;" v-code/>
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
                                    <label for="enable_wysiwyg">{{ __('admin::app.catalog.attributes.enable-wysiwyg') }}</label>

                                    <label class="switch">
                                        <input type="checkbox" id="enable_wysiwyg" name="enable_wysiwyg" value="1" {{ (old('enable_wysiwyg') ?: $attribute->enable_wysiwyg) ? 'checked' : '' }}>
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
                                <label for="admin_name" class="required">{{ __('admin::app.catalog.attributes.admin') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="admin_name" name="admin_name" value="{{ old('admin_name') ?: $attribute->admin_name }}" data-vv-as="&quot;{{ __('admin::app.catalog.attributes.admin_name') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('admin_name')" v-text="errors.first('admin_name')"></span>
                            </div>

                            @foreach ($allLocales as $locale)
                                <div class="control-group">
                                    <label for="locale-{{ $locale->code }}">{{ $locale->name . ' (' . $locale->code . ')' }}</label>
                                    <input type="text" class="control" id="locale-{{ $locale->code }}" name="<?php echo $locale->code; ?>[name]" value="{{ old($locale->code)['name'] ?? ($attribute->translate($locale->code)->name ?? '') }}"/>
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
                                <label for="is_required">{{ __('admin::app.catalog.attributes.is_required') }}</label>
                                <select class="control" id="is_required" name="is_required" {{ ! $attribute->is_user_defined ? 'disabled' : '' }}>
                                    <option value="0" {{ $attribute->is_required ? '' : 'selected' }}>{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1" {{ $attribute->is_required ? 'selected' : '' }}>{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_unique">{{ __('admin::app.catalog.attributes.is_unique') }}</label>
                                <select class="control" id="is_unique" name="is_unique" disabled>
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
                                    <?php $selectedValidation = old('validation') ?: $attribute->validation ?>
                                    <label for="validation">{{ __('admin::app.catalog.attributes.input_validation') }}</label>
                                    <select class="control" id="validation" name="validation" {{ ! $attribute->is_user_defined ? 'disabled' : '' }}>
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
                                <label for="value_per_locale">{{ __('admin::app.catalog.attributes.value_per_locale') }}</label>
                                <select class="control" id="value_per_locale" name="value_per_locale" disabled>
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
                                <label for="value_per_channel">{{ __('admin::app.catalog.attributes.value_per_channel') }}</label>
                                <select class="control" id="value_per_channel" name="value_per_channel" disabled>
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
                                <label for="is_filterable">{{ __('admin::app.catalog.attributes.is_filterable') }}</label>
                                <select class="control" id="is_filterable" name="is_filterable">
                                    <option value="0" {{ $attribute->is_filterable ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>
                                    <option value="1" {{ $attribute->is_filterable ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_configurable">{{ __('admin::app.catalog.attributes.is_configurable') }}</label>
                                <select class="control" id="is_configurable" name="is_configurable">
                                    <option value="0" {{ $attribute->is_configurable ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>
                                    <option value="1" {{ $attribute->is_configurable ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_visible_on_front">{{ __('admin::app.catalog.attributes.is_visible_on_front') }}</label>
                                <select class="control" id="is_visible_on_front" name="is_visible_on_front">
                                    <option value="0" {{ $attribute->is_visible_on_front ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>
                                    <option value="1" {{ $attribute->is_visible_on_front ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="use_in_flat">{{ __('admin::app.catalog.attributes.use_in_flat') }}</label>
                                <select class="control" id="use_in_flat" name="use_in_flat">
                                    <option value="0" {{ $attribute->use_in_flat ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>
                                    <option value="1" {{ $attribute->use_in_flat ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_comparable">{{ __('admin::app.catalog.attributes.is_comparable') }}</label>
                                <select class="control" id="is_comparable" name="is_comparable">
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
                <label for="swatch_type">{{ __('admin::app.catalog.attributes.swatch_type') }}</label>
                <select class="control" id="swatch_type" name="swatch_type" v-model="swatch_type">
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
                    <input type="checkbox" class="control" id="default-null-option" name="default-null-option" v-model="isNullOptionChecked">

                    <label class="checkbox-view" for="default-null-option"></label>

                    {{ __('admin::app.catalog.attributes.default_null_option') }}
                </span>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th v-if="show_swatch && (swatch_type == 'color' || swatch_type == 'image')">{{ __('admin::app.catalog.attributes.swatch') }}</th>

                            <th>{{ __('admin::app.catalog.attributes.admin_name') }}</th>

                            <th v-for="locale in allLocales" v-text="`${locale.name} (${locale.code})`"></th>

                            <th>{{ __('admin::app.catalog.attributes.position') }}</th>

                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- `v-show` used here, so that element remain inside the form. Don't use `v-if` here. --}}
                        <tr v-for="(row, index) in optionRows" :key="row.id" v-show="! row.isDelete">
                            <input type="hidden" :name="'options[' + row.id + '][isNew]'" :value="row.isNew">

                            <input type="hidden" :name="'options[' + row.id + '][isDelete]'" :value="row.isDelete">

                            <td v-if="show_swatch && swatch_type == 'color'">
                                <swatch-picker :input-name="'options[' + row.id + '][swatch_value]'" :color="row.swatch_value" colors="text-advanced" show-fallback />
                            </td>

                            <td style="white-space: nowrap;" v-if="show_swatch && swatch_type == 'image'">
                                <div class="control-group" :class="[errors.has('options[' + row.id + '][swatch_value]') ? 'has-error' : '']">
                                    <img style="width: 36px;height: 36px;vertical-align: middle;background: #F2F2F2;border-radius: 2px;margin-right: 10px;" v-if="row.swatch_value_url" :src="row.swatch_value_url"/>
                                    <input type="file" v-validate="'size:600'" accept="image/*" :name="'options[' + row.id + '][swatch_value]'"/>
                                    <span class="control-error" v-if="errors.has('options[' + row.id + '][swatch_value]')" v-text="'{{ __('admin::app.catalog.attributes.validation-messages.max-size') }}'"></span>
                                </div>
                            </td>

                            <td>
                                <div class="control-group" :class="[errors.has(adminName(row)) ? 'has-error' : '']">
                                    <input type="text" v-validate="getOptionValidation(row, '')" v-model="row['admin_name']" :name="adminName(row)" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.attributes.admin_name') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has(adminName(row))" v-text="errors.first(adminName(row))"></span>
                                </div>
                            </td>

                            <td v-for="locale in allLocales">
                                <div class="control-group" :class="[errors.has(localeInputName(row, locale.code)) ? 'has-error' : '']">
                                    <input type="text" v-validate="getOptionValidation(row, locale.code)" v-model="row['locales'][locale.code]" :name="localeInputName(row, locale.code)" class="control" :data-vv-as="`&quot;${locale.name} (${locale.code})&quot;`"/>
                                    <span class="control-error" v-if="errors.has(localeInputName(row, locale.code))" v-text="errors.first(localeInputName(row, locale.code))"></span>
                                </div>
                            </td>

                            <td>
                                <div class="control-group" :class="[errors.has(sortOrderName(row)) ? 'has-error' : '']">
                                    <input type="text" v-validate="getOptionValidation(row, '')" v-model="row['sort_order']" :name="sortOrderName(row)" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.attributes.position') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has(sortOrderName(row))" v-text="errors.first(sortOrderName(row))"></span>
                                </div>
                            </td>

                            <td class="actions">
                                <i class="icon trash-icon" @click="removeRow(row)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-lg btn-primary" id="load-more-btm" style="margin-top: 20px" @click="loadMoreOptions()" v-if="loadMore">
                {{ __('admin::app.catalog.attributes.load-more-options-btn-title') }}
            </button>

            <button type="button" class="btn btn-lg btn-primary" id="add-option-btn" style="margin-top: 20px" @click="addOptionRow()">
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
@endpush
