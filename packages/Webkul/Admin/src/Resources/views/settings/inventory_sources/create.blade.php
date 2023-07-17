<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.settings.inventory_sources.add-title')
    </x-slot:title>

    {{-- Create Inventory --}}
    <v-create></v-create>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-template">
            <div>
                 
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <x-admin::form 
                    :action="route('admin.inventory_sources.store')"
                    enctype="multipart/form-data"
                >
                    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                        <p class="text-[20px] text-gray-800 font-bold">
                            @lang('admin::app.settings.inventory_sources.add-title')
                        </p>

                        {{-- Save Inventory --}}
                        <div class="flex gap-x-[10px] items-center">
                            <button 
                                type="submit"
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.settings.inventory_sources.save-btn-title')
                            </button>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.settings.inventory.create.before') !!}

                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    @lang('admin::app.settings.inventory_sources.general')
                                </p>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        :value="old('code')"
                                        id="code"
                                        rules="required"
                                        :label="trans('Code')"
                                        :placeholder="trans('Code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        id="name"
                                        rules="required"
                                        :label="trans('name')"
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
                                        @lang('Description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        :value="old('description')"
                                        id="description"
                                        class="text-gray-600 "
                                        :label="trans('Description')"
                                        :placeholder="trans('Description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Latitude')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="latitude"
                                        :value="old('latitude')"
                                        id="latitude"
                                        :label="trans('Latitude')"
                                        :placeholder="trans('Latitude')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="latitude"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Longitude')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="longitude"
                                        :value="old('longitude')"
                                        id="longitude"
                                        :label="trans('Longitude')"
                                        :placeholder="trans('Longitude')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="longitude"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Priority')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="priority"
                                        :value="old('priority')"
                                        id="priority"
                                        :label="trans('Priority')"
                                        :placeholder="trans('Priority')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="priority"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        value="1"
                                        id="status"
                                        :label="trans('Status')"
                                        :placeholder="trans('Status')"
                                        :checked="old('status') ?? false"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    @lang('Contact Information')
                                </p>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="contact_name"
                                        :value="old('contact_name')"
                                        id="contact_name"
                                        rules="required"
                                        :label="trans('Contact name')"
                                        :placeholder="trans('Contact name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Email')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="email"
                                        name="contact_email"
                                        :value="old('contact_email')"
                                        id="contact_email"
                                        rules="required|email"
                                        :label="trans('Email')"
                                        :placeholder="trans('Email')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_email"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Contact Number')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="contact_number"
                                        :value="old('contact_number')"
                                        id="contact_number"
                                        rules="required"
                                        :label="trans('Contact Number')"
                                        :placeholder="trans('Contact Number')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_number"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Fax')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="contact_fax"
                                        :value="old('contact_fax')"
                                        id="contact_fax"
                                        :label="trans('Fax')"
                                        :placeholder="trans('Fax')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_fax"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                        <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                            <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                                <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                    @lang('Source Address') 
                                </p>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Country')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="country"
                                        id="country"
                                        rules="required"
                                        :label="trans('country')"
                                        :placeholder="trans('country')"
                                        v-model="country"
                                    >
                                        <option value=""></option>
                    
                                        @foreach (core()->countries() as $country)
                    
                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                    
                                        @endforeach
                                    </x-admin::form.control-group.control>
                    
                                    <x-admin::form.control-group.error
                                        control-name="country"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                        
                                <x-admin::form.control-group 
                                    class="mb-[10px]"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('State')
                                    </x-admin::form.control-group.label>
                    
                                    <template v-if="haveStates()">
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="state"
                                            id="state"
                                            rules="required"
                                            :label="trans('State')"
                                            :placeholder="trans('State')"
                                        >
                                            <option value="">@lang('admin::app.customers.customers.select-state')</option>

                                            <option 
                                                v-for='(state, index) in countryStates[country]'
                                                :value="state.code"
                                                v-text="state.default_name"
                                            >
                                            </option>
                                        </x-admin::form.control-group.control>
                                    </template>
                    
                                    <template v-else>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="state"
                                            :value="old('state')"
                                            id="state"
                                            rules="required"
                                            :label="trans('State')"
                                            :placeholder="trans('State')"
                                            v-model="state"
                                        >
                                        </x-admin::form.control-group.control>
                                    </template>

                                    <x-admin::form.control-group.error
                                        control-name="state"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('City')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="city"
                                        :value="old('city')"
                                        id="city"
                                        rules="required"
                                        :label="trans('City')"
                                        :placeholder="trans('City')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="city"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Street')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="street"
                                        :value="old('street')"
                                        id="street"
                                        rules="required"
                                        :label="trans('Street')"
                                        :placeholder="trans('Street')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="street"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('Postcode')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="postcode"
                                        :value="old('postcode')"
                                        id="postcode"
                                        rules="required"
                                        :label="trans('Postcode')"
                                        :placeholder="trans('Postcode')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="postcode"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.settings.inventory.create.after') !!}
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create', {
                template: '#v-create-template',

                data: function () {
                    return {
                        country: "{{ old('country') }}",

                        state: "{{ old('state')  }}",

                        countryStates: @json(core()->groupedStatesByCountries())
                    }
                },

                methods: {
                    haveStates: function () {
                        if (this.countryStates[this.country] && this.countryStates[this.country].length) {
                            return true;
                        }

                        return false;
                    },
                }
            })
        </script>
    @endpushOnce

</x-admin::layouts>