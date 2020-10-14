<form method="POST" action="{{ route('admin.customer.update', $customer->id) }}">
    <div class="page-content">
        <div class="form-container">
            @csrf()

            <input name="_method" type="hidden" value="PUT">
            <div class="style:overflow: auto;">&nbsp;</div>

            <div slot="body">
                {!! view_render_event('bagisto.admin.customer.edit.form.before', ['customer' => $customer]) !!}

                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <label for="first_name" class="required"> {{ __('admin::app.customers.customers.first_name') }}</label>
                    <input type="text"  class="control" name="first_name" v-validate="'required'" value="{{old('first_name') ?:$customer->first_name}}"
                            data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;"/>
                    <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                </div>

                {!! view_render_event('bagisto.admin.customer.edit.first_name.after', ['customer' => $customer]) !!}

                    <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name" class="required"> {{ __('admin::app.customers.customers.last_name') }}</label>
                        <input type="text"  class="control"  name="last_name"   v-validate="'required'" value="{{old('last_name') ?:$customer->last_name}}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;">
                        <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customer.edit.last_name.after', ['customer' => $customer]) !!}

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email" class="required"> {{ __('admin::app.customers.customers.email') }}</label>
                        <input type="email"  class="control"  name="email" v-validate="'required|email'" value="{{old('email') ?:$customer->email}}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;">
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customer.edit.email.after', ['customer' => $customer]) !!}

                            <div class="control-group" :class="[errors.has('gender') ? 'has-error' : '']">
                                <label for="gender" class="required">{{ __('admin::app.customers.customers.gender') }}</label>
                                <select name="gender" class="control" value="{{ $customer->gender }}" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.customers.customers.gender') }}&quot;">
                                    <option value="" {{ $customer->gender == "" ? 'selected' : '' }}></option>
                                    <option value="{{ __('admin::app.customers.customers.male') }}" {{ $customer->gender == __('admin::app.customers.customers.male') ? 'selected' : '' }}>{{ __('admin::app.customers.customers.male') }}</option>
                                    <option value="{{ __('admin::app.customers.customers.female') }}" {{ $customer->gender == __('admin::app.customers.customers.female') ? 'selected' : '' }}>{{ __('admin::app.customers.customers.female') }}</option>
                                    <option value="{{ __('admin::app.customers.customers.other') }}" {{ $customer->gender == __('admin::app.customers.customers.other') ? 'selected' : '' }}>{{ __('admin::app.customers.customers.other') }}</option>
                                </select>
                                <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.customer.edit.gender.after', ['customer' => $customer]) !!}

                            <div class="control-group">
                                <label for="status" class="required">{{ __('admin::app.customers.customers.status') }}</label>

                                <label class="switch">
                                    <input type="checkbox" id="status" name="status" value="{{ $customer->status }}" {{ $customer->status ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>

                                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.customer.edit.status.after', ['customer' => $customer]) !!}

                            <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                                <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                                <input type="date" class="control" name="date_of_birth" value="{{ old('date_of_birth') ?:$customer->date_of_birth }}" v-validate="" data-vv-as="&quot;{{ __('admin::app.customers.customers.date_of_birth') }}&quot;">
                                <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.customer.edit.date_of_birth.after', ['customer' => $customer]) !!}

                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone">{{ __('admin::app.customers.customers.phone') }}</label>
                                <input type="text" class="control" name="phone"  value="{{ $customer->phone }}" data-vv-as="&quot;{{ __('admin::app.customers.customers.phone') }}&quot;">
                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.customer.edit.phone.after', ['customer' => $customer]) !!}

                            <div class="control-group">
                                <label for="customerGroup" >{{ __('admin::app.customers.customers.customer_group') }}</label>

                                @if (! is_null($customer->customer_group_id))
                                    <?php $selectedCustomerOption = $customer->group->id ?>
                                @else
                                    <?php $selectedCustomerOption = '' ?>
                                @endif

                                <select  class="control" name="customer_group_id">
                                    @foreach ($customerGroup as $group)
                                    <option value="{{ $group->id }}" {{ $selectedCustomerOption == $group->id ? 'selected' : '' }}>
                                        {{ $group->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {!! view_render_event('bagisto.admin.customer.edit.form.after', ['customer' => $customer]) !!}

                        </div>
                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('admin::app.customers.customers.save-btn-title') }}
                        </button>
                </div>
            </div>
        </form>