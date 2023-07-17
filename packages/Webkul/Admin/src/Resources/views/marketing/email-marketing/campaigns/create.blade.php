<x-admin::layouts>
    {{-- Input Form --}}
    <x-admin::form :action="route('admin.campaigns.store')">
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.email-marketing.campaigns.create.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a href="{{ route('admin.email_templates.index') }}">
                    <span class="text-gray-600 leading-[24px]">
                        @lang('admin::app.marketing.email-marketing.campaigns.create.cancel')
                    </span>
                </a>

                {{-- Save Button --}}
                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.marketing.email-marketing.campaigns.create.save')
                </button>
            </div>
        </div>
        {{-- Informations --}}
        <div class="flex gap-[10px] mt-[28px] mb-2">
            <div class="flex flex-col gap-[8px] flex-1">
                {{-- General Section --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.marketing.email-marketing.campaigns.create.general')
                    </p>

                    <div class="mb-[10px]">
                        {{-- Name --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.campaigns.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                rules="required"
                                class="mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.campaigns.create.name') }}"
                                placeholder="{{ trans('admin::app.marketing.email-marketing.campaigns.create.name') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error 
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Subject --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.campaigns.create.subject')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="subject"
                                value="{{ old('subject') }}"
                                rules="required"
                                class="mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.campaigns.create.subject') }}"
                                placeholder="{{ trans('admin::app.marketing.email-marketing.campaigns.create.subject') }}"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="subject"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                         {{-- Event --}}
                         <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.campaigns.create.event')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="marketing_event_id"
                                rules="required"
                                class="cursor-pointer mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.campaigns.create.event') }}"
                            >
                                <option value="">
                                    @lang('admin::app.marketing.email-marketing.campaigns.create.select-event')
                                </option>

                                @foreach (app('Webkul\Marketing\Repositories\EventRepository')->all() as $event)
                                    <option 
                                        value="{{ $event->id }}" 
                                        {{ old('marketing_event_id') == $event->id ? 'selected' : '' }}
                                    >
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="marketing_event_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Email Template --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.campaigns.create.email-template')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="marketing_template_id"
                                rules="required"
                                class="cursor-pointer mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.campaigns.create.email-template') }}"
                            >
                                <option value="">
                                    @lang('admin::app.marketing.email-marketing.campaigns.create.select-template')
                                </option>

                                @foreach ($templates as $template)
                                    <option 
                                        value="{{ $template->id }}" 
                                        {{ old('marketing_template_id') == $template->id ? 'selected' : '' }}
                                    >
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="marketing_template_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Status --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.campaigns.create.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                rules="required"
                                class="cursor-pointer mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.campaigns.create.status') }}"
                            >
                                <option value="">
                                    @lang('admin::app.marketing.email-marketing.campaigns.create.select-status')
                                </option>

                                <option value="0">
                                    @lang('admin::app.marketing.email-marketing.campaigns.create.inactive')
                                </option>

                                <option value="1">
                                    @lang('admin::app.marketing.email-marketing.campaigns.create.active')
                                </option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="status"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>

                {{-- Audience section --}}
                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.marketing.email-marketing.campaigns.create.audience')
                    </p>

                    <div class="mb-[10px]">
                         {{-- Channel --}}
                         <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.campaigns.create.channel')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="channel_id"
                                rules="required"
                                class="cursor-pointer mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.campaigns.create.channel') }}"
                            >
                                <option value="">
                                    @lang('admin::app.marketing.email-marketing.campaigns.create.select-status')
                                </option>

                                @foreach (app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                    <option 
                                        value="{{ $channel->id }}" 
                                        {{ old('channel_id') == $channel->id ? 'selected' : '' }}
                                    >
                                        {{ core()->getChannelName($channel) }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="channel_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Email Template --}}
                        <x-admin::form.control-group class="mb-4">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.email-marketing.campaigns.create.customer-group')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="customer_group_id"
                                rules="required"
                                class="cursor-pointer mb-1"
                                label="{{ trans('admin::app.marketing.email-marketing.campaigns.create.customer-group') }}"
                            >
                                <option value="">
                                    @lang('admin::app.marketing.email-marketing.campaigns.create.customer-group')
                                </option>

                                @foreach (app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                    <option 
                                        value="{{ $customerGroup->id }}" 
                                        {{ old('customer_group_id') == $customerGroup->id ? 'selected' : '' }}
                                    >
                                        {{ $customerGroup->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="customer_group_id"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>