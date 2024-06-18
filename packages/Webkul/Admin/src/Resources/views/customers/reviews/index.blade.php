<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.customers.reviews.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.customers.reviews.index.title')
        </p>
    </div>

    {!! view_render_event('bagisto.admin.customers.reviews.edit.before') !!}

    <v-review-edit-drawer></v-review-edit-drawer>

    {!! view_render_event('bagisto.admin.customers.groups.edit.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-review-edit-drawer-template"
        >

            {!! view_render_event('bagisto.admin.customers.reviews.list.before') !!}

            <x-admin::datagrid
                :src="route('admin.customers.customers.review.index')"
                :isMultiRow="true"
                ref="review_data"
            >
                @php
                    $hasPermission = bouncer()->hasPermission('customers.reviews.edit') || bouncer()->hasPermission('customers.reviews.delete');
                @endphp

                <template #header="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
                    </template>

                    <template v-else>
                        <div class="row grid grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] grid-rows-1 items-center border-b px-4 py-2.5 dark:border-gray-800">
                            <div
                                class="flex items-center gap-2.5"
                                v-for="(columnGroup, index) in [['customer_full_name', 'product_name', 'product_review_status'], ['rating', 'created_at', 'product_review_id'], ['title', 'comment']]"
                            >
                                @if ($hasPermission)
                                    <label
                                        class="flex w-max cursor-pointer select-none items-center gap-1"
                                        for="mass_action_select_all_records"
                                        v-if="! index"
                                    >
                                        <input
                                            type="checkbox"
                                            id="mass_action_select_all_records"
                                            class="peer hidden"
                                            name="mass_action_select_all_records"
                                            :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                            @change="selectAll"
                                        >

                                        <span
                                            class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                            :class="[
                                                applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:text-blue-600' : (
                                                    applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:text-blue-600' : ''
                                                ),
                                            ]"
                                        >
                                        </span>
                                    </label>
                                @endif

                                <!-- Product Name, Review Status -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    <span class="[&>*]:after:content-['_/_']">
                                        <template v-for="column in columnGroup">
                                            <span
                                                class="after:content-['/'] last:after:content-['']"
                                                :class="{
                                                    'font-medium text-gray-800 dark:text-white': applied.sort.column == column,
                                                    'cursor-pointer hover:text-gray-800 dark:hover:text-white': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                                }"
                                                @click="
                                                    available.columns.find(columnTemp => columnTemp.index === column)?.sortable ? sort(available.columns.find(columnTemp => columnTemp.index === column)): {}
                                                "
                                            >
                                                @{{ available.columns.find(columnTemp => columnTemp.index === column)?.label }}
                                            </span>
                                        </template>
                                    </span>

                                    <i
                                        class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                        :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                        v-if="columnGroup.includes(applied.sort.column)"
                                    ></i>
                                </p>
                            </div>
                        </div>
                    </template>
                </template>

                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
                    </template>

                    <template v-else>
                        <div
                            class="row grid grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                            v-for="record in available.records"
                        >
                            <!-- Name, Product, Description -->
                            <div class="flex gap-2.5">
                                @if ($hasPermission)
                                    <input
                                        type="checkbox"
                                        :id="`mass_action_select_record_${record.product_review_id}`"
                                        class="peer hidden"
                                        :name="`mass_action_select_record_${record.product_review_id}`"
                                        :value="record.product_review_id"
                                        v-model="applied.massActions.indices"
                                        @change="setCurrentSelectionMode"
                                    >

                                    <label
                                        class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"
                                        :for="`mass_action_select_record_${record.product_review_id}`"
                                    ></label>
                                @endif

                                <div class="flex flex-col gap-1.5">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @{{ record.customer_full_name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ record.product_name }}
                                    </p>

                                    <p v-html="record.product_review_status"></p>
                                </div>
                            </div>

                            <!-- Rating, Date, Id Section -->
                            <div class="flex flex-col gap-1.5">
                                <div class="flex">
                                    <x-admin::star-rating
                                        :is-editable="false"
                                        ::value="record.rating"
                                    />
                                </div>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ record.created_at }}
                                </p>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                >
                                    @{{ "@lang('admin::app.customers.reviews.index.datagrid.review-id')".replace(':review_id', record.product_review_id) }}
                                </p>
                            </div>

                            <!-- Title, Description -->
                            <div class="flex flex-col gap-1.5">
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @{{ record.title }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ record.comment }}
                                </p>
                            </div>

                            <div class="flex place-content-end items-center gap-1.5 self-center">
                                <!-- Review Delete Button -->
                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                    >
                                    </span>
                                </a>

                                <!-- View Button -->
                                <a
                                    v-if="record.actions.find(action => action.index === 'edit')"
                                    @click="edit(record.actions.find(action => action.index === 'edit')?.url)"
                                >
                                    <span class="icon-sort-right rtl:icon-sort-left cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"></span>
                                </a>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>

            {!! view_render_event('bagisto.admin.customers.reviews.list.after') !!}

            <!-- Drawer content -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form
                        @submit="handleSubmit($event, update)"
                        ref="reviewCreateForm"
                    >
                        <x-admin::drawer ref="review">
                            <!-- Drawer Header -->
                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="text-xl font-medium dark:text-white">
                                        @lang('admin::app.customers.reviews.index.edit.title')
                                    </p>

                                    <button class="primary-button ltr:mr-11 rtl:ml-11">
                                        @lang('admin::app.customers.reviews.index.edit.save-btn')
                                    </button>
                                </div>
                            </x-slot>

                            <!-- Drawer Content -->
                            <x-slot:content>
                                <div class="flex flex-col gap-4 px-1.5 py-2.5">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="">
                                            <!-- Customer Name -->
                                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.customers.reviews.index.edit.customer')
                                            </p>

                                            <p class="font-semibold text-gray-800 dark:text-white">
                                                @{{ review.name !== '' ? review.name : 'N/A' }}
                                            </p>
                                        </div>

                                        <div class="">
                                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.customers.reviews.index.edit.product')
                                            </p>

                                            <p class="font-semibold text-gray-800 dark:text-white">
                                                @{{ review.product.name }}
                                            </p>
                                        </div>

                                        <div class="">
                                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.customers.reviews.index.edit.id')
                                            </p>

                                            <p class="font-semibold text-gray-800 dark:text-white">
                                                @{{ review.id }}
                                            </p>
                                        </div>

                                        <div class="">
                                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.customers.reviews.index.edit.date')
                                            </p>

                                            <p class="font-semibold text-gray-800 dark:text-white">
                                                @{{ review.date }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="w-full">
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            rules="required"
                                            ::value="review.id"
                                        />

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.customers.reviews.index.edit.status')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="status"
                                                rules="required"
                                                ::value="review.status"
                                            >
                                                <option value="approved" >
                                                    @lang('admin::app.customers.reviews.index.edit.approved')
                                                </option>

                                                <option value="disapproved">
                                                    @lang('admin::app.customers.reviews.index.edit.disapproved')
                                                </option>

                                                <option value="pending">
                                                    @lang('admin::app.customers.reviews.index.edit.pending')
                                                </option>
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="status" />
                                        </x-admin::form.control-group>
                                    </div>

                                    <div class="w-full">
                                        <p class="font-semibold text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.reviews.index.edit.rating')
                                        </p>

                                        <div class="flex">
                                            <x-admin::star-rating
                                                :is-editable="false"
                                                ::value="review.rating"
                                            />
                                        </div>
                                    </div>

                                    <div class="w-full">
                                        <p class="block text-xs font-medium leading-6 text-gray-800 dark:text-white">
                                            @lang('admin::app.customers.reviews.index.edit.review-title')
                                        </p>

                                        <p class="font-semibold text-gray-800 dark:text-white">
                                            @{{ review.title }}
                                        </p>
                                    </div>

                                    <div class="w-full">
                                        <p class="block text-xs font-semibold leading-6 text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.reviews.index.edit.review-comment')
                                        </p>

                                        <p class="text-gray-800 dark:text-white">
                                            @{{ review.comment }}
                                        </p>
                                    </div>

                                    <div
                                        class="w-full"
                                        v-if="review.images.length"
                                    >
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.reviews.index.edit.images')
                                        </x-admin::form.control-group.label>

                                        <div class="flex flex-wrap gap-4">
                                            <div v-for="image in review.images" :key="image.id">
                                                <img
                                                    :src="image.url"
                                                    class="h-[60px] w-[60px] rounded"
                                                    v-if="image.type === 'image'"
                                                    alt="Image"
                                                />

                                                <video
                                                    v-else
                                                    class="h-[60px] w-[60px] rounded"
                                                    controls
                                                    autoplay
                                                >
                                                    <source
                                                        :src="image.url"
                                                        type="video/mp4"
                                                    >
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </x-slot>
                        </x-admin::drawer>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-review-edit-drawer', {
                template: '#v-review-edit-drawer-template',

                data() {
                    return {
                        review: {},
                    }
                },

                methods: {
                    edit(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.$refs.review.open(),

                                this.review = response.data.data
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });

                    },

                    update(params) {
                        let formData = new FormData(this.$refs.reviewCreateForm);

                        formData.append('_method', 'put');

                        this.$axios.post(`{{ route('admin.customers.customers.review.update', '') }}/${params.id}`, formData)
                            .then((response) => {
                                this.$refs.review.close();

                                this.$refs.review_data.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data,message });
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
