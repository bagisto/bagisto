<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.customers.customers.view.title')
    </x-slot>

    <div class="grid">
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <div class="flex gap-2.5 items-center">
                <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                    {{ $customer->first_name . " " . $customer->last_name }}
                </p>
                
                <div>
                    <v-customer-status />
                </div>
            </div>    

            <!-- Back Button -->
            <a
                href="{{ route('admin.customers.customers.index') }}"
                class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
            >
                @lang('admin::app.customers.customers.view.back-btn')
            </a>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.view.filters.before') !!}

    <!-- Filters -->
    <div class="flex gap-x-1 gap-y-2 items-center flex-wrap mt-7">
        <!-- Address Create component -->
        @include('admin::customers.addresses.create')

        <!-- Account Delete button -->
        @if (bouncer()->hasPermission('customers.customers.delete'))
            <div 
                class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                @click="$emitter.emit('open-confirm-modal', {
                    message: '@lang('admin::app.customers.customers.view.account-delete-confirmation')',

                    agree: () => {
                        this.$refs['delete-account'].submit()
                    }
                })"
            >
                <span class="icon-cancel text-2xl"></span>

                @lang('admin::app.customers.customers.view.delete-account')

                <!-- Delete Customer Account -->
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

    <!-- Content -->
    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <!-- Left Component -->
        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">

            {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.before') !!}

            @php $orders = $customer->orders(); @endphp

            <!-- Orders -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                @if ($totalOrderCount = $orders->count())
                    <div class="p-4 flex justify-between">
                        <!-- Total Order Count -->
                        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.orders', ['order_count' => $totalOrderCount])
                        </p>    

                        @php
                            $revenue = core()->formatBasePrice($orders->get()
                                ->whereNotIn('status', ['canceled', 'closed'])
                                ->sum('base_grand_total_invoiced'));
                        @endphp

                        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.total-revenue', ['revenue' => $revenue])
                        </p>
                    </div>

                    <!-- Order Details -->
                    <div class="table-responsive grid w-full">
                        @foreach ($orders->paginate(10) as $order)
                            <div class="flex justify-between items-center px-4 py-4 transition-all hover:bg-gray-50 dark:hover:bg-gray-950">
                                <div class="row grid grid-cols-3 w-full">
                                    <div class="flex gap-2.5">
                                        <div class="flex flex-col gap-1.5">
                                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                                @lang('admin::app.customers.customers.view.increment-id', ['increment_id' => $order->increment_id])
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                {{ $order->created_at }}
                                            </p>

                                            @switch($order->status)
                                                @case('processing')
                                                    <p class="label-processing">
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
                                                    <p class="label-canceled">
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

                                    <div class="flex flex-col gap-1.5">
                                        <!-- Grand Total -->
                                        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                            {{ core()->formatBasePrice($order->base_grand_total ) }}
                                        </p>

                                        <!-- Payment methods -->   
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.customers.view.pay-by', ['payment_method' => core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title')])
                                        </p>

                                        <!-- Channel Code -->
                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ $order?->channel_name }}
                                        </p>                                        
                                    </div>

                                    <!-- Order Address Details -->
                                    @if($order->billingAddress)
                                        <div class="flex flex-col gap-1.5">
                                            <p class="text-base text-gray-800 dark:text-white">
                                                {{ $order->billingAddress->name }}
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                {{ $order->billingAddress->email }}
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                {{
                                                    collect([
                                                        $order->billingAddress->address1,
                                                        $order->billingAddress->city,
                                                        $order->billingAddress->state,
                                                    ])
                                                    ->filter(fn ($string) =>! empty($string))
                                                    ->join(', ')
                                                }}
                                            </p>                                        
                                        </div>
                                    @endif
                                </div>

                                <a 
                                    href="{{ route('admin.sales.orders.view', $order->id) }}" 
                                    class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                >
                                </a>
                            </div>

                            <span class="block w-full border-b dark:border-gray-800"></span>
                        @endforeach
                    </div>

                    @php $pagination = $orders->paginate(10)->toArray(); @endphp

                    <!-- Pagination -->
                    @if ($totalOrderCount > 10)
                        <div class="flex gap-x-2 items-center p-4 border-t dark:border-gray-800">
                            <div
                                class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 py-1.5 px-2 leading-6 text-center w-full max-w-max bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black max-sm:hidden" 
                            >
                                {{ $pagination['per_page'] }}
                            </div>
    
                            <span class="text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                @lang('admin::app.customers.customers.view.per-page')
                            </span>
    
                            <p
                                class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 py-1.5 px-2 leading-6 text-center w-full max-w-max bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black max-sm:hidden"
                            >
                                {{ $pagination['current_page'] }}
                            </p>
    
                            <span class="text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                @lang('admin::app.customers.customers.view.of')
                            </span>
    
                            <span class="text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                {{ $pagination['last_page'] }}
                            </span>
    
                            <!-- Prev & Next Page Button -->
                            <div class="flex gap-1 items-center">
                                <a href="{{ $pagination['first_page_url'] }}">
                                    <div class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 p-1.5 text-center w-full max-w-max bg-white dark:bg-gray-900 border rounded-md dark:border-gray-800 cursor-pointer transition-all hover:border hover:bg-gray-100 dark:hover:bg-gray-950 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
                                        <span class="icon-sort-left text-2xl"></span>
                                    </div>
                                </a>
    
                                <a href="{{ $pagination['next_page_url'] }}">
                                    <div class="inline-flex gap-x-1 items-center justify-between ltr:ml-2 rtl:mr-2 text-gray-600 dark:text-gray-300 p-1.5 text-center w-full max-w-max bg-white dark:bg-gray-900 border rounded-md dark:border-gray-800 cursor-pointer transition-all hover:border hover:bg-gray-100 dark:hover:bg-gray-950 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black">
                                        <span class="icon-sort-right text-2xl"></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty Container --> 
                    <div class="p-4 flex justify-between">
                        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.orders', ['order_count' => $totalOrderCount])
                        </p>
                    </div>

                    <!-- Order Details -->
                    <div class="table-responsive grid w-full">
                        <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
                            <!-- Placeholder Image -->
                            <img
                                src="{{ bagisto_asset('images/empty-placeholders/orders.svg') }}"
                                class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-base text-gray-400 font-semibold"> 
                                    @lang('admin::app.customers.customers.view.empty-order')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.orders.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.before') !!}

            <!-- Invoices -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                @if ($totalInvoiceCount = count($customer->invoices))
                    <!--Invoice Count -->
                    <p class="p-4 text-base text-gray-800 leading-none dark:text-white font-semibold">
                        @lang('admin::app.customers.customers.view.invoice', ['invoice_count' => $totalInvoiceCount])
                    </p>

                    <!-- Invoice Table -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left min-w-[800px]">
                            <thead class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                                <tr>
                                    @foreach (['invoice-id', 'invoice-date', 'invoice-amount', 'order-id'] as $item)
                                        <th scope="col" class="px-6 py-4 font-semibold"> 
                                            @lang('admin::app.customers.customers.view.' . $item)
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            @foreach ($customer->invoices as $invoice)
                                <tbody>
                                    <!-- Invoice Details -->
                                    <tr class="bg-white dark:bg-gray-900 border-b transition-all hover:bg-gray-50 dark:hover:bg-gray-950 dark:border-gray-800">
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.customers.view.invoice-id-prefix', ['invoice_id' => $invoice->id] )
                                        </td>

                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                            {{ $invoice->created_at }}
                                        </td>

                                        <td scope="row" class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                            {{ core()->formatBasePrice($invoice->base_grand_total) }}
                                        </td>

                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                            @lang('admin::app.customers.customers.view.order-id-prefix', ['order_id' => $invoice->order_id] )
                                        </td>

                                        <td class="text-center">
                                            <a 
                                                href="{{ route('admin.sales.invoices.view', $invoice->id) }}" 
                                                class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                                role="presentation"
                                            >
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                @else
                    <!-- Empty Container -->
                    <div class="flex justify-between p-4">
                        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.invoice', ['invoice_count' => $totalInvoiceCount])
                        </p>
                    </div>

                    <div class="table-responsive grid w-full">
                        <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
                            <!-- Placeholder Image -->
                            <img
                                src="{{ bagisto_asset('images/settings/invoice.svg') }}"
                                class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-base text-gray-400 font-semibold"> 
                                    @lang('admin::app.customers.customers.view.empty-invoice')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.invoices.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.before') !!}

            <!-- Reviews -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                @if($totalReviewsCount = count($customer->reviews))
                    <!-- Reviews Count -->
                    <p class="p-4 text-base text-gray-800 leading-none dark:text-white font-semibold">
                        @lang('admin::app.customers.customers.view.reviews', ['review_count' => $totalReviewsCount])
                    </p>

                    @foreach($customer->reviews as $review)
                        <!-- Reviews Details -->
                        <div class="grid gap-y-4 p-4 transition-all hover:bg-gray-50 dark:hover:bg-gray-950">
                            <div class="flex justify-start [&amp;>*]:flex-1">
                                <div class="flex flex-col gap-1.5">
                                    <!-- Review Name -->
                                    <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                        {{ $review->name }}
                                    </p>

                                    <!-- Product Name -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $review->product->name }}
                                    </p>

                                    <!-- Review Status -->
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
                                            <p class="label-canceled">
                                                @lang('admin::app.customers.customers.view.disapproved')
                                            </p>
                                            @break

                                    @endswitch
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <!-- need to update shivendra-webkul -->
                                    <div class="flex">
                                        <x-admin::star-rating 
                                            :is-editable="false"
                                            :value="$review->rating"
                                        />
                                    </div>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $review->created_at }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.customers.customers.view.id', ['id' => $review->id])
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between gap-x-4 items-center">
                                <div class="flex flex-col gap-1.5">
                                    <!-- Review Title -->
                                    <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                        {{ $review->title }}
                                    </p>

                                    <!-- Review Comment -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $review->comment }}
                                    </p>
                                </div>

                                <a 
                                    href="{{ route('admin.catalog.products.edit', $review->product->id) }}"
                                    class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                >
                                </a>
                            </div>
                        </div>

                        <span class="block w-full border-b dark:border-gray-800"></span>
                    @endforeach
                @else
                    <!-- Empty Invoice Container -->
                    <div class="flex justify-between p-4">
                        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                            @lang('admin::app.customers.customers.view.reviews', ['review_count' => $totalReviewsCount])
                        </p>
                    </div>

                    <div class="table-responsive grid w-full">
                        <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
                            <!-- Placeholder Image -->
                            <img
                                src="{{ bagisto_asset('images/empty-placeholders/reviews.svg') }}"
                                class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                            />

                            <div class="flex flex-col items-center">
                                <p class="text-base text-gray-400 font-semibold"> 
                                   @lang('admin::app.customers.customers.view.empty-review')
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
          
            {!! view_render_event('bagisto.admin.customers.customers.view.card.reviews.after') !!}

            {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.before') !!}

            <!-- Notes Form -->
            <div class="bg-white dark:bg-gray-900  rounded box-shadow">
                <p class="p-4 pb-0 text-base text-gray-800 leading-none dark:text-white font-semibold">
                    @lang('admin::app.customers.customers.view.add-note')
                </p>

                <x-admin::form 
                    :action="route('admin.customer.note.store', $customer->id)"
                >
                    <div class="p-4">
                        <!-- Note -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.control
                                type="textarea"
                                id="note"
                                name="note" 
                                rules="required"
                                :label="trans('admin::app.customers.customers.view.note')"
                                :placeholder="trans('admin::app.customers.customers.view.note-placeholder')"
                                rows="3"
                            />

                            <x-admin::form.control-group.error control-name="note" />
                        </x-admin::form.control-group>

                        <div class="flex justify-between items-center">
                            <label 
                                class="flex gap-1 w-max items-center p-1.5 cursor-pointer select-none"
                                for="customer_notified"
                            >
                                <input 
                                    type="checkbox" 
                                    name="customer_notified"
                                    id="customer_notified"
                                    value="1"
                                    class="hidden peer"
                                >
                    
                                <span class="icon-uncheckbox rounded-md text-2xl cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"></span>
                    
                                <p class="flex gap-x-1 items-center cursor-pointer text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 font-semibold">
                                    @lang('admin::app.customers.customers.view.notify-customer')
                                </p>
                            </label>
                            
                            <!--Note Submit Button -->
                            <button
                                type="submit"
                                class="secondary-button"
                            >
                                @lang('admin::app.customers.customers.view.submit-btn-title')
                            </button>
                        </div>
                    </div>
                </x-admin::form> 

                <!-- Notes List -->
                <span class="block w-full border-b dark:border-gray-800"></span>

                @foreach ($customer->notes as $note)
                    <div class="grid gap-1.5 p-4">
                        <p class="text-base text-gray-800 dark:text-white leading-6">
                            {{$note->note}}
                        </p>

                        <!-- Notes List Title and Time -->
                        <p class="flex gap-2 text-gray-600 dark:text-gray-300 items-center">
                            @if ($note->customer_notified)
                                <span class="h-fit text-2xl rounded-full icon-done text-blue-600 bg-blue-100"></span>  

                                @lang('admin::app.customers.customers.view.customer-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                            @else
                                <span class="h-fit text-2xl rounded-full icon-cancel-1 text-red-600 bg-red-100"></span>

                                @lang('admin::app.customers.customers.view.customer-not-notified', ['date' => core()->formatDate($note->created_at, 'Y-m-d H:i:s a')])
                            @endif
                        </p>
                    </div>

                    <span class="block w-full border-b dark:border-gray-800"></span>
                @endforeach
            </div>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.notes.after') !!}

        </div>

        <!-- Right Component -->
        <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.before') !!}

            <!-- Information -->
            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.customer.after') !!}

            <v-customer-details ref="customerDetails"></v-customer-details>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.before') !!}

            <!-- Addresses listing-->
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between p-1.5">
                        <p class="text-gray-800 dark:text-white text-base  font-semibold">
                            @lang('admin::app.customers.customers.view.address', ['count' => count($customer->addresses)])
                        </p>
                    </div>
                </x-slot>

                <x-slot:content>
                    @if (count($customer->addresses))
                        @foreach ($customer->addresses as $index => $address)
                            <div class="grid gap-y-2.5">
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

                                <div class="flex gap-2.5">
                                    <!-- Edit Address -->
                                    @include('admin::customers.addresses.edit')

                                    <!-- Delete Address -->
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

                                    <!-- Set Default Address -->
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
                                <span class="block w-full mb-4 mt-4 border-b dark:border-gray-800"></span>
                            @endif
                        @endforeach
                    @else    
                        <!-- Empty Address Container -->
                        <div class="flex gap-5 items-center py-2.5">
                            <img
                                src="{{ bagisto_asset('images/settings/address.svg') }}"
                                class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                            >

                            <div class="flex flex-col gap-1.5">
                                <p class="text-base text-gray-400 font-semibold">
                                    @lang('admin::app.customers.customers.view.empty-title')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.customers.customers.view.empty-description')
                                </p>
                            </div>
                        </div>
                    @endif
                </x-slot>
            </x-admin::accordion>

            {!! view_render_event('bagisto.admin.customers.customers.view.card.accordion.address.after') !!}

        </div>
    </div>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-customer-details-template"
        >
           <div>
                <x-admin::accordion ref="customerData">
                    <x-slot:header>
                        <div class="flex w-full">
                            <p class="w-full p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                @lang('admin::app.customers.customers.view.customer')
                            </p>
        
                            <!--Customer Edit Component -->
                            @include('admin::customers.customers.edit')
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="grid gap-y-2.5">
                            <p class="text-gray-800 font-semibold dark:text-white">
                                @{{ customer.first_name + ' ' + customer.last_name }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.view.email')".replace(':email', customer.email) }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.view.phone')".replace(':phone', customer.phone) }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.view.gender')".replace(':gender', customer.gender ?? 'N/A') }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.view.date-of-birth')".replace(':dob', customer.date_of_birth ?? 'N/A') }}
                            </p>
                            
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.view.group')".replace(':group_code', customer.group?.name ?? 'N/A') }}
                            </p>
                        </div>
                    </x-slot:content>
                </x-admin::accordion> 
           </div>
        </script>

        <script
            type="text/x-template"
            id="v-customer-status-template"
        >
            <!-- Customer Status -->
            <template v-if="customer">
                <span 
                    v-if="customer.status == 1"
                    class="label-active text-sm mx-1.5"
                >
                    @lang('admin::app.customers.customers.view.active')
                </span>

                <span
                    v-else
                    class="label-canceled text-sm mx-1.5"
                >
                    @lang('admin::app.customers.customers.view.inactive')
                </span>

                <!-- Customer Suspended Status -->
                <span
                    v-if="customer.is_suspended"
                    class="label-canceled text-sm"
                >
                    @lang('admin::app.customers.customers.view.suspended')
                </span>
            </template>
        </script>

        <script type="module">
            app.component('v-customer-status', {
                template: '#v-customer-status-template',

                data() {
                    return {
                        customer: null,
                    };
                },

                created() {
                    this.$emitter.on('customer-update', (customer) => this.customer = customer);
                },
            })
        </script>

        <script type="module">
            app.component('v-customer-details', {
                template: '#v-customer-details-template',

                data() {
                    return {
                        customer: {},
                    };
                },

                mounted() {
                    this.get();
                }, 

                methods: {
                    get() {
                        this.$axios.get('{{ route('admin.customers.customers.view', $customer->id) }}')
                            .then((response) => {
                                this.customer = response.data.customer;

                                this.$emitter.emit('customer-update', this.customer);
                            })
                            .catch((error) => {
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>