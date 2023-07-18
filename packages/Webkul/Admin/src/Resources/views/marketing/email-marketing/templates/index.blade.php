<x-admin::layouts>
    <x-admin::tabs 
        position="left" 
        class="!text-[12px]"
    >
        {{-- Email Template Tab --}}
        <x-admin::tabs.item
            class=""
            :title="trans('admin::app.marketing.email-marketing.templates.title')"
            :is-selected="true"
        >
            <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.marketing.email-marketing.templates.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <a href="{{ route('admin.email_templates.create') }}">
                        <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                            @lang('admin::app.marketing.email-marketing.templates.create.title')
                        </div>
                    </a>
                </div>
            </div>
        </x-admin::tabs.item>

        {{-- Events Tab --}}
        <x-admin::tabs.item
            class=""
            :title="trans('admin::app.marketing.email-marketing.templates.events')"
            :is-selected="false"
        >
            @include('admin::marketing.email-marketing.events.index')
        </x-admin::tabs.item>

        {{-- Campaigns Tab --}}
        <x-admin::tabs.item
            class=""
            :title="trans('admin::app.marketing.email-marketing.campaigns.title')"
            :is-selected="false"
        >
            @include('admin::marketing.email-marketing.campaigns.index')
        </x-admin::tabs.item>

        {{-- News Letter Subscription Tab --}}
        <x-admin::tabs.item
            class=""
            :title="trans('admin::app.marketing.email-marketing.newsletters.title')"
            :is-selected="false"
        >
            @include('admin::marketing.email-marketing.subscribers.index')
        </x-admin::tabs.item>
    </x-admin::tabs>

</x-admin::layouts>
