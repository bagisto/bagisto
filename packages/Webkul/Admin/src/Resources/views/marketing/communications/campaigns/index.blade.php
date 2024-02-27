<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.marketing.communications.campaigns.index.title')
    </x-slot>

    <div class="flex gap-4 justify-between max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.marketing.communications.campaigns.index.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            @if (bouncer()->hasPermission('marketing.communications.campaigns.create'))
                <a href="{{ route('admin.marketing.communications.campaigns.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.marketing.communications.campaigns.index.create-btn')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.marketing.communications.campaigns.index') }}" />

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.list.after') !!}

</x-admin::layouts>