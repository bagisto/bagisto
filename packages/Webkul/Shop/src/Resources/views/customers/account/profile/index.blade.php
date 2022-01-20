@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('account-content')
    <div class="account-layout">
        <div class="account-head">
            <span class="back-icon"><a href="{{ route('customer.profile.index') }}"><i class="icon icon-menu-back"></i></a></span>

            <span class="account-heading">{{ __('shop::app.customer.account.profile.index.title') }}</span>

            <span class="account-action">
                <a href="{{ route('customer.profile.edit') }}">{{ __('shop::app.customer.account.profile.index.edit') }}</a>
            </span>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

        <div class="account-table-content" style="width: 50%;">
            <table style="color: #5E5E5E;">
                <tbody>
                    {!! view_render_event('bagisto.shop.customers.account.profile.view.table.before', ['customer' => $customer]) !!}

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.fname') }}</td>
                        <td>{{ $customer->first_name }}</td>
                    </tr>

                    {!! view_render_event('bagisto.shop.customers.account.profile.view.table.first_name.after', ['customer' => $customer]) !!}

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.lname') }}</td>
                        <td>{{ $customer->last_name }}</td>
                    </tr>

                    {!! view_render_event('bagisto.shop.customers.account.profile.view.table.last_name.after', ['customer' => $customer]) !!}

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.gender') }}</td>
                        <td>{{ __($customer->gender) }}</td>
                    </tr>

                    {!! view_render_event('bagisto.shop.customers.account.profile.view.table.gender.after', ['customer' => $customer]) !!}

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.dob') }}</td>
                        <td>{{ $customer->date_of_birth }}</td>
                    </tr>

                    {!! view_render_event('bagisto.shop.customers.account.profile.view.table.date_of_birth.after', ['customer' => $customer]) !!}

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.email') }}</td>
                        <td>{{ $customer->email }}</td>
                    </tr>

                    <tr>
                        <td> 
                            <button type="submit" @click="showModal('deleteProfile')" class="btn btn-lg btn-primary mt-10">
                                {{ __('shop::app.customer.account.address.index.delete') }}
                            </button>
                        </td>                        
                    </tr>

                    {!! view_render_event('bagisto.shop.customers.account.profile.view.table.after', ['customer' => $customer]) !!}
                </tbody>
            </table>           

            <form method="POST" action="{{ route('customer.profile.destroy') }}" @submit.prevent="onSubmit">
                @csrf

                <modal id="deleteProfile" :is-open="modalIds.deleteProfile">
                    <h3 slot="header">{{ __('shop::app.customer.account.address.index.enter-password') }}</h3>

                    <div slot="body">
                        <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                            <label for="password" class="required">{{ __('admin::app.users.users.password') }}</label>
                            <input type="password" v-validate="'required|min:6|max:18'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                        </div>

                        <div class="page-action">
                            <button type="submit"  class="btn btn-lg btn-primary mt-10">
                            {{ __('shop::app.customer.account.address.index.delete') }}
                            </button>
                        </div>
                    </div>
                </modal>
            </form>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
    </div>
@endsection
