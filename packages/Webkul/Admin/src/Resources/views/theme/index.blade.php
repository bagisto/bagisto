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
                        :class="{'border-blue-600': componentSwitcher == 'v-slider-theme'}"
                        @click="switchComponent('v-slider-theme')"
                    >
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
                            >
                
                            <p class="mb-[5px] text-[16px] top-[134px] text-center text-gray-800 font-semibold"> Slider </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentSwitcher == 'v-product-theme'}"
                        @click="switchComponent('v-product-theme')"
                    >   
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
                            >
                
                            <p class="mb-[5px] text-[16px] top-[134px] text-center text-gray-800 font-semibold"> Product </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentSwitcher == 'v-static-theme'}"
                        @click="switchComponent('v-static-theme')"
                    >   
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
                            >
                
                            <p class="mb-[5px] text-[16px] top-[134px] text-center text-gray-800 font-semibold"> Static </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentSwitcher == 'v-content-theme'}"
                        @click="switchComponent('v-content-theme')"
                    >   
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/slider.png') }}"
                            >
                
                            <p class="mb-[5px] text-[16px] top-[134px] text-center text-gray-800 font-semibold"> Content </p>
                        </div>
                    </div>
                </div>

                <component :is="componentSwitcher"></component>
            </div>
        </script>

        <script type="module">
            app.component('v-theme-customizer', {
                template: '#v-theme-customizer-template',

                data() {
                    return {
                        componentSwitcher: 'v-slider-theme',
                    }
                },

                methods: {
                    switchComponent(component) {
                        this.componentSwitcher = component;
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
                        this.$axios.get("{{ route('admin.theme.themes') }}", {
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
                                <p class="text-[16px] text-gray-800 font-semibold">Products</p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    Product related theme customization
                                </p>
                            </div>
            
                            <div class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                Add Product
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
            app.component('v-product-theme', {
                template: '#v-product-theme-template'
            });
        </script>

        {{-- Static Theme --}}
        <script type="text/x-template" id="v-static-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">Static</p>

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
            app.component('v-static-theme', {
                template: '#v-static-theme-template'
            });
        </script>

          {{-- Static Theme --}}
        <script type="text/x-template" id="v-content-theme-template">
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
            app.component('v-content-theme', {
                template: '#v-content-theme-template'
            });
        </script>
    @endPushOnce
    
</x-admin::layouts>