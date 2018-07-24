@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.inventory_sources.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.inventory_sources.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.settings.inventory_sources.add-title') }}</h1>
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
                                <input v-validate="'required'" class="control" id="code" name="code" value="{{ old('code') }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.settings.inventory_sources.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}"/>
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

                            <div class="control-group">
                                <label for="contact_name">{{ __('admin::app.settings.inventory_sources.contact_name') }}</label>
                                <input class="control" id="contact_name" name="contact_name" value="{{ old('contact_name') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="contact_email">{{ __('admin::app.settings.inventory_sources.contact_email') }}</label>
                                <input class="control" id="contact_email" name="contact_email" value="{{ old('contact_email') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="contact_number">{{ __('admin::app.settings.inventory_sources.contact_number') }}</label>
                                <input class="control" id="contact_number" name="contact_number" value="{{ old('contact_number') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="contact_fax">{{ __('admin::app.settings.inventory_sources.contact_fax') }}</label>
                                <input class="control" id="country" name="contact_fax" value="{{ old('contact_fax') }}"/>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.settings.inventory_sources.address') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="country">{{ __('admin::app.settings.inventory_sources.country') }}</label>
                                <select class="control" id="country" name="country">
                                    @foreach(country()->all() as $countryCoode => $countryName)
                                        <option value="{{ $countryCoode }}" {{ old('country') == $countryCoode ? 'selected' : '' }}>
                                            {{ $countryName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="state">{{ __('admin::app.settings.inventory_sources.state') }}</label>
                                <input class="control" id="state" name="state" value="{{ old('state') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="city">{{ __('admin::app.settings.inventory_sources.city') }}</label>
                                <input class="control" id="city" name="city" value="{{ old('city') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="street">{{ __('admin::app.settings.inventory_sources.street') }}</label>
                                <input class="control" id="street" name="street" value="{{ old('street') }}"/>
                            </div>

                            <div class="control-group">
                                <label for="postcode">{{ __('admin::app.settings.inventory_sources.postcode') }}</label>
                                <input class="control" id="postcode" name="postcode" value="{{ old('postcode') }}"/>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop