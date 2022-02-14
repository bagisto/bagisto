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
                    'deviceTokenNumber': null,
                }
            },
            mounted:function(){
                this.deviceTokenNumber = localStorage.getItem("deviceTokenNumber");
                if(this.deviceTokenNumber == null || this.deviceTokenNumber == 'null'){
                    let deviceToken = Date.now()+""+Math.floor(Math.random() * 1000000)
                    localStorage.setItem("deviceTokenNumber",deviceToken);
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
                            this.updateCompareCount();

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
                        this.$http.put(
                            `${this.baseUrl}/guest-comparison`, {
                                productId: this.productId,
                                device_token: this.deviceTokenNumber,
                            }
                        ).then(response => {
                            this.updateCompareCount();

                            if(response.status == 200){
                                if(response.data.statusCode == 200){
                                    window.flashMessages = [{
                                            'type': `alert-success`,
                                            'message': "{{ __('shop::app.customer.compare.added') }}"
                                    }];
                                    this.$root.addFlashMessages();
                                }else{
                                    window.flashMessages = [{
                                            'type': `alert-success`,
                                            'message':response.data.message
                                    }];
                                    this.$root.addFlashMessages();
                                }
                            }else{
                                window.flashMessages = [{
                                        'type': `alert-success`,
                                        'message': "{{ __('shop::app.customer.compare.some_thing_went_wrong') }}"
                                }];
                                this.$root.addFlashMessages();
                            }

                        }).catch(error => {
                            window.flashMessages = [{
                                        'type': `alert-success`,
                                        'message': "{{ __('shop::app.customer.compare.some_thing_went_wrong') }}"
                                }];
                            this.$root.addFlashMessages();
                        });
                    }
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
                        let items = localStorage.getItem('deviceTokenNumber');
                        let url = `${this.baseUrl}/${'detailed-products'}`;
                        let data = { params: { items }};
                        this.$http.get(url, data)
                        .then(response => {
                            if(response.data.statusCode == 200){
                                $('#compare-items-count').html(response.data.products.length);
                            }
                        })
                        .catch(error => {
                            console.log("{{ __('shop::app.common.error') }}");
                        });
                    }
                }
            }
        });
    </script>
@endpush