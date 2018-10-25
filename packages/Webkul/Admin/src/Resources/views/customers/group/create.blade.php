@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.groups.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.groups.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        {{ __('admin::app.customers.groups.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.groups.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">
                            {{ __('admin::app.customers.groups.name') }}
                        </label>
                        <input type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') }}">
                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="is_user_defined">
                            {{ __('admin::app.customers.groups.is_user_defined') }}
                        </label>
                        <span class="checkbox">
                            <input type="checkbox"  name="is_user_defined" value="0">
                            <label class="checkbox-view" for="is_user_defined"></label>
                            {{ __('admin::app.customers.groups.yes') }}
                        </span>
                    </div>

                </div>

            </div>
        </form>
    </div>
@stop