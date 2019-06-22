@extends('saas::companies.layouts.master')

@section('page_title')
    Seller Registration
@endsection

@section('content-wrapper')
    <seller-registration></seller-registration>

    @push('scripts')
        <script type="text/x-template" id="seller-registration">
            <div class="form-container">
                <div class="step-navigator">
                    <ul class="step-list">
                        <li class="step-item" :class="{ active: isOneActive }" v-on:click="stepNav(1)">1</li>

                        <li class="step-item" :class="{ active: isTwoActive }" v-on:click="stepNav(2)">2</li>

                        <li class="step-item" :class="{ active: isThreeActive }" v-on:click="stepNav(3)">3</li>
                    </ul>
                </div>

                <form class="registration" data-vv-scope="step-one" v-if="step_one" @submit.prevent="validateForm('step-one')">
                    @csrf

                    <h3 class="mb-30">Step 1:</h3>

                    <h4>Authentication Credentials</h4>

                    <div class="control-group" :class="[errors.has('step-one.email') ? 'has-error' : '']">
                        <label for="email" class="required">Email</label>

                        <input type="text" v-validate="'required|email|max:191'" class="control" v-model="email" name="email" data-vv-as="&quot;{{ __('email') }}&quot;" placeholder="Auth Email">

                        <span class="control-error" v-show="errors.has('step-one.email')">@{{ errors.first('step-one.email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('step-one.password') ? 'has-error' : '']">
                        <label for="password" class="required">Password</label>

                        <input type="password" name="password" v-validate="'required|min:6'" ref="password" class="control" v-model="password" placeholder="password" data-vv-as="&quot;{{ __('password') }}&quot;">

                        <span class="control-error" v-show="errors.has('step-one.password')">@{{ errors.first('step-one.password') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('step-one.password_confirmation') ? 'has-error' : '']">
                        <label for="password_confirmation" class="required">Confirm Password</label>

                        <input type="password" v-validate="'required|min:6|confirmed:password'" class="control" v-model="password_confirmation" name="password_confirmation" placeholder="Confirm Password" data-vv-as="&quot;{{ __('confirm password') }}&quot;">

                        <span class="control-error" v-show="errors.has('step-one.password_confirmation')">@{{ errors.first('step-one.password_confirmation') }}</span>
                    </div>

                    <div class="control-group">
                        <!-- <input type="submit" class="btn btn-lg btn-primary" :disabled="errors.has('password') || errors.has('password_confirmation') || errors.has('email')"  value="Continue"> -->
                        <button class="btn btn-lg btn-primary" :disabled="errors.has('step-one.password') || errors.has('step-one.password_confirmation') || errors.has('step-one.email')">Continue</button>
                    </div>
                </form>

                <form class="registration" @submit.prevent="validateForm('step-two')" data-vv-scope="step-two" v-show="step_two">
                    <div class="step-two">
                        <h3 class="mb-30">Step 2:</h3>

                        <h4>Personal Details</h4>

                        <div class="control-group" :class="[errors.has('step-two.first_name') ? 'has-error' : '']" >
                            <label for="first_name" class="required">First Name</label>

                            <input type="text" class="control" v-model="first_name" name="first_name" placeholder="First Name" v-validate="'required|alpha_spaces'" data-vv-as="&quot;First Name&quot;">

                            <span class="control-error" v-show="errors.has('step-two.first_name')">@{{ errors.first('step-two.first_name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('step-two.last_name') ? 'has-error' : '']">
                            <label for="last_name">Last Name</label>

                            <input type="text" class="control" name="last_name" v-model="last_name" placeholder="Last Name" v-validate="'alpha_spaces'" data-vv-as="&quot;{{ __('last name') }}&quot;">

                            <span class="control-error" v-show="errors.has('step-two.last_name')">@{{ errors.first('step-two.last_name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('step-two.phone_no') ? 'has-error' : '']">
                            <label for="phone_no" class="required">Phone Number</label>

                            <input type="text" class="control" name="phone_no" v-model="phone_no" placeholder="Phone Number" v-validate="'required|numeric|min:10|max:11'" data-vv-as="&quot;{{ __('phone') }}&quot;">

                            <span class="control-error" v-show="errors.has('step-two.phone_no')">@{{ errors.first('step-two.phone_no') }}</span>
                        </div>

                        <div class="control-group">
                            <button class="btn btn-lg btn-primary" :disabled="errors.has('first_name') || errors.has('last_name') || errors.has('step-two.phone_no')">Continue</button>
                        </div>
                    </div>
                </form>

                <form class="registration" @submit.prevent="validateForm('step-three')" data-vv-scope="step-three" v-show="step_three">
                    <div class="step-three">
                        <h3 class="mb-30">Step 3:</h3>

                        <h4>Organization Details</h4>

                        <div class="control-group" :class="[errors.has('step-three.username') ? 'has-error' : '']">
                            <label for="username" class="required">User Name</label>

                            <input type="text" class="control" name="username" v-model="username" placeholder="User Name" v-validate="'required|alpha_num'" data-vv-as="&quot;{{ __('username') }}&quot;">

                            <span class="control-error" v-show="errors.has('step-three.username')">@{{ errors.first('step-three.username') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('step-three.org_name') ? 'has-error' : '']">
                            <label for="org_name" class="required">Organization Name</label>

                            <input type="text" class="control" name="org_name" v-model="org_name" placeholder="Organization Name" v-validate="'required'" data-vv-as="&quot;{{ __('organization name') }}&quot;">

                            <span class="control-error" v-show="errors.has('step-three.org_name')">@{{ errors.first('step-three.org_name') }}</span>
                        </div>

                        <div class="control-group">
                            <button class="btn btn-lg btn-primary" :disabled="errors.has('step-three.username') || errors.has('step-three.org_name') || createdclicked">Continue</button>
                        </div>
                    </div>
                </form>
            </div>
        </script>

        <script>
            Vue.component('seller-registration', {
                template: '#seller-registration',
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
                    this.isOneActive = true;
                },

                methods: {
                    validateForm: function(scope) {
                    var this_this = this;

                    this.$validator.validateAll(scope).then(function (result) {

                        if (result) {
                            if (scope == 'step-one') {
                                this_this.catchResponseOne();
                            } else if (scope == 'step-two') {
                                this_this.catchResponseTwo();
                            } else if (scope == 'step-three') {
                                this_this.catchResponseThree();
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
                        var o_this = this;

                        axios.post('{{ route('company.validate.step-one') }}', {
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation
                        }).then(function (response) {
                            o_this.step_two = true;
                            o_this.step_one = false;
                            o_this.isOneActive = false;
                            o_this.isTwoActive = true;

                            o_this.errors.clear();
                        }).catch(function (errors) {
                            serverErrors = errors.response.data.errors;

                            o_this.$root.addServerErrors('step-one');
                        });
                    },

                    catchResponseTwo () {
                        this.step_three = true;
                        this.step_two = false;
                        this.isTwoActive = false;
                        this.isThreeActive = true;
                    },

                    catchResponseThree () {
                        this.createdclicked = true;

                        var o_this = this;

                        axios.post('{{ route('company.validate.step-three') }}', {
                            username: this.username,
                            org_name: this.org_name,
                        }).then(function (response) {
                            o_this.errors.clear();

                            o_this.sendDataToServer();
                        }).catch(function (errors) {
                            serverErrors = errors.response.data.errors;

                            o_this.createdclicked = false;

                            o_this.$root.addServerErrors('step-three');
                        });
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

                            o_this.createdclicked = false;

                            for (i in serverErrors) {
                                window.flashMessages = [{'type': 'alert-error', 'message': serverErrors[i]}];
                            }

                            o_this.$root.addFlashMessages();
                            o_this.$root.addServerErrors('step-one');
                            o_this.$root.addServerErrors('step-two');
                            o_this.$root.addServerErrors('step-three');
                        });
                    }
                }
            });
        </script>
    @endpush
@endsection