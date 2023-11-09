<!-- Todays Details Vue Component -->
<v-services-content></v-services-content>

@pushOnce('scripts')
    <script type="text/x-template" id="v-services-content-template">
        <div>
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white dark:bg-gray-900 rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.services-content.services')
                                </p>
                                
                                <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                                    @lang('admin::app.settings.themes.edit.services-content.service-info')
                                </p>
                            </div>
            
                            <!-- Add Services Button -->
                            <div class="flex gap-[10px]">
                                <div
                                    class="secondary-button"
                                    @click="$refs.addServiceModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.edit.services-content.add-btn')
                                </div>
                            </div>
                        </div>

                        <template v-for="(deletedService, index) in deletedServices">
                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[deleted_services]['+ index +'][service_details]'"
                                :value="deletedService.service_details"
                            />
                        </template>

                        <div
                            class="grid pt-[16px]"
                            v-if="servicesContent.services.length"
                            v-for="(service_details, index) in servicesContent.services"
                        >
                            <!-- Hidden Input -->
                            <input
                                type="file"
                                class="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][service_details]'"
                                :ref="'imageInput_' + index"
                            />

                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][title]'"
                                :value="service_details.title"
                            />

                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][description]'"
                                :value="service_details.description"
                            />

                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][service_icon]'"
                                :value="service_details.service_icon"
                            />
                        
                            <!-- Service  Details  Listig -->
                            <div 
                                class="flex gap-[10px] justify-between py-5 cursor-pointer"
                                :class="{
                                    'border-b-[1px] border-slate-300 dark:border-gray-800': index < servicesContent.services.length - 1
                                }"
                            >
                                <div class="flex gap-[10px]">
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div> 
                                                @lang('admin::app.settings.themes.edit.services-content.title'): 

                                                <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                    @{{ service_details.title }}
                                                </span>
                                            </div>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div> 
                                                @lang('admin::app.settings.themes.edit.services-content.description'): 

                                                <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                    @{{ service_details.description }}
                                                </span>
                                            </div>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div class="flex justify-between"> 
                                                @lang('admin::app.settings.themes.edit.services-content.service-icon'): 

                                                <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                    @{{ service_details.service_icon }}
                                                </span>
                                            </div>
                                        </p>
                                    </div>
                                </div>

                                <!-- Service Actions -->
                                <div class="grid gap-[4px] place-content-start text-right">
                                    <div class="flex gap-x-[20px] items-center">
                                        <p 
                                            class="text-blue-600 cursor-pointer transition-all hover:underline"
                                            @click="edit(service_details)"
                                        > 
                                            @lang('admin::app.settings.themes.edit.edit')
                                        </p>

                                        <p 
                                            class="text-red-600 cursor-pointer transition-all hover:underline"
                                            @click="remove(service_details)"
                                        > 
                                            @lang('admin::app.settings.themes.edit.services-content.delete')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty Page -->
                        <div    
                            class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                            v-else
                        >
                            <img    
                                class="w-[120px] h-[120px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                alt="add-product-to-store"
                            >
            
                            <div class="flex flex-col gap-[5px] items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">
                                    @lang('admin::app.settings.themes.edit.services-content.add-btn')
                                </p>
                                
                                <p class="text-gray-400">
                                    @lang('admin::app.settings.themes.edit.services-content.service-info')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.edit.services-content.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input
                                type="hidden"
                                name="type"
                                value="services_content"
                            />

                            <!-- Name -->
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.services-content.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    label="@lang('admin::app.settings.themes.edit.services-content.name')"
                                    placeholder="@lang('admin::app.settings.themes.edit.services-content.name')"
                                ></v-field>

                                <x-admin::form.control-group.error
                                    control-name="name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Short Order -->
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.services-content.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    rules="required|min_value:1"
                                    value="{{ $theme->sort_order }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    label="@lang('admin::app.settings.themes.edit.services-content.sort-order')"
                                    placeholder="@lang('admin::app.settings.themes.edit.services-content.sort-order')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="sort_order"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Channels -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.services-content.channels')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="channel_id"
                                    rules="required"
                                    :value="$theme->channel_id"
                                >
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                    @endforeach 
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="channel_id"></x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.services-content.status')
                                </x-admin::form.control-group.label>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        :value="{{ $theme->status }}"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            id="status"
                                            class="sr-only peer"
                                            v-bind="field"
                                            :checked="{{ $theme->status }}"
                                        />
                                    </v-field>
                        
                                    <label
                                        class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:ltr:left-[2px] after:rtl:right-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
                                        for="status"
                                    ></label>
                                </label>

                                <x-admin::form.control-group.error
                                    control-name="status"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                        </x-slot:content>
                    </x-admin::accordion>
                </div>
            </div>

            <!-- Update Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form 
                    @submit="handleSubmit($event, saveServices)"
                    ref="createServiceForm"
                >
                    <x-admin::modal ref="addServiceModal">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.settings.themes.edit.services-content.update-service')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                <!-- Title -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.edit.services-content.title')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="{{ $currentLocale->code }}[title]"
                                        rules="required"
                                        v-model="selectedService.title"
                                        :label="trans('admin::app.settings.themes.edit.services-content.title')"
                                        :placeholder="trans('admin::app.settings.themes.edit.services-content.title')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="{{ $currentLocale->code }}[title]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Description -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.themes.edit.services-content.description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="{{ $currentLocale->code }}[description]"
                                        v-model="selectedService.description"
                                        :label="trans('admin::app.settings.themes.edit.services-content.description')"
                                        :placeholder="trans('admin::app.settings.themes.edit.services-content.description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="{{ $currentLocale->code }}[description]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Services Icon -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.edit.services-content.service-icon-class')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="{{ $currentLocale->code }}[service_icon]"
                                        rules="required"
                                        v-model="selectedService.service_icon"
                                        :label="trans('admin::app.settings.themes.edit.services-content.service-icon-class')"
                                        :placeholder="trans('admin::app.settings.themes.edit.services-content.service-icon-class')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="{{ $currentLocale->code }}[service_icon]"
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
                                    @lang('admin::app.settings.themes.edit.services-content.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-services-content', {
            template: '#v-services-content-template',

            props: ['errors'],

            data() {
                return {
                    
                    servicesContent: @json($theme->translate($currentLocale->code)['options'] ?? null),

                    deletedServices: [],

                    selectedService: [],

                    isUpdating: false
                };
            },
            
            created() {
                if (
                    this.servicesContent == null 
                    || this.servicesContent.length == 0
                ) {
                    this.servicesContent = { services: [] };
                }  
            },

            methods: {
                saveServices(params, { resetForm ,setErrors }) {
                    let formData = new FormData(this.$refs.createServiceForm);

                    if (! this.isUpdating) {
                        try {
                            const serviceImage = formData.get("service_icon[]");

                            this.servicesContent.services.push({
                                title: formData.get("{{ $currentLocale->code }}[title]"),
                                description: formData.get("{{ $currentLocale->code }}[description]"),
                                service_icon: formData.get("{{ $currentLocale->code }}[service_icon]"),
                            });

                            resetForm();
                        } catch (error) {
                            setErrors({'service_icon': [error.message]});
                        }
                        this.isUpdating = false;
                    }
                        
                    this.$refs.addServiceModal.toggle();
                },

                remove(service_details) {
                    this.deletedServices.push(service_details);
                    
                    this.servicesContent.services = this.servicesContent.services.filter(item => {
                        return (
                            item.title !== service_details.title || 
                            item.description !== service_details.description || 
                            item.service_icon !== service_details.service_icon
                        );
                    });
                },

                edit(service_details) {
                    this.selectedService = service_details;

                    this.isUpdating = true;

                    this.$refs.addServiceModal.toggle();
                },
            },
        });
    </script>
@endPushOnce    