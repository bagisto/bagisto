<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.templates.create.title')
    </x-slot:title>

    {{-- Input Form --}}
    <x-admin::form :action="route('admin.email_templates.store')">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.email-marketing.templates.create.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a href="{{ route('admin.email_templates.index') }}">
                    <span class="px-[12px] py-[6px] border-[2px] border-transparent rounded-[6px] text-gray-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 cursor-pointer">
                        @lang('admin::app.marketing.email-marketing.templates.create.cancel')
                    </span>
                </a>

                {{-- Save Button --}}
                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.marketing.email-marketing.templates.create.save-btn')
                </button>
            </div>
        </div>

        {{-- Informations --}}
        <div class="flex gap-[10px] mt-[28px] mb-2">
            <div class="flex flex-col gap-[8px] flex-1">
                {{-- General Section --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.marketing.email-marketing.templates.create.general')
                    </p>

                    <div class="mb-[10px]">
                        {{-- Template Name --}}
                        <x-admin::form.control-group class="w-full mb-[10px]">
                            <x-admin::form.control-group.label class="required !mt-0">
                                @lang('admin::app.marketing.email-marketing.templates.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                :value="old('name')"
                                rules="required"
                                label="{{ trans('admin::app.marketing.email-marketing.templates.create.name') }}"
                                placeholder="{{ trans('admin::app.marketing.email-marketing.templates.create.name') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Template Status --}}
                        <x-admin::form.control-group class="w-full mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.email-marketing.templates.create.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                class="cursor-pointer mb-1"
                                rules="required"
                                label="{{ trans('admin::app.marketing.email-marketing.templates.create.status') }}"
                            >
                                @foreach (['active', 'inactive', 'draft'] as $state)
                                    <option
                                        value="{{ $state }}" 
                                        {{ old('status') == $state ? 'selected' : '' }}
                                    >
                                        @lang('admin::app.marketing.email-marketing.templates.create.' . $state)
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="status"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Template Textarea --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.email-marketing.templates.create.content')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="tinymce"
                                name="content"
                                :value="old('content')"
                                rules="required"
                                id="content"
                                :label="trans('admin::app.marketing.email-marketing.templates.create.content')"
                                :placeholder="trans('admin::app.marketing.email-marketing.templates.create.content')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="content"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>