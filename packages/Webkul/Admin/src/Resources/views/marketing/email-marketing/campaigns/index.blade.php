<x-admin::layouts>
    @include ('admin::layouts.tabs')

    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.campaigns.title')
    </x-slot:title>

    <div class="mt-5">
        <div class="flex gap-[16px] justify-between max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.email-marketing.campaigns.title')
            </p>
    
            <div class="flex gap-x-[10px] items-center">
                <a href="{{ route('admin.campaigns.create') }}">
                    <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                        @lang('admin::app.marketing.email-marketing.campaigns.create.title')
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-admin::layouts>