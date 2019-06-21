@extends('saas::companies.layouts.master')

@section('page_title')
    Seller Registration
@endsection

@section('content-wrapper')
    <seller-registration></seller-registration>

    @push('scripts')
        <script type="text/x-template" id ="seller-registration-form">
            <div class="form-container">
                <div class="step-navigator">
                    <ul class="step-list">
                        <li class="step-item" :class="{ active: isOneActive }" v-on:click="stepNav(1)">1</li>

                        <li class="step-item" :class="{ active: isTwoActive }" v-on:click="stepNav(2)">2</li>

                        <li class="step-item" :class="{ active: isThreeActive }" v-on:click="stepNav(3)">3</li>
                    </ul>
                </div>

                <form class="registration" @submit.prevent="onSubmit">
                    @csrf

                    <div class="step-one" v-show="step_one" data-vv-scope="step-one">
                        <h3 class="mb-30">Step 1:</h3>
                        <h4>Authentication Credentials</h4>

                        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="required">Email</label>

                            <input type="text" v-validate="'required|email|max:191'" class="control" v-model="email" name="email" data-vv-as="&quot;{{ __('email') }}&quot;" placeholder="Auth Email">

                            {{-- data-vv-as="&quot;{{ __('shop::app.customer.login-form.email') }}&quot;" --}}

                            <span class="control-error" v-show="errors.has('email')">@{{ errors.first('email') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                            <label for="password" class="required">Password</label>

                            <input type="password" name="password" v-validate="'required|min:6'" ref="password" class="control" v-model="password" placeholder="password" data-vv-as="&quot;{{ __('password') }}&quot;">

                            <span class="control-error" v-show="errors.has('password')">@{{ errors.first('password') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                            <label for="password_confirmation" class="required">Confirm Password</label>

                            <input type="password" v-validate="'required|min:6|confirmed:password'" class="control" v-model="password_confirmation" name="password_confirmation" placeholder="Confirm Password" data-vv-as="&quot;{{ __('confirm password') }}&quot;">

                            <span class="control-error" v-show="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                        </div>

                        <div class="control-group">
                            <input type="submit" class="btn btn-lg btn-primary" :disabled="errors.has('password') || errors.has('password_confirmation') || errors.has('email')" @click="validateForm('step-one')" value="Continue">
                        </div>
                    </div>

                    <div class="step-two" v-show="step_two" data-vv-scope="step-two">
                        <h3 class="mb-30">Step 2:</h3>
                        <h4>Personal Details</h4>
                        <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']" >
                            <label for="first_name" class="required">First Name</label>

                            <input type="text" class="control" v-model="first_name" name="first_name" placeholder="First Name" v-validate="'required|alpha_spaces'" data-vv-as="&quot;First Name&quot;">

                            <span class="control-error" v-show="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                            <label for="last_name">Last Name</label>

                            <input type="text" class="control" name="last_name" v-model="last_name" placeholder="Last Name" v-validate="'alpha_spaces'" data-vv-as="&quot;{{ __('last name') }}&quot;">

                            <span class="control-error" v-show="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('phone_no') ? 'has-error' : '']">
                            <label for="phone_no" class="required">Phone Number</label>

                            <input type="text" class="control" name="phone_no" v-model="phone_no" placeholder="Phone Number" v-validate="'required|numeric|max:11'" data-vv-as="&quot;{{ __('phone') }}&quot;">

                            <span class="control-error" v-show="errors.has('phone_no')">@{{ errors.first('phone_no') }}</span>
                        </div>

                        <div class="control-group">
                            <input type="submit" class="btn btn-lg btn-primary" :disabled="errors.has('first_name') || errors.has('last_name') || errors.has('phone_no')" @click="validateForm('step-two')" value="Continue">
                        </div>
                    </div>

                    <div class="step-three" v-show="step_three" data-vv-scope="step-three">
                        <h3 class="mb-30">Step 3:</h3>
                        <h4>Organization Details</h4>

                        <div class="control-group" :class="[errors.has('username') ? 'has-error' : '']">
                            <label for="username" class="required">User Name</label>

                            <input type="text" class="control" name="username" v-model="username" placeholder="User Name" v-validate="'required|alpha_num'" data-vv-as="&quot;{{ __('username') }}&quot;">

                            <span class="control-error" v-show="errors.has('username')">@{{ errors.first('username') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('org_name') ? 'has-error' : '']">
                            <label for="org_name" class="required">Organization Name</label>

                            <input type="text" class="control" name="org_name" v-model="org_name" placeholder="Organization Name" v-validate="'required'" data-vv-as="&quot;{{ __('organization name') }}&quot;">

                            <span class="control-error" v-show="errors.has('org_name')">@{{ errors.first('org_name') }}</span>
                        </div>

                        <div class="control-group">
                            <button class="btn btn-lg btn-primary" :disabled="errors.has('username') || errors.has('org_name') || createdclicked" @click="validateForm('step-three')">Create Seller</button>
                        </div>
                    </div>
                </form>
            </div>
        </script>

        <script>
            Vue.component('seller-registration', {
                template: '#seller-registration-form',
                inject: ['$validator'],

                data: () => ({
                    step_one: true,
                    step_two: false,
                    step_three: false,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    first_name: null,
                    last_name: null,
                    phone_no: null,
                    org_name: null,
                    username: null,
                    createdclicked: false,
                    registrationData: {},
                    result: [],
                    isOneActive: false,
                    isTwoActive: false,
                    isThreeActive: false
                }),

                mounted() {
                    this.step_one = true;
                    this.isOneActive = true;
                },

                methods: {
                    validateForm: function (scope) {
                        this.$validator.validateAll(scope).then((result) => {
                            if (result) {
                                if (scope == 'step-one') {
                                    this.catchResponseOne();
                                } else if (scope == 'step-two') {
                                    this.catchResponseTwo();
                                } else if (scope == 'step-three') {
                                    this.catchResponseThree();
                                }
                            }
                        });
                    },

                    stepNav(step) {
                        if (step == 1) {
                            if (this.isThreeActive == true || this.isTwoActive == true){
                                this.step_three = false;
                                this.step_two = false;
                                this.step_one = true;

                                this.isThreeActive = false;
                                this.isTwoActive = false;
                                this.isOneActive = true;
                            }
                        } else if (step == 2) {
                            if (this.isThreeActive == true){
                                this.step_three = false;
                                this.step_one = false;
                                this.step_two = true;

                                this.isThreeActive = false;
                                this.isOneActive = false;
                                this.isTwoActive = true;
                            }
                        }
                    },

                    catchResponseOne () {
                        if (this.email != null && this.password != null && this.password_confirmation != null) {
                            this.step_two = true;
                            this.step_one = false;
                            this.isOneActive = false;
                            this.isTwoActive = true;
                        }
                    },

                    catchResponseTwo () {
                        if (this.first_name != null && this.phone_no != null) {
                            this.step_three = true;
                            this.step_two = false;
                            this.isTwoActive = false;
                            this.isThreeActive = true;
                        }
                    },

                    catchResponseThree () {
                        this.result = this.sendDataToServer();
                    },

                    handleErrorResponse (response, scope) {
                        serverErrors = response.data.errors;

                        this.$root.addServerErrors(scope);
                    },

                    sendDataToServer () {
                        var o_this = this;

                        return axios.post('/company/register', {
                            email: this.email,
                            first_name: this.first_name,
                            last_name: this.last_name,
                            phone_no: this.phone_no,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                            org_name: this.org_name,
                            username: this.username
                        }).then(function (response) {
                            console.log(response);
                            url = 'http://' + response.data.redirect + '/company/seed-data'
                            window.location = url;
                        }).catch(function (errors) {
                            serverErrors = errors.response.data.errors;

                            for (i in serverErrors) {
                                window.flashMessages = [{'type': 'alert-error', 'message': serverErrors[i]}];
                            }

                            o_this.$root.addFlashMessages()
                            o_this.$root.addServerErrors('step-one');
                            o_this.$root.addServerErrors('step-two');
                            o_this.$root.addServerErrors('step-three');
                        });
                    },

                    onSubmit: function (e) {
                        this.$validator.validateAll().then(result => {
                            if (result) {
                                return true;
                            }
                        });
                    },

                    toggleButtonDisable (value) {
                        var buttons = document.getElementsByTagName("button");

                        for (var i = 0; i < buttons.length; i++) {
                            buttons[i].disabled = value;
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection