<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.communications.campaigns.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.before') !!}

    <!-- Input Form -->
    <x-admin::form :action="route('admin.marketing.communications.campaigns.store')">

        {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.marketing.communications.campaigns.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.marketing.communications.campaigns.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.marketing.communications.campaigns.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.marketing.communications.campaigns.create.save-btn')
                </button>
            </div>
        </div>
        <!-- Information -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.general.before') !!}

                <!-- General Section -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.marketing.communications.campaigns.create.general')
                    </p>

                    <div class="mb-2.5">
                        <!-- Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.create.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('admin::app.marketing.communications.campaigns.create.name')"
                                :placeholder="trans('admin::app.marketing.communications.campaigns.create.name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <!-- Subject -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.create.subject')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="subject"
                                rules="required"
                                :value="old('subject')"
                                :label="trans('admin::app.marketing.communications.campaigns.create.subject')"
                                :placeholder="trans('admin::app.marketing.communications.campaigns.create.subject')"
                            />

                            <x-admin::form.control-group.error control-name="subject" />
                        </x-admin::form.control-group>

                         <!-- Event -->
                         <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.create.event')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                class="cursor-pointer"
                                name="marketing_event_id"
                                rules="required"
                                :value="old('marketing_event_id')"
                                :label="trans('admin::app.marketing.communications.campaigns.create.event')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('admin::app.marketing.communications.campaigns.create.select-event')
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

                            <x-admin::form.control-group.error control-name="marketing_event_id" />
                        </x-admin::form.control-group>

                        <!-- Email Template -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.create.email-template')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="marketing_template_id"
                                rules="required"
                                class="cursor-pointer"
                                :value="old('marketing_template_id')"
                                :label="trans('admin::app.marketing.communications.campaigns.create.email-template')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('admin::app.marketing.communications.campaigns.create.select-template')
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

                            <x-admin::form.control-group.error control-name="marketing_template_id" />
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.general.after') !!}
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-md:w-full">

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.accordion.setting.before') !!}

                <!-- Setting -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.marketing.communications.campaigns.create.setting')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <!-- Channel -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.create.channel')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                class="cursor-pointer"
                                name="channel_id"
                                rules="required"
                                :value="old('channel_id')"
                                :label="trans('admin::app.marketing.communications.campaigns.create.channel')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('admin::app.marketing.communications.campaigns.create.select-channel')
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

                            <x-admin::form.control-group.error control-name="channel_id" />
                        </x-admin::form.control-group>

                        <!-- Customer Group -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.communications.campaigns.create.customer-group')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                class="cursor-pointer"
                                name="customer_group_id"
                                rules="required"
                                :value="old('customer_group_id')"
                                :label="trans('admin::app.marketing.communications.campaigns.create.customer-group')"
                            >
                                <!-- Default Option -->
                                <option value="">
                                    @lang('admin::app.marketing.communications.campaigns.create.select-group')
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

                            <x-admin::form.control-group.error control-name="customer_group_id" />
                        </x-admin::form.control-group>

                         <!-- Status -->
                         <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.marketing.communications.campaigns.create.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                class="cursor-pointer"
                                name="status"
                                value="1"
                                :label="trans('admin::app.marketing.communications.campaigns.create.status')"
                            />

                            <x-admin::form.control-group.error control-name="status" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>

                {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.card.accordion.setting.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.create_form_controls.after') !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.create.after') !!}

</x-admin::layouts>
