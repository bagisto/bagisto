<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.customers.customers.view.title')
    </x-slot:title>

    <div class="grid">
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <div class="flex gap-[10px] items-center">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                    {{ $customer->first_name . " " . $customer->last_name }}
                </p>
                
                <div>
                    {{-- Customer Status --}}
                    @if ($customer->status == 1)
                        <span class="label-active text-[14px] mx-[5px]">
                            @lang('admin::app.customers.customers.view.active')
                        </span>
                    @else    
                        <span class="label-cancelled text-[14px] mx-[5px]">
                            @lang('admin::app.customers.customers.view.inactive')
                        </span>
                    @endif

                    {{-- Customer Suspended Status --}}
                    @if ($customer->is_suspended == 1)
                        <span class="label-cancelled text-[14px]">
                            @lang('admin::app.customers.customers.view.suspended')
                        </span>
                    @endif
                </div>
            </div>    

            {{-- Back Button --}}
            <a
                href="{{ route('admin.customers.customers.index') }}"
                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
            >
                @lang('admin::app.customers.customers.view.back-btn')
            </a>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.view.filters.before') !!}

    {{-- Filters --}}
    <div class="flex gap-x-[4px] gap-y-[8px] items-center flex-wrap mt-[28px]">
        {{-- Address Create component --}}
        @include('admin::customers.addresses.create')

        {{-- Account Delete button --}}
        @if (bouncer()->hasPermission('customers.customers.delete'))
            <div 
                class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]"
                @click="$emitter.emit('open-confirm-modal', {
                    message: '@lang('admin::app.customers.customers.view.account-delete-confirmation')',

                    agree: () => {
                        this.$refs['delete-account'].submit()
                    }
                })"
            >
                <span class="icon-cancel text-[24px]"></span>

                @lang('admin::app.customers.customers.view.delete-account')

                {{-- Delete Customer Account --}}
                <form 
                    method="post"
                    action="{{ route('admin.customers.customers.delete', $customer->id) }}" 
                    ref="delete-account"
                >
                    @csrf
                </form>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.view.filters.after') !!}

    {{-- Content --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        {{-- Left Component --}}
        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

            {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.before') !!}

            {{-- Orders --}}
            <div class=" bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                @if ($totalOrderCount = count($customer->orders))
                    <div class=" p-[16px] flex justify-between">
                        {{-- Total Order Count --}}
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.orders', ['order_count' => $totalOrderCount])
                        </p>    

                        @php
                            $revenue = core()->currency($customer->orders
                                ->whereNotIn('status', ['canceled', 'closed'])
                                ->sum('grand_total'));
                        @endphp

                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.total-revenue', ['revenue' => $revenue])
                        </p>
                    </div>

                    {{-- Order Details --}}
                    <div class="table-responsive grid w-full">
                        @foreach ($customer->orders as $order)
                            <div class="flex justify-between items-center px-[16px] py-[16px] transition-all hover:bg-gray-50 dark:hover:bg-gray-950">
                                <div class="row grid grid-cols-3 w-full">
                                    <div class="flex gap-[10px]">
                                        <div class="flex flex-col gap-[6px]">
                                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                                @lang('admin::app.customers.customers.view.increment-id', ['increment_id' => $order->increment_id])
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                {{ $order->created_at }}
                                            </p>

                                            @switch($order->status)
                                                @case('processing')
                                                    <p class="label-active">
                                                        @lang('admin::app.customers.customers.view.processing')
                                                    </p>
                                                    @break

                                                @case('completed')
                                                    <p class="label-active">
                                                        @lang('admin::app.customers.customers.view.completed')
                                                    </p>
                                                    @break

                                                @case('pending')
                                                    <p class="label-pending">
                                                        @lang('admin::app.customers.customers.view.pending')
                                                    </p>
                                                    @break

                                                @case('canceled')
                                                    <p class="label-cancelled">
                                                        @lang('admin::app.customers.customers.view.canceled')
                                                    </p>
                                                    @break

                                                @case('closed')
                                                    <p class="label-closed">
                                                        @lang('admin::app.customers.customers.view.closed')
                                                    </p>
                                                    @break

                                            @endswitch
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-[6px]">
                                        {{-- Grand Total --}}
                                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                            {{ core()->currency($order->grand_total ) }}
                                        </p>

                                        {{-- Payment methods --}}   
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.customers.view.pay-by', ['payment_method' => core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title')])
                                        </p>

                                        {{-- Channel Code --}}
                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ $order->channel->code }}
                                        </p>
                                    </div>

                                    {{-- Order Address Details --}}
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800 dark:text-white">
                                            {{ $order->billingAddress->name }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ $order->billingAddress->email }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @if($order->billingAddress->address1)
                                                {{ $order->billingAddress->address1 }},
                                            @endif

                                            @if($order->billingAddress->city)
                                                {{ $order->billingAddress->city }},
                                            @endif

                                            @if($order->billingAddress->state)
                                                {{ $order->billingAddress->state  }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <a 
                                    href="{{ route('admin.sales.orders.view', $order->id) }}" 
                                    class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                >
                                </a>
                            </div>

                            <span class="block w-full border-b-[1px] dark:border-gray-800"></span>
                        @endforeach
                    </div>
                @else
                    {{-- Empty Container --}} 
                    <div class="p-[16px] flex justify-between">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.orders', ['order_count' => $totalOrderCount])
                        </p>
                    </div>

                    {{-- Order Details --}}
                    <div class="table-responsive grid w-full">
                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                            <!-- Placeholder Image -->
                            <img
                                src="{{ bagisto_asset('images/empty-placeholders/orders-empty.svg') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold"> 
                                    @lang('admin::app.customers.customers.view.empty-order')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.before') !!}

            {{-- Invoices --}}
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                @if ($totalInvoiceCount = count($customer->invoices))
                    {{--Invoice Count --}}
                    <p class="p-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.customers.customers.view.invoice', ['invoice_count' => $totalInvoiceCount])
                    </p>

                    {{-- Invoice Table --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left min-w-[800px]">
                            <thead class="text-[14px] text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 border-b-[1px] border-gray-200 dark:border-gray-800">
                                <tr>
                                    @foreach (['invoice-id', 'invoice-date', 'invoice-amount', 'order-id'] as $item)
                                        <th scope="col" class="px-6 py-[16px] font-semibold"> 
                                            @lang('admin::app.customers.customers.view.' . $item)
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            @foreach ($customer->invoices as $invoice)
                                <tbody>
                                    {{-- Invoice Details --}}
                                    <tr class="bg-white dark:bg-gray-900 border-b transition-all hover:bg-gray-50 dark:hover:bg-gray-950 dark:border-gray-800">
                                        <td class="px-6 py-[16px] text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.customers.view.invoice-id-prefix', ['invoice_id' => $invoice->id] )
                                        </td>

                                        <td class="px-6 py-[16px] text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                            {{ $invoice->created_at }}
                                        </td>

                                        <td scope="row" class="px-6 py-[16px] text-gray-600 dark:text-gray-300">
                                            {{ core()->currency($invoice->grand_total) }}
                                        </td>

                                        <td class="px-6 py-[16px] text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.customers.view.order-id-prefix', ['order_id' => $invoice->order_id] )
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                @else
                    {{-- Empty Container --}}
                    <div class="flex justify-between p-[16px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.invoice', ['invoice_count' => $totalInvoiceCount])
                        </p>
                    </div>

                    <div class="table-responsive grid w-full">
                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                            {{-- Placeholder Image --}}
                            <img
                                src="{{ bagisto_asset('images/settings/invoice.svg') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold"> 
                                    @lang('admin::app.customers.customers.view.empty-invoice')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.before') !!}

            {{-- Reviews --}}
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                @if($totalReviewsCount = count($customer->reviews) )
                    {{-- Reviews Count --}}
                    <p class=" p-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.customers.customers.view.reviews', ['review_count' => $totalReviewsCount])
                    </p>

                    @foreach($customer->reviews as $review)
                        {{-- Reviews Details --}}
                        <div class="grid gap-y-[16px] p-[16px] transition-all hover:bg-gray-50 dark:hover:bg-gray-950">
                            <div class="flex justify-start [&amp;>*]:flex-1">
                                <div class="flex flex-col gap-[6px]">
                                    {{-- Review Name --}}
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                        {{ $review->name }}
                                    </p>

                                    {{-- Product Name --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $review->product->name }}
                                    </p>

                                    {{-- Review Status --}}
                                    @switch($review->status)
                                        @case('approved')
                                            <p class="label-active">
                                                @lang('admin::app.customers.customers.view.approved')
                                            </p>
                                            @break

                                        @case('pending')
                                            <p class="label-pending">
                                                @lang('admin::app.customers.customers.view.pending')
                                            </p>
                                            @break

                                        @case('disapproved')
                                            <p class="label-cancelled">
                                                @lang('admin::app.customers.customers.view.disapproved')
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

                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $review->created_at }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.customers.customers.view.id', ['id' => $review->id])
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between gap-x-[16px] items-center">
                                <div class="flex flex-col gap-[6px]">
                                    {{-- Review Title --}}
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                        {{ $review->title }}
                                    </p>

                                    {{-- Review Comment --}}
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $review->comment }}
                                    </p>
                                </div>

                                <a 
                                    href="{{ route('admin.catalog.products.edit', $review->product->id) }}"
                                    class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                >
                                </a>
                            </div>
                        </div>

                        <span class="block w-full border-b-[1px] dark:border-gray-800"></span>
                    @endforeach    
                @else
                    {{-- Empty Invoice Container --}}
                    <div class="flex justify-between p-[16px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.reviews', ['review_count' => $totalReviewsCount])
                        </p>
                    </div>

                    <div class="table-responsive grid w-full">
                        <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                            {{-- Placeholder Image --}}
                            <img
                                src="{{ bagisto_asset('images/empty-placeholders/reviews-empty.svg') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold"> 
                                   @lang('admin::app.customers.customers.view.empty-review')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
          
            {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.before') !!}

            {{-- Notes Form --}}
            <div class="bg-white dark:bg-gray-900  rounded box-shadow">
                <p class="p-[16px] pb-0 text-[16px] text-gray-800 dark:text-white font-semibold">
                    @lang('admin::app.customers.customers.view.add-note')
                </p>

                <x-admin::form 
                    :action="route('admin.customer.note.store', $customer->id)"
                >
                    <div class="p-[16px]">
                        {{-- Note --}}
                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="note" 
                                id="note"
                                rules="required"
                                :label="trans('admin::app.customers.customers.view.note')"
                                :placeholder="trans('admin::app.customers.customers.view.note-placeholder')"
                                rows="3"
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
                    
                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600 "></span>
                    
                                <p class="flex gap-x-[4px] items-center cursor-pointer text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 font-semibold">
                                    @lang('admin::app.customers.customers.view.notify-customer')
                                </p>
                            </label>
                            
                            {{--Note Submit Button --}}
                            <button
                                type="submit"
                                class="secondary-button"
                            >
                                @lang('admin::app.customers.customers.view.submit-btn-title')
                            </button>
                        </div>
                    </div>
                </x-admin::form> 

                {{-- Notes List --}}
                <span class="block w-full border-b-[1px] dark:border-gray-800"></span>

                @foreach ($customer->notes as $note)
                    <div class="grid gap-[6px] p-[16px]">
                        <p class="text-[16px] text-gray-800 dark:text-white leading-6">
                            {{$note->note}}
                        </p>

                        {{-- Notes List Title and Time --}}
                        <p class="flex gap-2 text-gray-600 dark:text-gray-300 items-center">
                            @if ($note->customer_notified)
                                <span class="h-fit text-[24px] rounded-full icon-done text-blue-600 bg-blue-100"></span>  

                                @lang('admin::app.customers.customers.view.customer-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                            @else
                                <span class="h-fit text-[24px] rounded-full icon-cancel-1 text-red-600 bg-red-100"></span>

                                @lang('admin::app.customers.customers.view.customer-not-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                            @endif
                        </p>
                    </div>

                    <span class="block w-full border-b-[1px] dark:border-gray-800"></span>
                @endforeach
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.after') !!}

        </div>

        {{-- Right Component --}}
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.before') !!}

            {{-- Information --}}
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex w-[100%]">
                        <p class="w-[100%] p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                            @lang('admin::app.customers.customers.view.customer')
                        </p>
    
                        {{--Customer Edit Component --}}
                       @include('admin::customers.customers.edit', ['groups' => $groups])
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid gap-y-[10px]">
                        <p class="text-gray-800 font-semibold dark:text-white">
                            {{ $customer->first_name . " " . $customer->last_name }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.customers.customers.view.email', ['email' => $customer->email])
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.customers.customers.view.phone', ['phone' => $customer->phone ?? 'N/A'])
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.customers.customers.view.gender', ['gender' => $customer->gender ?? 'N/A'])
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.customers.customers.view.date-of-birth', ['dob' => $customer->date_of_birth ?? 'N/A'])
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.customers.customers.view.group', ['group_code' => $customer->group->code ?? 'N/A'])
                        </p>
                    </div>
                </x-slot:content>
            </x-admin::accordion> 

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.before') !!}

            {{-- Addresses listing--}}
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between p-[6px]">
                        <p class="text-gray-800 dark:text-white text-[16px] font-semibold">
                            @lang('admin::app.customers.customers.view.address', ['count' => count($customer->addresses)])
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    @if (count($customer->addresses))
                        @foreach ($customer->addresses as $index => $address)
                            <div class="grid gap-y-[10px]">
                                @if ( $address->default_address )
                                    <p class="label-pending">
                                        @lang('admin::app.customers.customers.view.default-address')
                                    </p>
                                @endif

                                <p class="text-gray-800 font-semibold dark:text-white">
                                    {{ $address->name }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $address->address1 }},

                                    @if ($address->address2)
                                        {{ $address->address2 }},
                                    @endif

                                    {{ $address->city }},
                                    {{ $address->state }},
                                    {{ core()->country_name($address->country) }}

                                    @if($address->postcode)
                                        ({{ $address->postcode }})
                                    @endif
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.customers.customers.view.phone', ['phone' => $address->phone ?? 'N/A'])
                                </p>

                                <div class="flex gap-[10px]">
                                    {{-- Edit Address --}}
                                    @include('admin::customers.addresses.edit')

                                    {{-- Delete Address --}}
                                    @if (bouncer()->hasPermission('customers.addresses.delete'))
                                        <p 
                                            class="text-blue-600 cursor-pointer transition-all hover:underline"
                                            @click="$emitter.emit('open-confirm-modal', {
                                                message: '@lang('admin::app.customers.customers.view.address-delete-confirmation')',

                                                agree: () => {
                                                    this.$refs['delete-address-{{ $address->id }}'].submit()
                                                }
                                            })"
                                        >
                                            @lang('admin::app.customers.customers.view.delete')
                                        </p>

                                        <form 
                                            method="post"
                                            action="{{ route('admin.customers.customers.addresses.delete', $address->id) }}"
                                            ref="delete-address-{{ $address->id }}" 
                                        >
                                            @csrf
                                        </form>
                                    @endif

                                    {{-- Set Default Address --}}
                                    @if (! $address->default_address )
                                        <p 
                                            class="text-blue-600 cursor-pointer transition-all hover:underline"
                                            onclick="event.preventDefault();
                                            document.getElementById('default-address{{ $address->id }}').submit();"
                                        >
                                            @lang('admin::app.customers.customers.view.set-as-default')
                                        </p>

                                        <form
                                            class="hidden"
                                            method="post"
                                            action="{{ route('admin.customers.customers.addresses.set_default', $customer->id) }}" 
                                            id="default-address{{ $address->id }}" 
                                        >
                                            @csrf

                                            <input
                                                type="text"
                                                name="set_as_default"
                                                value="{{ $address->id }}"
                                            />
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            @if ($index < count($customer->addresses) - 1)
                                <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] dark:border-gray-800"></span>
                            @endif
                        @endforeach
                    @else    
                        {{-- Empty Address Container --}}
                        <div class="flex gap-[20px] items-center py-[10px]">
                            <img
                                src="{{ bagisto_asset('images/settings/address.svg') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                            >

                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-400 font-semibold">
                                    @lang('admin::app.customers.customers.view.empty-title')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.customers.customers.view.empty-description')
                                </p>
                            </div>
                        </div>
                    @endif
                </x-slot:content>
            </x-admin::accordion>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.after') !!}

        </div>
    </div>
</x-admin::layouts>