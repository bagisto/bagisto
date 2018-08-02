@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.attributes.edit-title') }}
@stop


@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.attributes.update', $attribute->id) }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.attributes.edit-title') }}</h1>
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

                    <accordian :title="'{{ __('admin::app.catalog.attributes.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.catalog.attributes.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" value="{{ $attribute->code }}" disabled="disabled"/>
                                <input type="hidden" name="code" value="{{ $attribute->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group">
                                <?php $selectedOption = old('type') ?: $attribute->type ?>
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
                                </select>
                                <input type="hidden" name="type" value="{{ $attribute->type }}"/>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.catalog.attributes.label') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('admin_name') ? 'has-error' : '']">
                                <label for="admin_name" class="required">{{ __('admin::app.catalog.attributes.admin') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="admin_name" name="admin_name" value="{{ old('admin_name') ?: $attribute->admin_name }}"/>
                                <span class="control-error" v-if="errors.has('admin_name')">@{{ errors.first('admin_name') }}</span>
                            </div>

                            @foreach(Webkul\Core\Models\Locale::all() as $locale)

                                <div class="control-group">
                                    <label for="locale-{{ $locale->code }}">{{ $locale->name . ' (' . $locale->code . ')' }}</label>
                                    <input type="text" class="control" id="locale-{{ $locale->code }}" name="<?php echo $locale->code; ?>[name]" value="{{ old($locale->code)['name'] ?: $attribute->translate($locale->code)['name'] }}"/>
                                </div>

                            @endforeach

                        </div>
                    </accordian>

                    <div class="{{ in_array($attribute->type, ['select', 'multiselect', 'checkbox']) ?: 'hide' }}">
                        <accordian :title="'{{ __('admin::app.catalog.attributes.options') }}'" :active="true" :id="'options'">
                            <div slot="body">
                            
                                <option-wrapper></option-wrapper>
                                
                            </div>
                        </accordian>
                    </div>

                    <accordian :title="'{{ __('admin::app.catalog.attributes.validations') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="is_required">{{ __('admin::app.catalog.attributes.is_required') }}</label>
                                <select class="control" id="is_required" name="is_required">
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

                            <div class="control-group">
                                <?php $selectedValidation = old('input_validation') ?: $attribute->input_validation ?>
                                <label for="validation">{{ __('admin::app.catalog.attributes.input_validation') }}</label>
                                <select class="control" id="validation" name="validation">
                                    <option value=""></option>
                                    <option value="number" {{ $selectedValidation == 'number' ? 'selected' : '' }}>
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

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.catalog.attributes.configuration') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="status">{{ __('admin::app.catalog.attributes.status') }}</label>
                                <select class="control" id="status" name="status">
                                    <option value="1" {{ $attribute->status ? 'selected' : '' }}>
                                        {{ __('admin::app.catalog.attributes.yes') }}
                                    </option>
                                    <option value="0" {{ $attribute->status ? '' : 'selected' }}>
                                        {{ __('admin::app.catalog.attributes.no') }}
                                    </option>
                                </select>
                            </div>
                        
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

                        </div>
                    </accordian>
                </div>
            </div>

        </form>
    </div>
@stop

@section('javascript')
    <script type="text/x-template" id="options-template">
        <div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin::app.catalog.attributes.admin_name') }}</th>
                            
                            @foreach(Webkul\Core\Models\Locale::all() as $locale)

                                <th>{{ $locale->name . ' (' . $locale->code . ')' }}</th>
                            
                            @endforeach

                            <th>{{ __('admin::app.catalog.attributes.position') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                        
                    <tbody>
                        <tr v-for="row in optionRows">
                            <td>
                                <div class="control-group" :class="[errors.has(adminName(row)) ? 'has-error' : '']">
                                    <input type="text" v-validate="'required'" v-model="row['admin_name']" :name="adminName(row)" class="control"/>
                                    <span class="control-error" v-if="errors.has(adminName(row))">@{{ errors.first(adminName(row)) }}</span>
                                </div>
                            </td>

                            @foreach(Webkul\Core\Models\Locale::all() as $locale)
                                <td>
                                    <div class="control-group" :class="[errors.has(localeInputName(row, '{{ $locale->code }}')) ? 'has-error' : '']">
                                        <input type="text" v-validate="'required'" v-model="row['{{ $locale->code }}']" :name="localeInputName(row, '{{ $locale->code }}')" class="control"/>
                                        <span class="control-error" v-if="errors.has(localeInputName(row, '{{ $locale->code }}'))">@{{ errors.first(localeInputName(row, '{!! $locale->code !!}')) }}</span>
                                    </div>
                                </td>
                            @endforeach

                            <td>
                                <div class="control-group" :class="[errors.has(sortOrderName(row)) ? 'has-error' : '']">
                                    <input type="text" v-validate="'required|numeric'" v-model="row['sort_order']" :name="sortOrderName(row)" class="control"/>
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

            <button type="button" class="btn btn-lg btn-primary" id="add-option-btn" style="margin-top: 20px" @click="addOptionRow()">
                {{ __('admin::app.catalog.attributes.add-option-btn-title') }}
            </button>
        </div>
    </script>

    <script>
        $(document).ready(function () {
            $('#type').on('change', function (e) {
                if(['select', 'multiselect', 'checkbox'].indexOf($(e.target).val()) === -1) {
                    $('#options').parent().addClass('hide')
                } else {
                    $('#options').parent().removeClass('hide')
                }
            })

            var optionWrapper = Vue.component('option-wrapper', {

                template: '#options-template', 

                created () {
                    @foreach($attribute->options as $option)
                        this.optionRowCount++;
                        var row = {'id': '{{ $option->id }}', 'admin_name': '{{ $option->admin_name }}', 'sort_order': '{{ $option->sort_order }}'};

                        @foreach(Webkul\Core\Models\Locale::all() as $locale)
                            row['{{ $locale->code }}'] = "{{ $option->translate($locale->code)['label'] }}";
                        @endforeach

                        this.optionRows.push(row);
                    @endforeach
                },

                data: () => ({
                    optionRowCount: 0,
                    optionRows: []
                }),

                methods: {
                    addOptionRow () {
                        var rowCount = this.optionRowCount++;
                        var row = {'id': 'option_' + rowCount};

                        @foreach(Webkul\Core\Models\Locale::all() as $locale)
                            row['{{ $locale->code }}'] = '';
                        @endforeach

                        this.optionRows.push(row);
                    },

                    removeRow (row) {
                        var index = this.optionRows.indexOf(row)
                        Vue.delete(this.optionRows, index);
                    },

                    adminName (row) {
                        return 'options[' + row.id + '][admin_name]';
                    },

                    localeInputName (row, locale) {
                        return 'options[' + row.id + '][' + locale + '][label]';
                    },

                    sortOrderName (row) {
                        return 'options[' + row.id + '][sort_order]';
                    }
                }
            })

            new Vue({
                el: '#options',

                components: {
                    optionWrapper: optionWrapper
                },
            })
        });
    </script>
@stop