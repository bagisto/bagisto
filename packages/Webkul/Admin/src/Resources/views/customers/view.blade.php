<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.customers.view.title')
    </x-slot:title>

    <div class="grid">
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                {{ $customer->first_name . " " . $customer->last_name }}

                {{-- Customer Status --}}
                @if($customer->status == 1)
                    <span class="label-pending text-[14px] mx-[5px]">
                        @lang('admin::app.customers.view.active')
                    </span>
                @else    
                    <span class="label-pending text-[14px] mx-[5px]">
                        @lang('admin::app.customers.view.inactive')
                    </span>
                @endif

                {{-- Customer Suspended Status --}}
                @if($customer->is_suspended == 1)
                    <span class="label-pending text-[14px]">
                        @lang('admin::app.customers.view.suspended')
                    </span>
                @endif
            </p>   
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex gap-x-[4px] gap-y-[8px] items-center flex-wrap mt-[28px]">
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-printer text-[24px] "></span> Print
        </div>
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-mail text-[24px] "></span> Email
        </div>
        
        {{--Address Create component --}}
        <v-create-customer-address></v-create-customer-address>
       
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-cart text-[24px] "></span> Create Order
        </div>
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-cancel text-[24px] "></span> Delete Account
        </div>
    </div>

    {{-- Content --}}
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

            {{-- Orders --}}
            @if($totalOrderCount = count($customer->orders))
                <div class=" bg-white rounded-[4px] box-shadow">
                    <div class=" p-[16px] flex justify-between">
                        {{-- Total Order Count --}}
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.customers.view.orders')({{ $totalOrderCount }})
                        </p>
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @lang('admin::app.customers.view.total-revenue')- {{ core()->currency($customer->orders->sum('grand_total')) }}
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

                                                <p class="label-pending">
                                                    {{ $order->status }}
                                                </p>
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
                        @endforeach

                        {{-- single row --}}
                        <span class="border-b-[1px] border-gray-300"></span>
                    </div>
                </div>
            @endif

            {{-- Invoices --}}
            @if($totalInvoiceCount = count($customer->invoices))
                <div class="bg-white rounded box-shadow">
                    {{--Invoice Count --}}
                    <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.customers.view.invoice') ({{ $totalInvoiceCount }})
                    </p>
                    {{-- Invoice Table --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left min-w-[800px]">
                            <thead class="text-[14px] text-gray-600 bg-gray-50 border-b-[1px] border-gray-200  ">
                                <tr>
                                    <th scope="col" class="px-6 py-[16px] font-semibold"> 
                                        @lang('admin::app.customers.view.invoice-id')  
                                    </th>

                                    <th scope="col" class="px-6 py-[16px] font-semibold"> 
                                        @lang('admin::app.customers.view.invoice-date')  
                                    </th>
                                    
                                    <th scope="col" class="px-6 py-[16px] font-semibold">
                                        @lang('admin::app.customers.view.invoice-amount') 
                                    </th>

                                    <th scope="col" class="px-6 py-[16px] font-semibold">
                                        @lang('admin::app.customers.view.order-id')  
                                    </th>
                                </tr>
                            </thead>
                            @foreach ($customer->invoices as $invoice)
                                <tbody>
                                    {{-- Invoice Details --}}
                                    <tr class="bg-white border-b ">
                                        <td class="px-6 py-[16px] text-gray-600">#{{ $invoice->id }}</td>
                                        <td class="px-6 py-[16px] text-gray-600 whitespace-nowrap">{{ $invoice->created_at }}</td>
                                        <td scope="row" class="px-6 py-[16px] text-gray-600">{{ core()->currency($invoice->grand_total) }}</td>
                                        <td class="px-6 py-[16px] text-gray-600">#{{ $invoice->order_id }}</td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif

            {{-- Reviews --}}
            @if($totalReviewsCount = count($customer->reviews) )
                <div class="bg-white rounded box-shadow">
                    {{-- Reviews Count --}}
                    <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.customers.view.reviews')({{ $totalReviewsCount }})
                    </p>

                    @foreach($customer->reviews as $review)
                        {{-- Reviews Details --}}
                        <div class="">
                            <div class="grid gap-y-[16px] p-[16px]">
                                <div class="flex justify-start [&amp;>*]:flex-1">
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            {{ $review->name }}
                                        </p>

                                        <p class="text-gray-600">
                                            {{ $review->product->name }}
                                        </p>
                                        
                                        <p class="label-pending">
                                            {{ $review->status }}
                                        </p>
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

                                <div class="flex gap-x-[16px] items-center">
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            {{ $review->title }}
                                        </p>

                                        <p class="text-gray-600">
                                            {{ $review->comment }}
                                        </p>
                                    </div>

                                    <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                                </div>
                            </div>
                            <span class="block w-full border-b-[1px] border-gray-300"></span>
                        </div>
                    @endforeach    
                </div>
            @endif
          
            {{-- Notes Form --}}
            <div class="bg-white rounded box-shadow">
                <p class=" p-[16px] pb-0 text-[16px] text-gray-800 font-semibold">
                    @lang('admin::app.customers.view.add-note')
                </p>

                <x-admin::form 
                    action="{{ route('admin.customer.note.store', $customer->id) }}"
                >
                    <div class="p-[16px]">
                        <div class="mb-[10px]">
                            <x-admin::form.control-group>
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
                        </div>

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
                    
                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                    
                                <p class="flex gap-x-[4px] items-center cursor-pointer">
                                    @lang('admin::app.customers.view.notify-customer')
                                </p>
                            </label>
                            
                            {{--Note Submit Button --}}
                            <button
                                type="submit"
                                class="text-blue-600 font-semibold whitespace-nowrap px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] cursor-pointer"
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

        {{-- Information --}}
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                        @lang('admin::app.customers.view.customer')
                    </p>

                    {{--Customer Edit Component --}}
                   @include('admin::customers.edit', ['groups' => $groups])
                </x-slot:header>

                <x-slot:content>
                    <div class="grid gap-y-[10px]">
                        <div class="">
                            <p class="text-gray-800 font-semibold">
                                {{ $customer->first_name . " " . $customer->last_name }}
                            </p>

                            <p class="text-gray-600">
                                @lang('admin::app.customers.view.email') - {{ $customer->email }}
                            </p>

                            <p class="text-gray-600">
                                @lang('admin::app.customers.view.phone') - {{ $customer->phone }}
                            </p>
                        </div>

                        <div class="">
                            <p class="text-gray-600">
                                @lang('admin::app.customers.view.gender') - {{ $customer->gender }}
                            </p>

                            <p class="text-gray-600">
                                @lang('admin::app.customers.view.date-of-birth') - {{ $customer->date_of_birth }}
                            </p>
                        </div>

                        <div class="">
                            <p class="text-gray-600">
                                @lang('admin::app.customers.view.group')- {{ $customer->group->code }}
                            </p>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion> 

            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between p-[6px]">
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">
                            @lang('admin::app.customers.view.address')
                            ({{ count($customer->addresses) }})
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    @if(count($customer->addresses))
                        @foreach ($customer->addresses as $address)
                            <div class="grid gap-y-[10px]">
                                @if( $address->default_address )
                                    <p class="label-pending">
                                        @lang('admin::app.customers.view.default-address')
                                    </p>
                                @endif

                                <div class="">
                                    <p class="text-gray-800 font-semibold">
                                        {{$address->name}}
                                    </p>

                                    <p class="text-gray-600">
                                        {{$address->address1}}
                                        {{$address->city}} 
                                        {{$address->state}} 
                                        {{$address->country}}
                                    </p>
                                </div>

                                <div class="">
                                    <p class="text-gray-600">
                                        @lang('admin::app.customers.view.phone') : {{$address->phone}}
                                    </p>
                                </div>

                                <div class="flex gap-[10px]">
                                    {{-- Edit Address --}}
                                    <p class="text-blue-600">
                                        @lang('admin::app.customers.view.edit')
                                    </p>

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
                                    @if(! $address->default_address )
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
                            <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] border-gray-300"></span>
                        @endforeach
                    @else    
                        {{-- Empty Address Container --}}
                        <div
                            class="flex gap-[20px] items-center py-[10px]"
                        >
                            <img
                                src="{{ bagisto_asset('images/icon-discount.svg') }}"
                                class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                            />

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

    {{-- Customer Address Modal --}}
    @pushOnce('scripts')
        <!-- Customer Address Form -->
        <script type="text/x-template" id="v-create-customer-address-template">
            <div>
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form 
                        @submit="handleSubmit($event, create)"
                        ref="addressForm"
                    >
                        <!-- Address Create Modal -->
                        <x-admin::modal ref="addressCreateModal">
                            <x-slot:toggle>
                                <!-- Address Create Button -->
                                @if (bouncer()->hasPermission('customers.addresses.create '))
                                    <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                                        <span class="icon-location text-[24px]"></span>
                                        @lang('admin::app.customers.view.create-address.create-address-btn')
                                    </div>
                                @endif
                            </x-slot:toggle>
            
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.customers.view.create-address.title')
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                {!! view_render_event('admin.customer.addresses.create.before') !!}

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="customer_id"
                                        :value="$customer->id"
                                    >
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>

                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <div class="w-full">
                                            <!-- Company Name -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                        @lang('admin::app.customers.view.create-address.company-name')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="company_name"
                                                    :label="trans('admin::app.customers.view.create-address.company-name')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.company-name')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="company_name"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <!-- Vat Id -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.vat-id')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="vat_id"
                                                    :label="trans('admin::app.customers.view.create-address.vat-id')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.vat-id')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="vat_id"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>

                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <div class="w-full">
                                            <!-- First Name -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.first-name')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="first_name"
                                                    rules="required"
                                                    :label="trans('admin::app.customers.view.create-address.first-name')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.first-name')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="first_name"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <!-- Last Name -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.last-name')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="last_name"
                                                    rules="required"
                                                    :label="trans('admin::app.customers.view.create-address.last-name')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.last-name')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="last_name"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>

                                    <!-- Street Address -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.view.create-address.street-address')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="address1[]"
                                            id="address_0"
                                            rules="required"
                                            :label="trans('admin::app.customers.view.create-address.street-address')"
                                            :placeholder="trans('admin::app.customers.view.create-address.street-address')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="address1[]"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!--need to check this -->
                                    @if (
                                        core()->getConfigData('customer.address.information.street_lines')
                                        && core()->getConfigData('customer.address.information.street_lines') > 1
                                    )
                                        <div v-for="(address, index) in addressLines" :key="index">
                                            <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.customers.view.create-address.street-address')
                                            </x-admin::form.control-group.label>
                                    
                                            <x-admin::form.control-group.control
                                                type="text"
                                                :name="'address1[' + index + ']'"
                                                :id="'address_' + index"
                                                rules="required"
                                                :label="trans('admin::app.customers.view.create-address.street-address')"
                                                :placeholder="trans('admin::app.customers.view.create-address.street-address')"
                                            >
                                            </x-admin::form.control-group.control>
                                    
                                            <x-admin::form.control-group.error
                                                :control-name="'address1[' + index + ']'"
                                            >
                                            </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    @endif

                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <div class="w-full">
                                            <!-- City -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.city')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="city"
                                                    rules="required"
                                                    :label="trans('admin::app.customers.view.create-address.city')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.city')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="city"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <!-- PostCode -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.post-code')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="postcode"
                                                    rules="required|integer"
                                                    :label="trans('admin::app.customers.view.create-address.post-code')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.post-code')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="postcode"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>

                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <div class="w-full">
                                            <!-- Country Name -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.country')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="country"
                                                    rules="required"
                                                    :label="trans('admin::app.customers.view.create-address.country')"
                                                >
                                                    <option value="">@lang('Select Country')</option>

                                                    @foreach (core()->countries() as $country)
                                                        <option 
                                                            {{ $country->code === config('app.default_country') ? 'selected' : '' }}  
                                                            value="{{ $country->code }}"
                                                        >
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="country"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <!-- State Name -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.state')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="state"
                                                    rules="required"
                                                    :label="trans('admin::app.customers.view.create-address.state')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.state')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="state"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>

                                    <div class="flex gap-[16px] max-sm:flex-wrap items-center">
                                        <div class="w-full">
                                            <!--Phone number -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.customers.view.create-address.phone')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="phone"
                                                    rules="required|integer"
                                                    :label="trans('admin::app.customers.view.create-address.phone')"
                                                    :placeholder="trans('admin::app.customers.view.create-address.phone')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="phone"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        
                                        <div class="w-full">
                                            <!-- Default Address -->
                                            <x-admin::form.control-group class="flex gap-[10px] mt-[20px]">
                                                <x-admin::form.control-group.control
                                                    type="checkbox"
                                                    name="default_address"
                                                    :value="1"
                                                    id="default_address"
                                                    :label="trans('admin::app.customers.view.create-address.default-address')"
                                                    :checked="false"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.label 
                                                    for="default_address"
                                                    class="text-gray-600 font-semibold cursor-pointer" 
                                                >
                                                    @lang('admin::app.customers.view.create-address.default-address')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.error
                                                    control-name="default_address"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                                </div>

                                {!! view_render_event('bagisto.admin.customers.create.after') !!}
                            </x-slot:content>
            
                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-[10px] items-center">
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.customers.view.create-address.save-btn-title') 
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-customer-address', {
                template: '#v-create-customer-address-template',

                props: {
                    addressLines: {
                        type: Number,
                        default: 0, // Default to 0 if no data is provided
                    }
                },

                methods: {

                    create(params, { resetForm, setErrors }) {
                        this.$axios.post('{{ route("admin.customer.addresses.store", $customer->id) }}', params,
                            {
                                headers: {
                                'Content-Type': 'multipart/form-data'
                                }
                            }
                            )
                        
                            .then((response) => {
                                this.$refs.addressCreateModal.toggle();

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
    