<x-admin::layouts>
    <x-slot:title>
        Theme Customization
    </x-slot:title>
   
    <div class="flex justify-between items-center">
        <p class="text-[20px] px-[12px] py-[6px] text-gray-800 font-bold">
            Theme Customization
        </p>
    </div>
    
    <v-theme-customizer></v-theme-customizer>

    @pushOnce('scripts')
        {{-- Customizer parent --}}
        <script type="text/x-template" id="v-theme-customizer-template">
            <div>
                <div class="flex gap-[10px]">
                    <div 
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2 box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-slider-theme'}"
                        @click="switchComponent('v-slider-theme')"
                    >
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-slider-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_slider.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_slider.png') }}"
                            >
                
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold"> Slider </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-product-theme'}"
                        @click="switchComponent('v-product-theme')"
                    >   
                        <div class="flex flex-col items-center max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-product-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/product_carousel.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_product_carousel.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold"> Product Carousel</p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-category-theme'}"
                        @click="switchComponent('v-category-theme')"
                    >   
                        <div class="flex flex-col items-center max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-category-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_category.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_category.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold"> Category Carousel</p>
                        </div>
                    </div>
                

                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-static-theme'}"
                        @click="switchComponent('v-static-theme')"
                    >   
                        <div class="flex flex-col items-center max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-static-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_static_content.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_static_content.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold"> Static Content </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-footer-link-theme'}"
                        @click="switchComponent('v-footer-link-theme')"
                    >   
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-footer-link-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_footer_link.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_footer_link.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold">Footer Link</p>
                        </div>
                    </div>
                </div>

                <KeepAlive>
                    <component :is="componentName"></component>
                </KeepAlive>
            </div>
        </script>

        <script type="module">
            app.component('v-theme-customizer', {
                template: '#v-theme-customizer-template',

                data() {
                    return {
                        componentName: 'v-slider-theme',
                    }
                },

                methods: {
                    switchComponent(component) {
                        this.componentName = component;
                    }
                },
            })
        </script>

        {{-- Slider theme --}}
        <script type="text/x-template" id="v-slider-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">Slider</p>
                                <p class="text-[12px] text-gray-500 font-medium">
                                    Slider related theme customization
                                </p>
                            </div>
            
                            <div class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                Add Slider
                            </div>
                        </div>
            
                        <div    
                            class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                            v-if="! sliders.length"
                        >
                            <img class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]"
                                src="http://192.168.15.62/bagisto-admin-panel/resources/images/placeholder/add-product-to-store.png"
                                alt="add-product-to-store">
            
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">Add Product Variant</p>
                                <p class="text-gray-400">To create various combination of product on a go.</p>
                            </div>
            
                            <div class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                Add Variant
                            </div>
                        </div>

                        <div class="grid">
                            <!-- Single product column -->
                            <div    
                                class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300"
                                v-for="image in sliders"
                            >
                                <div class="flex items-center gap-[10px]">
                                    <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                        <img class="w-[20px]" :src="image">
                                        <p class="text-[6px] text-gray-400 font-semibold">Product Image</p>
                                    </div>
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-[16x] text-gray-800 font-semibold">Image carousel</p>
                                    </div>
                                </div>
                                <div class="grid gap-[4px] place-content-start">
                                    <div class="flex gap-[10px]">
                                        <p class="text-red-600 cursor-pointer">Delete</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <div class="bg-white rounded-[4px] box-shadow">
                        <div class="flex items-center justify-between p-[6px]">
                            <p class="text-gray-600 text-[16px] p-[10px] font-semibold">General</p>
                            <span
                                class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                        </div>
            
                        <!-- Price -->
                        <div class="p-[16px]">
                            <div class="mb-[10px]">
                                <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Price*
                                </label>
                                <input
                                    class="text-[20px] text-gray-600 font-bold appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                    type="text" placeholder="$ 10.00">
                            </div>
                            <div class="">
                                <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Cost
                                    Price* </label>
                                <input
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                    type="text" placeholder="8.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-slider-theme', {
                template: '#v-slider-theme-template',

                data() {
                    return {
                        sliders: [],
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                       
                    }
                }
            });
        </script>

        {{-- Product Theme --}}
        <script type="text/x-template" id="v-product-theme-template">
            <div>
                <x-admin::form 
                    :action="route('admin.theme.store')"
                    class="flex gap-[10px] mt-[14px] max-xl:flex-wrap"
                >
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white rounded box-shadow">
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">Product Carousel</p>

                                    <p class="text-[12px] text-gray-500 font-medium">
                                        Showcase products elegantly with a dynamic and responsive product carousel.
                                    </p>
                                </div>
                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        @click="$refs.productFilterModal.toggle()"
                                    >
                                        Add Filter
                                    </div>

                                    <button
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        type="submit"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Title
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="options[title]"
                                    rules="required"
                                    :label="trans('Title')"
                                    :placeholder="trans('Title')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="options[title]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Sort
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="options[sort]"
                                    rules="required"
                                    :label="trans('Sort')"
                                    :placeholder="trans('Sort')"
                                >
                                    <option value="desc">Desc</option>
                                    <option value="asc">Asc</option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="options[sort]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Limit
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="options[limit]"
                                    rules="required"
                                    :label="trans('Limit')"
                                    :placeholder="trans('Limit')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="options[limit]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <div v-if="options.filters.length">
                                <div
                                    class="flex gap-[10px] justify-between p-[16px] !pl-0 border-b-[1px] border-slate-300"
                                    v-for="filter in options.filters"
                                >
                                    <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 

                                    <!-- Information -->
                                    <div class="flex gap-[10px]">
                                        <!-- Details -->
                                        <div class="grid gap-[6px] place-content-start">
                                            <p class="text-[16x] text-gray-800 font-semibold">
                                                Key: @{{ filter.key }}
                                            </p>

                                            <p class="text-[16x] text-gray-800 font-semibold">
                                                Value: @{{ filter.value }}
                                            </p>
                                        </div>
                                    </div>
    
                                    <!-- Actions -->
                                    <div class="flex gap-[4px] place-content-start text-right">
                                        <p
                                            class="text-red-600 cursor-pointer hover:underline"
                                            @click="remove(filter)"
                                        >
                                            Delete
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div    
                               class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                               v-else
                            >
                                <img
                                    class="w-[120px] h-[120px] p-2 border border-dashed border-gray-300 rounded-[4px]"
                                    src="{{ bagisto_asset('images/empty_theme.png') }}"
                                    alt="add-product-to-store"
                                >
                
                                <div class="flex flex-col items-center">
                                    <p class="text-[16px] text-gray-400 font-semibold">Add Product Carousel</p>
                                    <p class="text-gray-400">Product Carousel related theme customization.</p>
                                </div>
                
                                <div 
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.productFilterModal.toggle()"
                                >
                                    Add Filter
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- General -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    General
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="type"
                                        value="product_carousel"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Name
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('Name')"
                                        :placeholder="trans('Name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Sort Order
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="sort_order"
                                        rules="required"
                                        :label="trans('Sort Order')"
                                        :placeholder="trans('Sort Order')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="sort_order"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        Status
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        :value="1"
                                        :label="trans('Status')"
                                        :placeholder="trans('Status')"
                                        :checked="true"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </x-slot:content>
                        </x-admin::accordion>
                    </div>
                </x-admin::form>

                <!-- For Fitler Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="categoryCarouselform"
                >
                    <form @submit="handleSubmit($event, addFilter)">
                        <x-admin::modal ref="productFilterModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                   Add Filter
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Key
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="key"
                                            :label="trans('Key')"
                                            rules="required"
                                            :placeholder="trans('Key')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="key"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Value -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Value
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="value"
                                            :label="trans('Value')"
                                            rules="required"
                                            :placeholder="trans('Value')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="value"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>
        
                            <x-slot:footer>
                                <div class="flex gap-x-[10px] items-center">
                                    <!-- Save Button -->
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        Save 
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-product-theme', {
                template: '#v-product-theme-template',

                data() {
                    return {
                        options: {
                            filters: [],
                        },
                    };
                },

                methods: {
                    addFilter(params) {
                        this.options.filters.push(params);

                        this.$refs.productFilterModal.toggle();
                    },

                    remove(filter) {
                        let index = this.options.filters.indexOf(filter);

                        this.options.filters.splice(index, 1);
                    }
                }
            });
        </script>

        {{-- Category Theme --}}
        <script type="text/x-template" id="v-category-theme-template">
            <div>
                <x-admin::form 
                    :action="route('admin.theme.store')"
                    class="flex gap-[10px] mt-[14px] max-xl:flex-wrap"
                >
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white rounded box-shadow">
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">Category Carousel</p>
    
                                    <p class="text-[12px] text-gray-500 font-medium">
                                        Display dynamic categories attractively using a responsive category carousel.
                                    </p>
                                </div>
                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        @click="$refs.categoryFilterModal.toggle()"
                                    >
                                        Add Filter
                                    </div>
    
                                    <button
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        type="submit"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
    
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Title
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="options[title]"
                                    rules="required"
                                    :label="trans('Title')"
                                    :placeholder="trans('Title')"
                                >
                                </x-admin::form.control-group.control>
    
                                <x-admin::form.control-group.error
                                    control-name="options[title]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
    
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Sort
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="select"
                                    name="options[sort]"
                                    rules="required"
                                    :label="trans('Sort')"
                                    :placeholder="trans('Sort')"
                                >
                                    <option value="desc">Desc</option>
                                    <option value="asc">Asc</option>
                                </x-admin::form.control-group.control>
    
                                <x-admin::form.control-group.error
                                    control-name="options[sort]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
    
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    Limit
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="options[limit]"
                                    rules="required"
                                    :label="trans('Limit')"
                                    :placeholder="trans('Limit')"
                                >
                                </x-admin::form.control-group.control>
    
                                <x-admin::form.control-group.error
                                    control-name="options[limit]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
    
                            <div v-if="options.filters.length">
                                <div
                                    class="flex gap-[10px] justify-between p-[16px] !pl-0 border-b-[1px] border-slate-300"
                                    v-for="filter in options.filters"
                                >
                                    <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 
    
                                    <!-- Information -->
                                    <div class="flex gap-[10px]">
                                        <!-- Details -->
                                        <div class="grid gap-[6px] place-content-start">
                                            <p class="text-[16x] text-gray-800 font-semibold">
                                                Key: @{{ filter.key }}
                                            </p>
    
                                            <p class="text-[16x] text-gray-800 font-semibold">
                                                Value: @{{ filter.value }}
                                            </p>
                                        </div>
                                    </div>
    
                                    <!-- Actions -->
                                    <div class="flex gap-[4px] place-content-start text-right">
                                        <p
                                            class="text-red-600 cursor-pointer hover:underline"
                                            @click="remove(filter)"
                                        >
                                            Delete
                                        </p>
                                    </div>
                                </div>
                            </div>
    
                            <div    
                               class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                               v-else
                            >
                                <img
                                    class="w-[120px] h-[120px] p-2 border border-dashed border-gray-300 rounded-[4px]"
                                    src="{{ bagisto_asset('images/empty_theme.png') }}"
                                    alt="add-category-to-store"
                                >
                
                                <div class="flex flex-col items-center">
                                    <p class="text-[16px] text-gray-400 font-semibold">Add Category Carousel</p>
                                    <p class="text-gray-400">Category Carousel related theme customization.</p>
                                </div>
                
                                <div 
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.categoryFilterModal.toggle()"
                                >
                                    Add Filter
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- General -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    General
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="type"
                                        value="category_carousel"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Name
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('Name')"
                                        :placeholder="trans('Name')"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Sort Order
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="sort_order"
                                        rules="required"
                                        :label="trans('Sort Order')"
                                        :placeholder="trans('Sort Order')"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="sort_order"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        Status
                                    </x-admin::form.control-group.label>
    
                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        :value="1"
                                        :label="trans('Status')"
                                        :placeholder="trans('Status')"
                                        :checked="true"
                                    >
                                    </x-admin::form.control-group.control>
    
                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </x-slot:content>
                        </x-admin::accordion>
                    </div>
                </x-admin::form>
    
                <!-- For Fitler Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="categoryCarouselform"
                >
                    <form @submit="handleSubmit($event, addFilter)">
                        <x-admin::modal ref="categoryFilterModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                   Add Filter
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Key
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="key"
                                            :label="trans('Key')"
                                            rules="required"
                                            :placeholder="trans('Key')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="key"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
    
                                    <!-- Value -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Value
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="value"
                                            :label="trans('Value')"
                                            rules="required"
                                            :placeholder="trans('Value')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="value"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>
        
                            <x-slot:footer>
                                <div class="flex gap-x-[10px] items-center">
                                    <!-- Save Button -->
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        Save 
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>
    
        <script type="module">
            app.component('v-category-theme', {
                template: '#v-category-theme-template',
    
                data() {
                    return {
                        options: {
                            filters: [],
                        },
                    };
                },
    
                methods: {
                    addFilter(params) {
                        this.options.filters.push(params);
    
                        this.$refs.categoryFilterModal.toggle();
                    },
    
                    remove(filter) {
                        let index = this.options.filters.indexOf(filter);
    
                        this.options.filters.splice(index, 1);
                    }
                }
            });
        </script>

        {{-- Static Theme --}}
        <script type="text/x-template" id="v-static-theme-template">
            <div>
                <x-admin::form 
                    :action="route('admin.theme.store')"
                    class="flex gap-[10px] mt-[14px] max-xl:flex-wrap"
                >
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white rounded box-shadow">
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">Static Content</p>
    
                                    <p class="text-[12px] text-gray-500 font-medium">
                                        Improve engagement with concise, informative static content for your audience.
                                    </p>
                                </div>
                
                                <div class="flex gap-[10px]">
                                    <button
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        type="submit"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
    
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label for="status">
                                    HTML
                                </x-admin::form.control-group.label>

                                <div ref="html"></div>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="options[html]"
                                    v-model="options.html"
                                >
                                </x-admin::form.control-group.control>

                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    CSS
                                </x-admin::form.control-group.label>

                                <div ref="css"></div>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="options[css]"
                                    v-model="options.css"
                                >
                                </x-admin::form.control-group.control>

                            </x-admin::form.control-group>
                        </div>
                    </div>
    
                    <!-- General -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    General
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="type"
                                        value="static_content"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Name
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('Name')"
                                        :placeholder="trans('Name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        Sort Order
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="sort_order"
                                        rules="required"
                                        :label="trans('Sort Order')"
                                        :placeholder="trans('Sort Order')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="sort_order"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        Status
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        :value="1"
                                        :label="trans('Status')"
                                        :placeholder="trans('Status')"
                                        :checked="true"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </x-slot:content>
                        </x-admin::accordion>
                    </div>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-static-theme', {
                template: '#v-static-theme-template',

                data() {
                    return {
                        options:{
                            html: '',
                            css: '',
                        }
                    };
                },

                created() {
                    this.initHtmlEditor();

                    this.initCssEditor();
                },

                methods: {
                    initHtmlEditor() {
                        setTimeout(() => {
                            this._html = new CodeMirror(this.$refs.html, {
                                lineNumbers: true,
                                tabSize: 2,
                                value: this.options.html,
                                mode: 'html',
                                theme: 'monokai'
                            });

                            this._html.on('changes', () => {
                                this.options.html = this._html.getValue();
                            });
                        }, 0);
                    },

                    initCssEditor() {
                        setTimeout(() => {
                            this._css = new CodeMirror(this.$refs.css, {
                                lineNumbers: true,
                                tabSize: 2,
                                value: this.options.css,
                                mode: 'css',
                                theme: 'monokai'
                            });

                            this._css.on('changes', () => {
                                this.options.css = this._css.getValue();
                            });
                        }, 0);
                    },
                },
            });
        </script>

        {{-- Footer Link --}}
        <script type="text/x-template" id="v-footer-link-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">Content</p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    Static related theme customization
                                </p>
                            </div>
            
                            <div class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                Add Static
                            </div>
                        </div>

                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                            <img class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]"
                                src="http://192.168.15.62/bagisto-admin-panel/resources/images/placeholder/add-product-to-store.png"
                                alt="add-product-to-store">
            
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">Add Product Variant</p>
                                <p class="text-gray-400">To create various combination of product on a go.</p>
                            </div>
            
                            <div
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                Add Variant
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <div class="bg-white rounded-[4px] box-shadow">
                        <div class="flex items-center justify-between p-[6px]">
                            <p class="text-gray-600 text-[16px] p-[10px] font-semibold">General</p>
                            <span
                                class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                        </div>
            
                        <!-- Price -->
                        <div class="p-[16px]">
                            <div class="mb-[10px]">
                                <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Name*
                                </label>
                                <input
                                    class="text-[20px] text-gray-600 font-bold appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                    type="text" placeholder="$ 10.00">
                            </div>
                            <div class="">
                                <label class="block text-[12px]  text-gray-800 font-medium leading-[24px]" for="username"> Cost
                                    Price* </label>
                                <input
                                    class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400"
                                    type="text" placeholder="8.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-footer-link-theme', {
                template: '#v-footer-link-theme-template',

                data() {
                    return {
                        footerLinks: {},
                    };
                },
            });
        </script>

        {{-- Code mirror script CDN --}}
        <script
            type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"
        >
        </script>
    @endPushOnce

    @pushOnce('styles')
        {{-- Code mirror style cdn --}}
        <link 
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css"
        >
        </link>
    @endPushOnce
</x-admin::layouts>