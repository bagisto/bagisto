@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer.store') }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        {{ __('admin::app.customers.customers.title') }}

                        {{ Config::get('carrier.social.facebook.url') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.account.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('admin::app.account.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                                <label for="first_name">{{ __('shop::app.customer.signup-form.firstname') }}</label>
                                <input type="text" class="control" name="first_name" v-validate="'required'" value="{{ old('first_name') }}">
                                <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                                <label for="last_name">{{ __('shop::app.customer.signup-form.lastname') }}</label>
                                <input type="text" class="control" name="last_name" v-validate="'required'" value="{{ old('last_name') }}">
                                <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email">{{ __('shop::app.customer.signup-form.email') }}</label>
                                <input type="email" class="control" name="email" v-validate="'required|email'" value="{{ old('email') }}">
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="gender">{{ __('admin::app.customers.customers.gender') }}</label>
                                <select name="gender" class="control"  v-validate="'required'">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                                <input type="date" class="control" name="date_of_birth"  v-validate="'required'">
                            </div>

                            <div class="control-group">
                                <label for="phone">{{ __('admin::app.customers.customers.phone') }}</label>
                                <input type="text" class="control" name="phone"  v-validate="'required'">
                            </div>

                            <div class="control-group">
                                <label for="name" >{{ __('admin::app.customers.customers.customer_group') }}</label>
                                <select  class="control" name="customer_group_id">
                                @foreach ($customerGroup as $group)
                                    <option value="{{ $group->id }}"> {{ $group->group_name}} </>
                                @endforeach
                                </select>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop