@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.users.users.edit-user-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.users.users.edit-user-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.users.users.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.users.users.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.users.users.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="email" name="name" data-vv-as="&quot;{{ __('admin::app.users.users.name') }}&quot;" value="{{ $user->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="required">{{ __('admin::app.users.users.email') }}</label>
                                <input type="text" v-validate="'required|email'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('admin::app.users.users.email') }}&quot;" value="{{ $user->email }}"/>
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.users.users.password') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password">{{ __('admin::app.users.users.password') }}</label>
                                <input type="password" v-validate="'min:6|max:18'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                                <label for="password_confirmation">{{ __('admin::app.users.users.confirm-password') }}</label>
                                <input type="password" v-validate="'min:6|max:18|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation" data-vv-as="&quot;{{ __('admin::app.users.users.confirm-password') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.users.users.status-and-role') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('role_id') ? 'has-error' : '']">
                                <label for="role" class="required">{{ __('admin::app.users.users.role') }}</label>
                                <select v-validate="'required'" class="control" name="role_id" data-vv-as="&quot;{{ __('admin::app.users.users.role') }}&quot;">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('role_id')">@{{ errors.first('role_id') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="status">{{ __('admin::app.users.users.status') }}</label>
                                <span class="checkbox">
                                    <input type="checkbox" id="status" name="status" value="{{ $user->status }}" {{ $user->status ? 'checked' : '' }}>

                                    <label class="checkbox-view" for="status"></label>
                                    {{ __('admin::app.users.users.account-is-active') }}
                                </span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop