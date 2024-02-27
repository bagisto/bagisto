<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.marketing.communications.templates.index.title')
    </x-slot>

    <div class="flex gap-4 justify-between max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.marketing.communications.templates.index.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            @if (bouncer()->hasPermission('marketing.communications.email_templates.create'))
                <a href="{{ route('admin.marketing.communications.email_templates.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.marketing.communications.templates.index.create-btn')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.marketing.communications.templates.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.marketing.communications.email_templates.index') }}" />

    {!! view_render_event('bagisto.admin.marketing.communications.templates.list.after') !!}

</x-admin::layouts>
