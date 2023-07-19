<x-admin::layouts>
    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.customers.groups.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            <!-- Create a new Group -->
            @include('admin::customers.groups.create')
        </div>
    </div>
</x-admin::layouts>