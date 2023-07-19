<x-admin::layouts>
    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.users.users.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Create new User -->
            @include('admin::users.users.create')
        </div>
    </div>
</x-admin::layouts>