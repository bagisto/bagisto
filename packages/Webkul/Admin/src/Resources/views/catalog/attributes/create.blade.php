@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.attributes.store') }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Add Attribute') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('Save Attribute') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('General') }}'" :active="true">
                        <div class="accordian-content">
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code">{{ __('Attribute Code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="type">{{ __('Attribute Type') }}</label>
                                <select class="control" id="type" name="type">
                                    <option value="text">{{ __('Text') }}</option>
                                    <option value="textarea">{{ __('Textarea') }}</option>
                                    <option value="integer">{{ __('Integer') }}</option>
                                    <option value="select">{{ __('Select') }}</option>
                                    <option value="multiselect">{{ __('Multiselect') }}</option>
                                    <option value="checkbox">{{ __('Multiselect') }}</option>
                                    <option value="datetime">{{ __('Datetime') }}</option>
                                    <option value="date">{{ __('Date') }}</option>
                                </select>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('Label') }}'" :active="true">
                        <div class="accordian-content">

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name">{{ __('Admin') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            @foreach(Webkul\Core\Models\Locale::all() as $locale)

                                <div class="control-group">
                                    <label for="locale-{{ $locale->code }}">{{ $locale->name . ' (' . $locale->code . ')' }}</label>
                                    <input type="text" class="control" id="locale-{{ $locale->code }}" name="<?php echo $locale->code; ?>[name]"/>
                                </div>

                            @endforeach

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('Options') }}'" :active="true" :class-name="'hide'" :id="'options'">
                    </accordian>

                    <accordian :title="'{{ __('Validations') }}'" :active="true">
                    </accordian>

                    <accordian :title="'{{ __('Configuration') }}'" :active="true">
                    </accordian>
                </div>
            </div>

        </form>
    </div>
@stop

@section('javascript')

@stop