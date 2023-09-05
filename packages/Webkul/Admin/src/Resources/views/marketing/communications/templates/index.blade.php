<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.communications.templates.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.marketing.communications.templates.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <a href="{{ route('admin.marketing.communications.email_templates.create') }}">
                <div class="primary-button">
                    @lang('admin::app.marketing.communications.templates.index.create-btn')
                </div>
            </a>
        </div>
    </div>

    <x-admin::datagrid src="{{ route('admin.marketing.communications.email_templates.index') }}"></x-admin::datagrid>
</x-admin::layouts>
