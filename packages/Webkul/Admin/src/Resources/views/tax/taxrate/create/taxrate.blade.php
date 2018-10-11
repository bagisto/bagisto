@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.taxrate.title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.taxrate.create') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.configuration.taxrate.title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.configuration.taxrule.create') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('admin::app.configuration.taxrate.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('identifier') ? 'has-error' : '']">
                                <label for="identifier" class="required">{{ __('admin::app.configuration.taxrate.identifier') }}</label>

                                <input v-validate="'required'" class="control" id="identifier" name="identifier" value="{{ old('identifier') }}"/>

                                <span class="control-error" v-if="errors.has('identifier')">@{{ errors.first('identifier') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('state') ? 'has-error' : '']">
                                <label for="state" class="required">{{ __('admin::app.configuration.taxrate.state') }}</label>

                                <input v-validate="'required'" class="control" id="state" name="state" value="{{ old('state') }}"/>

                                <span class="control-error" v-if="errors.has('state')">@{{ errors.first('state') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
                                <label for="country" class="required">{{ __('admin::app.configuration.taxrate.country') }}</label>

                                <input v-validate="'required'" class="control" id="country" name="country" value="{{ old('country') }}"/>

                                <span class="control-error" v-if="errors.has('country')">@{{ errors.first('country') }}</span>
                            </div>

                            <div class="control-group">
                                <span class="checkbox">

                                    <input type="checkbox" id="is_zip" name="is_zip" id="is_zip" onclick="myFunction()" value="{{ old('is_zip') }}">

                                    <label class="checkbox-view" for="is_zip"></label>
                                    Enable Zip Range
                                </span>
                            </div>

                            <div class="control-group" :class="[errors.has('zip_code') ? 'has-error' : '']" id="zip_code">
                                <label for="zip_code" class="required">{{ __('admin::app.configuration.taxrate.zip_code') }}</label>

                                <input class="control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}"/>

                                <span class="control-error" v-if="errors.has('zip_code')">@{{ errors.first('zip_code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('zip_from') ? 'has-error' : '']" id="zip_from">
                                <label for="zip_from" class="required">{{ __('admin::app.configuration.taxrate.zip_from') }}</label>

                                <input v-validate="'numeric'" class="control" id="zip_from" name="zip_from" value="{{ old('zip_from') }}"/>

                                <span class="control-error" v-if="errors.has('zip_from')">@{{ errors.first('zip_from') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('zip_to') ? 'has-error' : '']" id="zip_to">
                                <label for="zip_to" class="required">{{ __('admin::app.configuration.taxrate.zip_to') }}</label>

                                <input v-validate="'numeric'" class="control" id="zip_to" name="zip_to" value="{{ old('zip_to') }}"/>

                                <span class="control-error" v-if="errors.has('zip_to')">@{{ errors.first('zip_to') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('tax_rate') ? 'has-error' : '']">
                                <label for="tax_rate" class="required">{{ __('admin::app.configuration.taxrate.tax_rate') }}</label>

                                <input v-validate="'required'" class="control" id="tax_rate" name="tax_rate" value="{{ old('tax_rate') }}"/>

                                <span class="control-error" v-if="errors.has('tax_rate')">@{{ errors.first('tax_rate') }}</span>
                            </div>

                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>

            window.onload = function () {
                document.getElementById("zip_from").style.display = "none";

                document.getElementById("zip_to").style.display = "none";
            };

            function myFunction() {
                value = document.getElementById('is_zip').checked;

                if(value) {
                    document.getElementById("zip_from").style.display = "";

                    document.getElementById("zip_to").style.display = "";

                    document.getElementById("zip_code").style.display = "none";
                } else {
                    document.getElementById("zip_from").style.display = "none";

                    document.getElementById("zip_to").style.display = "none";

                    document.getElementById("zip_code").style.display = "";
                }

                console.log("Value = ", value);
            }
        </script>
    @endpush
@stop