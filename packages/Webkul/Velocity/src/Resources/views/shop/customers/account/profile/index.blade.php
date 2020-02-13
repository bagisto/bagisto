@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@push('css')
    <style type="text/css">
        .account-head {
            height: 50px;
        }
        .remove-icon {
            right: 15px;
            font-size: 22px;
            height: 24px;
            text-align: center;
            position: absolute;
            border-radius: 50%;
            color: #333;
            width: 24px;
            padding: 0px;
            top: 10px;
        }
        .remove-icon:before {
            content: "x";
        }
    </style>
@endpush


@section('page-detail-wrapper')
    <div class="account-head mb-0">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>
        <span class="account-heading">
            {{ __('shop::app.customer.account.profile.index.title') }}
        </span>

        <span class="account-action">
            <a href="{{ route('customer.profile.edit') }}" class="theme-btn light unset pull-right">
                {{ __('shop::app.customer.account.profile.index.edit') }}
            </a>
        </span>

        <div class="horizontal-rule"></div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

    <div class="account-table-content profile-page-content">
        <div class="table">
            <table>
                <tbody>
                    {!! view_render_event(
                    'bagisto.shop.customers.account.profile.view.table.before', ['customer' => $customer])
                    !!}

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.fname') }}</td>
                        <td>{{ $customer->first_name }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.lname') }}</td>
                        <td>{{ $customer->last_name }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.gender') }}</td>
                        <td>{{ $customer->gender ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.dob') }}</td>
                        <td>{{ $customer->date_of_birth ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('shop::app.customer.account.profile.email') }}</td>
                        <td>{{ $customer->email }}</td>
                    </tr>

                    {!! view_render_event(
                    'bagisto.shop.customers.account.profile.view.table.after', ['customer' => $customer])
                    !!}
                </tbody>
            </table>
        </div>

        <button
            type="submit"
            class="theme-btn mb20" @click="showModal('deleteProfile')" >
            {{ __('shop::app.customer.account.address.index.delete') }}
        </button>

        <form method="POST" action="{{ route('customer.profile.destroy') }}" @submit.prevent="onSubmit">
            @csrf

            <modal id="deleteProfile" :is-open="modalIds.deleteProfile">
                <h3 slot="header">{{ __('shop::app.customer.account.address.index.enter-password') }}
                </h3>
                <i class="rango-close"></i>

                <div slot="body">
                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password" class="required">{{ __('admin::app.users.users.password') }}</label>
                        <input type="password" v-validate="'required|min:6|max:18'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('admin::app.users.users.password') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="page-action">
                        <button type="submit"  class="theme-btn mb20">
                        {{ __('shop::app.customer.account.address.index.delete') }}
                        </button>
                    </div>
                </div>
            </modal>
        </form>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
@endsection