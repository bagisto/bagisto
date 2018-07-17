@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="" @submit.prevent="onSubmit">
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

                    <accordian :title="'{{ __('General') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name" value="{{ $user->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email">{{ __('Email') }}</label>
                                <input type="text" v-validate="'required|email'" class="control" id="email" name="email" value="{{ $user->email }}"/>
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('Password') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" v-validate="'min:6'" class="control" id="password" name="password"/>
                                <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                <input type="password" v-validate="'min:6|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation"/>
                                <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop