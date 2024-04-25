<x-admin::layouts>
    <v-customer-view>
        <!-- Shimmer Effect -->
        <x-admin::shimmer.customers.view />
    </v-customer-view>

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
                <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                    <div class="flex items-center gap-2.5">
                        <template
                            v-if="! customer"
                            class="flex gap-5"
                        >
                            <p class="shimmer w-32 p-2.5"></p>

                            <p class="shimmer w-14 p-2.5"></p>
                        </template>

                        <template v-else>
                            <h1
                                v-if="customer"
                                class="text-xl font-bold leading-6 text-gray-800 dark:text-white"
                                v-text="`${customer.first_name} ${customer.last_name}`"
                            ></h1>

                            <span
                                v-if="customer.status"
                                class="label-active mx-1.5 text-sm"
                            >
                                @lang('admin::app.customers.customers.view.active')
                            </span>

                            <span
                                v-else
                                class="label-canceled mx-1.5 text-sm"
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
                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    >
                        @lang('admin::app.customers.customers.view.back-btn')
                    </a>
                </div>
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.filters.before') !!}

            <!-- Filters -->
            <div class="mt-7 flex flex-wrap items-center gap-x-1 gap-y-2">
                <!-- Create Order button -->
                @if (bouncer()->hasPermission('sales.orders.create'))
                    <div
                        class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
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
                    class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                    href="{{ route('admin.customers.customers.login_as_customer', $customer->id) }}"
                    target="_blank"
                >
                    <span class="icon-exit text-2xl"></span>

                    @lang('admin::app.customers.customers.view.login-as-customer')
                </a>
                
                <!-- Account Delete button -->
                @if (bouncer()->hasPermission('customers.customers.delete'))
                    <div
                        class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
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
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                <!-- Left Component -->
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
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
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.before') !!}

                    <!-- Information -->
                    {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.after') !!}

                    <template v-if="! customer">
                        <x-admin::shimmer.accordion class="h-[271px] w-[360px]"/>
                    </template>

                    <template v-else>
                        <x-admin::accordion>
                            <x-slot:header>
                                <div class="flex w-full">
                                    <p class="w-full p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.customers.customers.view.customer')
                                    </p>

                                    <!--Customer Edit Component -->
                                    @include('admin::customers.customers.view.edit')
                                </div>
                            </x-slot:header>

                            <x-slot:content>
                                <div class="grid gap-y-2.5">
                                    <p
                                        class="font-semibold text-gray-800 dark:text-white"
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
                        <x-admin::shimmer.accordion class="h-[271px] w-[360px]"/>
                    </template>

                    <template v-else>
                        <!-- Addresses listing-->
                        <x-admin::accordion>
                            <x-slot:header>
                                <div class="flex w-full">
                                    <!-- Address Title -->
                                    <p class="w-full p-2.5 text-base font-semibold text-gray-800 dark:text-white">
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

                                        <p class="font-semibold text-gray-800 dark:text-white">
                                            @{{ `${address.first_name} ${address.last_name}` }}

                                            <template v-if="address.company_name">
                                                (@{{ address.company_name }})
                                            </template>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            <template v-if="address.address">
                                                @{{ address.address.split('\n').join(', ') }},
                                            </template>

                                            @{{ address.city }},
                                            @{{ address.state }},
                                            @{{ address.country }},
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
                                                    class="cursor-pointer text-red-600 transition-all hover:underline"
                                                    @click="deleteAddress(address.id)"
                                                >
                                                    @lang('admin::app.customers.customers.view.delete')
                                                </p>
                                            @endif

                                            <!-- Set Default Address -->
                                            <template v-if="! address.default_address">
                                                <x-admin::button
                                                    button-type="button"
                                                    class="flex cursor-pointer justify-center text-sm text-blue-600 transition-all hover:underline"
                                                    :title="trans('admin::app.customers.customers.view.set-as-default')"
                                                    ::loading="isUpdating[index]"
                                                    ::disabled="isUpdating[index]"
                                                    @click="setAsDefault(address, index)"
                                                />
                                            </template>
                                        </div>

                                        <span
                                            v-if="index != customer?.addresses.length - 1"
                                            class="mb-4 mt-4 block w-full border-b dark:border-gray-800"
                                        ></span>
                                    </div>
                                </template>

                                <template v-else>
                                    <!-- Empty Address Container -->
                                    <div class="flex items-center gap-5 py-2.5">
                                        <img
                                            src="{{ bagisto_asset('images/settings/address.svg') }}"
                                            class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                                        />

                                        <div class="flex flex-col gap-1.5">
                                            <p class="text-base font-semibold text-gray-400">
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

                        this.customer.addresses.push({
                            ...address,
                            address: address.address.join('\n'),
                        });
                    },

                    addressUpdated(updatedAddress) {
                        if (updatedAddress.default_address) {
                            this.customer.addresses.forEach(address => address.default_address = false);
                        }

                        this.customer.addresses =this.customer.addresses.map(address => {
                            if (address.id === updatedAddress.id) {
                                return {
                                    ...updatedAddress,
                                    address: updatedAddress.address.join('\n'),
                                };
                            }

                            return address;
                        });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>