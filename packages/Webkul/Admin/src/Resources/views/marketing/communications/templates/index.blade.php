<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.communications.templates.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('admin::app.marketing.communications.templates.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            @if (bouncer()->hasPermission('marketing.communications.email-templates.create'))
                <a href="{{ route('admin.marketing.communications.email_templates.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.marketing.communications.templates.index.create-btn')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('admin.marketing.communications.templates.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.marketing.communications.email_templates.index') }}"></x-admin::datagrid>

    {!! view_render_event('admin.marketing.communications.templates.list.after') !!}

</x-admin::layouts>
