<v-binding-component>
    {{ $slot }}
</v-binding-component>

@pushOnce('scripts')
    <script type="text/x-template" id="v-binding-component-template">
        <div class="shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.20)] border-[1px] border-gray-300 rounded-[4px] mt-[16px] bg-white">
            <div class="table-responsive grid w-full">
                <slot name="header">
                    <div class="row grid px-[16px] py-[10px] border-b-[1px] border-gray-300 grid-cols-4 grid-rows-1 bg-gray-50  text-gray-600" style="grid-template-columns: repeat(6, 1fr)">
                        <div class="flex gap-[10px]">
                            <span class="icon-uncheckbox text-[24px]"></span>
                            
                            <p>Order ID</p>
                        </div>

                        <p>Status</p>

                        <p>Price</p>

                        <p>Customer</p>

                        <p>Email</p>

                        <p>Image</p>
                    </div>
                </slot>

                <slot name="body" :items="items" :updateCounter="updateCounter">
                    <div class="row grid px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600" style="grid-template-columns: repeat(6, 1fr)">
                        <p>#02153</p>

                        <p>Pending</p>

                        <p>$75.00</p>

                        <p>John Doe</p>

                        <p>john@deo.com</p>

                        <p>Broadway, New York</p>
                    </div>
                </slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-binding-component', {
            template: '#v-binding-component-template',

            data() {
                return {
                    items: [{
                        id: 1,
                        name: 'Item 1',
                        count: 0,
                    }, {
                        id: 2,
                        name: 'Item 2',
                        count: 0,
                    }, {
                        id: 3,
                        name: 'Item 3',
                        count: 0,
                    }]
                }
            },

            methods: {
                updateCounter(item) {
                    item.count++;
                }
            }
        });
    </script>
@endpushOnce