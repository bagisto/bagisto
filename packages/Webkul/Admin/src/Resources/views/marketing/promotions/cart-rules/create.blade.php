<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.cart-rules.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.before') !!}

    <x-admin::form
        :action="route('admin.marketing.promotions.cart_rules.store')"
        enctype="multipart/form-data"
    >

        {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.create_form_controls.before') !!}

        <div class="mt-3 flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.marketing.promotions.cart-rules.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.marketing.promotions.cart_rules.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
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

        <v-cart-rule-create-form>
            <!-- Shimmer Effect -->
            <x-admin::shimmer.marketing.promotions.cart-rules />
        </v-cart-rule-create-form>

        {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.create_form_controls.after') !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.after') !!}


    @pushOnce('scripts')
        <!-- v cart rules create form template -->
        <script
            type="text/x-template"
            id="v-cart-rule-create-form-template"
        >
            <!-- body content  -->
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                <!-- Left sub-component -->
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.general.before') !!}

                    <!-- General -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.marketing.promotions.cart-rules.create.general')
                        </p>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.promotions.cart-rules.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="name"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('admin::app.marketing.promotions.cart-rules.create.name')"
                                :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.promotions.cart-rules.create.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                class="text-gray-600 dark:text-gray-300"
                                id="description"
                                name="description"
                                :value="old('description')"
                                :label="trans('admin::app.marketing.promotions.cart-rules.create.description')"
                                :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.description')"
                            />

                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
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

                            <x-admin::form.control-group.error control-name="coupon_type" />
                        </x-admin::form.control-group>

                        <template v-if="parseInt(couponType)">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.promotions.cart-rules.create.auto-generate-coupon')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="use_auto_generation"
                                    name="use_auto_generation"
                                    rules="required"
                                    v-model="useAutoGeneration"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.auto-generate-coupon')"
                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.auto-generate-coupon')"
                                >
                                    <option value="0">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.no')
                                    </option>

                                    <option value="1">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.yes')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="use_auto_generation" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group v-if="! parseInt(useAutoGeneration)">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.promotions.cart-rules.create.coupon-code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="coupon_code"
                                    name="coupon_code"
                                    rules="required"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.coupon-code')"
                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.coupon-code')"
                                />

                                <x-admin::form.control-group.error control-name="coupon_code" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.cart-rules.create.uses-per-coupon')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="uses_per_coupon"
                                    name="uses_per_coupon"
                                    rules="numeric"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-coupon')"
                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-coupon')"
                                />

                                <x-admin::form.control-group.error control-name="uses_per_coupon" />
                            </x-admin::form.control-group>
                        </template>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.promotions.cart-rules.create.uses-per-customer')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="usage_per_customer"
                                name="usage_per_customer"
                                :label="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-customer')"
                                :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.uses-per-customer')"
                            />

                            <x-admin::form.control-group.error control-name="usage_per_customer" />
                        </x-admin::form.control-group>

                        <p class="text-sm text-gray-500">
                            @lang('admin::app.marketing.promotions.cart-rules.create.uses-per-customer-control-info')
                        </p>
                    </div>

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.general.after') !!}

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.before') !!}

                    <!-- Conditions -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <div class="mb-8 flex items-center justify-between gap-4">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.marketing.promotions.cart-rules.create.conditions')
                            </p>

                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.cart-rules.create.condition-type')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    class="ltr:pr-10 rtl:pl-10"
                                    id="condition_type"
                                    name="condition_type"
                                    v-model="conditionType"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.condition-type')"
                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.condition-type')"
                                >
                                    <option value="1">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.all-conditions-true')
                                    </option>

                                    <option value="2">
                                        @lang('admin::app.marketing.promotions.cart-rules.create.any-conditions-true')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="condition_type" />
                            </x-admin::form.control-group>
                        </div>

                        <v-cart-rule-condition-item
                            v-for='(condition, index) in conditions'
                            :condition="condition"
                            :key="index"
                            :index="index"
                            @onRemoveCondition="removeCondition($event)"
                        >
                        </v-cart-rule-condition-item>

                        <div
                            class="secondary-button mt-4 max-w-max"
                            @click="addCondition"
                        >
                            @lang('admin::app.marketing.promotions.cart-rules.create.add-condition')
                        </div>

                    </div>

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.after') !!}

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.before') !!}

                    <!-- Action -->
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <div class="grid gap-1.5">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.marketing.promotions.cart-rules.create.actions')
                            </p>

                            <div class="flex gap-4 max-sm:flex-wrap">
                                <div class="w-full">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.action-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="action_type"
                                            name="action_type"
                                            rules="required"
                                            v-model="actionType"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.action-type')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.action-type')"
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

                                        <x-admin::form.control-group.error control-name="action_type" />
                                    </x-admin::form.control-group>
                                </div>

                                <div class="w-full">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.promotions.cart-rules.create.discount-amount')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="discount_amount"
                                            name="discount_amount"
                                            rules="required"
                                            :value="old('discount_amount', 0)"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.discount-amount')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.discount-amount')"
                                        />

                                        <x-admin::form.control-group.error control-name="discount_amount" />
                                    </x-admin::form.control-group>
                                </div>
                            </div>

                            <div class="flex gap-4 max-sm:flex-wrap">
                                <div class="w-full">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.marketing.promotions.cart-rules.create.maximum-quantity-allowed-to-be-discounted')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="discount_quantity"
                                            name="discount_quantity"
                                            :value="old('discount_quantity', 0)"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.maximum-quantity-allowed-to-be-discounted')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.maximum-quantity-allowed-to-be-discounted')"
                                        />

                                        <x-admin::form.control-group.error control-name="discount_quantity" />
                                    </x-admin::form.control-group>
                                </div>

                                <div class="w-full">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.marketing.promotions.cart-rules.create.buy-x-quantity')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="discount_step"
                                            name="discount_step"
                                            :value="old('discount_step', 0)"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.buy-x-quantity')"
                                            :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.buy-x-quantity')"
                                        />

                                        <x-admin::form.control-group.error control-name="discount_step" />
                                    </x-admin::form.control-group>
                                </div>
                            </div>

                            <div class="flex gap-4 max-sm:flex-wrap">
                                <div class="w-full">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.marketing.promotions.cart-rules.create.apply-to-shipping')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="apply_to_shipping"
                                            name="apply_to_shipping"
                                            :value="old('apply_to_shipping', 0)"
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

                                        <x-admin::form.control-group.error control-name="apply_to_shipping" />
                                    </x-admin::form.control-group>
                                </div>

                                <div class="w-full">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.marketing.promotions.cart-rules.create.free-shipping')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="free_shipping"
                                            name="free_shipping"
                                            :value="old('free_shipping', 0)"
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

                                        <x-admin::form.control-group.error control-name="free_shipping" />
                                    </x-admin::form.control-group>
                                </div>
                            </div>

                            <div class="flex justify-between gap-4 max-sm:flex-wrap">
                                <div class="w-full">
                                    <x-admin::form.control-group class="!mb-0">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.marketing.promotions.cart-rules.create.end-of-other-rules')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            class="!w-1/2 max-sm:!w-full"
                                            id="end_other_rules"
                                            name="end_other_rules"
                                            :value="old('end_other_rules', 0)"
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

                                        <x-admin::form.control-group.error control-name="end_other_rules" />
                                    </x-admin::form.control-group>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.conditions.after') !!}

                </div>

                <!-- Right sub-component -->
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.settings.before') !!}

                    <!-- Settings -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.marketing.promotions.cart-rules.create.settings')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.cart-rules.create.priority')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="sort_order"
                                    name="sort_order"
                                    :value="old('sort_order')"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.priority')"
                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.priority')"
                                />

                                <x-admin::form.control-group.error control-name="sort_order" />
                            </x-admin::form.control-group>

                            <!-- channels -->
                            <div class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.promotions.cart-rules.create.channels')
                                </x-admin::form.control-group.label>

                                @foreach(core()->getAllChannels() as $channel)
                                    <x-admin::form.control-group class="!mb-2 flex items-center gap-2.5">
                                        <x-admin::form.control-group.control
                                            type="checkbox"
                                            :id="'channel_' . '_' . $channel->id"
                                            name="channels[]"
                                            :value="$channel->id"
                                            rules="required"
                                            :for="'channel_' . '_' . $channel->id"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.channels')"
                                            :checked="in_array($channel->id, old('channels', []))"
                                        />

                                        <label
                                            class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                            for="{{ 'channel_' . '_' . $channel->id }}"
                                        >
                                            {{ core()->getChannelName($channel) }}
                                        </label>
                                    </x-admin::form.control-group>
                                @endforeach

                                <x-admin::form.control-group.error control-name="channels[]" />
                            </div>

                            <!-- Customer Grous -->
                            <div class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.promotions.cart-rules.create.customer-groups')
                                </x-admin::form.control-group.label>

                                @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                    <x-admin::form.control-group class="!mb-2 flex items-center gap-2.5">
                                        <x-admin::form.control-group.control
                                            type="checkbox"
                                            :id="'customer_group_' . '_' . $customerGroup->id"
                                            name="customer_groups[]"
                                            rules="required"
                                            :value="$customerGroup->id"
                                            :for="'customer_group_' . '_' . $customerGroup->id"
                                            :label="trans('admin::app.marketing.promotions.cart-rules.create.customer-groups')"
                                            :checked="in_array($customerGroup->id, old('customer_groups', []))"
                                        />

                                        <label
                                            class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                            for="{{ 'customer_group_' . '_' . $customerGroup->id }}"
                                        >
                                            {{ $customerGroup->name }}
                                        </label>
                                    </x-admin::form.control-group>
                                @endforeach

                                <x-admin::form.control-group.error control-name="customer_groups[]" />
                            </div>

                            <!-- Status -->
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.cart-rules.create.status')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="switch"
                                    name="status"
                                    value="1"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.status')"
                                    :checked="(boolean) old('status')"
                                />

                                <x-admin::form.control-group.error control-name="status" />
                            </x-admin::form.control-group>
                        </x-slot>
                    </x-admin::accordion>

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.settings.after') !!}

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.marketing_time.before') !!}

                    <!-- Marketing Time -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.marketing.promotions.cart-rules.create.marketing-time')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.cart-rules.create.from')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="datetime"
                                    name="starts_from"
                                    id="starts_from"
                                    :value="old('starts_from')"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.from')"
                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.from')"
                                />

                                <x-admin::form.control-group.error control-name="starts_from" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.cart-rules.create.to')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="datetime"
                                    id="ends_till"
                                    name="ends_till"
                                    :value="old('ends_till')"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.create.to')"
                                    :placeholder="trans('admin::app.marketing.promotions.cart-rules.create.to')"
                                />

                                <x-admin::form.control-group.error control-name="ends_till" />
                            </x-admin::form.control-group>
                        </x-slot>
                    </x-admin::accordion>

                    {!! view_render_event('bagisto.admin.marketing.promotions.cart_rules.create.card.accordion.marketing_time.after') !!}

                </div>
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

        <!-- v catalog rule condition item form template -->
        <script
            type="text/x-template"
            id="v-cart-rule-condition-item-template"
        >
            <div class="mt-4 flex justify-between gap-4">
                <div class="flex flex-1 gap-4 max-sm:flex-1 max-sm:flex-wrap">
                    <select
                        :name="['conditions[' + index + '][attribute]']"
                        :id="['conditions[' + index + '][attribute]']"
                        class="custom-select min:w-1/3 flex h-10 w-1/3 rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 max-sm:max-w-full max-sm:flex-auto"
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
                        class="custom-select inline-flex h-10 w-full max-w-[196px] items-center justify-between gap-x-1 rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 max-sm:max-w-full max-sm:flex-auto"
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
                            || matchedAttribute.key == 'product|parent::category_ids'
                            || matchedAttribute.key == 'product|children::category_ids'"
                        >
                            <x-admin::tree.view
                                input-type="checkbox"
                                selection-type="individual"
                                ::name-field="'conditions[' + index + '][value]'"
                                value-field="id"
                                id-field="id"
                                ::items='matchedAttribute.options'
                                :fallback-locale="config('app.fallback_locale')"
                            />
                        </div>

                        <div v-else>
                            <div
                                v-if="matchedAttribute.type == 'text'
                                    || matchedAttribute.type == 'price'
                                    || matchedAttribute.type == 'decimal'
                                    || matchedAttribute.type == 'integer'"
                            >
                                <v-field
                                    :name="`conditions[${index}][value]`"
                                    v-slot="{ field, errorMessage }"
                                    :id="`conditions[${index}][value]`"
                                    :rules="
                                        matchedAttribute.type == 'price' ? 'regex:^[0-9]+(\.[0-9]+)?$' : ''
                                        || matchedAttribute.type == 'decimal' ? 'regex:^[0-9]+(\.[0-9]+)?$' : ''
                                        || matchedAttribute.type == 'integer' ? 'regex:^[0-9]+(\.[0-9]+)?$' : ''
                                        || matchedAttribute.type == 'text' ? 'regex:^([A-Za-z0-9_ \'\-]+)$' : ''"
                                    label="Conditions"
                                    v-model="condition.value"
                                >
                                    <input
                                        type="text"
                                        v-bind="field"
                                        :class="{ 'border border-red-500': errorMessage }"
                                        class="min:w-1/3 flex h-10 w-[289px] rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    />
                                </v-field>

                                <v-error-message
                                    :name="`conditions[${index}][value]`"
                                    class="mt-1 text-xs italic text-red-500"
                                    as="p"
                                >
                                </v-error-message>
                            </div>

                            <div v-if="matchedAttribute.type == 'date'">
                                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                                    <input
                                        type="date"
                                        :name="['conditions[' + index + '][value]']"
                                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                        v-model="condition.value"
                                    />
                                </x-admin::flat-picker.date>
                            </div>

                            <div v-if="matchedAttribute.type == 'datetime'">
                                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                                    <input
                                        type="datetime"
                                        :name="['conditions[' + index + '][value]']"
                                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                        v-model="condition.value"
                                    />
                                </x-admin::flat-picker.date>
                            </div>

                            <div v-if="matchedAttribute.type == 'boolean'">
                                <select
                                    :name="['conditions[' + index + '][value]']"
                                    class="custom-select inline-flex h-10 w-[196px] max-w-[196px] items-center justify-between gap-x-1 rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
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
                                    class="custom-select inline-flex h-10 w-full min-w-[196px] items-center justify-between gap-x-1 rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
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
                                    class="custom-select inline-flex h-10 w-full max-w-[196px] items-center justify-between gap-x-1 rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 max-sm:max-w-full max-sm:flex-auto"
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
                                    class="inline-flex h-10 w-[196px] max-w-[196px] items-center justify-between gap-x-1 rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
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
                    class="icon-delete max-h-9 max-w-9 cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                    @click="removeCondition"
                >
                </span>
            </div>
        </script>

        <!-- v catalog rule condition item component -->
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
                        if (this.condition.attribute == '') {
                            return;
                        }

                        let attributeIndex = this.attributeTypeIndexes[this.condition.attribute.split("|")[0]];

                        let matchedAttribute = this.conditionAttributes[attributeIndex]['children'].find(attribute => attribute.key == this.condition.attribute);

                        if (
                            matchedAttribute['type'] == 'multiselect'
                            || matchedAttribute['type'] == 'checkbox'
                        ) {
                            this.condition.operator = '{}';

                            this.condition.value = [];
                        }

                        return matchedAttribute;
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
