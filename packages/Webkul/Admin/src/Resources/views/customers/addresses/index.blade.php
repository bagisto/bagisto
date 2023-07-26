<v-customer-address></v-customer-address>

@pushOnce('scripts')
    <script type="text/x-template" id="v-customer-address-template">
        <div v-if="addresses.length">
            {!! view_render_event('bagisto.admin.customer.addresses.list.before') !!}
            
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Address(@{{addresses.length}})</p>
                    <div class="flex gap-[6px] items-center">
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <template  v-for="address in addresses">
                        <div class="grid gap-y-[10px] pb-[16px]">
                            <p 
                                class="label-pending"
                                v-if="address.default_address"
                            >
                                Default Address
                            </p>
                            <div class="">
                                <p class="text-gray-800 font-semibold" >@{{ address.first_name }} @{{ address.last_name }}</p>
                                <p class="text-gray-600">
                                    @{{ address.address1 }} @{{ address.address2 }},
                                    @{{ address.city }}, 
                                    @{{ address.state }}, 
                                    @{{ address.country }}, 
                                    @{{ address.postcode }}
                                </p>
                            </div>
                            <div class="">
                                <p class="text-gray-600">Phone : @{{ address.phone }}</p>
                            </div>
                            <div class="flex gap-[10px] items-center">
                                <p class="text-blue-600">Edit</p>
                                <a
                                    class="text-blue-600 text-[14px] cursor-pointer" 
                                    @click="remove(address.id)"
                                >   
                                    Delete
                                </a>
                                
                                <p class="text-blue-600">Set as Default</p>
                            </div>
                        </div>

                        <span class="block w-full border-b-[1px] mb-[20px] border-gray-300"></span>
                    </template>
                </x-slot:content>
            </x-admin::accordion>

            {!! view_render_event('bagisto.admin.customer.addresses.list.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-customer-address', {
            template: '#v-customer-address-template',

            data() {
                return {
                    addresses: {},
                }
            },

            created() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get('{{ route('admin.customer.addresses.index', $customer->id) }}')

                    .then(response => {
                        console.log(response);
                        this.addresses = response.data.addresses;                              
                    })

                    .catch(error => {});  
                },

                remove(id) {
                    this.$axios.delete(`{{ route('admin.customer.addresses.delete', '') }}/${id}`)

                    .then(response => {
                        this.get();
                        console.log(response);

                    })
                    .catch(error => {});
                }
            }
        })
    </script>
@endPushOnce
