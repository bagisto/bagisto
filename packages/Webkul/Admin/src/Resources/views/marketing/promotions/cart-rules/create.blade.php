<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.promotions.cart-rules.create.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.before') !!}

    <v-cart-rule-create-form></v-cart-rule-create-form>

    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.after') !!}


    @pushOnce('scripts')
        {{-- v cart rules create form template --}}
        <script
            type="text/x-template"
            id="v-cart-rule-create-form-template"
        >
            <div>
                <x-admin::form 
                    :action="route('admin.marketing.promotions.cart_rules.store')"
                    enctype="multipart/form-data"
                >
                  
                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.create_form_controls.before') !!}

                    <div class="flex gap-[16px] justify-between items-center mt-3 max-sm:flex-wrap">
                        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.marketing.promotions.cart-rules.create.title')
                        </p>
                
                        <div class="flex gap-x-[10px] items-center">
                            <!-- Cancel button -->
                            <a
                                href="{{ route('admin.marketing.promotions.cart_rules.index') }}"
                                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                            >
                                @lang('admin::app.account.edit.back-btn')
                            </a>

                            <!-- Save button -->
                            <button 
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.marketing.promotions.cart-rules.create.save-btn')
                            </button>
                        </div>
                    </div>

                    <!-- body content  -->
                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        <!-- Left sub-component -->
                        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.general.before') !!}

                            <!-- General -->
                            <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
                                    @lang('admin::app.marketing.promotions.cart-rules.create.general')
                                </p>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.marketing.promotions.cart-rules.create.name')"
                                        :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.marketing.promotions.cart-rules.create.description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        :value="old('description')"
                                        id="description"
                                        class="text-gray-600 dark:text-gray-300"
                                        :label="trans('admin::app.marketing.promotions.cart-rules.create.description')"
                                        :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.coupon-type')
                                    </x-admin::form.control-group.label>
                                
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="coupon_type"
                                        id="coupon_type"
                                        rules="required"
                                        :label="trans('admin::app.marketing.promotions.cart-rules.create.coupon-type')"
                                        :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.coupon-type')"
                                        v-model="couponType"
                                    >
                                        <option value="0">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.no-coupon')
                                        </option>
                                
                                        <option value="1">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.specific-coupon')
                                        </option>
                                    </x-admin::form.control-group.control>
                                
                                    <x-admin::form.control-group.error
                                        control-name="coupon_type"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                
                                <template v-if="parseInt(couponType)">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.auto-generate-coupon')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="use_auto_generation"
                                            id="use_auto_generation"
                                            rules="required"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.auto-generate-coupon')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.auto-generate-coupon')"
                                            v-model="useAutoGeneration"
                                        >
                                            <option value="0">
                                                @lang('admin::app.marketing.promotions.cart-rules.create.no')
                                            </option>
                                
                                            <option value="1">
                                                @lang('admin::app.marketing.promotions.cart-rules.create.yes')
                                            </option>
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="use_auto_generation"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                
                                    <x-admin::form.control-group
                                        class="mb-[10px]"
                                        v-if="! parseInt(useAutoGeneration)"
                                    >
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.coupon-code')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="coupon_code"
                                            id="coupon_code"
                                            rules="required"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.coupon-code')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.coupon-code')"
                                        >
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="coupon_code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                    
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.marketing.promotions.cart-rules.create.uses-per-coupon')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="uses_per_coupon"
                                            id="uses_per_coupon"
                                            rules="numeric"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-coupon')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-coupon')"
                                        >
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="uses_per_coupon"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </template>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.marketing.promotions.cart-rules.create.uses-per-customer')
                                    </x-admin::form.control-group.label>
                            
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="usage_per_customer"
                                        id="usage_per_customer"
                                        :label="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-customer')"
                                        :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-customer')"
                                    >
                                    </x-admin::form.control-group.control>
                            
                                    <x-admin::form.control-group.error
                                        control-name="usage_per_customer"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <p class="text-sm text-gray-500">
                                    @lang('admin::app.marketing.promotions.cart-rules.create.uses-per-customer-control-info')
                                </p>
                            </div>

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.general.after') !!}
                
                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.before') !!}

                            <!-- Conditions -->
                            <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                                <div class="flex gap-[16px] items-center justify-between">
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.conditions')
                                    </p>

                                    <x-admin::form.control-group class="!mb-0">
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="condition_type"
                                            id="condition_type"
                                            class="ltr:pr-[40px] rtl:pl-[40px]"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.condition-type')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.condition-type')"
                                            v-model="conditionType"
                                        >
                                            <option value="1">
                                                @lang('admin::app.marketing.promotions.cart-rules.create.all-conditions-true')
                                            </option>

                                            <option value="2">
                                                @lang('admin::app.marketing.promotions.cart-rules.create.any-conditions-true')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="condition_type"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
        
                                <v-cart-rule-condition-item
                                    v-for='(condition, index) in conditions'
                                    :condition="condition"
                                    :key="index"
                                    :index="index"
                                    @onRemoveCondition="removeCondition($event)">
                                >
                                </v-cart-rule-condition-item>
                      
                                <div 
                                    class="secondary-button max-w-max mt-[15px]"
                                    @click="addCondition"
                                >
                                    @lang('admin::app.marketing.promotions.cart-rules.create.add-condition')
                                </div>
        
                            </div>

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.after') !!}

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.before') !!}

                            <!-- Action -->
                            <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                                <div class="grid gap-[6px]">
                                    <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.actions')
                                    </p>
                
                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <div class="w-full">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('admin::app.marketing.promotions.cart-rules.create.action-type')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="action_type"
                                                    id="action_type"
                                                    rules="required"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.action-type')"
                                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.action-type')"
                                                    v-model="actionType"
                                                >
                                                    <option
                                                        value="by_percent"
                                                        {{ old('action_type') == 'by_percent' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.percentage-product-price')
                                                    </option>

                                                    <option 
                                                        value="by_fixed"
                                                        {{ old('action_type') == 'by_fixed' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.fixed-amount')
                                                    </option>

                                                    <option 
                                                        value="cart_fixed"
                                                        {{ old('action_type') == 'cart_fixed' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.fixed-amount-whole-cart')
                                                    </option>

                                                    <option 
                                                        value="buy_x_get_y"
                                                        {{ old('action_type') == 'buy_x_get_y' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.buy-x-get-y-free')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="action_type"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <div class="w-full">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('admin::app.marketing.promotions.cart-rules.create.discount-amount')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="discount_amount"
                                                    :value="old('discount_amount', 0)"
                                                    id="discount_amount"
                                                    rules="required"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.discount-amount')"
                                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.discount-amount')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="discount_amount"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>

                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <div class="w-full">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.marketing.promotions.cart-rules.create.maximum-quantity-allowed-to-be-discounted')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="discount_quantity"
                                                    :value="old('discount_quantity', 0)"
                                                    id="discount_quantity"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.maximum-quantity-allowed-to-be-discounted')"
                                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.maximum-quantity-allowed-to-be-discounted')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="discount_quantity"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <div class="w-full">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.marketing.promotions.cart-rules.create.buy-x-quantity')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="discount_step"
                                                    :value="old('discount_step', 0)"
                                                    id="discount_step"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.buy-x-quantity')"
                                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.buy-x-quantity')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="discount_step"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                
                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <div class="w-full">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.marketing.promotions.cart-rules.create.apply-to-shipping')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="apply_to_shipping"
                                                    :value="old('apply_to_shipping', 0)"
                                                    id="apply_to_shipping"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.apply-to-shipping')"
                                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.apply-to-shipping')"
                                                >
                                                    <option 
                                                        value="0"
                                                        {{ ! old('apply_to_shipping') ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.no')
                                                    </option>

                                                    <option 
                                                        value="1"
                                                        {{ old('apply_to_shipping') ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.yes')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="apply_to_shipping"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <div class="w-full">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.marketing.promotions.cart-rules.create.free-shipping')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="free_shipping"
                                                    :value="old('free_shipping', 0)"
                                                    id="free_shipping"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.free-shipping')"
                                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.free-shipping')"
                                                >
                                                    <option
                                                        value="0"
                                                        {{ ! old('free_shipping') ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.no')
                                                    </option>

                                                    <option
                                                        value="1"
                                                        {{ old('free_shipping') ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.yes')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="free_shipping"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                
                                    <div class="flex gap-[16px] justify-between max-sm:flex-wrap">
                                        <div class="w-full">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.marketing.promotions.cart-rules.create.end-of-other-rules')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="end_other_rules"
                                                    :value="old('end_other_rules', 0)"
                                                    id="end_other_rules"
                                                    class="!w-1/2 max-sm:!w-full" 
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.end-of-other-rules')"
                                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.end-of-other-rules')"
                                                >
                                                    <option
                                                        value="0"
                                                        {{ ! old('end_other_rules') ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.no')
                                                    </option>

                                                    <option
                                                        value="1"
                                                        {{ old('end_other_rules') ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.marketing.promotions.cart-rules.create.yes')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="end_other_rules"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.after') !!}

                        </div>

                        <!-- Right sub-component -->
                        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.settings.before') !!}

                            <!-- Settings -->
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="text-gray-800 dark:text-white text-[16px] p-[10px] font-semibold">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.settings')
                                        </p>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.marketing.promotions.cart-rules.create.priority')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="sort_order"
                                            :value="old('sort_order')"
                                            id="sort_order"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.priority')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.priority')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="sort_order"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- channels -->
                                    <div class="mb-[10px]">
                                        <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.channels')
                                        </p>
                                        
                                        @foreach(core()->getAllChannels() as $channel)
                                            <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                                <x-admin::form.control-group.control
                                                    type="checkbox"
                                                    name="channels[]"
                                                    :value="$channel->id"
                                                    :id="'channel_' . '_' . $channel->id"
                                                    :for="'channel_' . '_' . $channel->id"
                                                    rules="required"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.channels')"
                                                    :checked="in_array($channel->id, old('channels', []))"
                                                >
                                                </x-admin::form.control-group.control>
                                
                                                <x-admin::form.control-group.label
                                                    :for="'channel_' . '_' . $channel->id"
                                                    class="!text-[14px] !text-gray-600 dark:!text-gray-300 font-semibold cursor-pointer"
                                                >
                                                    {{ core()->getChannelName($channel) }}
                                                </x-admin::form.control-group.label>
                                            </x-admin::form.control-group>
                                        @endforeach

                                        <x-admin::form.control-group.error
                                            control-name="channels[]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </div>
    
                                    <!-- Customer Grous -->
                                    <div class="mb-[10px]">
                                        <p class="required block leading-[24px] text-gray-800 dark:text-white font-medium">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.customer-groups')
                                        </p>
                                        
                                        @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                            <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                                <x-admin::form.control-group.control
                                                    type="checkbox"
                                                    name="customer_groups[]"
                                                    :value="$customerGroup->id"
                                                    :id="'customer_group_' . '_' . $customerGroup->id"
                                                    :for="'customer_group_' . '_' . $customerGroup->id"
                                                    rules="required"
                                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.customer-groups')"
                                                    :checked="in_array($customerGroup->id, old('customer_groups', []))"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.label
                                                    :for="'customer_group_' . '_' . $customerGroup->id"
                                                    class="!text-[14px] !text-gray-600 dark:!text-gray-300 font-semibold cursor-pointer"
                                                >
                                                    {{ $customerGroup->name }}
                                                </x-admin::form.control-group.label>
                                            </x-admin::form.control-group>
                                        @endforeach

                                        <x-admin::form.control-group.error
                                            control-name="customer_groups[]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-[10px]">
                                        <p class="block leading-[24px] text-gray-800 dark:text-white font-medium">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.status')
                                        </p>
    
                                        <x-admin::form.control-group class="flex gap-[10px] !mb-0 p-[6px]">
                                            <x-admin::form.control-group.control
                                                type="checkbox"
                                                name="status"
                                                value="1"
                                                id="status"
                                                for="status"
                                                label="status"
                                                :checked="(boolean) old('status')"
                                            >
                                            </x-admin::form.control-group.control>
                            
                                            <x-admin::form.control-group.label
                                                for="status"
                                                class="!text-[14px] !text-gray-600 dark:!text-gray-300 font-semibold cursor-pointer"
                                            >
                                                @lang('admin::app.marketing.promotions.cart-rules.create.status')
                                            </x-admin::form.control-group.label>
    
                                            <x-admin::form.control-group.error
                                                control-name="status"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>  
                                    </div>
                                </x-slot:content>
                            </x-admin::accordion>
                
                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.settings.after') !!}

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.marketing_time.before') !!}

                            <!-- Marketing Time -->
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="text-gray-800 dark:text-white text-[16px] p-[10px] font-semibold">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.marketing-time')
                                        </p>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <div class="flex gap-[16px]">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.marketing.promotions.cart-rules.create.from')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="date"
                                                name="starts_from"
                                                :value="old('starts_from')"
                                                id="starts_from"
                                                :label="trans('admin::app.marketing.promotions.cart-rules.create.from')"
                                                :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.from')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="starts_from"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.marketing.promotions.cart-rules.create.to')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="date"
                                                name="ends_till"
                                                :value="old('ends_till')"
                                                id="ends_till"
                                                :label="trans('admin::app.marketing.promotions.cart-rules.create.to')"
                                                :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.to')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="ends_till"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </x-slot:content>
                            </x-admin::accordion>

                            {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.marketing_time.after') !!}

                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.create_form_controls.after') !!}

                </x-admin::form>
           </div>
        </script>

        <script type="module">
            app.component('v-cart-rule-create-form', {
                template: '#v-cart-rule-create-form-template',

                data() {
                    return {
                        couponType: "{{ old('coupon_type', 0) }}",

                        useAutoGeneration: "{{ old('use_auto_generation', 0) }}",

                        conditionType: "{{ old('condition_type', 1) }}",

                        conditions: [],

                        actionType: "{{ old('action_type') ?: 'by_percent' }}",
                    }
                },

                methods: {
                    addCondition() {
                        this.conditions.push({
                            'attribute': '',
                            'operator': '==',
                            'value': '',
                        });
                    },

                    removeCondition(condition) {
                        let index = this.conditions.indexOf(condition);

                        this.conditions.splice(index, 1);
                    },
                },
            });
        </script>

        {{-- v catalog rule condition item form template --}}
        <script 
            type="text/x-template"
            id="v-cart-rule-condition-item-template"
        >
            <div class="flex gap-[16px] justify-between mt-[15px]">
                <div class="flex gap-[16px] flex-1 max-sm:flex-wrap max-sm:flex-1">
                    <select
                        :name="['conditions[' + index + '][attribute]']"
                        :id="['conditions[' + index + '][attribute]']"
                        class="custom-select flex w-1/3 min:w-1/3 h-[40px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 ltr:pr-[40px] rtl:pl-[40px]"
                        v-model="condition.attribute"
                    >
                        <option value="">@lang('admin::app.marketing.promotions.cart-rules.create.choose-condition-to-add')</option>

                        <optgroup 
                            v-for='(conditionAttribute, index) in conditionAttributes'
                            :label="conditionAttribute.label"
                        >
                            <option 
                                v-for='(childAttribute, index) in conditionAttribute.children'
                                :value="childAttribute.key"
                                :text="childAttribute.label"
                            >
                            </option>
                        </optgroup>
                    </select>

                    <select 
                        :name="['conditions[' + index + '][operator]']"
                        class="custom-select flex w-1/3 min:w-1/3 h-[40px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 ltr:pr-[40px] rtl:pl-[40px]"
                        v-model="condition.operator"
                        v-if="matchedAttribute"
                    >
                        <option 
                            v-for='operator in conditionOperators[matchedAttribute.type]'
                            :value="operator.operator"
                            :text="operator.label"
                        >
                        </option>
                    </select>

                    <div v-if="matchedAttribute">
                        <input 
                            type="hidden"
                            :name="['conditions[' + index + '][attribute_type]']"
                            v-model="matchedAttribute.type"
                        >

                        <div 
                            v-if="matchedAttribute.key == 'product|category_ids' 
                            || matchedAttribute.key == 'product|category_ids'
                            || matchedAttribute.key == 'product|parent::category_ids'"
                        >
                            <x-admin::tree.view
                                value-field="id"
                                id-field="id"
                                ::name-field="'conditions[' + index + '][value]'"
                                input-type="checkbox"
                                ::items='matchedAttribute.options'
                                :behavior="'no'"
                                :fallback-locale="config('app.fallback_locale')"
                            >
                            </x-admin::tree.view>
                        </div>

                        <div v-else>
                            <div
                                v-if="matchedAttribute.type == 'text' 
                                    || matchedAttribute.type == 'price'
                                    || matchedAttribute.type == 'decimal'
                                    || matchedAttribute.type == 'integer'"
                            >
                                <v-field 
                                    :name="`['conditions[${index}][value]']`"
                                    v-slot="{ field, errorMessage }"
                                    :id="`['conditions[${index}][value]']`"
                                    :rules="
                                        matchedAttribute.type == 'price' ? 'regex:^[0-9]+\.[0-9]{2}$' : ''
                                        || matchedAttribute.type == 'decimal' ? 'regex:^[0-9]+\.[0-9]{2}$' : ''
                                        || matchedAttribute.type == 'integer' ? 'regex:^[0-9]+\.[0-9]{2}$' : ''
                                        || matchedAttribute.type == 'text' ? 'regex:^([A-Za-z0-9_ \'\-]+)$' : ''"
                                    label="Conditions"
                                    v-model="condition.value"
                                >
                                    <input 
                                        type="text"
                                        v-bind="field"
                                        :class="{ 'border border-red-500': errorMessage }"
                                        class="flex w-[289px] min:w-1/3 h-[40px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 ltr:pr-[40px] rtl:pl-[40px]"
                                    />
                                </v-field>
                                
                                <v-error-message
                                    :name="`['conditions[${index}][value]']`"
                                    class="mt-1 text-red-500 text-xs italic"
                                    as="p"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'date'">
                                <input 
                                    type="date"
                                    :name="['conditions[' + index + '][value]']"
                                    class="border w-[289px] py-2 px-3 appearance-none rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'datetime'">
                                <input 
                                    type="datetime"
                                    :name="['conditions[' + index + '][value]']"
                                    class="border w-full py-2 px-3 appearance-none rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'boolean'">
                                <select 
                                    :name="['conditions[' + index + '][value]']"
                                    class="custom-select inline-flex gap-x-[4px] justify-between items-center h-[40px] w-[196px] max-w-[196px] py-[6px] ltr:pl-[12px] rtl:pr-[12px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                >
                                    <option value="1">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.yes')
                                    </option>

                                    <option value="0">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.no')
                                    </option>
                                </select>
                            </div>

                            <div v-if="matchedAttribute.type == 'select' || matchedAttribute.type == 'radio'">
                                <select
                                    :name="['conditions[' + index + '][value]']"
                                    class="custom-select inline-flex gap-x-[4px] justify-between items-center h-[40px] w-[196px] max-w-[196px] py-[6px] ltr:pl-[12px] rtl:pr-[12px] px-[12px] bg-white dark:bg-gray-900  border dark:border-gray-800   rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-if="matchedAttribute.key != 'catalog|state'"
                                    v-model="condition.value"
                                >
                                    <option
                                        v-for='option in matchedAttribute.options'
                                        :value="option.id"
                                        :text="option.admin_name"
                                    >
                                    </option>
                                </select>

                                <select 
                                    :name="['conditions[' + index + '][value]']"
                                    class="custom-select inline-flex gap-x-[4px] justify-between items-center h-[40px] w-[196px] max-w-[196px] py-[6px] ltr:pl-[12px] rtl:pr-[12px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-model="condition.value" 
                                    v-else
                                >
                                    <optgroup
                                        v-for='option in matchedAttribute.options'
                                        :label="option.admin_name"
                                    >
                                        <option
                                            v-for='state in option.states'
                                            :value="state.code"
                                            :text="state.admin_name"
                                        >
                                        </option>
                                    </optgroup>
                                </select>
                            </div>

                            <div v-if="matchedAttribute.type == 'multiselect' || matchedAttribute.type == 'checkbox'">
                                <select 
                                    :name="['conditions[' + index + '][value][]']"
                                    class="custom-select inline-flex gap-x-[4px] justify-between items-center h-[40px] w-[196px] max-w-[196px] py-[6px] ltr:pl-[12px] rtl:pr-[12px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                    multiple
                                >
                                    <option
                                        v-for='option in matchedAttribute.options'
                                        :value="option.id"
                                        :text="option.admin_name"
                                    >
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <span 
                    class="icon-delete max-h-[36px] max-w-[36px] text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                    @click="removeCondition"
                >
                </span>
            </div>
        </script>

        {{-- v catalog rule condition item component --}}
        <script type="module">
            app.component('v-cart-rule-condition-item', {
                template: "#v-cart-rule-condition-item-template",

                props: ['index', 'condition'],

                data() {
                    return {
                        conditionAttributes: @json(app('\Webkul\CartRule\Repositories\CartRuleRepository')->getConditionAttributes()),

                        attributeTypeIndexes: {
                            'cart': 0,

                            'cart_item': 1,

                            'product': 2
                        },

                        conditionOperators: {
                            'price': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }, {
                                'operator': '>=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-greater-than')"
                            }, {
                                'operator': '<=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-less-than')"
                            }, {
                                'operator': '>',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.greater-than')"
                            }, {
                                'operator': '<',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.less-than')"
                            }],
                            'decimal': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }, {
                                'operator': '>=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-greater-than')"
                            }, {
                                'operator': '<=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-less-than')"
                            }, {
                                'operator': '>',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.greater-than')"
                            }, {
                                'operator': '<',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.less-than')"
                            }],
                            'integer': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }, {
                                'operator': '>=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-greater-than')"
                            }, {
                                'operator': '<=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-less-than')"
                            }, {
                                'operator': '>',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.greater-than')"
                            }, {
                                'operator': '<',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.less-than')"
                            }],
                            'text': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }, {
                                'operator': '{}',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.contain')"
                            }, {
                                'operator': '!{}',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.does-not-contain')"
                            }],
                            'boolean': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }],
                            'date': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }, {
                                'operator': '>=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-greater-than')"
                            }, {
                                'operator': '<=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-less-than')"
                            }, {
                                'operator': '>',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.greater-than')"
                            }, {
                                'operator': '<',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.less-than')"
                            }],
                            'datetime': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }, {
                                'operator': '>=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-greater-than')"
                            }, {
                                'operator': '<=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.equals-or-less-than')"
                            }, {
                                'operator': '>',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.greater-than')"
                            }, {
                                'operator': '<',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.less-than')"
                            }],
                            'select': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }],
                            'radio': [{
                                'operator': '==',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-equal-to')"
                            }, {
                                'operator': '!=',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.is-not-equal-to')"
                            }],
                            'multiselect': [{
                                'operator': '{}',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.contains')"
                            }, {
                                'operator': '!{}',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.does-not-contain')"
                            }],
                            'checkbox': [{
                                'operator': '{}',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.contains')"
                            }, {
                                'operator': '!{}',
                                'label': "@lang('admin::app.marketing.promotions.cart-rules.create.does-not-contain')"
                            }]
                        }
                    }
                },

                computed: {
                    matchedAttribute() {
                        if (this.condition.attribute == '')
                            return;

                        let attributeIndex = this.attributeTypeIndexes[this.condition.attribute.split("|")[0]];

                        let matchedAttribute = this.conditionAttributes[attributeIndex]['children'].filter((attribute) => {
                            return attribute.key == this.condition.attribute;
                        });

                        if (matchedAttribute[0]['type'] == 'multiselect' || matchedAttribute[0]['type'] ==
                            'checkbox') {
                            this.condition.operator = '{}';

                            this.condition.value = [];
                        }

                        return matchedAttribute[0];
                    }
                },

                methods: {
                    removeCondition() {
                        this.$emit('onRemoveCondition', this.condition);
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
