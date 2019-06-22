<form class="registration" @submit.prevent="onSubmit" data-vv-scope="step-one">
    @csrf
    <div class="step-one" v-show="step_one">
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
    </div>
</form>