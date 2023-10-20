<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.communications.campaigns.edit.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.edit.before') !!}

    {{-- Input Form --}}
    <x-admin::form
        :action="route('admin.marketing.communications.campaigns.update', $campaign->id)"
        method="PUT"
    >

        {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.create_form_controls.before') !!}

        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.marketing.communications.campaigns.edit.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                {{-- Cancel Button --}}
                <a
                    href="{{ route('admin.marketing.communications.campaigns.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('admin::app.marketing.communications.campaigns.edit.back-btn')
                </a>

                {{-- Save Button --}}
                <button 
                    type="submit" 
                    class="py-[6px] px-[12px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.marketing.communications.campaigns.edit.save-btn')
                </button>
            </div>
        </div>

        {{-- Informations --}}
        <div class="flex gap-[10px] mt-[28px] mb-2">
            {{-- Left Section --}}
            <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.edit.card.general.before') !!}

                {{-- General Section --}}
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.marketing.communications.campaigns.edit.general')
                    </p>

                    <div class="mb-[10px]">
                        {{-- Name --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                :value="old('name') ?: $campaign->name"
                                rules="required"
                                :label="trans('admin::app.marketing.communications.campaigns.edit.name')"
                                :placeholder="trans('admin::app.marketing.communications.campaigns.edit.name')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error 
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        {{-- Subject --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.edit.subject')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="subject"
                                :value="old('subject') ?: $campaign->subject"
                                rules="required"
                                :label="trans('admin::app.marketing.communications.campaigns.edit.subject')"
                                :placeholder="trans('admin::app.marketing.communications.campaigns.edit.subject')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="subject"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                         {{-- Event --}}
                         <x-admin::form.control-group class="mb-[10px]">
                             <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.edit.event')
                            </x-admin::form.control-group.label>

                            @php $selectedOption = old('marketing_event_id') ?: $campaign->marketing_event_id @endphp
                                
                            <x-admin::form.control-group.control
                                type="select"
                                name="marketing_event_id"
                                class="cursor-pointer"
                                :value="$selectedOption"
                                rules="required"
                                :label="trans('admin::app.marketing.communications.campaigns.edit.event')"
                            >
                                @foreach (app('Webkul\Marketing\Repositories\EventRepository')->all() as $event)
                                    <option 
                                        value="{{ $event->id }}" 
                                        {{ $selectedOption == $event->id ? 'selected' : '' }}
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
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.edit.email-template')
                            </x-admin::form.control-group.label>

                            @php $selectedOption = old('marketing_template_id') ?: $campaign->marketing_template_id; @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                name="marketing_template_id"
                                rules="required"
                                :value="$selectedOption"
                                class="cursor-pointer"
                                :label="trans('admin::app.marketing.communications.campaigns.edit.email-template')"
                            >
                                @foreach ($templates as $template)
                                    <option 
                                        value="{{ $template->id }}" 
                                        {{ $selectedOption == $template->id ? 'selected' : '' }}
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
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.edit.card.general.after') !!}

            </div>

             {{-- Right Section --}}
             <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-md:w-full">

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.edit.card.accordion.setting.before') !!}

                {{-- Setting --}}
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                            @lang('admin::app.marketing.communications.campaigns.create.setting')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                         {{-- Channel --}}
                         <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.edit.channel')
                            </x-admin::form.control-group.label>

                            @php $selectedOption = old('channel_id') ?: $campaign->channel_id; @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                name="channel_id"
                                rules="required"
                                :value="$selectedOption"
                                class="cursor-pointer mb-1"
                                :label="trans('admin::app.marketing.communications.campaigns.edit.channel')"
                            >
                                @foreach (app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                    <option
                                        value="{{ $channel->id }}"
                                        {{ $selectedOption == $channel->id ? 'selected' : '' }}
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

                        {{-- Customer Group --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.edit.customer-group')
                            </x-admin::form.control-group.label>

                            @php $selectedOption = old('customer_group_id') ?: $campaign->customer_group_id @endphp

                            <x-admin::form.control-group.control
                                type="select"
                                name="customer_group_id"
                                rules="required"
                                :value="$campaign->customer_group_id"
                                class="cursor-pointer mb-1"
                                :label="trans('admin::app.marketing.communications.campaigns.edit.customer-group')"
                            >
                                @foreach (app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                    <option
                                        value="{{ $customerGroup->id }}"
                                        {{ $selectedOption == $customerGroup->id ? 'selected' : '' }}
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

                        {{-- Status --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.communications.campaigns.edit.status')
                            </x-admin::form.control-group.label>

                            @php $selectedOption = old('status') ?: $campaign->status; @endphp

                            <x-admin::form.control-group.control
                                type="switch"
                                name="status"
                                :value="1"
                                :checked="(boolean) $selectedOption"
                                :label="trans('admin::app.marketing.communications.campaigns.edit.status')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="status"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.edit.card.accordion.setting.after') !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.create_form_controls.after') !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.edit.after') !!}

</x-admin::layouts>