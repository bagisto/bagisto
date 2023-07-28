<x-admin::layouts>
    <div class="grid">
        <div class="flex items-center cursor-pointer">
            <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-center w-full max-w-max rounded-[6px]">
                <span class="icon-sort-left text-[24px]"></span>
            </div>
            <p class="text-gray-600">Customers</p>
        </div>
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                {{ $customer->first_name . " " . $customer->last_name }}
            <div class="flex gap-x-[10px] items-center">
                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all hover:bg-gray-200">
                    <span class="icon-arrow-right text-[24px]"></span>
                </div>
                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all hover:bg-gray-200">
                    <span class="icon-arrow-left text-[24px]"></span>
                </div>
            </div>
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
                        <p class="text-[16px] text-gray-800 font-semibold">Orders</p>
                        <p class="text-[16px] text-gray-800 font-semibold">Total Revenue - {{ core()->currency($customer->orders->sum('grand_total')) }}</p>
                    </div>
                    <div class="table-responsive grid w-full">
                        @foreach ($customer->orders as $order)
                    
                            <div class="flex justify-between items-center px-[16px] py-[16px]">
                                <div class="row grid grid-cols-3 w-full">
                                    <div class="">
                                        <div class="flex gap-[10px]">
                                            <span class="icon-uncheckbox text-[24px]"></span>
                                            <div class="flex flex-col gap-[6px]">
                                                <p class="text-[16px] text-gray-800 font-semibold">#{{ $order->id }}</p>
                                                <p class="text-gray-600">{{ $order->created_at }}</p>
                                                <p class="label-pending">{{ $order->status }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="flex flex-col gap-[6px]">
                                            <p class="text-[16px] text-gray-800 font-semibold">{{ core()->currency($order->grand_total ) }}</p>
                                            <p class="text-gray-600">Pay by - {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</p>
                                            <p class="text-gray-600">{{ $order->channel->code }}</p>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="flex flex-col gap-[6px]">
                                            <p class="text-[16px] text-gray-800">{{ $order->billingAddress->name }}</p>
                                            <p class="text-gray-600">{{ $order->billingAddress->email }}</p>
                                            <p class="text-gray-600">{{ $order->billingAddress->address1 }},{{ $order->billingAddress->city }},{{ $order->billingAddress->state }}</p>
                                        </div>
                                    </div>
                                </div>
                                <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                            </div>
                        @endforeach
                        <!-- Body -->
                        <!-- single row -->
                    
                        <span class="border-b-[1px] border-gray-300"></span>

                    </div>
                </div>
            @endif

            
            {{-- Invoices --}}
            {{-- @if($totalInvoiceCount = count($invoices))
                <div class="bg-white rounded box-shadow">
                    <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">Invoice ({{ $totalInvoiceCount }})</p>
                    <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left min-w-[800px]">
                            <thead class="text-[14px] text-gray-600 bg-gray-50 border-b-[1px] border-gray-200  ">
                                <tr>
                                    <th scope="col" class="px-6 py-[16px] font-semibold"> Invoice Id  </th>
                                    <th scope="col" class="px-6 py-[16px] font-semibold"> Invoice Date </th>
                                    <th scope="col" class="px-6 py-[16px] font-semibold"> Invoice Amount </th>
                                    <th scope="col" class="px-6 py-[16px] font-semibold"> Order ID </th>
                                </tr>
                            </thead>
                            @foreach ($invoices as $invoice)
                                <tbody>
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
            @endif --}}

            {{-- Reviews --}}
            @if($totalReviewsCount = count($customer->reviews) )
                <div class="bg-white rounded box-shadow">
                    <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">Review ({{ $totalReviewsCount }})</p>
                    @foreach($customer->reviews as $review)
                        <div class="">
                            <div class="grid gap-y-[16px] p-[16px]">
                                <div class="flex justify-start [&amp;>*]:flex-1">
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">{{ $review->name }}</p>
                                        <p class="text-gray-600">{{ $review->product->name }}</p>
                                        <p class="label-pending">{{ $review->status }}</p>
                                    </div>
                                    <div class="flex flex-col gap-[6px]">

                                        <!--need to update @shivendra -->
                                        <div class="flex">
                                            <span class="icon-star text-[18px] text-amber-500"></span>
                                            <span class="icon-star text-[18px] text-amber-500"></span>
                                            <span class="icon-star text-[18px] text-amber-500"></span>
                                            <span class="icon-star text-[18px] text-amber-500"></span>
                                            <span class="icon-star text-[18px] text-gray-300"></span>
                                        </div>
                                        <p class="text-gray-600">{{ $review->created_at }}</p>
                                        <p class="text-gray-600">ID - {{ $review->id }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-x-[16px] items-center">
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">{{ $review->title }}</p>
                                        <p class="text-gray-600">{{ $review->comment }}</p>
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
                    Add Note 
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
                                    label="Note"
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
                                    Notify Customer
                                </p>
                            </label>
                            
                            <button
                                type="submit"
                                class="text-blue-600 font-semibold whitespace-nowrap px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] cursor-pointer"
                            >
                                Submit Note
                            </button>

                            
                        </div>
                    </div>
                </x-admin::form> 

                {{-- Notes List --}}
                <span class="block w-full border-b-[1px] border-gray-300"></span>

                @foreach ($customer->notes as $note)
                    <div class="grid gap-[6px] p-[16px]">
                        <p class="text-[16px] text-gray-800">{{$note->note}}</p>
                        <p class="text-gray-600">  
                            {{$note->created_at}}
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
                        Customer
                    </p>

                    <div class="flex gap-[6px] items-center justify-between">
                        <p class="text-blue-600 ">Edit</p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid gap-y-[10px]">
                        <div class="">
                            <p class="text-gray-800 font-semibold">{{ $customer->first_name . " " . $customer->last_name }}</p>
                            <p class="text-gray-600">Email- {{ $customer->email }}</p>
                            <p class="text-gray-600">Phone - {{ $customer->phone }}</p>
                        </div>
                        <div class="">
                            <p class="text-gray-600">Gender : {{ $customer->gender }}</p>
                            <p class="text-gray-600">DOB : {{ $customer->date_of_birth }}</p>
                        </div>
                        <div class="">
                            <p class="text-gray-600">Group- {{ $customer->group->code }}</p>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion> 

            <!-- component 2 -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Active Status</p>
                    <div class="flex gap-[6px] items-center">
                        <p class="text-blue-600">Edit</p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                        <div class="flex gap-[10px] p-[6px] items-center cursor-pointer hover:bg-gray-100 hover:rounded-[8px]">
                            <label 
                                class="flex gap-[10px] w-max items-center cursor-pointer select-none"
                                for="status" 
                            >
                                <input 
                                    type="checkbox" 
                                    name="status"
                                    id="status"
                                    value="{{ $customer->status }}" {{ $customer->status ? 'checked' : '' }}
                                    class="hidden peer"
                                >
                    
                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                    
                                <p class="text-gray-600 font-semibold cursor-pointer">
                                    Customer Status
                                </p>
                            </label>
                        </div>
                         
                        <div class="flex gap-[10px] p-[6px] items-center cursor-pointer hover:bg-gray-100 hover:rounded-[8px]">
                            <label 
                                class="flex gap-[10px] w-max items-center cursor-pointer select-none"
                                for="isSuspended"
                            >
                                <input 
                                    type="checkbox" 
                                    name="is_suspended"
                                    id="isSuspended"
                                    value="{{ $customer->is_suspended }}" {{ $customer->is_suspended ? 'checked' : '' }}
                                    class="hidden peer"
                                >
                    
                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                    
                                <p class="text-gray-600 font-semibold cursor-pointer">
                                    Suspend
                                </p>
                            </label>
                        </div>
                </x-slot:content>
            </x-admin::accordion>

            <!-- component 3 -->
            {!! view_render_event('bagisto.admin.customer.addresses.list.before') !!}
            
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between p-[6px]">
                        <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Address ({{ count($customer->addresses) }})</p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    @foreach ($customer->addresses as $address)
                        <div class="grid gap-y-[10px]">
                            @if( $address->default_address )
                                <p class="label-pending">Default Address</p>
                            @endif    
                            <div class="">
                                <p class="text-gray-800 font-semibold">{{$address->name}}</p>
                                <p class="text-gray-600">
                                    {{$address->address1}}
                                    {{$address->city}} 
                                    {{$address->state}} 
                                    {{$address->country}}
                                </p>
                            </div>
                            <div class="">
                                <p class="text-gray-600">Mobile : {{$address->phone}}</p>
                            </div>
                            <div class="flex gap-[10px]">
                                <p class="text-blue-600">Edit</p>
                                <p class="text-blue-600">Delete</p>
                                @if(! $address->default_address )
                                    <p 
                                        class="text-blue-600 cursor-pointer"
                                        onclick="event.preventDefault();
                                        document.getElementById('default-address').submit();"
                                    >
                                        Set as Default
                                    </p>

                                    <form 
                                        class="hidden"
                                        method="post"
                                        action="{{ route('admin.customer.addresses.set_default', $customer->id) }}" 
                                        id="default-address" 
                                    >
                                        @csrf
                                        <input type="text" name="set_as_default" value="{{ $address->id }}">
                                    </form>
                                @endif
                            </div>
                        </div>
                        <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] border-gray-300"></span>
                    @endforeach
                </x-slot:content>
            </x-admin::accordion>

            {!! view_render_event('bagisto.admin.customer.addresses.list.after') !!}
        </div>
    </div>

    @pushOnce('scripts')
        <!-- Customer Address Form -->
        <script type="text/x-template" id="v-create-customer-address-template">
            <div>
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Address Create Modal -->
                        <x-admin::modal ref="addressCreateModal">
                            <x-slot:toggle>
                                <!-- Address Create Button -->
                                @if (bouncer()->hasPermission('customers.addresses.create '))
                                    <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                                        <span class="icon-location text-[24px] "></span>
                                        Add New Address
                                    </div>
                                @endif
                            </x-slot:toggle>
            
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 font-bold">
                                    Create Customer's Address
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
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                        @lang('shop::app.customers.account.addresses.company-name')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="company_name"
                                                    :label="trans('shop::app.customers.account.addresses.company-name')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.company-name')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="company_name"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.vat-id')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="vat_id"
                                                    :label="trans('shop::app.customers.account.addresses.vat-id')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.vat-id')"
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
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.first-name')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="first_name"
                                                    rules="required"
                                                    :label="trans('shop::app.customers.account.addresses.first-name')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.first-name')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="first_name"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.last-name')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="last_name"
                                                    rules="required"
                                                    :label="trans('shop::app.customers.account.addresses.last-name')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.last-name')"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    control-name="last_name"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('shop::app.customers.account.addresses.street-address')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="address1[]"
                                            id="address_0"
                                            rules="required"
                                            :label="trans('shop::app.customers.account.addresses.street-address')"
                                            :placeholder="trans('shop::app.customers.account.addresses.street-address')"
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
                                                @lang('shop::app.customers.account.addresses.street-address')
                                            </x-admin::form.control-group.label>
                                    
                                            <x-admin::form.control-group.control
                                                type="text"
                                                :name="'address1[' + index + ']'"
                                                :id="'address_' + index"
                                                rules="required"
                                                :label="trans('shop::app.customers.account.addresses.street-address')"
                                                :placeholder="trans('shop::app.customers.account.addresses.street-address')"
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
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.city')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="city"
                                                    rules="required"
                                                    :label="trans('shop::app.customers.account.addresses.city')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.city')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="city"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.post-code')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="postcode"
                                                    rules="required|integer"
                                                    :label="trans('shop::app.customers.account.addresses.post-code')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.post-code')"
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
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.country')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="country"
                                                    rules="required"
                                                    :label="trans('shop::app.customers.account.addresses.country')"
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
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.state')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="state"
                                                    rules="required"
                                                    :label="trans('shop::app.customers.account.addresses.state')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.state')"
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
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.phone')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="phone"
                                                    rules="required|integer"
                                                    :label="trans('shop::app.customers.account.addresses.phone')"
                                                    :placeholder="trans('shop::app.customers.account.addresses.phone')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="phone"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                        <div class="w-full">
                                            <label 
                                                class="flex gap-[4px] w-max items-center p-[6px] cursor-pointer select-none mt-[10px]"
                                                for="default_address"
                                            >
                                                <input 
                                                    type="checkbox" 
                                                    name="default_address" 
                                                    id="default_address"
                                                    value="1"
                                                    class="hidden peer"
                                                >
                                    
                                                <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                    
                                                <p class="flex gap-x-[4px] items-center cursor-pointer">
                                                    Default Address
                                                </p>
                                            </label>
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
                                        @lang('Save Address')
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
    