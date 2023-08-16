<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.events.edit.title')
    </x-slot:title>

    <x-admin::form 
        :action="route('admin.events.update', $event->id)"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.email-marketing.events.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Cancel Button -->
                <a href="{{ route('admin.events.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.marketing.email-marketing.events.edit.cancel-btn')
                    </span>
                </a>

                <!-- Save Button -->
                <div class="flex gap-x-[10px] items-center">
                    <button 
                        type="submit"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                    >
                        @lang('admin::app.marketing.email-marketing.events.edit.save-btn')
                    </button>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.settings.exchangerate.edit.before') !!}

        <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
    
            <!-- Full Pannel -->
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <!-- General -->
                <div class="p-[16px] bg-white box-shadow rounded-[4px]">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.marketing.email-marketing.events.edit.general')
                    </p>

                    <!-- Event Name -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.email-marketing.events.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            value="{{ old('name') ?: $event->name }}"
                            :label="trans('admin::app.marketing.email-marketing.events.edit.name')"
                            :placeholder="trans('admin::app.marketing.email-marketing.events.edit.name')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="name"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Event Description -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.email-marketing.events.edit.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            rules="required"
                            id="description"
                            class="h-[100px]"
                            value="{{ old('description') ?: $event->description }}"
                            :label="trans('admin::app.marketing.email-marketing.events.edit.description')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="description"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Event Date -->
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.marketing.email-marketing.events.edit.date')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="date"
                            name="date"
                            rules="required"
                            value="{{ old('date') ?: $event->date }}"
                            :label="trans('admin::app.marketing.email-marketing.events.edit.date')"
                            placeholder="{{ trans('admin::app.marketing.email-marketing.events.edit.date') }}"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="date"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.marketing.events.create.after') !!}
    </x-admin::form>
</x-admin::layouts>