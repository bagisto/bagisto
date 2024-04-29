<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.inventory-sources.create.add-title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.create.before') !!}

    <x-admin::form 
        :action="route('admin.settings.inventory_sources.store')"
        enctype="multipart/form-data"
    >

        {!! view_render_event('bagisto.admin.settings.inventory_sources.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.settings.inventory-sources.create.add-title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.settings.inventory_sources.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.marketing.communications.campaigns.create.back-btn')
                </a>
                    
                <!-- Save Inventory -->
                <button 
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.inventory-sources.create.save-btn')
                </button>
            </div>
        </div>
    
        <!-- Full Pannel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.general.before') !!}

                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.settings.inventory-sources.create.general')
                    </p>

                    <!-- Code -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.inventory-sources.create.code')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="code"
                            name="code"
                            rules="required"
                            :value="old('code')"
                            :label="trans('admin::app.settings.inventory-sources.create.code')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.code')"
                        />

                        <x-admin::form.control-group.error control-name="code" />
                    </x-admin::form.control-group>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.inventory-sources.create.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="name"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('admin::app.settings.inventory-sources.create.name')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.inventory-sources.create.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            class="!mb-0 text-gray-600 dark:text-gray-300"
                            id="description"
                            name="description"
                            :value="old('description')"
                            :label="trans('admin::app.settings.inventory-sources.create.description')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.description')"
                        />

                        <x-admin::form.control-group.error control-name="description" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.general.after') !!}

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.contact_info.before') !!}

                <!-- Contact Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.settings.inventory-sources.create.contact-info')
                    </p>

                    <!-- Contact name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.inventory-sources.create.contact-name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="contact_name"
                            name="contact_name"
                            rules="required"
                            :value="old('contact_name')"
                            :label="trans('admin::app.settings.inventory-sources.create.contact-name')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.contact-name')"
                        />

                        <x-admin::form.control-group.error control-name="contact_name" />
                    </x-admin::form.control-group>

                    <!-- Contact Email -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.inventory-sources.create.contact-email')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="email"
                            id="contact_email"
                            name="contact_email"
                            rules="required|email"
                            :value="old('contact_email')"
                            :label="trans('admin::app.settings.inventory-sources.create.contact-email')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.contact-email')"
                        />

                        <x-admin::form.control-group.error control-name="contact_email" />
                    </x-admin::form.control-group>

                    <!-- Contact Number -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.inventory-sources.create.contact-number')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="contact_number"
                            name="contact_number"
                            rules="required"
                            :value="old('contact_number')"
                            :label="trans('admin::app.settings.inventory-sources.create.contact-number')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.contact-number')"
                        />

                        <x-admin::form.control-group.error control-name="contact_number" />
                    </x-admin::form.control-group>

                    <!-- Contact fax -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.settings.inventory-sources.create.contact-fax')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="contact_fax"
                            name="contact_fax"
                            :value="old('contact_fax')"
                            :label="trans('admin::app.settings.inventory-sources.create.contact-fax')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.contact-fax')"
                        />

                        <x-admin::form.control-group.error control-name="contact_fax" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.contact_info.after') !!}

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.address.before') !!}

                <!-- Source Address -->
                <v-source-address></v-source-address>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.address.after') !!}

            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.accordion.settings.before') !!}

                <!-- Settings -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.inventory-sources.create.settings')
                            </p>
                        </div>
                    </x-slot>
                
                    <x-slot:content>
                        <!-- Latitude -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory-sources.create.latitude')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="latitude"
                                name="latitude"
                                :value="old('latitude')"
                                :label="trans('admin::app.settings.inventory-sources.create.latitude')"
                                :placeholder="trans('admin::app.settings.inventory-sources.create.latitude')"
                            />

                            <x-admin::form.control-group.error control-name="latitude" />
                        </x-admin::form.control-group>

                        <!-- Longitude -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory-sources.create.longitude')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="longitude"
                                name="longitude"
                                :value="old('longitude')"
                                :label="trans('admin::app.settings.inventory-sources.create.longitude')"
                                :placeholder="trans('admin::app.settings.inventory-sources.create.longitude')"
                            />

                            <x-admin::form.control-group.error control-name="longitude" />
                        </x-admin::form.control-group>

                        <!-- Priority -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory-sources.create.priority')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="priority"
                                name="priority"
                                :value="old('priority')"
                                :label="trans('admin::app.settings.inventory-sources.create.priority')"
                                :placeholder="trans('admin::app.settings.inventory-sources.create.priority')"
                            />

                            <x-admin::form.control-group.error control-name="priority" />
                        </x-admin::form.control-group>

                        <!-- Status -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory-sources.create.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                value="1"
                                :label="trans('admin::app.settings.inventory-sources.create.status')"
                                :placeholder="trans('admin::app.settings.inventory-sources.create.status')"
                                :checked="(bool) old('status')"
                            />

                            <x-admin::form.control-group.error control-name="status" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.settings.inventory_sources.create.card.accordion.settings.after') !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.inventory_sources.create.create_form_controls.after') !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-source-address-template"
        >
            <!-- Source Address -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.settings.inventory-sources.create.address')
                </p>

                <!-- Country -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.inventory-sources.create.country')
                    </x-admin::form.control-group.label>
    
                    <x-admin::form.control-group.control
                        type="select"
                        id="country"
                        name="country"
                        rules="required"
                        v-model="country"
                        :label="trans('admin::app.settings.inventory-sources.create.country')"
                        :placeholder="trans('admin::app.settings.inventory-sources.create.country')"
                    >
                        <option value="">
                            @lang('admin::app.settings.inventory-sources.create.select-country')
                        </option>
    
                        @foreach (core()->countries() as $country)
                            <option value="{{ $country->code }}">
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </x-admin::form.control-group.control>
    
                    <x-admin::form.control-group.error control-name="country" />
                </x-admin::form.control-group>
                        
                <!-- State -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.inventory-sources.create.state')
                    </x-admin::form.control-group.label>
    
                    <template v-if="haveStates()">
                        <x-admin::form.control-group.control
                            type="select"
                            id="state"
                            name="state"
                            rules="required"
                            :value="old('state')"
                            :label="trans('admin::app.settings.inventory-sources.create.state')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.state')"
                        >
                            <option value="">
                                @lang('admin::app.settings.inventory-sources.create.select-state')
                            </option>

                            <option 
                                v-for='(state, index) in countryStates[country]'
                                :value="state.code"
                            >
                                @{{ state.default_name }}
                            </option>
                        </x-admin::form.control-group.control>
                    </template>
    
                    <template v-else>
                        <x-admin::form.control-group.control
                            type="text"
                            id="state"
                            name="state"
                            rules="required"
                            :value="old('state')"
                            v-model="state"
                            :label="trans('admin::app.settings.inventory-sources.create.state')"
                            :placeholder="trans('admin::app.settings.inventory-sources.create.state')"
                        />
                    </template>

                    <x-admin::form.control-group.error control-name="state" />
                </x-admin::form.control-group>

                <!-- City -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.inventory-sources.create.city')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        id="city"
                        name="city"
                        rules="required"
                        :value="old('city')"
                        :label="trans('admin::app.settings.inventory-sources.create.city')"
                        :placeholder="trans('admin::app.settings.inventory-sources.create.city')"
                    />

                    <x-admin::form.control-group.error control-name="city" />
                </x-admin::form.control-group>

                <!-- Street -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.inventory-sources.create.street')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        id="street"
                        name="street"
                        rules="required"
                        :value="old('street')"
                        :label="trans('admin::app.settings.inventory-sources.create.street')"
                        :placeholder="trans('admin::app.settings.inventory-sources.create.street')"
                    />

                    <x-admin::form.control-group.error control-name="street" />
                </x-admin::form.control-group>

                <!-- postcode -->
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.inventory-sources.create.postcode')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        id="postcode"
                        name="postcode"
                        rules="required"
                        :value="old('postcode')"
                        :label="trans('admin::app.settings.inventory-sources.create.postcode')"
                        :placeholder="trans('admin::app.settings.inventory-sources.create.postcode')"
                    />

                    <x-admin::form.control-group.error control-name="postcode" />
                </x-admin::form.control-group>
            </div>
        </script>

        <script type="module">
            app.component('v-source-address', {
                template: '#v-source-address-template',

                data() {
                    return {
                        country: "{{ old('country') }}",

                        state: "{{ old('state')  }}",

                        countryStates: @json(core()->groupedStatesByCountries())
                    }
                },

                methods: {
                    haveStates() {
                        /*
                        * The double negation operator is used to convert the value to a boolean.
                        * It ensures that the final result is a boolean value,
                        * true if the array has a length greater than 0, and otherwise false.
                        */
                        return !!this.countryStates[this.country]?.length;
                    },
                }
            })
        </script>
    @endpushOnce
</x-admin::layouts>
