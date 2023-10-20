<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.customers.reviews.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('admin::app.customers.reviews.index.title')
        </p>
    </div>

    {!! view_render_event('admin.customers.reviews.edit.before') !!}

    <v-review-edit-drawer></v-review-edit-drawer>

    {!! view_render_event('admin.customers.groups.edit.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-review-edit-drawer-template">

            {!! view_render_event('admin.customers.reviews.list.before') !!}

            <x-admin::datagrid
                src="{{ route('admin.customers.customers.review.index') }}"
                :isMultiRow="true"
                ref="review_data"
            >
                @php 
                    $hasPermission = bouncer()->hasPermission('customers.reviews.mass-update') || bouncer()->hasPermission('customers.reviews.mass-delete');
                @endphp

                <!-- Datagrid Header -->
                <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading }">
                    <template v-if="! isLoading">
                        <div class="row grid grid-rows-1 grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                            <div
                                class="flex gap-[10px] items-center"
                                v-for="(columnGroup, index) in [['customer_full_name', 'product_name', 'product_review_status'], ['rating', 'created_at', 'product_review_id'], ['title', 'comment']]"
                            >
                                @if ($hasPermission)
                                    <label
                                        class="flex gap-[4px] w-max items-center cursor-pointer select-none"
                                        for="mass_action_select_all_records"
                                        v-if="! index"
                                    >
                                        <input 
                                            type="checkbox" 
                                            name="mass_action_select_all_records"
                                            id="mass_action_select_all_records"
                                            class="hidden peer"
                                            :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                            @change="selectAllRecords"
                                        >
                            
                                        <span
                                            class="icon-uncheckbox cursor-pointer rounded-[6px] text-[24px]"
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
                                                    'text-gray-800 dark:text-white font-medium': applied.sort.column == column,
                                                    'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                                }"
                                                @click="
                                                    columns.find(columnTemp => columnTemp.index === column)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === column)): {}
                                                "
                                            >
                                                @{{ columns.find(columnTemp => columnTemp.index === column)?.label }}
                                            </span>
                                        </template>
                                    </span>

                                    <i
                                        class="ltr:ml-[5px] rtl:mr-[5px] text-[16px] text-gray-800 dark:text-white align-text-bottom"
                                        :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                        v-if="columnGroup.includes(applied.sort.column)"
                                    ></i>
                                </p>
                            </div>
                        </div>
                    </template>               

                    <!-- Datagrid Head Shimmer -->
                    <template v-else>
                        <x-admin::shimmer.datagrid.table.head :isMultiRow="true"></x-admin::shimmer.datagrid.table.head>
                    </template>
                </template>

                <template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading, performAction }">
                    <template v-if="! isLoading">
                        <div
                            class="row grid grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                            v-for="record in records"
                        >
                            <!-- Name, Product, Description -->
                            <div class="flex gap-[10px]">
                                @if ($hasPermission)
                                    <input 
                                        type="checkbox" 
                                        :name="`mass_action_select_record_${record.product_review_id}`"
                                        :id="`mass_action_select_record_${record.product_review_id}`"
                                        :value="record.product_review_id"
                                        class="hidden peer"
                                        v-model="applied.massActions.indices"
                                        @change="setCurrentSelectionMode"
                                    >
                        
                                    <label 
                                        class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                                        :for="`mass_action_select_record_${record.product_review_id}`"
                                    ></label>
                                @endif

                                <div class="flex flex-col gap-[6px]">
                                    <p
                                        class="text-[16px] text-gray-800 dark:text-white font-semibold"
                                        v-text="record.customer_full_name"
                                    >
                                    </p>
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="record.product_name"
                                    >
                                    </p>

                                    <p v-html="record.product_review_status"></p>
                                </div>
                            </div>

                            <!-- Rating, Date, Id Section -->
                            <div class="flex flex-col gap-[6px]">
                                <div class="flex">
                                    <x-admin::star-rating 
                                        :is-editable="false"
                                        ::value="record.rating"
                                    >
                                    </x-admin::star-rating>
                                </div>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-text="record.created_at"
                                >
                                </p>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                >
                                    @{{ "@lang('admin::app.customers.reviews.index.datagrid.review-id')".replace(':review_id', record.product_review_id) }}
                                </p>
                            </div>

                            <!-- Title, Description -->
                            <div class="flex flex-col gap-[6px]">
                                <p
                                    class="text-[16px] text-gray-800 dark:text-white font-semibold"
                                    v-text="record.title"
                                >
                                </p>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-text="record.comment"
                                >
                                </p>
                            </div>

                            <div class="flex gap-[5px] place-content-end items-center self-center">
                                <!-- Review Delete Button -->
                                <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                    <span
                                        :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                        class="text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                    >
                                    </span>
                                </a>

                                <!-- View Button -->
                                <a
                                    v-if="record.actions.find(action => action.title === 'Edit')"
                                    @click="edit(record.actions.find(action => action.title === 'Edit')?.url)"
                                >
                                    <span class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800">
                                    </span>
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- Datagrid Body Shimmer -->
                    <template v-else>
                        <x-admin::shimmer.datagrid.table.body :isMultiRow="true"></x-admin::shimmer.datagrid.table.body>
                    </template>
                </template>
            </x-admin::datagrid>

            {!! view_render_event('admin.customers.reviews.list.after') !!}

            <!-- Drawer content -->
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
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
                                <div class="flex justify-between items-center">
                                    <p class="text-[20px] font-medium dark:text-white">
                                        @lang('admin::app.customers.reviews.index.edit.title')
                                    </p>
                
                                    <button class="mr-[45px] primary-button">
                                        @lang('admin::app.customers.reviews.index.edit.save-btn')
                                    </button>
                                </div>
                            </x-slot:header>

                            <!-- Drawer Content -->
                            <x-slot:content>
                                <div class="flex flex-col gap-[16px] px-[5px] py-[10px]">
                                    <div class="grid grid-cols-2 gap-[16px]">
                                        <div class="">
                                            <!-- Customer Name -->
                                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                                @lang('admin::app.customers.reviews.index.edit.customer')
                                            </p>

                                            <p 
                                                class="text-gray-800 font-semibold dark:text-white" 
                                                v-text="review.name !== '' ? review.name : 'N/A'"
                                            >
                                            </p>
                                        </div>

                                        <div class="">
                                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                                @lang('admin::app.customers.reviews.index.edit.product')
                                            </p>

                                            <p 
                                                class="text-gray-800 font-semibold dark:text-white" 
                                                v-text="review.product.name"
                                            >
                                            </p>
                                        </div>
                
                                        <div class="">
                                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                                @lang('admin::app.customers.reviews.index.edit.id')
                                            </p>

                                            <p 
                                                class="text-gray-800 font-semibold dark:text-white" 
                                                v-text="review.id"
                                            >
                                            </p>
                                        </div>
                
                                        <div class="">
                                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                                @lang('admin::app.customers.reviews.index.edit.date')
                                            </p>

                                            <p 
                                                class="text-gray-800 font-semibold dark:text-white" 
                                                v-text="review.date"
                                            >
                                            </p>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            ::value="review.id"
                                            rules="required"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.customers.reviews.index.edit.status')
                                            </x-admin::form.control-group.label>
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="status"
                                                ::value="review.status"
                                                rules="required"
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
                
                                            <x-admin::form.control-group.error
                                                control-name="status"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                
                                    <div class="w-full ">
                                        <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                            @lang('admin::app.customers.reviews.index.edit.rating') 
                                        </p>

                                        <div class="flex">
                                            <x-admin::star-rating 
                                                :is-editable="false"
                                                ::value="review.rating"
                                            >
                                            </x-admin::star-rating>
                                        </div>
                                    </div>
                
                                    <div class="w-full">
                                        <p class="block text-[12px] text-gray-800 dark:text-white font-medium leading-[24px]">
                                            @lang('admin::app.customers.reviews.index.edit.review-title') 
                                        </p>

                                        <p 
                                            class="text-gray-800 font-semibold dark:text-white" 
                                            v-text="review.title"
                                        >
                                        </p>
                                    </div>
                
                                    <div class="w-full">
                                        <p class="block text-[12px] text-gray-600 dark:text-gray-300 font-semibold leading-[24px]">
                                            @lang('admin::app.customers.reviews.index.edit.review-comment')     
                                        </p>

                                        <p 
                                            class="text-gray-800 dark:text-white" 
                                            v-text="review.comment"
                                        >
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
                                                    v-if="image.type === 'image'"
                                                    class="h-[60px] w-[60px] rounded-[4px]"
                                                    :src="image.url"
                                                    alt="Image"
                                                />

                                                <video
                                                    v-else
                                                    class="h-[60px] w-[60px] rounded-[4px]"
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
                            </x-slot:content>
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

                                this.$emitter.emit('add-flash', { type: 'success', message: 'Review Updated Successfully' });
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