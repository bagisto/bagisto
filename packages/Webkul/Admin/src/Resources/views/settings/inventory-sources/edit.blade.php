<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.settings.inventory-sources.edit.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.before') !!}

    {{-- Create Inventory --}}
    <v-inventory-edit-form></v-inventory-edit-form>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-inventory-edit-form-template">
            <div>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.before') !!}

                <x-admin::form 
                    :action="route('admin.settings.inventory_sources.update', $inventorySource->id)"
                    enctype="multipart/form-data"
                    method="PUT"
                >

                    {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.edit_form_controls.before') !!}

                    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.settings.inventory-sources.edit.title')
                        </p>

                        <div class="flex gap-x-[10px] items-center">
                            <!-- Cancel Button -->
                            <a
                                href="{{ route('admin.settings.inventory_sources.index') }}"
                                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                            >
                                @lang('admin::app.settings.inventory-sources.edit.back-btn')
                            </a>
                                
                            <!-- Save Inventory -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.settings.inventory-sources.edit.save-btn')
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Full Pannel -->
                    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                
                        <!-- Left Section -->
                        <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.general.before') !!}

                            <!-- General -->
                            <div class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px]">
                                <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.inventory-sources.edit.general')
                                </p>

                                <!-- Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        :value="old('code') ?? $inventorySource->code"
                                        id="code"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.code')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Name -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name') ?? $inventorySource->name"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.name')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Description -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.inventory-sources.edit.description')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        :value="old('description') ?? $inventorySource->description"
                                        id="description"
                                        class="text-gray-600 dark:text-gray-300"
                                        :label="trans('admin::app.settings.inventory-sources.edit.description')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.description')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.general.after') !!}

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.contact_info.before') !!}

                            <!-- Contact Information -->
                            <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                                <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.inventory-sources.edit.contact-info')
                                </p>

                                <!-- Contact Name -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.contact-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="contact_name"
                                        :value="old('contact_name') ?? $inventorySource->contact_name"
                                        id="contact_name"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.contact-name')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.contact-name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Contact Email -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.contact-email')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="email"
                                        name="contact_email"
                                        :value="old('contact_email') ?? $inventorySource->contact_email"
                                        id="contact_email"
                                        rules="required|email"
                                        :label="trans('admin::app.settings.inventory-sources.edit.contact-email')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.contact-email')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_email"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Contact Number -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.inventory-sources.edit.contact-number')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="contact_number"
                                        :value="old('contact_number') ?? $inventorySource->contact_number"
                                        id="contact_number"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.contact-number')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.contact-number')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_number"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Contact Fax -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.inventory-sources.edit.contact-fax')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="contact_fax"
                                        :value="old('contact_fax') ?? $inventorySource->contact_fax"
                                        id="contact_fax"
                                        :label="trans('admin::app.settings.inventory-sources.edit.contact-fax')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.contact-fax')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="contact_fax"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.contact_info.after') !!}

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.source_address.before') !!}

                            <!-- Source Address -->
                            <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                                <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.inventory-sources.edit.source-address')
                                </p>

                                <!-- Country -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.country')
                                    </x-admin::form.control-group.label>
                    
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="country"
                                        id="country"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.country')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.country')"
                                        v-model="country"
                                    >
                                        @foreach (core()->countries() as $country)
                                            <option value="{{ $country->code }}">
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>
                    
                                    <x-admin::form.control-group.error
                                        control-name="country"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- State -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.state')
                                    </x-admin::form.control-group.label>
                    
                                    <template v-if="haveStates()">
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="state"
                                            id="state"
                                            rules="required"
                                            :label="trans('admin::app.settings.inventory-sources.edit.state')"
                                            :placeholder="trans('admin::app.settings.inventory-sources.edit.state')"
                                            v-model="state"
                                        >
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
                                            :value="old('state') ?? $inventorySource->code"
                                            id="state"
                                            rules="required"
                                            :label="trans('admin::app.settings.inventory-sources.edit.state')"
                                            :placeholder="trans('admin::app.settings.inventory-sources.edit.state')"
                                            v-model="state"
                                        >
                                        </x-admin::form.control-group.control>
                                    </template>

                                    <x-admin::form.control-group.error
                                        control-name="state"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- City -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.city')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="city"
                                        :value="old('city') ?? $inventorySource->city"
                                        id="city"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.city')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.city')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="city"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Street -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.street')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="street"
                                        :value="old('street') ?? $inventorySource->street"
                                        id="street"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.street')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.street')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="street"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Post Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.inventory-sources.edit.postcode')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="postcode"
                                        :value="old('postcode') ?? $inventorySource->postcode"
                                        id="postcode"
                                        rules="required"
                                        :label="trans('admin::app.settings.inventory-sources.edit.postcode')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.postcode')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="postcode"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.source_address.after') !!}
                        </div>
                
                        <!-- Right Section -->
                        <div class="flex flex-col gap-[8px] w-[360px] max-w-full">

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.accordion.settings.before') !!}

                            <!-- Settings -->
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex items-center justify-between p-[6px]">
                                        <p class="text-gray-600 dark:text-gray-300  text-[16px] p-[10px] font-semibold">
                                            @lang('admin::app.settings.inventory-sources.edit.settings')
                                        </p>
                                    </div>
                                </x-slot:header>
                            
                                <x-slot:content>
                                    <!-- Latitute -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.inventory-sources.edit.latitude')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="latitude"
                                            :value="old('latitude') ?? $inventorySource->latitude"
                                            id="latitude"
                                            :label="trans('admin::app.settings.inventory-sources.edit.latitude')"
                                            :placeholder="trans('admin::app.settings.inventory-sources.edit.latitude')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="latitude"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Longitude -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.inventory-sources.edit.longitude')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="longitude"
                                            :value="old('longitude') ?? $inventorySource->longitude"
                                            id="longitude"
                                            :label="trans('admin::app.settings.inventory-sources.edit.longitude')"
                                            :placeholder="trans('admin::app.settings.inventory-sources.edit.longitude')"
                                        >
                                        </x-admin::form.control-group.control>
    
                                        <x-admin::form.control-group.error
                                            control-name="longitude"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Priority -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.inventory-sources.edit.priority')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="priority"
                                            :value="old('priority') ?? $inventorySource->priority"
                                            id="priority"
                                            :label="trans('admin::app.settings.inventory-sources.edit.priority')"
                                            :placeholder="trans('admin::app.settings.inventory-sources.edit.priority')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="priority"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Status -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.inventory-sources.edit.status')
                                        </x-admin::form.control-group.label>

                                        @php $selectedValue = old('status') ?: $inventorySource->status; @endphp

                                        <x-admin::form.control-group.control
                                            type="switch"
                                            name="status"
                                            :value="old('status') ?? ($inventorySource->status)"
                                            :label="trans('admin::app.settings.inventory-sources.edit.status')"
                                            :placeholder="trans('admin::app.settings.inventory-sources.edit.status')"
                                            :checked="(boolean) $selectedValue"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="status"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </x-slot:content>
                            </x-admin::accordion>

                            {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.card.accordion.settings.after') !!}

                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.edit_form_controls.after') !!}
                </x-admin::form>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.edit.after') !!}

            </div>
        </script>
        
        <script type="module">
            app.component('v-inventory-edit-form', {
                template: '#v-inventory-edit-form-template',

                data: function () {
                    return {
                        country: "{{ old('country') ?? $inventorySource->country }}",

                        state: "{{ old('state') ?? $inventorySource->state }}",

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