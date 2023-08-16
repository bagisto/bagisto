@php
    $admin = auth()->guard('admin')->user();
@endphp

<x-admin::layouts>
    {{-- <div class="mb-[26px] flex items-center justify-between">
        <div class="">
            <p class="mb-[10px] gap-[64px] text-[20px] font-bold text-gray-800">
                Hi! {{ $admin->name }},
            </p>

            <p class="gap-[10px] pr-[10px] text-[12px] text-gray-600">
                @lang("Quickly Review what's going on in your store")
            </p>
        </div>

        <div class="flex items-center gap-x-[10px]">
            <!-- Dropdown -->
            <x-admin::dropdown position="bottom-right">
                <x-slot:toggle>
                    <span class="icon-setting cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100"></span>
                </x-slot:toggle>

                <x-slot:content class="z-10 w-[174px] max-w-full rounded-[4px] border border-gray-300 bg-white !p-[8PX] shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)]">
                    <div class="grid gap-[2px]">
                        <!-- Current Channel -->
                        <div class="cursor-pointer items-center p-[6px] transition-all hover:rounded-[6px] hover:bg-gray-100">
                            <p class="font-semibold leading-[24px] text-gray-600">
                                Channel - {{ core()->getCurrentChannel()->name }}
                            </p>
                        </div>

                        <!-- Current Locale -->
                        <div class="cursor-pointer items-center p-[6px] transition-all hover:rounded-[6px] hover:bg-gray-100">
                            <p class="font-semibold leading-[24px] text-gray-600">
                                Language - {{ core()->getCurrentLocale()->name }}
                            </p>
                        </div>

                        <div class="cursor-pointer items-center p-[6px] transition-all hover:rounded-[6px] hover:bg-gray-100">
                            <!-- Export Modal -->
                            <x-admin::modal ref="exportModal">
                                <x-slot:toggle>
                                    <p class="font-semibold leading-[24px] text-gray-600">
                                        Export
                                    </p>
                                </x-slot:toggle>

                                <x-slot:header>
                                    <p class="text-[18px] font-bold text-gray-800">
                                        @lang('Download')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="p-[16px]">
                                        <x-admin::form action="">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="format"
                                                    id="format"
                                                >
                                                    <option value="xls">XLS</option>
                                                    <option value="csv">CLS</option>
                                                </x-admin::form.control-group.control>
                                            </x-admin::form.control-group>
                                        </x-admin::form>
                                    </div>
                                </x-slot:content>
                                <x-slot:footer>
                                    <!-- Save Button -->
                                    <button
                                        type="submit"
                                        class="cursor-pointer rounded-[6px] border border-blue-700 bg-blue-600 px-[12px] py-[6px] font-semibold text-gray-50"
                                    >
                                        @lang('Export')
                                    </button>
                                </x-slot:footer>
                            </x-admin::modal>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::dropdown>
        </div>
    </div>

    <div class="flex gap-[10px] max-xl:flex-wrap">
        <div class="flex flex-1 flex-col gap-[8px] max-xl:flex-auto">
            <div class="gap-[30px]">
                <div class="grid gap-[4px]">
                    <p class="font-semibold text-gray-600">
                        Overall Details
                    </p>
                </div>

                <div class="box-shadow max-1580:grid-cols-3 mt-[8px] grid grid-cols-5 flex-wrap justify-between gap-[48px] rounded-[4px] bg-white p-[16px] max-xl:grid-cols-2 max-sm:grid-cols-1">
                    <div class="">
                        {{ core()->formatBasePrice($statistics['total_sales']['current']) }}

                        <p class="text-[12px] text-gray-800">
                            {{ __('admin::app.dashboard.total-sale') }}
                        </p>
                        <span class="progress">
                            @if ($statistics['total_sales']['progress'] < 0)
                                <span class="icon-arrow-down"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                    'progress' => -number_format($statistics['total_sales']['progress'], 1),
                                ]) }}
                            @else
                                <span class="icon-arrow-up"></span>
                                {{ __('admin::app.dashboard.increased', [
                                    'progress' => number_format($statistics['total_sales']['progress'], 1),
                                ]) }}
                            @endif
                        </span>
                    </div>

                    <div class="">
                        {{ $statistics['total_orders']['current'] }}

                        <p class="text-[12px] text-gray-800">
                            {{ __('admin::app.dashboard.total-orders') }}
                        </p>

                        <span class="progress">
                            @if ($statistics['total_orders']['progress'] < 0)
                                <span class="icon-arrow-down"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                    'progress' => -number_format($statistics['total_orders']['progress'], 1),
                                ]) }}
                            @else
                                <span class="icon-arrow-up"></span>
                                {{ __('admin::app.dashboard.increased', [
                                    'progress' => number_format($statistics['total_orders']['progress'], 1),
                                ]) }}
                            @endif
                        </span>
                    </div>

                    <div class="">
                        {{ $statistics['total_customers']['current'] }}

                        <p class="text-[12px] text-gray-800">
                            {{ __('admin::app.dashboard.total-customers') }}
                        </p>

                        <span class="progress">
                            @if ($statistics['total_customers']['progress'] < 0)
                                <span class="icon-arrow-down"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                    'progress' => -number_format($statistics['total_customers']['progress'], 1),
                                ]) }}
                            @else
                                <span class="icon-arrow-up"></span>
                                {{ __('admin::app.dashboard.increased', [
                                    'progress' => number_format($statistics['total_customers']['progress'], 1),
                                ]) }}
                            @endif
                        </span>
                    </div>

                    <div class="">
                        {{ core()->formatBasePrice($statistics['avg_sales']['current']) }}

                        <p class="text-[12px] text-gray-800">
                            {{ __('admin::app.dashboard.average-sale') }}
                        </p>

                        <span class="progress">
                            @if ($statistics['avg_sales']['progress'] < 0)
                                <span class="icon-arrow-down"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                    'progress' => -number_format($statistics['avg_sales']['progress'], 1),
                                ]) }}
                            @else
                                <span class="icon-arrow-up"></span>
                                {{ __('admin::app.dashboard.increased', [
                                    'progress' => number_format($statistics['avg_sales']['progress'], 1),
                                ]) }}
                            @endif
                        </span>
                    </div>

                    <div class="">
                        {{ core()->formatBasePrice($statistics['total_unpaid_invoices']) }}

                        <p class="text-[12px] text-gray-800">
                            {{ __('admin::app.dashboard.total-unpaid-invoices') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex w-[360px] max-w-full flex-col gap-[8px]">
            <x-admin::form action="">
                <div class="gap-[30px]">
                    <div class="grid gap-[4px]">
                        <p class="font-semibold text-gray-600">
                            Store Stats
                        </p>
                    </div>

                    <div class="box-shadow mt-[8px] flex-wrap justify-between rounded-[4px] bg-white p-[16px]">
                        <div class="flex gap-[16px]">
                            <x-admin::form.control-group class="mb-[10px]">

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="Default Store"
                                    :value="old('Default Store')"
                                    :label="trans('admin::app.marketing.promotions.catalog-rules.create.from')"
                                    :placeholder="trans('admin::app.marketing.promotions.catalog-rules.create.from')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="Default Store">
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="This Month"
                                    id="This Month"
                                    id="ends_till"
                                    :value="old('ends_till')"
                                    :label="trans('admin::app.marketing.promotions.catalog-rules.create.to')"
                                    :placeholder="trans('admin::app.marketing.promotions.catalog-rules.create.to')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="This Month">
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>

                    </div>
                </div>
            </x-admin::form>
        </div>
    </div> --}}
</x-admin::layouts>
