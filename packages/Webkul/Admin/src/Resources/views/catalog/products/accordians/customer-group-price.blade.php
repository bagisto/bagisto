@section('css')
    @parent
    <style>
        .table th.price, .table th.weight {
            width: 100px;
        }
        .table th.actions {
            width: 85px;
        }
        .table td.actions .icon {
            margin-top: 8px;
        }
        .table td.actions .icon.pencil-lg-icon {
            margin-right: 10px;
        }
    </style>
@stop

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.customer_group_prices.before', ['product' => $product]) !!}

<customer-group-price-list></customer-group-price-list>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.customer_group_prices.after', ['product' => $product]) !!}

@push('scripts')
    @parent

    <script type="text/x-template" id="customer-group-price-list-template">
        <div>
            <div class="table" style="margin-top: 20px; overflow-x: unset;">
                <table>

                    <thead>
                        <tr>
                            <th>{{ __('admin::app.catalog.products.customer-group') }}</th>
                            <th>{{ __('admin::app.catalog.products.qty') }}</th>
                            <th>{{ __('admin::app.catalog.products.price') }}</th>
                            <th class="actions"></th>
                        </tr>
                    </thead>

                    <tbody>

                        <customer-group-price-item v-for='(customerGroupPrice, index) in customer_group_prices' :customer-group-price="customerGroupPrice" :key="index" :index="index" @onRemoveCustomerGroupPrice="removeCustomerGroupPrice($event)"></customer-group-price-item>

                    </tbody>

                </table>

                <button type="button" class="btn btn-lg btn-primary" style="margin-top: 20px" @click="addCustomerGroupPrice()">
                    {{ __('admin::app.catalog.products.add-group-price') }}
                </button>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="customer-group-price-item-template">
        <tr>
            <td>
                <div class="control-group">
                    <select :name="[inputName + '[customer_group_id]']" v-model="customerGroupPrice.customer_group_id" class="control">
                        <option value="">{{ __('admin::app.catalog.products.all-group') }}</option>

                        @foreach (app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                            <option value="{{ $customerGroup->id }}">{{ $customerGroup->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(inputName + '[qty]') ? 'has-error' : '']">
                    <input type="number" v-validate="'required|min_value:0'" :name="[inputName + '[qty]']" v-model="customerGroupPrice.qty" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.qty') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(inputName + '[qty]')">@{{ errors.first(inputName + '[qty]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group">
                    <select :name="[inputName + '[value_type]']" v-model="customerGroupPrice.value_type" class="control">
                        <option value="fixed">{{ __('admin::app.catalog.products.fixed') }}</option>
                        <option value="discount">{{ __('admin::app.catalog.products.discount') }}</option>
                    </select>
                </div>

                <div class="control-group">
                    <div class="control-group" :class="[errors.has(inputName + '[value]') ? 'has-error' : '']">
                        <input type="number" step=".01" v-validate="{required: true, min_value: 0, ...(customerGroupPrice.value_type === 'discount' ? {max_value: 100} : {})}" :name="[inputName + '[value]']" v-model="customerGroupPrice.value" class="control" data-vv-as="&quot;{{ __('admin::app.datagrid.price') }}&quot;"/>
                        <span class="control-error" v-if="errors.has(inputName + '[value]')">@{{ errors.first(inputName + '[value]') }}</span>
                    </div>
                </div>
            </td>

            <td class="actions">
                <i class="icon remove-icon" @click="removeCustomerGroupPrice()"></i>
            </td>
        </tr>
    </script>

    <script>
        Vue.component('customer-group-price-list', {

            template: '#customer-group-price-list-template',

            inject: ['$validator'],

            data: function() {
                return {
                    customer_group_prices: @json($product->customer_group_prices()->get())
                }
            },

            methods: {
                addCustomerGroupPrice: function(item, key) {
                    this.customer_group_prices.push({
                        customer_group_id: '',
                        qty: 0,
                        value_type: 'fixed',
                        amount: 0
                    });
                },

                removeCustomerGroupPrice: function(customerGroupPrice) {
                    let index = this.customer_group_prices.indexOf(customerGroupPrice)

                    this.customer_group_prices.splice(index, 1)
                }
            }
        });

        Vue.component('customer-group-price-item', {

            template: '#customer-group-price-item-template',

            props: ['index', 'customerGroupPrice'],

            inject: ['$validator'],

            mounted: function() {
                if (! this.customerGroupPrice['customer_group_id']) {
                    this.customerGroupPrice['customer_group_id'] = '';
                }
            },

            computed: {
                inputName: function () {
                    if (this.customerGroupPrice.id) {
                        return 'customer_group_prices[' + this.customerGroupPrice.id + ']';
                    }

                    return 'customer_group_prices[customer_group_price_' + this.index + ']';
                }
            },

            methods: {
                removeCustomerGroupPrice: function () {
                    this.$emit('onRemoveCustomerGroupPrice', this.customerGroupPrice)
                }
            }
        });
    </script>
@endpush
