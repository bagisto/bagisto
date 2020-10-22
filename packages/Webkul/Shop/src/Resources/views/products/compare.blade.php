<compare-component product-id="{{ $productId }}"></compare-component>

@push('scripts')

    <script type="text/x-template" id="compare-component-template">
        <a
            class="unset text-right"
            title="{{  __('shop::app.customer.compare.add-tooltip') }}"
            @click="addProductToCompare"
            style="cursor: pointer">
            <img src="{{ asset('themes/default/assets/images/compare_arrows.png') }}" alt="" />
        </a>
    </script>

    <script>
        Vue.component('compare-component', {
            props: ['productId'],

            template: '#compare-component-template',

            data: function () {
                return {
                    'baseUrl': "{{ url()->to('/') }}",
                    'customer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            methods: {
                'addProductToCompare': function () {
                    if (this.customer == "true" || this.customer == true) {
                        this.$http.put(
                            `${this.baseUrl}/comparison`, {
                                productId: this.productId,
                            }
                        ).then(response => {
                            window.flashMessages = [{
                                'type': `alert-${response.data.status}`,
                                'message': response.data.message
                            }];

                            this.$root.addFlashMessages()
                        }).catch(error => {
                            window.flashMessages = [{
                                'type': `alert-danger`,
                                'message': "{{ __('shop::app.common.error') }}"
                            }];

                            this.$root.addFlashMessages()
                        });
                    } else {
                        let updatedItems = [this.productId];
                        let existingItems = this.getStorageValue('compared_product');

                        if (existingItems) {
                            if (existingItems.indexOf(this.productId) == -1) {
                                updatedItems = existingItems.concat(updatedItems);

                                this.setStorageValue('compared_product', updatedItems);

                                window.flashMessages = [{
                                    'type': `alert-success`,
                                    'message': "{{ __('shop::app.customer.compare.added') }}"
                                }];

                                this.$root.addFlashMessages()
                            } else {
                                window.flashMessages = [{
                                    'type': `alert-success`,
                                    'message': "{{ __('shop::app.customer.compare.already_added') }}"
                                }];

                                this.$root.addFlashMessages()
                            }
                        } else {
                            this.setStorageValue('compared_product', updatedItems);

                            window.flashMessages = [{
                                'type': `alert-success`,
                                'message': "{{ __('shop::app.customer.compare.added') }}"
                            }];

                                this.$root.addFlashMessages()
                        }
                    }

                    this.updateCompareCount();
                },

                'getStorageValue': function (key) {
                    let value = window.localStorage.getItem(key);

                    if (value) {
                        value = JSON.parse(value);
                    }

                    return value;
                },

                'setStorageValue': function (key, value) {
                    window.localStorage.setItem(key, JSON.stringify(value));

                    return true;
                },

                'updateCompareCount': function () {
                    if (this.customer == "true" || this.customer == true) {
                        this.$http.get(`${this.baseUrl}/items-count`)
                        .then(response => {
                            $('#compare-items-count').html(response.data.compareProductsCount);
                        })
                        .catch(exception => {
                            window.flashMessages = [{
                                'type': `alert-error`,
                                'message': "{{ __('shop::app.common.error') }}"
                            }];

                            this.$root.addFlashMessages();
                        });
                    } else {
                        let comparedItems = JSON.parse(localStorage.getItem('compared_product'));
                        comparedItemsCount = comparedItems ? comparedItems.length : 0;

                        $('#compare-items-count').html(comparedItemsCount);
                    }
                }
            }
        });
    </script>
@endpush