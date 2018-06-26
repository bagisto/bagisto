@extends('admin::layouts.master')

@section('content')
    @include ('admin::layouts.nav-aside')

    <div class="content">
        <form method="POST" action="">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        {{ __('My Account') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <div class="control-group" :class="[errors.first('name') ? 'has-error' : '']">
                        <label for="">{{ __('Name') }}</label>
                        <input type="text" v-validate="'required'" class="control" name="name" value="{{ $user->name }}"/>
                        <span class="control-error" v-if="errors.first('name')">@{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.first('email') ? 'has-error' : '']">
                        <label for="">{{ __('Email') }}</label>
                        <input type="text" v-validate="'required|email'" class="control" name="email" value="{{ $user->email }}"/>
                        <span class="control-error" v-if="errors.first('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="">{{ __('Password') }}</label>
                        <input type="text" class="control" name="password"/>
                    </div>

                    <div class="control-group">
                        <label for="">{{ __('Confirm Password') }}</label>
                        <input type="text" class="control" name="confirm_password"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop