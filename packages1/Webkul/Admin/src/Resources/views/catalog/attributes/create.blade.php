@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.attributes.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.attributes.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.catalog.attributes.add-title') }}
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

                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.general.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.attributes.general') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.catalog.attributes.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}"  data-vv-as="&quot;{{ __('admin::app.catalog.attributes.code') }}&quot;" v-code/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="type" class="required">{{ __('admin::app.catalog.attributes.type') }}</label>
                                <select class="control" id="type" name="type">
                                    <option value="text">{{ __('admin::app.catalog.attributes.text') }}</option>
                                    <option value="textarea">{{ __('admin::app.catalog.attributes.textarea') }}</option>
                                    <option value="price">{{ __('admin::app.catalog.attributes.price') }}</option>
                                    <option value="boolean">{{ __('admin::app.catalog.attributes.boolean') }}</option>
                                    <option value="select">{{ __('admin::app.catalog.attributes.select') }}</option>
                                    <option value="multiselect">{{ __('admin::app.catalog.attributes.multiselect') }}</option>
                                    <option value="datetime">{{ __('admin::app.catalog.attributes.datetime') }}</option>
                                    <option value="date">{{ __('admin::app.catalog.attributes.date') }}</option>
                                    <option value="image">{{ __('admin::app.catalog.attributes.image') }}</option>
                                    <option value="file">{{ __('admin::app.catalog.attributes.file') }}</option>
                                    <option value="checkbox">{{ __('admin::app.catalog.attributes.checkbox') }}</option>
                                </select>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.general.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.general.after') !!}


                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.label.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.attributes.label') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.label.controls.before') !!}

                            <div class="control-group" :class="[errors.has('admin_name') ? 'has-error' : '']">
                                <label for="admin_name" class="required">{{ __('admin::app.catalog.attributes.admin') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" data-vv-as="&quot;{{ __('admin::app.catalog.attributes.admin') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('admin_name')">@{{ errors.first('admin_name') }}</span>
                            </div>

                            @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)

                                <div class="control-group">
                                    <label for="locale-{{ $locale->code }}">{{ $locale->name . ' (' . $locale->code . ')' }}</label>
                                    <input type="text" class="control" id="locale-{{ $locale->code }}" name="<?php echo $locale->code; ?>[name]" value="{{ old($locale->code)['name'] ?? '' }}"/>
                                </div>

                            @endforeach

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.label.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.label.after') !!}



                    <div class="hide">
                        {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.options.before') !!}

                        <accordian :title="'{{ __('admin::app.catalog.attributes.options') }}'" :active="true" :id="'options'">
                            <div slot="body">

                                {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.options.controls.before') !!}

                                <option-wrapper></option-wrapper>

                                {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.options.controls.after') !!}

                            </div>
                        </accordian>

                        {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.options.after') !!}
                    </div>


                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.validations.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.attributes.validations') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.options.controls.before') !!}

                            <div class="control-group">
                                <label for="is_required">{{ __('admin::app.catalog.attributes.is_required') }}</label>
                                <select class="control" id="is_required" name="is_required">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_unique">{{ __('admin::app.catalog.attributes.is_unique') }}</label>
                                <select class="control" id="is_unique" name="is_unique">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="validation">{{ __('admin::app.catalog.attributes.input_validation') }}</label>
                                <select class="control" id="validation" name="validation">
                                    <option value=""></option>
                                    <option value="numeric">{{ __('admin::app.catalog.attributes.number') }}</option>
                                    <option value="email">{{ __('admin::app.catalog.attributes.email') }}</option>
                                    <option value="decimal">{{ __('admin::app.catalog.attributes.decimal') }}</option>
                                    <option value="url">{{ __('admin::app.catalog.attributes.url') }}</option>
                                </select>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.options.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.validations.after') !!}


                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.configuration.before') !!}

                    <accordian :title="'{{ __('admin::app.catalog.attributes.configuration') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.configuration.controls.before') !!}

                            <div class="control-group">
                                <label for="value_per_locale">{{ __('admin::app.catalog.attributes.value_per_locale') }}</label>
                                <select class="control" id="value_per_locale" name="value_per_locale">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="value_per_channel">{{ __('admin::app.catalog.attributes.value_per_channel') }}</label>
                                <select class="control" id="value_per_channel" name="value_per_channel">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_filterable">{{ __('admin::app.catalog.attributes.is_filterable') }}</label>
                                <select class="control" id="is_filterable" name="is_filterable">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_configurable">{{ __('admin::app.catalog.attributes.is_configurable') }}</label>
                                <select class="control" id="is_configurable" name="is_configurable">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_visible_on_front">{{ __('admin::app.catalog.attributes.is_visible_on_front') }}</label>
                                <select class="control" id="is_visible_on_front" name="is_visible_on_front">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="use_in_flat">{{ __('admin::app.catalog.attributes.use_in_flat') }}</label>
                                <select class="control" id="use_in_flat" name="use_in_flat">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="is_comparable">{{ __('admin::app.catalog.attributes.is_comparable') }}</label>
                                <select class="control" id="is_comparable" name="is_comparable">
                                    <option value="0">{{ __('admin::app.catalog.attributes.no') }}</option>
                                    <option value="1">{{ __('admin::app.catalog.attributes.yes') }}</option>
                                </select>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.configuration.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.attribute.create_form_accordian.configuration.after') !!}

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
                    <input type="checkbox" class="control" id="default-null-option" name="default-null-option" v-model="isNullOptionChecked" >

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

                            @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)

                                <th>{{ $locale->name . ' (' . $locale->code . ')' }}</th>

                            @endforeach

                            <th>{{ __('admin::app.catalog.attributes.position') }}</th>

                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="row in optionRows">
                            <td v-if="show_swatch && swatch_type == 'color'">
                                <swatch-picker :input-name="'options[' + row.id + '][swatch_value]'" :color="row.swatch_value" colors="text-advanced" show-fallback />
                            </td>

                            <td v-if="show_swatch && swatch_type == 'image'">
                                <input type="file" accept="image/*" :name="'options[' + row.id + '][swatch_value]'"/>
                            </td>

                            <td>
                                <div class="control-group" :class="[errors.has(adminName(row)) ? 'has-error' : '']">
                                    <input type="text" v-validate="'required'" v-model="row['admin_name']" :name="adminName(row)" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.attributes.admin_name') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has(adminName(row))">@{{ errors.first(adminName(row)) }}</span>
                                </div>
                            </td>

                            @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                                <td>
                                    <div class="control-group" :class="[errors.has(localeInputName(row, '{{ $locale->code }}')) ? 'has-error' : '']">
                                        <input type="text" v-validate="getOptionValidation(row, '{{ $locale->code }}')"  v-model="row['{{ $locale->code }}']" :name="localeInputName(row, '{{ $locale->code }}')" class="control" data-vv-as="&quot;{{ $locale->name . ' (' . $locale->code . ')' }}&quot;"/>
                                        <span class="control-error" v-if="errors.has(localeInputName(row, '{{ $locale->code }}'))">@{{ errors.first(localeInputName(row, '{!! $locale->code !!}')) }}</span>
                                    </div>
                                </td>
                            @endforeach

                            <td>
                                <div class="control-group" :class="[errors.has(sortOrderName(row)) ? 'has-error' : '']">
                                    <input type="text" v-validate="'required|numeric'" :name="sortOrderName(row)" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.attributes.position') }}&quot;"/>
                                    <span class="control-error" v-if="errors.has(sortOrderName(row))">@{{ errors.first(sortOrderName(row)) }}</span>
                                </div>
                            </td>

                            <td class="actions">
                                <i class="icon trash-icon" @click="removeRow(row)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-lg btn-primary mt-20" id="add-option-btn" @click="addOptionRow(false)">
                {{ __('admin::app.catalog.attributes.add-option-btn-title') }}
            </button>
        </div>
    </script>

    <script>
        $(document).ready(function () {
            $('#type').on('change', function (e) {
                if (['select', 'multiselect', 'checkbox'].indexOf($(e.target).val()) === -1) {
                    $('#options').parent().addClass('hide')
                } else {
                    $('#options').parent().removeClass('hide')
                }
            })
        });


        Vue.component('option-wrapper', {

            template: '#options-template',

            inject: ['$validator'],

            data: function() {
                return {
                    optionRowCount: 1,
                    optionRows: [],
                    show_swatch: false,
                    swatch_type: '',
                    isNullOptionChecked: false,
                    idNullOption: null
                }
            },

            created: function () {
                var this_this = this;

                $(document).ready(function () {
                    $('#type').on('change', function (e) {
                        if (['select'].indexOf($(e.target).val()) === -1) {
                            this_this.show_swatch = false;
                        } else {
                            this_this.show_swatch = true;
                        }
                    });
                });
            },

            methods: {
                addOptionRow: function (isNullOptionRow) {
                    const rowCount = this.optionRowCount++;
                    const id = 'option_' + rowCount;
                    let row = {'id': id};

                    @foreach (app('Webkul\Core\Repositories\LocaleRepository')->all() as $locale)
                        row['{{ $locale->code }}'] = '';
                    @endforeach

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
                    Vue.delete(this.optionRows, index);
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

                getOptionValidation: (row, localeCode) => {
                    if (row.notRequired === true) {
                        return '';
                    }

                    return ('{{ app()->getLocale() }}' === localeCode) ? 'required' : '';
                }
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
            }
        })
    </script>
@endpush
