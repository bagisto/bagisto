<x-admin::layouts>
    @pushOnce('styles')
        <link 
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css"
        >
        </link>
    @endPushOnce

    @pushOnce('scripts')
        <script
            type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"
        >
        </script>
    @endPushOnce

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
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
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
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold"> Product Carousel</p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-static-theme'}"
                        @click="switchComponent('v-static-theme')"
                    >   
                        <div class="flex flex-col items-center max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
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
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
                            >
                
                            <p class="mb-[5px] text-[16px] top-[134px] text-center text-gray-800 font-semibold">Footer Link</p>
                        </div>
                    </div>
                </div>

                <component :is="componentName"></component>
            </div>
        </script>

        <script type="module">
            app.component('v-theme-customizer', {
                template: '#v-theme-customizer-template',

                data() {
                    return {
                        componentName: 'v-product-theme',
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
                        this.$axios.get('{{ route('admin.theme.themes') }}', {
                                params: {
                                    type: 'image_carousel'
                                }
                            })
                            .then((response) => {
                                this.sliders = response.data.data.options.images;

                                console.log(response.data.data);
                            })
                            .catch((error) => {

                            })
                    }
                }
            });
        </script>

        {{-- Product Theme --}}
        <script type="text/x-template" id="v-product-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">Product Carousels</p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    Product Carousel related theme customization
                                </p>
                            </div>
            
                            <div
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.productCarouselModal.toggle()"
                            >
                                Add Product
                            </div>
                        </div>

                        <div v-if="productCarousels.length">
                            <div
                                class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300"
                                v-for="productCarousel in productCarousels"
                            >
                                <!-- Information -->
                                <div class="flex gap-[10px]">
                                    <!-- Image -->
                                    <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">

                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">Product Image</p>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-[16x] text-gray-800 font-semibold">
                                            @{{ productCarousel.name }}
                                        </p>

                                        <p 
                                            v-if="productCarousel.status"
                                            class="label-processing text-white"
                                        >
                                            Active
                                        </p>

                                        <p 
                                            v-else
                                            class="label-pending text-white"
                                        >
                                            Inactive
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-[4px] place-content-start text-right">
                                    <p
                                        class="text-blue-600 cursor-pointer"
                                        @click="edit(productCarousel);"
                                    >
                                        Edit
                                    </p>

                                    <p
                                        class="text-red-600 cursor-pointer"
                                        @click="remove(productCarousel)"
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
                            <img class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]"
                                src="http://192.168.15.62/bagisto-admin-panel/resources/images/placeholder/add-product-to-store.png"
                                alt="add-product-to-store">
            
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">Add Static Content</p>
                                <p class="text-gray-400">Insert concise static content to enhance webpage engagement and readability..</p>
                            </div>
            
                            <div
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.openStatisContentDrawer.open();this.initHtmlEditor();this.initCssEditor();"
                            >
                                Add Static Content
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Model Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="productCarouselform"
                >
                    <form @submit="handleSubmit($event, storeOrUpdate)">
                        <x-admin::modal ref="productCarouselModal">
                            <x-slot:header>
                                <p      
                                    class="text-[18px] text-gray-800 font-bold"
                                    v-if="id"
                                >
                                    Update Product Carousel
                                </p>

                                <p      
                                    class="text-[18px] text-gray-800 font-bold"
                                    v-else
                                >
                                    Create Product Carousel
                                </p>
                            </x-slot:header>

                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="id"
                                    >
                                    </x-admin::form.control-group.control>

                                    {{-- Name --}}
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Name
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            :label="trans('Name')"
                                            rules="required"
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
                                            Sort By
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="options.sort"
                                            :label="trans('Sort')"
                                            :placeholder="trans('Sort')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="options.sort"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Limit
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="options.limit"
                                            rules="numeric"
                                            :label="trans('limit')"
                                            :placeholder="trans('limit')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="options.limit"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Status
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="switch"
                                            name="options.status"
                                            :value="1"
                                            label="Status"
                                            :checked="(boolean) true"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="options.status"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Theme Sort Order
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="sort_order"
                                            label="Sort Order"
                                            rules="numeric|required"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="sort_order"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            Theme Status
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="switch"
                                            name="theme_status"
                                            :value="1"
                                            label="Theme Status"
                                            :checked="(boolean) true"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="theme_status"
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
                        productCarousels: {},

                        id: null,
                    };
                },

                created() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get('{{ route('admin.theme.themes') }}', {
                                params: {
                                    type: 'product_carousel',
                                }
                            })
                            .then((response) => {
                                this.productCarousels = response.data.data;
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    },

                    storeOrUpdate(params) {
                        if (params.id) {
                            this.$axios.post(`{{ route('admin.theme.update_product_carousel', '') }}/${params.id}`, params)
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    this.get();

                                    this.$refs.productCarouselModal.toggle();
                                })
                                .catch((error) => {
                                    console.log(error);
                                })
                        } else {
                            this.$axios.post('{{ route('admin.theme.store_product_carousel') }}', params)
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    this.get();

                                    this.$refs.productCarouselModal.toggle();
                                })
                                .catch((error) => {
                                    console.log(error);
                                })
                        }
                    },

                    edit(productCarousel) {
                        this.id = productCarousel.id;

                        this.$refs.productCarouselform.setValues(productCarousel);

                        this.$refs.productCarouselModal.toggle();
                    },

                    remove(productCarousel) {
                        if (! confirm('Are you sure you want to remove')) {
                            return;
                        }

                        this.$axios.delete(`{{ route('admin.theme.delete_product_carousel', '') }}/${productCarousel.id}`)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.get();
                            })
                            .catch((error) => {});
                    }
                }
            });
        </script>

        {{-- Static Theme --}}
        <script type="text/x-template" id="v-static-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">Static Content</p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    Static Content related theme customization
                                </p>
                            </div>

                            <div
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.openStatisContentDrawer.open();this.initHtmlEditor();this.initCssEditor();"
                            >
                                Add Static Content
                            </div>
                        </div>

                        <div v-if="staticContents.length">
                            <div
                                class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300"
                                v-for="staticContent in staticContents"
                            >
                                <!-- Information -->
                                <div class="flex gap-[10px]">
                                    <!-- Image -->
                                    <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">

                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">Product Image</p>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-[16x] text-gray-800 font-semibold">
                                            @{{ staticContent.name }}
                                        </p>

                                        <p 
                                            v-if="staticContent.status"
                                            class="label-processing text-white"
                                        >
                                            Active
                                        </p>

                                        <p 
                                            v-else
                                            class="label-pending text-white"
                                        >
                                            Inactive
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-[4px] place-content-start text-right">
                                    <p
                                        class="text-blue-600 cursor-pointer"
                                        @click="edit(staticContent);this.initHtmlEditor();this.initCssEditor();"
                                    >
                                        Edit
                                    </p>

                                    <p
                                        class="text-red-600 cursor-pointer"
                                        @click="remove(staticContent)"
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
                            <img class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]"
                                src="http://192.168.15.62/bagisto-admin-panel/resources/images/placeholder/add-product-to-store.png"
                                alt="add-product-to-store">
            
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">Add Static Content</p>
                                <p class="text-gray-400">Insert concise static content to enhance webpage engagement and readability..</p>
                            </div>
            
                            <div
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.openStatisContentDrawer.open();this.initHtmlEditor();this.initCssEditor();"
                            >
                                Add Static Content
                            </div>
                        </div>

                        <!-- Edit Drawer -->
                        <x-admin::form
                            v-slot="{ meta, errors, handleSubmit }"
                            ref="staticContentForm"
                            as="div"
                        >
                            <form @submit="handleSubmit($event, storeOrUpdate)">
                                <!-- Edit Drawer -->
                                <x-admin::drawer
                                    ref="openStatisContentDrawer"
                                    class="text-left"
                                >
                                    <!-- Drawer Header -->
                                    <x-slot:header>
                                        <div class="flex justify-between items-center">
                                            <p 
                                                v-if="id"
                                                class="text-[20px] font-medium"
                                            >
                                                Update Static Content
                                            </p>

                                            <p 
                                                v-else
                                                class="text-[20px] font-medium"
                                            >
                                                Create Static Content
                                            </p>

                                            <button class="mr-[45px] px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.save-btn')
                                            </button>
                                        </div>
                                    </x-slot:header>

                                    <!-- Drawer Content -->

                                    <x-slot:content>
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                Name
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="name"
                                                rules="required"
                                                label="Name"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="name"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                Sort Order
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="sort_order"
                                                rules="required"
                                                label="Sort Order"
                                                rules="numeric"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="sort_order"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label for="status">
                                                Status
                                            </x-admin::form.control-group.label>
    
                                            <x-admin::form.control-group.control
                                                type="switch"
                                                name="status"
                                                :value="1"
                                                label="status"
                                                :checked="true"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="status"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>


                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label for="status">
                                                HTML
                                            </x-admin::form.control-group.label>
    
                                            <div ref="html"></div>
                
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label for="status">
                                                CSS
                                            </x-admin::form.control-group.label>
    
                                            <div ref="css"></div>
                
                                        </x-admin::form.control-group>
                                       
                                    </x-slot:content>
                                </x-admin::drawer>
                            </form>
                        </x-admin::form>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-static-theme', {
                template: '#v-static-theme-template',

                data() {
                    return {
                        html: '',
                        css: '',
                        staticContents: {},
                        id: null,
                    };
                },

                created() {
                    this.get();
                },

                methods: {
                    initHtmlEditor() {
                        setTimeout(() => {
                            this._html = new CodeMirror(this.$refs.html, {
                                lineNumbers: true,
                                tabSize: 2,
                                value: this.html,
                                mode: 'html',
                                theme: 'monokai'
                            });

                            this._html.on('changes', () => {
                                this.html = this._html.getValue();
                            });
                        }, 0);
                    },

                    initCssEditor() {
                        setTimeout(() => {
                            this._css = new CodeMirror(this.$refs.css, {
                                lineNumbers: true,
                                tabSize: 2,
                                value: this.css,
                                mode: 'css',
                                theme: 'monokai'
                            });

                            this._css.on('changes', () => {
                                this.css = this._css.getValue();
                            });
                        }, 0);
                    },

                    get() {
                        this.$axios.get('{{ route('admin.theme.themes') }}', {
                                params: {
                                    type: 'static_content'
                                }
                            })
                            .then((response) => {
                                this.staticContents = response.data.data;
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    },

                    remove(staticContent) {
                        if (! confirm('Are you sure you want to remove')) {
                            return;
                        }

                        this.$axios.delete(`{{ route('admin.theme.delete_static_content', '') }}/${staticContent.id}`)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.get();
                            })
                            .catch((error) => {});
                    },

                    storeOrUpdate(params) {
                        params.html = this.html;

                        params.css = this.css;

                        params.type = 'static_content';

                        if (params.id) {
                            this.$axios.post(`{{ route('admin.theme.update_static_content', '') }}/${params.id}`, params)
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    this.get();

                                    this.$refs.openStatisContentDrawer.close();
                                })
                                .catch((error) => {
                                    console.log(error);
                                })
                        } else {
                            this.$axios.post('{{ route('admin.theme.store_static_content') }}', params)
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    this.get();

                                    this.$refs.openStatisContentDrawer.close();
                                })
                                .catch((error) => {
                                    console.log(error);
                                })
                        }
                    },

                    edit(staticContent) {
                        this.id = staticContent.id;

                        this.$refs.staticContentForm.setValues(staticContent);

                        this.html = staticContent.options.html;

                        this.css = staticContent.options.css;

                        this.$refs.openStatisContentDrawer.open();
                    }
                },
            });
        </script>

          {{-- Static Theme --}}
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
                template: '#v-footer-link-theme-template'
            });
        </script>
    @endPushOnce
    
</x-admin::layouts>