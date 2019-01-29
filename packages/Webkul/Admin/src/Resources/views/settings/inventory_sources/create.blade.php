@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.inventory_sources.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.inventory_sources.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.settings.inventory_sources.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.inventory_sources.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('admin::app.settings.inventory_sources.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.settings.inventory_sources.code') }}</label>
                                <input v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.code') }}&quot;" v-code/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.settings.inventory_sources.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.name') }}&quot;" value="{{ old('name') }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">{{ __('admin::app.settings.inventory_sources.description') }}</label>
                                <textarea class="control" id="description" name="description">{{ old('description') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="latitude">{{ __('admin::app.settings.inventory_sources.latitude') }}</label>
                                <input class="control" id="latitude" name="latitude" value="{{ old('latitude') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="longitude">{{ __('admin::app.settings.inventory_sources.longitude') }}</label>
                                <input class="control" id="longitude" name="longitude" value="{{ old('longitude') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="priority">{{ __('admin::app.settings.inventory_sources.priority') }}</label>
                                <input class="control" id="priority" name="priority" value="{{ old('priority') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="status">{{ __('admin::app.settings.inventory_sources.status') }}</label>
                                <span class="checkbox">
                                    <input type="checkbox" id="status" name="status" value="1">
                                    <label class="checkbox-view" for="status"></label>
                                    {{ __('admin::app.settings.inventory_sources.source-is-active') }}
                                </span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.inventory_sources.contact-info') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('contact_name') ? 'has-error' : '']">
                                <label for="contact_name" class="required">{{ __('admin::app.settings.inventory_sources.contact_name') }}</label>
                                <input class="control" v-validate="'required'" class="required" id="contact_name" name="contact_name" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.contact_name') }}&quot;" value="{{ old('contact_name') }}"/>
                                <span class="control-error" v-if="errors.has('contact_name')">@{{ errors.first('contact_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('contact_email') ? 'has-error' : '']">
                                <label for="contact_email" class="required">{{ __('admin::app.settings.inventory_sources.contact_email') }}</label>
                                <input class="control" v-validate="'required|email'" class="required" id="contact_email" name="contact_email" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.contact_email') }}&quot;" value="{{ old('contact_email') }}"/>
                                <span class="control-error" v-if="errors.has('contact_email')">@{{ errors.first('contact_email') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('contact_number') ? 'has-error' : '']">
                                <label for="contact_number" class="required">{{ __('admin::app.settings.inventory_sources.contact_number') }}</label>
                                <input class="control" v-validate="'required'" class="required" id="contact_number" name="contact_number" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.contact_number') }}&quot;" value="{{ old('contact_number') }}"/>
                                <span class="control-error" v-if="errors.has('contact_number')">@{{ errors.first('contact_number') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="contact_fax">{{ __('admin::app.settings.inventory_sources.contact_fax') }}</label>
                                <input class="control" class="required" id="country" name="contact_fax" value="{{ old('contact_fax') }}"/>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.inventory_sources.address') }}'" :active="true">
                        <div slot="body">

                            @include ('admin::customers.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')])

                            <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                                <label for="city" class="required">{{ __('admin::app.settings.inventory_sources.city') }}</label>
                                <input v-validate="'required'" class="control" id="city" name="city" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.city') }}&quot;" value="{{ old('city') }}"/>
                                <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('street') ? 'has-error' : '']">
                                <label for="street" class="required">{{ __('admin::app.settings.inventory_sources.street') }}</label>
                                <input v-validate="'required'" class="control" id="street" name="street" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.street') }}&quot;" value="{{ old('street') }}"/>
                                <span class="control-error" v-if="errors.has('street')">@{{ errors.first('street') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                                <label for="postcode" class="required">{{ __('admin::app.settings.inventory_sources.postcode') }}</label>
                                <input v-validate="'required'" class="control" id="postcode" name="postcode" data-vv-as="&quot;{{ __('admin::app.settings.inventory_sources.postcode') }}&quot;" value="{{ old('postcode') }}"/>
                                <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop