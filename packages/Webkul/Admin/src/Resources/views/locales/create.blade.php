@extends('admin::layouts.content')

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.locales.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Add Locale') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('Save Locale') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('General') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code">{{ __('Code') }}</label>
                                <input v-validate="'required'" class="control" id="code" name="code"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name">{{ __('Name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>
                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop