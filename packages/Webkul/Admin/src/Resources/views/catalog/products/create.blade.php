@extends('admin::layouts.content')

@section('css')
    <style>
        .table td .label {
            margin-right: 10px;
        }
        .table td .label:last-child {
            margin-right: 0;
        }
        .table td .label .icon {
            vertical-align: middle;
            cursor: pointer;
        }
    </style>
@stop

@section('content')
    <div class="content">
        <form method="POST" action="" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.products.add-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()

                <?php $familyId = app('request')->input('family') ?>

                <accordian :title="'{{ __('admin::app.catalog.products.general') }}'" :active="true">
                    <div slot="body">

                        <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                            <label for="type" class="required">{{ __('admin::app.catalog.products.product-type') }}</label>
                            <select class="control" v-validate="'required'" id="type" name="type" {{ $familyId ? 'disabled' : '' }}>
                                <option value="simple">{{ __('admin::app.catalog.products.simple') }}</option>
                                <option value="configurable" {{ $familyId ? 'selected' : '' }}>{{ __('admin::app.catalog.products.configurable') }}</option>
                            </select>

                            @if($familyId)
                                <input type="hidden" name="type" value="configurable"/>
                            @endif
                            <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                            <label for="attribute_family_id" class="required">{{ __('admin::app.catalog.products.familiy') }}</label>
                            <select class="control" v-validate="'required'" id="attribute_family_id" name="attribute_family_id" {{ $familyId ? 'disabled' : '' }}>
                                <option value=""></option>
                                @foreach($families as $family)
                                    <option value="{{ $family->id }}" {{ $familyId == $family->id ? 'selected' : '' }}>{{ $family->name }}</option>
                                    @endforeach
                            </select>

                            @if($familyId)
                                <input type="hidden" name="attribute_family_id" value="{{ $familyId }}"/>
                            @endif
                            <span class="control-error" v-if="errors.has('attribute_family_id')">@{{ errors.first('attribute_family_id') }}</span>
                        </div>

                        @if($familyId)
                            <div class="control-group" :class="[errors.has('sku') ? 'has-error' : '']">
                                <label for="sku">{{ __('admin::app.catalog.products.sku') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="sku" name="sku" value="{{ old('sku') }}"/>
                                <span class="control-error" v-if="errors.has('sku')">@{{ errors.first('sku') }}</span>
                            </div>
                        @endif
                    
                    </div>
                </accordian>

                @if($familyId)
                    <accordian :title="'{{ __('admin::app.catalog.products.configurable-attributes') }}'" :active="true">
                        <div slot="body">

                            <div class="table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin::app.catalog.products.attribute-header') }}</th>
                                            <th>{{ __('admin::app.catalog.products.attribute-option-header') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                        
                                    <tbody>
                                        @foreach($configurableFamily->configurable_attributes as $attribute)
                                            <tr>
                                                <td>
                                                    {{ $attribute->name }}
                                                </td>
                                                <td>
                                                    @foreach($attribute->options as $option)
                                                        <span class="label">
                                                            <input type="hidden" name="super_attributes[{{$attribute->id}}][options][]" value="{{ $option->id }}"/>
                                                            {{ $option->label }}

                                                            <i class="icon cross-icon"></i>
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td class="actions">
                                                    <i class="icon trash-icon"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </accordian>
                @endif

            </div>

        </form>
    </div>
@stop

@section('javascript')
    <script>
        (function() {

        })();
    </script>
@stop