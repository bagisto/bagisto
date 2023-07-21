@inject('channels', 'Webkul\Core\Repositories\ChannelRepository')

<x-admin::layouts>
    <!--Page title -->
    <x-slot:title>
        @lang('admin::app.cms.create.add-title')
    </x-slot:title>

    <!--Create Page Form -->
    <x-admin::form 
        :action="route('admin.cms.store')"
        enctype="multipart/form-data"
    >
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.cms.create.add-title')
            </p>
            
            <!--Save Form -->
            <div class="flex gap-x-[10px] items-center">
                <button 
                    type="submit"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.cms.create.save')
                </button>
            </div>
        </div>

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                    {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.cms.create.general')
                    </p>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.create.page-title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="page_title"
                            :value="old('page_title')"
                            id="page_title"
                            rules="required"
                            :label="trans('admin::app.cms.create.page-title')"
                            :placeholder="trans('admin::app.cms.create.page-title')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="page_title"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.create.channel')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="channels[]"
                            :value="old('channels[]')"
                            id="channels[]"
                            rules="required"
                            :label="trans('admin::app.cms.create.channel')"
                            :placeholder="trans('admin::app.cms.create.channel')"
                            multiple="multiple"
                        >
                            @foreach($channels->all() as $channel)
                                <option value="{{ $channel->id }}">{{ core()->getChannelName($channel) }}</option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="channels[]"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.create.content')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="tinymce"
                            name="html_content"
                            :value="old('html_content')"
                            id="content"
                            rules="required"
                            :label="trans('admin::app.cms.create.content')"
                            :placeholder="trans('admin::app.cms.create.content')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="html_content"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    {!! view_render_event('bagisto.admin.settings.currencies.create.after') !!}
                </div>
            </div>
        </div>

        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                    {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                        @lang('admin::app.cms.create.seo')
                    </p>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.create.meta-title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="meta_title"
                            :value="old('meta_title')"
                            id="meta_title"
                            rules="required"
                            :label="trans('admin::app.cms.create.meta-title')"
                            :placeholder="trans('admin::app.cms.create.meta-title')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="meta_title"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.create.url-key')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="url_key"
                            :value="old('url_key')"
                            id="url_key"
                            rules="required"
                            :label="trans('admin::app.cms.create.url-key')"
                            :placeholder="trans('admin::app.cms.create.url-key')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="url_key"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>
                    
                    <x-admin::form.control-group class="mb-[10px]">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.create.meta-keywords')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="meta_keywords"
                            :value="old('meta_keywords')"
                            id="meta_keywords"
                            rules="required"
                            :label="trans('admin::app.cms.create.meta-keywords')"
                            :placeholder="trans('admin::app.cms.create.meta-keywords')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="meta_keywords"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.cms.create.meta-description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="meta_description"
                            :value="old('meta_description')"
                            id="meta_description"
                            rules="required"
                            :label="trans('admin::app.cms.create.meta-description')"
                            :placeholder="trans('admin::app.cms.create.meta-description')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="meta_description"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    {!! view_render_event('bagisto.admin.settings.currencies.create.after') !!}
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
