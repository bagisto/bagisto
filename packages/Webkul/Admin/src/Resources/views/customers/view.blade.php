<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.customers.view.title')
    </x-slot:title>

    <div class="grid">
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                {{ $customer->first_name . " " . $customer->last_name }}

                {{-- Customer Status --}}
                @if ($customer->status == 1)
                    <span class="label-active text-[14px] mx-[5px]">
                        @lang('admin::app.customers.view.active')
                    </span>
                @else    
                    <span class="label-cancelled text-[14px] mx-[5px]">
                        @lang('admin::app.customers.view.inactive')
                    </span>
                @endif

                {{-- Customer Suspended Status --}}
                @if ($customer->is_suspended == 1)
                    <span class="label-pending text-[14px]">
                        @lang('admin::app.customers.view.suspended')
                    </span>
                @endif
            </p>   
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex gap-x-[4px] gap-y-[8px] items-center flex-wrap mt-[28px]">
 
        {{--Address Create component --}}
        @include('admin::customers.addresses.create')
       
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-cancel text-[24px] "></span>

            @lang('admin::app.customers.view.delete-account')
        </div>
    </div>

    {{-- Content --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        {{-- Left Component --}}
        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
            {{-- Orders --}}
            <div class=" bg-white rounded-[4px] box-shadow">
                @if ($totalOrderCount = count($customer->orders))
                    <div class=" p-[16px] flex justify-between">
                        {{-- Total Order Count --}}
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.customers.view.orders') ({{ $totalOrderCount }})
                        </p>
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.customers.view.total-revenue') - {{ core()->currency($customer->orders->sum('grand_total')) }}
                        </p>
                    </div>

                    {{-- Order Details --}}
                    <div class="table-responsive grid w-full">
                        @foreach ($customer->orders as $order)
                            <div class="flex justify-between items-center px-[16px] py-[16px]">
                                <div class="row grid grid-cols-3 w-full">
                                    <div class="">
                                        <div class="flex gap-[10px]">
                                            <span class="icon-uncheckbox text-[24px]"></span>

                                            <div class="flex flex-col gap-[6px]">
                                                <p class="text-[16px] text-gray-800 font-semibold">
                                                    #{{ $order->id }}
                                                </p>

                                                <p class="text-gray-600">
                                                    {{ $order->created_at }}
                                                </p>

                                                @switch($order->status)
                                                    @case('processing')
                                                        <p class="label-active">
                                                            {{ $order->status }}
                                                        </p>
                                                        @break

                                                    @case('completed')
                                                        <p class="label-active">
                                                            {{ $order->status }}
                                                        </p>
                                                        @break

                                                    @case('pending')
                                                        <p class="label-pending">
                                                            {{ $order->status }}
                                                        </p>
                                                        @break

                                                    @case('canceled')
                                                        <p class="label-cancelled">
                                                            {{ $order->status }}
                                                        </p>
                                                        @break

                                                    @case('closed')
                                                        <p class="label-closed">
                                                            {{ $order->status }}
                                                        </p>
                                                        @break

                                                @endswitch
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="flex flex-col gap-[6px]">
                                            {{-- Grand Total --}}
                                            <p class="text-[16px] text-gray-800 font-semibold">
                                                {{ core()->currency($order->grand_total ) }}
                                            </p>

                                            {{-- Payment methods --}}   
                                            <p class="text-gray-600">
                                                @lang('admin::app.customers.view.pay-by') - {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}
                                            </p>

                                            {{-- Channel Code --}}
                                            <p class="text-gray-600">
                                                {{ $order->channel->code }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Order Address Details --}}
                                    <div class="">
                                        <div class="flex flex-col gap-[6px]">
                                            <p class="text-[16px] text-gray-800">
                                                {{ $order->billingAddress->name }}
                                            </p>

                                            <p class="text-gray-600">
                                                {{ $order->billingAddress->email }}
                                            </p>

                                            <p class="text-gray-600">
                                                {{ $order->billingAddress->address1 }},
                                                {{ $order->billingAddress->city }},
                                                {{ $order->billingAddress->state }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                            </div>

                            <span class="block w-full border-b-[1px] border-gray-300"></span>
                        @endforeach

                        {{-- single row --}}
                        <span class="border-b-[1px] border-gray-300"></span>
                    </div>
                @else
                    {{-- Empty Container --}} 
                    <div class="p-[16px] flex justify-between">
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.customers.view.orders') (0)
                        </p>
                    </div>

                    {{-- Order Details --}}
                    <div class="table-responsive grid w-full">
                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                            <!-- Placeholder Image -->
                            <img src="{{ bagisto_asset('images/empty-order.png') }}" class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]">
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold"> 
                                    @lang('admin::app.customers.view.empty-order')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Invoices --}}
            <div class="bg-white rounded box-shadow">
                @if ($totalInvoiceCount = count($customer->invoices))
                    {{--Invoice Count --}}
                    <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.customers.view.invoice') ({{ $totalInvoiceCount }})
                    </p>

                    {{-- Invoice Table --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left min-w-[800px]">
                            <thead class="text-[14px] text-gray-600 bg-gray-50 border-b-[1px] border-gray-200">
                                <tr>
                                    @foreach (['invoice-id', 'invoice-date', 'invoice-amount', 'order-id'] as $item)
                                        <th scope="col" class="px-6 py-[16px] font-semibold"> 
                                            @lang('admin::app.customers.view.' . $item)
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            @foreach ($customer->invoices as $invoice)
                                <tbody>
                                    {{-- Invoice Details --}}
                                    <tr class="bg-white border-b ">
                                        <td class="px-6 py-[16px] text-gray-600">
                                            #{{ $invoice->id }}
                                        </td>

                                        <td class="px-6 py-[16px] text-gray-600 whitespace-nowrap">
                                            {{ $invoice->created_at }}
                                        </td>

                                        <td scope="row" class="px-6 py-[16px] text-gray-600">
                                            {{ core()->currency($invoice->grand_total) }}
                                        </td>

                                        <td class="px-6 py-[16px] text-gray-600">
                                            #{{ $invoice->order_id }}
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                @else
                    {{-- Empty Container --}}
                    <div class="flex justify-between p-[16px]">
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.customers.view.invoice') (0)
                        </p>
                    </div>

                    <div class="table-responsive grid w-full">
                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                            <!-- Placeholder Image -->
                            <img
                                src="{{ bagisto_asset('images/empty-order.png') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold"> 
                                    @lang('admin::app.customers.view.empty-invoice')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Reviews --}}
            <div class="bg-white rounded box-shadow">
                @if($totalReviewsCount = count($customer->reviews) )
                    {{-- Reviews Count --}}
                    <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.customers.view.reviews') ({{ $totalReviewsCount }})
                    </p>

                    @foreach($customer->reviews as $review)
                        {{-- Reviews Details --}}
                        <div class="">
                            <div class="grid gap-y-[16px] p-[16px]">
                                <div class="flex justify-start [&amp;>*]:flex-1">
                                    <div class="flex flex-col gap-[6px]">
                                        {{-- Review Name --}}
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            {{ $review->name }}
                                        </p>

                                        {{-- Product Name --}}
                                        <p class="text-gray-600">
                                            {{ $review->product->name }}
                                        </p>

                                        {{-- Review Status --}}
                                        @switch($review->status)
                                            @case('approved')
                                                <p class="label-active">
                                                    {{ $review->status }}
                                                </p>
                                                @break

                                            @case('pending')
                                                <p class="label-pending">
                                                    {{ $review->status }}
                                                </p>
                                                @break

                                            @case('disapproved')
                                                <p class="label-cancelled">
                                                    {{ $review->status }}
                                                </p>
                                                @break

                                        @endswitch
                                    </div>

                                    <div class="flex flex-col gap-[6px]">

                                        {{-- need to update shivendra-webkul --}}
                                        <div class="flex">
                                            <x-admin::star-rating 
                                                :is-editable="false"
                                                :value="$review->rating"
                                            >
                                            </x-admin::star-rating>
                                        </div>

                                        <p class="text-gray-600">
                                            {{ $review->created_at }}
                                        </p>

                                        <p class="text-gray-600">
                                            @lang('admin::app.customers.view.id') - {{ $review->id }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-between gap-x-[16px] items-center">
                                    <div class="flex flex-col gap-[6px]">
                                        {{-- Review Title --}}
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            {{ $review->title }}
                                        </p>

                                        {{-- Review Comment --}}
                                        <p class="text-gray-600">
                                            {{ $review->comment }}
                                        </p>
                                    </div>

                                    <span class="icon-sort-right ml-[4px] text-[24px] cursor-pointer"></span>
                                </div>
                            </div>

                            <span class="block w-full border-b-[1px] border-gray-300"></span>
                        </div>
                    @endforeach    
                @else
                    {{-- Empty Invoice Container --}}
                    <div class="flex justify-between p-[16px]">
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.customers.view.reviews') (0)
                        </p>
                    </div>

                    <div class="table-responsive grid w-full">
                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                            {{-- Placeholder Image --}}
                            <img
                                src="{{ bagisto_asset('images/empty-order.png') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold"> 
                                   @lang('admin::app.customers.view.empty-review')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
          
            {{-- Notes Form --}}
            <div class="bg-white rounded box-shadow">
                <p class=" p-[16px] pb-0 text-[16px] text-gray-800 font-semibold">
                    @lang('admin::app.customers.view.add-note')
                </p>

                <x-admin::form 
                    action="{{ route('admin.customer.note.store', $customer->id) }}"
                >
                    <div class="p-[16px]">
                        {{-- Note --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="note" 
                                id="note"
                                rules="required"
                                :label="trans('admin::app.customers.view.note')"
                                placeholder="Note"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="note"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div class="flex justify-between items-center">
                            <label 
                                class="flex gap-[4px] w-max items-center p-[6px] cursor-pointer select-none"
                                for="customer_notified"
                            >
                                <input 
                                    type="checkbox" 
                                    name="customer_notified"
                                    id="customer_notified"
                                    value="1"
                                    class="hidden peer"
                                >
                    
                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"></span>
                    
                                <p class="flex gap-x-[4px] items-center cursor-pointer">
                                    @lang('admin::app.customers.view.notify-customer')
                                </p>
                            </label>
                            
                            {{--Note Submit Button --}}
                            <button
                                type="submit"
                                class="px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                            >
                                @lang('admin::app.customers.view.submit-btn-title')
                            </button>
                        </div>
                    </div>
                </x-admin::form> 

                {{-- Notes List --}}
                <span class="block w-full border-b-[1px] border-gray-300"></span>

                @foreach ($customer->notes as $note)
                    <div class="grid gap-[6px] p-[16px]">
                        <p class="text-[16px] text-gray-800">
                            {{$note->note}}
                        </p>

                        {{-- Notes List Title and Time --}}
                        <p class="text-gray-600">  
                            @if ($note->customer_notified)
                                @lang('admin::app.customers.view.customer-notified') | {{$note->created_at}}
                            @else
                                @lang('admin::app.customers.view.customer-not-notified') | {{$note->created_at}}
                            @endif
                        </p>
                    </div>

                    <span class="block w-full border-b-[1px] border-gray-300"></span>
                @endforeach
            </div>
        </div>

        {{-- Right Component --}}
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            {{-- Information --}}
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex w-[100%]">
                        <p class="w-[100%] p-[10px] text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.customers.view.customer')
                        </p>
    
                        {{--Customer Edit Component --}}
                       @include('admin::customers.edit', ['groups' => $groups])
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid gap-y-[10px]">
                        <p class="text-gray-800 font-semibold">
                            {{ $customer->first_name . " " . $customer->last_name }}
                        </p>

                        <p class="text-gray-600">
                            @lang('admin::app.customers.view.email') - {{ $customer->email }}
                        </p>

                        <p class="text-gray-600">
                            @lang('admin::app.customers.view.phone') - {{ $customer->phone }}
                        </p>

                        <p class="text-gray-600">
                            @lang('admin::app.customers.view.gender') - {{ $customer->gender }}
                        </p>

                        <p class="text-gray-600">
                            @lang('admin::app.customers.view.date-of-birth') - {{ $customer->date_of_birth }}
                        </p>

                        <p class="text-gray-600">
                            @lang('admin::app.customers.view.group') - {{ $customer->group->code }}
                        </p>
                    </div>
                </x-slot:content>
            </x-admin::accordion> 

            {{-- Addresses listing--}}
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between p-[6px]">
                        <p class="text-gray-600 text-[16px] font-semibold">
                            @lang('admin::app.customers.view.address')
                            ({{ count($customer->addresses) }})
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    @if (count($customer->addresses))
                        @foreach ($customer->addresses as $index => $address)
                            <div class="grid gap-y-[10px]">
                                @if ( $address->default_address )
                                    <p class="label-pending">
                                        @lang('admin::app.customers.view.default-address')
                                    </p>
                                @endif

                                <div class="">
                                    <p class="text-gray-800 font-semibold">
                                        {{ $address->name }}
                                    </p>

                                    <p class="text-gray-600">
                                        {{ $address->address1 }},
                                        {{ $address->city }},
                                        {{ $address->postcode }},
                                        {{ $address->state }}, 
                                        {{ core()->country_name($address->country) }}
                                    </p>
                                </div>

                                <div class="">
                                    <p class="text-gray-600">
                                        @lang('admin::app.customers.view.phone') : {{$address->phone}}
                                    </p>
                                </div>

                                <div class="flex gap-[10px]">
                                    {{-- Edit Address --}}
                                    @include('admin::customers.addresses.edit')

                                    {{-- Delete Address --}}
                                    <p 
                                        class="text-blue-600 cursor-pointer"
                                        onclick="event.preventDefault();
                                        document.getElementById('delete-address{{ $address->id }}').submit();"
                                    >
                                        @lang('admin::app.customers.view.delete')
                                    </p>

                                    <form 
                                        method="post"
                                        action="{{ route('admin.customer.addresses.delete', $address->id) }}" 
                                        id="delete-address{{ $address->id }}" 
                                    >
                                        @csrf
                                    </form>

                                    {{-- Set Default Address --}}
                                    @if (! $address->default_address )
                                        <p 
                                            class="text-blue-600 cursor-pointer"
                                            onclick="event.preventDefault();
                                            document.getElementById('default-address{{ $address->id }}').submit();"
                                        >
                                            @lang('admin::app.customers.view.set-as-default')
                                        </p>

                                        <form 
                                            class="hidden"
                                            method="post"
                                            action="{{ route('admin.customer.addresses.set_default', $customer->id) }}" 
                                            id="default-address{{ $address->id }}" 
                                        >
                                            @csrf

                                            <input
                                                type="text"
                                                name="set_as_default"
                                                value="{{ $address->id }}"
                                            >
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            @if ($index < count($customer->addresses) - 1)
                                <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] border-gray-300"></span>
                            @endif
                        @endforeach
                    @else    
                        {{-- Empty Address Container --}}
                        <div class="flex gap-[20px] items-center py-[10px]">
                            <img
                                src="{{ bagisto_asset('images/address-setting.png') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                            >

                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-400 font-semibold">
                                    @lang('admin::app.customers.view.empty-title')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.customers.view.empty-description')
                                </p>
                            </div>
                        </div>
                    @endif
                </x-slot:content>
            </x-admin::accordion>
        </div>
    </div>
</x-admin::layouts>