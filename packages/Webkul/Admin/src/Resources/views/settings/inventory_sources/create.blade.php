<x-admin::layouts>
    {{-- Input Form --}}
    <x-admin::form :action="route('admin.inventory_sources.store')">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.inventory_sources.create.add-title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <span class="text-gray-600 leading-[24px]">
                    @lang('admin::app.settings.inventory_sources.create.cancel')
                </span>

                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.inventory_sources.create.save')
                </button>
            </div>
        </div>

        {{-- Filter row --}}
        <div class="flex gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                <div class="">
                    <div class="inline-flex gap-x-[8px] items-center justify-between w-full px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600 max-w-max">
                        <span class="icon-store text-[24px]"></span>

                        @lang('Default Store')

                        <span class="icon-sort-down text-[24px]"></span>
                    </div>

                    <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow"></div>
                </div>

                {{-- Language changer --}}
                <div class="">
                    <div class="inline-flex gap-x-[4px] items-center justify-between w-full px-[4px] py-[6px] text-gray-600 font-semibold text-center cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600 max-w-max">
                        <span class="icon-language text-[24px] "></span>

                        English

                        <span class="icon-sort-down text-[24px]"></span>
                    </div>

                    <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow"></div>
                </div>
            </div>
        </div>

        {{-- Informations --}}
        <div class="flex gap-[10px] mt-[14px] mb-2">
            <div class="flex flex-col gap-[8px] flex-1">
                <div class="p-[16px] bg-white box-shadow rounded-[4px]">
                    <h1 class="mb-3">
                        @lang('admin::app.settings.inventory_sources.create.general')
                    </h1>

                    <div class="mb-[10px]">
                        {{-- Code --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                value="{{ old('code') }}"
                                rules="required"
                                label="Code"
                                placeholder="Code"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error 
                                control-name="code"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Name --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                rules="required"
                                label="Name"
                                placeholder="Name"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Description --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="description"
                                value="{{ old('description') }}"
                                label="Description"
                                placeholder="Description"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="description"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Latitute --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.latitude')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="latitute"
                                value="{{ old('latitute') }}"
                                label="Latitute"
                                placeholder="Latitute"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="latitute"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Longitute --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.longitude')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="longitute"
                                value="{{ old('longitute') }}"
                                label="Longitute"
                                placeholder="Longitute"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="longitute"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Priority --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.priority')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="priority"
                                value="{{ old('priority') }}"
                                rules="numeric"
                                label="Priority"
                                placeholder="Priority"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="priority"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Status --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="status"
                                value="{{ old('status') }}"
                                rules="numeric"
                                label="Status"
                                placeholder="Status"
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
        </div>

        <div class="flex gap-[10px] mt-[14px] mb-2">
            <div class=" flex flex-col gap-[8px] flex-1">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <h1 class="mb-3">
                        @lang('admin::app.settings.inventory_sources.create.contact-information')
                    </h1>

                    <div class="mb-[10px]">
                        {{-- Contact Name --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.contact-name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="contact_name"
                                value="{{ old('contact_name') }}"
                                rules="required"
                                label="Name"
                                placeholder="Name"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="contact_name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Contact Email --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.contact-email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="email"
                                name="contact_email"
                                value="{{ old('contact_email') }}"
                                rules="required"
                                label="Email"
                                placeholder="Email"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="contact_email"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Contact Number --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.contact-number')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="contact_number"
                                value="{{ old('contact_number') }}"
                                label="Contact Number"
                                placeholder="Contact Number"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="contact_number"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Contact Fax --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.contact-fax')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="contact_fax"
                                value="{{ old('contact_fax') }}"
                                label="Fax"
                                placeholder="Fax"
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
        </div>

        <div class="flex gap-[10px] mt-[14px] mb-2">
            <div class=" flex flex-col gap-[8px] flex-1">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <h1 class="mb-3">
                        @lang('admin::app.settings.inventory_sources.create.source-address')
                    </h1>
                    
                    <div class="mb-[10px]">
                        {{-- Country --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.country')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="country"
                                class="cursor-pointer"
                                rules="required"
                                label="Country"
                            >
                                <option value="" readonly>
                                    @lang('Select Country')
                                </option>

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

                        {{-- State --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.contact-name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="state"
                                value="{{ old('state') }}"
                                rules="required"
                                label="State"
                                placeholder="State"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="state"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- City --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.city')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="city"
                                value="{{ old('city') }}"
                                rules="required"
                                label="City"
                                placeholder="City"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="city"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Street --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.street')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="street"
                                value="{{ old('street') }}"
                                rules="required"
                                label="Street"
                                placeholder="Street"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="street"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Pincode --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.inventory_sources.create.postcode')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="postcode"
                                value="{{ old('postcode') }}"
                                rules="required"
                                label="Postcode"
                                placeholder="Postcode"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="Postcode"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>

                </div>
            </div>
        </div>

    </x-admin::form>
</x-admin::layouts>