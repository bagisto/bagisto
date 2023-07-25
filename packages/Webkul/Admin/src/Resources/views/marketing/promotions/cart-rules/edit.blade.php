<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.promotions.cart-rules.edit.title')
    </x-slot:title>

    <v-cart-rule-edit-form></v-cart-rule-edit-form>

    @pushOnce('scripts')
        {{-- v cart rules create form template --}}
        <script
            type="text/x-template"
            id="v-cart-rule-edit-form-template"
        >
            <div>
                <x-admin::form 
                    :action="route('admin.cart_rules.update', $cartRule->id)"
                    enctype="multipart/form-data"
                >
                    <div class="grid">
                        <div class="flex justify-end items-center pt-[11px] cursor-pointer">
                            <button 
                                type="submit"
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.promotions.cart-rules.edit.save')
                            </button>
                        </div>
                    </div>
                
                    {{-- body content  --}}
                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        {{-- Left sub-component --}}
                        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                            {{-- General --}}
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    @lang('admin::app.promotions.cart-rules.edit.general')
                                </p>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.promotions.cart-rules.edit.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name') ?? $cartRule->name"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.promotions.cart-rules.edit.name')"
                                        :placeholder="trans('admin::app.promotions.cart-rules.edit.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.promotions.cart-rules.edit.description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        :value="old('description') ?? $cartRule->description"
                                        id="description"
                                        rules="required"
                                        :label="trans('admin::app.promotions.cart-rules.edit.description')"
                                        :placeholder="trans('admin::app.promotions.cart-rules.edit.description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <div class="mb-[10px]">
                                    <label class="block leading-[24px] text-[12px] text-gray-800 font-medium">
                                        @lang('admin::app.promotions.cart-rules.edit.channels')
                                    </label>

                                    @php($selectedOptionIds = old('channels') ?? $cartRule->channels->pluck('id')->toArray())
                                    
                                    @foreach(core()->getAllChannels() as $channel)
                                        <label
                                            class="flex gap-[10px] w-full items-center p-[6px] cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]"
                                            for="channel_{{ $channel->id }}"
                                        >
                                            <input
                                                type="checkbox"
                                                name="channels[]"
                                                id="channel_{{ $channel->id }}"
                                                value="{{ $channel->id }}"
                                                class="hidden peer"
                                                {{ in_array($channel->id, $selectedOptionIds) ? 'checked' : '' }}
                                            >
                                            
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                            
                                            <p class="text-gray-600 font-semibold cursor-pointer">
                                                {{ core()->getChannelName($channel) }}
                                            </p>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="mb-[10px]">
                                    <label class="block leading-[24px] text-[12px] text-gray-800 font-medium">
                                        @lang('admin::app.promotions.cart-rules.edit.customer-groups')
                                    </label>
                                    
                                        
                                    @php($selectedOptionIds = old('customer_groups') ?: $cartRule->customer_groups->pluck('id')->toArray())

                                    @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                        <label
                                            class="flex gap-[10px] w-full items-center p-[6px] cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]"
                                            for="customer_group_{{ $customerGroup->id }}"
                                        >
                                            <input
                                                type="checkbox"
                                                name="customer_groups[]"
                                                id="customer_group_{{ $customerGroup->id }}"
                                                value="{{ $customerGroup->id }}"
                                                class="hidden peer"
                                                {{ in_array($customerGroup->id, $selectedOptionIds) ? 'checked' : '' }}
                                            >
                                            
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                            
                                            <p class="text-gray-600 font-semibold cursor-pointer">
                                                {{ $customerGroup->name }}
                                            </p>
                                        </label>
                                    @endforeach
                                </div>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.promotions.cart-rules.edit.coupon-type')
                                    </x-admin::form.control-group.label>
                                
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="coupon_type"
                                        id="coupon_type"
                                        rules="required"
                                        :label="trans('admin::app.promotions.cart-rules.edit.coupon-type')"
                                        :placeholder="trans('admin::app.promotions.cart-rules.edit.coupon-type')"
                                        v-model="coupon_type"
                                    >
                                        <option 
                                            value="0"
                                            {{ old('coupon_type') == 0 ? 'selected' : '' }}
                                        >
                                            @lang('admin::app.promotions.cart-rules.edit.no-coupon')
                                        </option>
                                
                                        <option 
                                            value="1"
                                            {{ old('coupon_type') == 1 ? 'selected' : '' }}
                                        >
                                            @lang('admin::app.promotions.cart-rules.edit.specific-coupon')
                                        </option>
                                    </x-admin::form.control-group.control>
                                
                                    <x-admin::form.control-group.error
                                        control-name="coupon_type"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                
                                <template v-if="parseInt(coupon_type)">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.promotions.cart-rules.edit.auto-generate-coupon')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="use_auto_generation"
                                            id="use_auto_generation"
                                            rules="required"
                                            :label="trans('admin::app.promotions.cart-rules.edit.auto-generate-coupon')"
                                            :placeholder="trans('admin::app.promotions.cart-rules.edit.auto-generate-coupon')"
                                            v-model="use_auto_generation"
                                        >
                                            <option 
                                                value="0"
                                                {{ old('use_auto_generation') == 0 ? 'selected' : '' }}
                                            >
                                                @lang('admin::app.promotions.cart-rules.edit.no')
                                            </option>
                                
                                            <option 
                                                value="1"
                                                {{ old('use_auto_generation') == 1 ? 'selected' : '' }}
                                            >
                                                @lang('admin::app.promotions.cart-rules.edit.yes')
                                            </option>
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="coupon_type"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                
                                    <x-admin::form.control-group
                                        class="mb-[10px]"
                                        v-if="! parseInt(use_auto_generation)"
                                    >
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.promotions.cart-rules.edit.coupon-code')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="coupon_code"
                                            :value="old('coupon_code') ?? $cartRule->coupon_code"
                                            id="coupon_code"
                                            rules="required"
                                            :label="trans('admin::app.promotions.cart-rules.edit.coupon-code')"
                                            :placeholder="trans('admin::app.promotions.cart-rules.edit.coupon-code')"
                                        >
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            control-name="coupon_code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                    
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.promotions.cart-rules.edit.uses-per-coupon')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="uses_per_coupon"
                                            :value="old('uses_per_coupon') ?? $cartRule->uses_per_coupon" 
                                            id="uses_per_coupon"
                                            :label="trans('admin::app.promotions.cart-rules.edit.uses-per-coupon')"
                                            :placeholder="trans('admin::app.promotions.cart-rules.edit.uses-per-coupon')"
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
                                        @lang('admin::app.promotions.cart-rules.edit.uses-per-customer')
                                    </x-admin::form.control-group.label>
                            
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="usage_per_customer"
                                        :value="old('usage_per_customer') ?? $cartRule->usage_per_customer"
                                        id="usage_per_customer"
                                        :label="trans('admin::app.promotions.cart-rules.edit.uses-per-customer')"
                                        :placeholder="trans('admin::app.promotions.cart-rules.edit.uses-per-customer')"
                                    >
                                    </x-admin::form.control-group.control>
                            
                                    <x-admin::form.control-group.error
                                        control-name="usage_per_customer"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                
                            </div>
                
                            {{-- Conditions --}}
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <div class="flex gap-[16px] items-center justify-between">
                                    <p class="text-[16px] text-gray-800 font-semibold">
                                        @lang('admin::app.promotions.cart-rules.edit.conditions')
                                    </p>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.promotions.cart-rules.edit.condition-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="condition_type"
                                            id="condition_type"
                                            class="pr-[40px]"
                                            :label="trans('admin::app.promotions.cart-rules.edit.condition-type')"
                                            :placeholder="trans('admin::app.promotions.cart-rules.edit.condition-type')"
                                            v-model="condition_type"
                                        >
                                            <option value="1">
                                                @lang('admin::app.promotions.cart-rules.edit.all-conditions-true')
                                            </option>

                                            <option value="2">
                                                @lang('admin::app.promotions.cart-rules.edit.any-conditions-true')
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
                                    {{-- @onRemoveCondition="removeCondition($event)" --}}
                                >
                                </v-cart-rule-condition-item>
                      
                                <div 
                                    class="max-w-max px-[12px] py-[5px] mt-[15px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="addCondition"
                                >
                                    @lang('admin::app.promotions.cart-rules.edit.add-condition')
                                </div>
        
                            </div>

                            {{-- Action --}}
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                <div class="grid gap-[6px]">
                                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                                        @lang('admin::app.promotions.cart-rules.edit.actions')
                                    </p>
                
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.action-type')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="action_type"
                                                    id="action_type"
                                                    rules="required"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.action-type')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.action-type')"
                                                    v-model="action_type"
                                                >
                                                    <option
                                                        value="by_percent"
                                                        {{ old('action_type') == 'by_percent' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.percentage-product-price')
                                                    </option>

                                                    <option 
                                                        value="by_fixed"
                                                        {{ old('action_type') == 'by_fixed' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.fixed-amount')
                                                    </option>

                                                    <option 
                                                        value="cart_fixed"
                                                        {{ old('action_type') == 'cart_fixed' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.fixed-amount-whole-cart')
                                                    </option>

                                                    <option 
                                                        value="buy_x_get_y"
                                                        {{ old('action_type') == 'buy_x_get_y' ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.buy-x-get-y-free')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="action_type"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <div class="w-full mb-[10px]">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.discount-amount')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="discount_amount"
                                                    :value="old('discount_amount') ?? $cartRule->discount_amount"
                                                    id="discount_amount"
                                                    rules="required"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.discount-amount')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.discount-amount')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="discount_amount"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>

                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.maximum-quantity-allowed-to-be-discounted')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="discount_quantity"
                                                    :value="old('discount_quantity') ?? $cartRule->discount_quantity"
                                                    id="discount_quantity"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.maximum-quantity-allowed-to-be-discounted')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.maximum-quantity-allowed-to-be-discounted')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="discount_quantity"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <div class="w-full mb-[10px]">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.buy-x-quantity')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="discount_step"
                                                    :value="old('discount_step') ?? $cartRule->discount_step"
                                                    id="discount_step"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.buy-x-quantity')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.buy-x-quantity')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="discount_step"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                
                                    <div class="flex  gap-[16px]  max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            @php($selectedOption = old('apply_to_shipping') ?? $cartRule->apply_to_shipping)
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.apply-to-shipping')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="apply_to_shipping"
                                                    :value="old('apply_to_shipping') ?? 0"
                                                    id="apply_to_shipping"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.apply-to-shipping')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.apply-to-shipping')"
                                                    ::disabled="action_type == 'cart_fixed'"
                                                >
                                                    <option 
                                                        value="0"
                                                        {{ ! $selectedOption ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.no')
                                                    </option>
            
                                                    <option 
                                                        value="1"
                                                        {{ $selectedOption ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.yes')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="apply_to_shipping"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <div class="w-full mb-[10px]">
                                            @php($selectedOption = old('free_shipping') ?? $cartRule->free_shipping)

                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.free-shipping')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="free_shipping"
                                                    :value="old('free_shipping') ?? 0"
                                                    id="free_shipping"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.free-shipping')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.free-shipping')"
                                                >
                                                    <option
                                                        value="0"
                                                        {{ ! $selectedOption ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.no')
                                                    </option>

                                                    <option
                                                        value="1"
                                                        {{ $selectedOption ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.yes')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="free_shipping"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                
                                    <div class="flex  gap-[16px] justify-between max-sm:flex-wrap">
                                        <div class="w-full mb-[10px]">
                                            @php($selectedOption = old('end_other_rules') ?? $cartRule->end_other_rules)

                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.end-of-other-rules')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="end_other_rules"
                                                    :value="old('end_other_rules') ?? 0"
                                                    id="end_other_rules"
                                                    class="!w-1/2 max-sm:!w-full" 
                                                    :label="trans('admin::app.promotions.cart-rules.edit.end-of-other-rules')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.end-of-other-rules')"
                                                >
                                                    <option
                                                        value="0"
                                                        {{ ! $selectedOption ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.no')
                                                    </option>

                                                    <option
                                                        value="1"
                                                        {{ $selectedOption ? 'selected' : '' }}
                                                    >
                                                        @lang('admin::app.promotions.cart-rules.edit.yes')
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
                        </div>

                        {{-- Right sub-component --}}
                        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                            {{-- Settings --}}
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                                            @lang('admin::app.promotions.cart-rules.edit.settings')
                                        </p>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <label
                                        for="status"
                                        class="flex gap-[10px] w-full items-center p-[6px] cursor-pointer select-none hover:bg-gray-100 hover:rounded-[8px]"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            value="{{ old('status') ?? $cartRule->status }}" 
                                            id="status"
                                            class="hidden peer"
                                            {{ $cartRule->status ? 'checked' : '' }}
                                        >

                                        <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>

                                        <div
                                            class="text-[14px] text-gray-600 font-semibold cursor-pointer"
                                        >
                                            @lang('admin::app.promotions.cart-rules.edit.status')
                                        </div>

                                    </label>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.promotions.cart-rules.edit.priority')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="sort_order"
                                            :value="old('sort_order') ?? $cartRule->sort_order"
                                            id="sort_order"
                                            :label="trans('admin::app.promotions.cart-rules.edit.priority')"
                                            :placeholder="trans('admin::app.promotions.cart-rules.edit.priority')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="sort_order"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </x-slot:content>
                            </x-admin::accordion>
                
                            {{-- Marketing Time --}}
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                                            @lang('admin::app.promotions.cart-rules.edit.marketing-time')
                                        </p>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <div class="px-[16px] pb-[16px]">
                                        <div class="flex gap-[16px]">
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.from')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="starts_from"
                                                    :value="old('starts_from') ?? $cartRule->starts_from"
                                                    id="starts_from"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.from')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.from')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="starts_from"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.promotions.cart-rules.edit.to')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="ends_till"
                                                    :value="old('ends_till') ?? $cartRule->ends_till"
                                                    id="ends_till"
                                                    :label="trans('admin::app.promotions.cart-rules.edit.to')"
                                                    :placeholder="trans('admin::app.promotions.cart-rules.edit.to')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="ends_till"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                                </x-slot:content>
                            </x-admin::accordion>
                        </div>
                    </div>
                </x-admin::form>
           </div>
        </script>

        <script type="module">
            app.component('v-cart-rule-edit-form', {
                template: '#v-cart-rule-edit-form-template',

                data: function() {
                    return {
                        coupon_type: {{ old('coupon_type') ?? $cartRule->coupon_type }},

                        use_auto_generation: {{ old('use_auto_generation') ?? $cartRule->use_auto_generation }},

                        condition_type: {{ old('condition_type') ?? $cartRule->condition_type }},

                        conditions: @json($cartRule->conditions ?? []),

                        action_type: "{{ old('action_type') ?? $cartRule->action_type }}"
                    }
                },

                methods: {
                    addCondition: function() {
                        this.conditions.push({
                            'attribute': '',
                            'operator': '==',
                            'value': '',
                        });
                    },

                    removeCondition: function(condition) {
                        let index = this.conditions.indexOf(condition)

                        this.conditions.splice(index, 1)
                    },

                    onSubmit: function(e) {
                        this.$root.onSubmit(e)
                    },

                    onSubmit: function(e) {
                        this.$root.onSubmit(e)
                    },

                    redirectBack: function(fallbackUrl) {
                        this.$root.redirectBack(fallbackUrl)
                    }
                }
            })
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
                        class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full"
                        v-model="condition.attribute"
                    >
                        <option value="">@lang('admin::app.promotions.catalog-rules.create.choose-condition-to-add')</option>

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
                        class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
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
                            <v-tree-view 
                                value-field="id"
                                id-field="id"
                                :name-field="'conditions[' + index + '][value]'"
                                input-type="checkbox"
                                :items='matchedAttribute.options'
                                :value='condition.value'
                                :behavior="'no'"
                                fallback-locale="{{ config('app.fallback_locale') }}"
                            >
                            </v-tree-view>
                        </div>

                        <div v-else>
                            <div
                                v-if="matchedAttribute.type == 'text' 
                                    || matchedAttribute.type == 'price'
                                    || matchedAttribute.type == 'decimal'
                                    || matchedAttribute.type == 'integer'"
                            >
                                <input 
                                    type="text"
                                    :name="['conditions[' + index + '][value]']"
                                    :id="['conditions[' + index + '][value]']"
                                    class="border w-full py-2 px-3 appearance-none rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'date'">
                                <input 
                                    type="date"
                                    :name="['conditions[' + index + '][value]']"
                                    class="border w-full py-2 px-3 appearance-none rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'datetime'">
                                <input 
                                    type="datetime"
                                    :name="['conditions[' + index + '][value]']"
                                    class="border w-full py-2 px-3 appearance-none rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                />
                            </div>

                            <div v-if="matchedAttribute.type == 'boolean'">
                                <select 
                                    :name="['conditions[' + index + '][value]']"
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
                                    v-model="condition.value"
                                >
                                    <option value="1">
                                        @lang('admin::app.promotions.cart-rules.edit.yes')
                                    </option>

                                    <option value="0">
                                        @lang('admin::app.promotions.cart-rules.edit.no')
                                    </option>
                                </select>
                            </div>

                            <div v-if="matchedAttribute.type == 'select' || matchedAttribute.type == 'radio'">
                                <select
                                    :name="['conditions[' + index + '][value]']"
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
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
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-full max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
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
                                    class="inline-flex gap-x-[4px] justify-between items-center max-h-[40px] w-[196px] max-w-[196px] py-[6px] pl-[12px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400"
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
                    class="icon-delete max-h-[36px] max-w-[36px] text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 max-sm:place-self-center"
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
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.less-than') }}'
                            }],
                            'decimal': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.less-than') }}'
                            }],
                            'integer': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.less-than') }}'
                            }],
                            'text': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }, {
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.contain') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.does-not-contain') }}'
                            }],
                            'boolean': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }],
                            'date': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.less-than') }}'
                            }],
                            'datetime': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.less-than') }}'
                            }],
                            'select': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }],
                            'radio': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.is-not-equal-to') }}'
                            }],
                            'multiselect': [{
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.contains') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.does-not-contain') }}'
                            }],
                            'checkbox': [{
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.contains') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.edit.does-not-contain') }}'
                            }]
                        }
                    }
                },

                computed: {
                    matchedAttribute: function () {
                        if (this.condition.attribute == '')
                            return;

                        let self = this;

                        let attributeIndex = this.attributeTypeIndexes[this.condition.attribute.split("|")[0]];

                        let matchedAttribute = this.conditionAttributes[attributeIndex]['children'].filter(function (attribute) {
                            return attribute.key == self.condition.attribute;
                        });

                        if (matchedAttribute[0]['type'] == 'multiselect' || matchedAttribute[0]['type'] == 'checkbox') {
                            this.condition.operator = '{}';

                            this.condition.value = this.condition.value == '' && this.condition.value != undefined
                                    ? []
                                    : Array.isArray(this.condition.value) ? this.condition.value : [];
                        }

                        return matchedAttribute[0];
                    }
                },


                methods: {
                    removeCondition: function() {
                        this.$emit('onRemoveCondition', this.condition)
                    }
                }
            });
        </script>

        {{-- v tree view --}}
        @include('admin::tree.view')

        {{-- v tree item --}}
        @include('admin::tree.item')

        {{-- v tree checkbox --}}
        @include('admin::tree.item')

        {{-- v tree checkbox --}}
        @include('admin::tree.checkbox')

        {{-- v tree radio --}}
        @include('admin::tree.radio')
    @endPushOnce
</x-admin::layouts>
