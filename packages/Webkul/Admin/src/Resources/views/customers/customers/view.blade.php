<x-admin::layouts>
    <v-customer-view></v-customer-view>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-customer-view-template"
        >
            <!-- Page Title -->
            <x-slot:title>
                @lang('admin::app.customers.customers.view.title')
            </x-slot>

            <div class="grid">
                <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                    <div class="flex gap-2.5 items-center">
                        <template
                            v-if="! customer"
                            class="flex gap-5"
                        >
                            <p class="w-32 p-2.5 shimmer"></p>

                            <p class="w-14 p-2.5 shimmer"></p>
                        </template>

                        <template v-else>
                            <h1
                                v-if="customer"
                                class="text-xl text-gray-800 dark:text-white font-bold leading-6"
                                v-text="`${customer.first_name} ${customer.last_name}`"
                            ></h1>

                            <span
                                v-if="customer.status"
                                class="label-active text-sm mx-1.5"
                            >
                                @lang('admin::app.customers.customers.view.active')
                            </span>

                            <span
                                v-else
                                class="label-canceled text-sm mx-1.5"
                            >
                                @lang('admin::app.customers.customers.view.inactive')
                            </span>

                            <span
                                v-if="customer.is_suspended"
                                class="label-canceled text-sm"
                            >
                                @lang('admin::app.customers.customers.view.suspended')
                            </span>
                        </template>
                    </div>

                    <!-- Back Button -->
                    <a
                        href="{{ route('admin.customers.customers.index') }}"
                        class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                    >
                        @lang('admin::app.customers.customers.view.back-btn')
                    </a>
                </div>
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.filters.before') !!}

            <!-- Filters -->
            <div class="flex gap-x-1 gap-y-2 items-center flex-wrap mt-7">
                <!-- Create Order button -->
                @if (bouncer()->hasPermission('sales.orders.create'))
                    <div
                        class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                        @click="$emitter.emit('open-confirm-modal', {
                            message: '@lang('admin::app.customers.customers.view.order-create-confirmation')',

                            agree: () => {
                                this.$refs['create-order'].submit()
                            }
                        })"
                    >
                        <span class="icon-cart text-2xl"></span>

                        @lang('admin::app.customers.customers.view.create-order')

                        <!-- Create Order Form -->
                        <form
                            method="post"
                            action="{{ route('admin.customers.customers.cart.store', $customer->id) }}"
                            ref="create-order"
                        >
                            @csrf
                        </form>
                    </div>
                @endif

                <a
                    class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                    href="{{ route('admin.customers.customers.login_as_customer', $customer->id) }}"
                    target="_blank"
                >
                    <span class="icon-exit text-2xl"></span>

                    @lang('admin::app.customers.customers.view.login-as-customer')
                </a>
                
                <!-- Account Delete button -->
                @if (bouncer()->hasPermission('customers.customers.delete'))
                    <div
                        class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                        @click="$emitter.emit('open-confirm-modal', {
                            message: '@lang('admin::app.customers.customers.view.account-delete-confirmation')',

                            agree: () => {
                                this.$refs['delete-account'].submit()
                            }
                        })"
                    >
                        <span class="icon-cancel text-2xl"></span>

                        @lang('admin::app.customers.customers.view.delete-account')

                        <!-- Delete Customer Account -->
                        <form
                            method="post"
                            action="{{ route('admin.customers.customers.delete', $customer->id) }}"
                            ref="delete-account"
                        >
                            @csrf
                        </form>
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.filters.after') !!}

            <!-- Content -->
            <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
                <!-- Left Component -->
                <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.before') !!}

                    @include('admin::customers.customers.view.orders')

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.after') !!}

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.before') !!}

                    @include('admin::customers.customers.view.invoices')

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.after') !!}

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.before') !!}

                    @include('admin::customers.customers.view.reviews')

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.after') !!}

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.before') !!}

                    @include('admin::customers.customers.view.notes')

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.after') !!}
                </div>

                <!-- Right Component -->
                <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.before') !!}

                    <!-- Information -->
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.after') !!}

                    <template v-if="! customer">
                        <x-admin::shimmer.accordion class="w-[360px] h-[271px]"/>
                    </template>

                    <template v-else>
                        <x-admin::accordion>
                            <x-slot:header>
                                <div class="flex w-full">
                                    <p class="w-full p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                        @lang('admin::app.customers.customers.view.customer')
                                    </p>

                                    <!--Customer Edit Component -->
                                    @include('admin::customers.customers.view.edit')
                                </div>
                            </x-slot:header>

                            <x-slot:content>
                                <div class="grid gap-y-2.5">
                                    <p
                                        lass="text-gray-800 font-semibold dark:text-white"
                                        v-text="`${customer.first_name} ${customer.last_name}`"
                                    >
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.customers.customers.view.email')".replace(':email', customer.email ?? 'N/A') }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.customers.customers.view.phone')".replace(':phone', customer.phone ?? 'N/A') }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.customers.customers.view.gender')".replace(':gender', customer.gender ?? 'N/A') }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.customers.customers.view.date-of-birth')".replace(':dob', customer.date_of_birth ?? 'N/A') }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.customers.customers.view.group')".replace(':group_code', customer.group?.name ?? 'N/A') }}
                                    </p>
                                </div>
                            </x-slot:content>
                        </x-admin::accordion>
                    </template>

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.before') !!}

                    <template v-if="! customer">
                        <x-admin::shimmer.accordion class="w-[360px] h-[271px]"/>
                    </template>

                    <template v-else>
                        <!-- Addresses listing-->
                        <x-admin::accordion>
                            <x-slot:header>
                                <div class="flex w-full">
                                    <!-- Address Title -->
                                    <p class="w-full p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                        @{{ "@lang('admin::app.customers.customers.view.address.count')".replace(':count', customer.addresses.length) }}
                                    </p>

                                    <!-- Address Create component -->
                                    @include('admin::customers.customers.view.address.create')
                                </div>
                            </x-slot>

                            <x-slot:content>
                                <template v-if="customer.addresses.length">
                                    <div
                                        class="grid gap-y-2.5"
                                        v-for="(address, index) in customer.addresses"
                                    >
                                        <p
                                            class="label-pending"
                                            v-if="address.default_address"
                                        >
                                            @lang('admin::app.customers.customers.view.default-address')
                                        </p>

                                        <p class="text-gray-800 font-semibold dark:text-white">
                                            @{{ `${address.first_name} ${address.last_name}` }}

                                            <template v-if="address.company_name">
                                                (@{{ address.company_name }})
                                            </template>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            <template v-if="address.address">
                                                @{{ address.address.split('\n').join(', ') }}
                                            </template>

                                            @{{ address.city }},
                                            @{{ address.state }},
                                            @{{ address.country }}
                                            @{{ address.postcode }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ '@lang('admin::app.customers.customers.view.phone')'.replace(':phone', address.phone ?? 'N/A') }}
                                        </p>

                                        <!-- E-mail -->
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ '@lang('admin::app.customers.customers.view.email')'.replace(':email', address.email ?? 'N/A') }}
                                        </p>

                                        <div class="flex items-center gap-2.5">
                                            <!-- Edit Address -->
                                            @include('admin::customers.customers.view.address.edit')

                                            <!-- Delete Address -->
                                            @if (bouncer()->hasPermission('customers.addresses.delete'))
                                                <p
                                                    class="text-red-600 cursor-pointer transition-all hover:underline"
                                                    @click="deleteAddress(address.id)"
                                                >
                                                    @lang('admin::app.customers.customers.view.delete')
                                                </p>
                                            @endif

                                            <!-- Set Default Address -->
                                            <template v-if="! address.default_address">
                                                <x-admin::button
                                                    button-type="button"
                                                    class="flex justify-center text-sm text-blue-600 cursor-pointer transition-all hover:underline"
                                                    :title="trans('admin::app.customers.customers.view.set-as-default')"
                                                    ::loading="isUpdating[index]"
                                                    ::disabled="isUpdating[index]"
                                                    @click="setAsDefault(address, index)"
                                                />
                                            </template>
                                        </div>

                                        <span
                                            v-if="index != customer?.addresses.length - 1"
                                            class="block w-full mb-4 mt-4 border-b dark:border-gray-800"
                                        ></span>
                                    </div>
                                </template>

                                <template v-else>
                                    <!-- Empty Address Container -->
                                    <div class="flex gap-5 items-center py-2.5">
                                        <img
                                            src="{{ bagisto_asset('images/settings/address.svg') }}"
                                            class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                                        />

                                        <div class="flex flex-col gap-1.5">
                                            <p class="text-base text-gray-400 font-semibold">
                                                @lang('admin::app.customers.customers.view.empty-title')
                                            </p>

                                            <p class="text-gray-400">
                                                @lang('admin::app.customers.customers.view.empty-description')
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </x-slot>
                        </x-admin::accordion>
                    </template>

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.after') !!}
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-customer-view', {
                template: '#v-customer-view-template',

                data() {
                    return {
                        customer: @json($customer),

                        isUpdating: {},
                    };
                },

                methods: {
                    deleteAddress(id) {
                        this.$emitter.emit('open-confirm-modal', {
                            message: '@lang('admin::app.customers.customers.view.address-delete-confirmation')',

                            agree: () => {
                                this.$axios.post(`{{ route('admin.customers.customers.addresses.delete', '') }}/${id}`)
                                    .then((response) => {
                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                        this.customer.addresses = this.customer.addresses.filter(address => address.id !== id);
                                    })
                                    .catch((error) => {});
                            },
                        });
                    },

                    setAsDefault(address, index) {
                        this.isUpdating[index] = true;

                        this.$axios.post(`{{ route('admin.customers.customers.addresses.set_default', '') }}/${this.customer.id}`, {
                            set_as_default: address.id,
                        })
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.customer.addresses = this.customer.addresses.map(address => ({
                                    ...address,
                                    default_address: address.id === response.data.data.id
                                        ? response.data.data.default_address
                                        : false,
                                }));

                                this.isUpdating[index] = false;
                            })
                            .catch((error) => this.isUpdating[index] = false);
                    },

                    updateCustomer(data) {
                        this.customer = {
                            ...this.customer,
                            ...data.customer,
                            group: {
                                ...data.group
                            },
                        };
                    },

                    addressCreated(address) {
                        if (address.default_address) {
                            this.customer.addresses.forEach(address => address.default_address = false);
                        }

                        this.customer.addresses.push(address);
                    },

                    addressUpdated(updatedAddress) {
                        if (updatedAddress.default_address) {
                            this.customer.addresses.forEach(address => address.default_address = false);
                        }

                        let toUpdate = this.customer.addresses.find(address => address.id == updatedAddress.id);

                        if (! toUpdate) {
                            return;
                        }

                        Object.assign(toUpdate, updatedAddress);
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
