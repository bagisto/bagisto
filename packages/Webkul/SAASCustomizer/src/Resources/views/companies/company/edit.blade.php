@extends('saas::companies.layouts.master')

@section('page_title')
    {{ __('Edit Company') }}
@endsection

@section('content-wrapper')
    <seller-registration></seller-registration>

    @push('scripts')
        <script type="text/x-template" id ="seller-details-form">
            <div class="mb-30" style="margin-left: 200px;">
                <h2>Company Details</h2>

                <form method="POST" action="{{ route('super.companies.update', $company->id) }}">
                    @csrf

                    <div class="control-group">
                        <label for="name" class="required">Name</label>

                        <input type="text" v-validate="'required'" class="control" v-model="name" placeholder="name" name="name" data-vv-as="&quot;{{ __('shop::app.customer.login-form.name') }}&quot;">

                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email" class="required">Email</label>

                        <input type="text" class="control" name="email" v-model="email" placeholder="email" data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;">

                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('domain') ? 'has-error' : '']">
                        <label for="domain" class="required">Domain</label>

                        <input type="text" v-validate="'required'" class="control" name="domain" v-model="domain" placeholder="domain" data-vv-as="&quot;{{ __('shop::app.customer.login-form.domain') }}&quot;">

                        <span class="control-error" v-if="errors.has('domain')">@{{ errors.first('domain') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('is_active') ? 'has-error' : '']">
                        <label for="is_active" class="required">Status</label>

                        <select class="control" name="is_active" v-model="is_active" v-validate="'required'">
                            <option value="0">Deactivate</option>
                            <option value="1">Activate</option>
                        </select>

                        <span class="control-error" v-if="errors.has('is_active')">@{{ errors.first('is_active') }}</span>

                        @if ($company->is_active)
                            <span class="badge badge-md badge-success">
                                Activated
                            </span>
                        @else
                            <span class="badge badge-md badge-danger">
                                De Activated
                            </span>
                        @endif
                    </div>

                    <button class="btn btn-lg btn-primary">
                        Save
                    </button>
                </form>
            </div>
        </script>

        <script>
            Vue.component('seller-registration', {
                template: '#seller-details-form',
                inject: ['$validator'],

                data: () => ({
                    name: '{{ $company->name }}',
                    email: '{{ $company->email }}',
                    domain: '{{ $company->domain }}',
                    is_active: null
                }),

                mounted: function () {
                    @if ($company->is_active)
                        this.is_active = 1;
                    @else
                        this.is_active = 1;
                    @endif
                }
            });
        </script>
    @endpush
@endsection