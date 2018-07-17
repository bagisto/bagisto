@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.users.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Add User') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('Save User') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('General') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="email" name="name"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email">{{ __('Email') }}</label>
                                <input type="text" v-validate="'required|email'" class="control" id="email" name="email"/>
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('Password') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" v-validate="'min:6|max:18'" class="control" id="password" name="password"/>
                                <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                <input type="password" v-validate="'min:6|max:18|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation"/>
                                <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('Status and Role') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('role_id') ? 'has-error' : '']">
                                <label for="role">{{ __('Role') }}</label>
                                <select v-validate="'required'" class="control" name="role_id">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('role_id')">@{{ errors.first('role_id') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="status">{{ __('Status') }}</label>
                                <span class="checkbox">
                                    <input type="checkbox" id="status" name="status" value="1">
                                    <label class="checkbox-view" for="status"></label>
                                    {{ __('Account is Active') }}
                                </span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop