@if (in_array($product->type, explode(',', core()->getConfigData('sales.rma.setting.select_allowed_product_type'))))

    @if (empty($product->parent_id))
        @php
            $rmaRules = app('Webkul\RMA\Repositories\RMARuleRepository')->where('status', 1)->get();
        @endphp

        {!! view_render_event('bagisto.admin.catalog.product.edit.form.allow-rma.before', ['product' => $product]) !!}

        <v-allow-rma-product></v-allow-rma-product>

        {!! view_render_event('bagisto.admin.catalog.product.edit.form.allow-rma.after', ['product' => $product]) !!}

        @pushOnce('scripts')
            <script type="text/x-template" id="v-allow-rma-product-template">
                <div class="relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <div class="text-base text-gray-800 dark:text-white font-semibold mb-4">
                        @lang('admin::app.configuration.index.sales.rma.title')
                    </div>

                    <div class="mb-4 last:!mb-0">
                        @php $selectedValue = old('allow_rma') ?: $product->allow_rma @endphp

                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                            @lang('admin::app.marketing.promotions.cart-rules.create.status')
                            </x-admin::form.control-group.label>

                            <input
                                type="hidden"
                                name="allow_rma"
                                value="0"
                                />

                            <x-admin::form.control-group.control
                                type="switch"
                                name="allow_rma"
                                value="1"
                                :label="trans('admin::app.marketing.promotions.cart-rules.create.status')"
                                :checked="(boolean) $selectedValue"
                                @change="allowRma = !allowRma"
                            />

                            <x-admin::form.control-group.error control-name="status" />
                        </x-admin::form.control-group>
                    </div>
                    <div
                        v-if="allowRma"
                        class="mb-4 last:!mb-0"
                        >
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.rma.sales.rma.rules.index.title')
                        </x-admin::form.control-group.label>

                        <v-field
                            as="select"
                            name="rma_rules"
                            class="custom-select flex min-h-10 w-full rounded-lg border bg-white px-3 py-2 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                            label="{{ trans('admin::app.rma.sales.rma.all-rma.index.datagrid.rma-status') }}"
                            v-model="rmaRules"
                        >
                            <option value="">
                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.select')
                            </option>

                            @foreach ($rmaRules as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->title }}
                                </option>
                            @endforeach
                        </v-field>

                        <v-error-message
                            name="rma_rules"
                            v-slot="{ message }"
                        >
                            <p
                                class="mt-1 text-xs italic text-red-600"
                                v-text="message"
                            >
                            </p>
                        </v-error-message>
                    </div>
                </div>
            </script>

            <script type="module">
                app.component('v-allow-rma-product', {
                    template: '#v-allow-rma-product-template',

                    data() {
                        return {
                            allowRma: "{{ (boolean) $product->allow_rma ?? old('allow_rma') }}",

                            rmaRules: "{{ $product->rma_rules ?? old('rma_rules') }}",
                        };
                    },
                })
            </script>
        @endPushOnce
    @endif
@endif