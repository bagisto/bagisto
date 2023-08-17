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

        {{-- body content --}}
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            {{-- Left sub-component --}}
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                {{--Content --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="required text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.marketing.email-marketing.templates.create.content')
                    </p>

                    <div class="mb-[10px]">
                        {{-- Template Textarea --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="content"
                                :value="old('content')"
                                rules="required"
                                id="content"
                                :label="trans('admin::app.marketing.email-marketing.templates.create.content')"
                                :placeholder="trans('admin::app.marketing.email-marketing.templates.create.content')"
                                :tinymce="true"
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

            {{-- Right sub-component --}}
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                {{-- General --}}
                <div class="bg-white rounded-[4px] box-shadow">
                    <x-admin::accordion>
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.users.roles.create.general')
                                </p>
                            </div>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="w-full mb-[10px]">
                                {{-- Template Name --}}
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.email-marketing.templates.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        rules="required"
                                        :label="trans('admin::app.marketing.email-marketing.templates.create.name')"
                                        :placeholder="trans('admin::app.marketing.email-marketing.templates.create.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                {{-- Template Status --}}
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.email-marketing.templates.create.status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="status"
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
                            </div>
                        </x-slot:content>
                    </x-admin::accordion>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
