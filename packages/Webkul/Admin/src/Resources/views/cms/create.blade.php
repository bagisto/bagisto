@inject('channels', 'Webkul\Core\Repositories\ChannelRepository')

<x-admin::layouts>
    <div class="flex-1 h-full max-w-full px-[16px] pt-[11px] pb-[22px] pl-[275px] max-lg:px-[16px]">
        
        <x-shop::form 
            :action="route('admin.cms.store')"
            enctype="multipart/form-data"
        >
            <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('Add Currency')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <button 
                        type="submit"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                    >
                        @lang('Save Currency')
                    </button>
                </div>
            </div>

            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded-[4px] box-shadow">

                        {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

                        <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                            @lang('General')
                        </p>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                Page Title
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="page_title"
                                :value="old('page_title')"
                                id="page_title"
                                rules="required"
                                label="Page Title"
                                :placeholder="trans('Page Title')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="page_title"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                Channels
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="channels[]"
                                :value="old('channels[]')"
                                id="channels[]"
                                rules="required"
                                label="Channels"
                                :placeholder="trans('Channels')"
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
                                Page Title
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="html_content"
                                :value="old('html_content')"
                                id="content"
                                rules="required"
                                label="Content"
                                :placeholder="trans('Content')"
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
                            @lang('Seo')
                        </p>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                Meta Title
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="meta_title"
                                :value="old('meta_title')"
                                id="meta_title"
                                rules="required"
                                label="Meta Title"
                                :placeholder="trans('Meta Title')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="meta_title"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                Url Key
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="url_key"
                                :value="old('url_key')"
                                id="url_key"
                                rules="required"
                                label="Url Key"
                                :placeholder="trans('Url Key')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="url_key"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                        
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                Meta Keywords
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="meta_keywords"
                                :value="old('meta_keywords')"
                                id="meta_keywords"
                                rules="required"
                                label="Meta Keywords"
                                :placeholder="trans('Meta Keywords')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="meta_keywords"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                Meta Description
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="meta_description"
                                :value="old('meta_description')"
                                id="meta_description"
                                rules="required"
                                label="Meta Description"
                                :placeholder="trans('Meta Description')"
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
    </div>

    @pushOnce('scripts')
        @include('admin::layouts.tinymce')

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                tinyMCEHelper.initTinyMCE({
                    selector: 'textarea#content',
                    height: 200,
                    width: "100%",
                    plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                    toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor alignleft aligncenter alignright alignjustify | link hr |numlist bullist outdent indent  | removeformat | code | table',
                    image_advtab: true,
                    valid_elements : '*[*]',
                });
            });
        </script>
    @endPushOnce

</x-admin::layouts>
