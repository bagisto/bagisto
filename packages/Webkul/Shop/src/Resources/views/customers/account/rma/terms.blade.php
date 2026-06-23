{!! view_render_event('marketplace.seller.account.sign_up.form.agreement.before') !!}

<v-customer-rma-return-policy></v-customer-rma-return-policy>

{!! view_render_event('marketplace.seller.account.sign_up.form.agreement.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-customer-rma-return-policy-template"
    >
        <div class="mb-4">
            <v-field
                type="checkbox" 
                name="agreement" 
                rules="required" 
                v-slot="{ field, errors }" 
                value="1"
            >
                <label class="relative inline-flex cursor-pointer items-center">
                    <input 
                        type="checkbox" 
                        class="peer sr-only" 
                        id="agreement" 
                        name="agreement" 
                        value="1"
                        v-bind="field" 
                    />

                    <span 
                        class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl peer-checked:text-navyBlue"
                    >
                    </span>

                    <span class="ml-2 mt-4 block">
                        <span class="text-zinc-500 max-md:text-xs">
                            @lang('shop::app.customers.account.rma.terms.terms')
                        </span>
                        
                        <a 
                            href="javascript:void(0);" 
                            class="ml-1 text-blue-500 hover:text-blue-600 hover:underline max-md:text-xs"
                            @click.prevent="$refs.agreementModel.open()"
                        >
                            @lang('shop::app.customers.account.rma.terms.read')
                        </a>
                    </span>
                </label>

                <span 
                    v-if="errors[0]" 
                    class="mt-1 block text-xs italic text-red-600"
                    v-text="errors[0]"
                >
                </span>
            </v-field>
        </div>

        <!-- Agreement modal -->
        <x-shop::modal ref="agreementModel">
            <!-- Modal Header -->
            <x-slot:header>
                <h2 class="text-lg font-semibold max-md:text-base">
                    @lang('installer::app.seeders.cms.pages.terms-conditions.title')
                </h2>
            </x-slot>

            <!-- Modal Content -->
            <x-slot:content>
                <div 
                    class="overflow-y-auto rounded border border-gray-200 bg-gray-50 p-4" 
                    style="min-height: 400px; max-height: 500px;"
                >
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {{ core()->getConfigData('sales.rma.setting.return_policy') }}
                    </div>
                </div>
            </x-slot>
        </x-shop::modal>
    </script>

    <script type="module">
        app.component('v-customer-rma-return-policy', {
            template: '#v-customer-rma-return-policy-template',
        })
    </script>
@endPushOnce