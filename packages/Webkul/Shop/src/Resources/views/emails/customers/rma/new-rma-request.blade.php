@component('shop::emails.layout')
    <!-- Header Section -->
    <div style="margin-bottom: 40px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #121A26; margin: 0 0 20px 0;">
            @lang('shop::app.rma.mail.customer-rma-create.heading')
        </h1>
        
        <p style="font-size: 16px; color: #5E5E5E; line-height: 26px; margin: 0 0 16px 0;">
            @lang('shop::app.rma.mail.customer-rma-create.hello', ['name' => $rma->order->customer->name]), 👋
        </p>

        <p style="font-size: 16px; color: #5E5E5E; line-height: 26px; margin: 0;">
            @lang('shop::app.rma.mail.customer-rma-create.greeting', [
                'order_id' =>
                    '<a href="' .
                    route('shop.customers.account.orders.view', $rma->order_id) .
                    '" style="font-weight: 600; color: #2563eb; text-decoration: none;">#' .
                    $rma->order_id .
                    '</a>',
            ])
        </p>
    </div>

    <!-- Summary Section -->
    <div style="margin-bottom: 32px; padding: 24px; background-color: #f8fafc; border-radius: 8px; border-left: 4px solid #2563eb;">
        <h2 style="font-size: 18px; font-weight: 700; color: #121A26; margin: 0 0 20px 0;">
            @lang('shop::app.rma.mail.customer-rma-create.summary')
        </h2>

        <!-- Info Grid -->
        <div style="display: flex; gap: 32px; flex-wrap: wrap;">
            <!-- RMA ID -->
            <div style="flex: 1; min-width: 200px;">
                <p style="font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">
                    @lang('shop::app.rma.mail.customer-rma-create.rma-id')
                </p>
                <p style="font-size: 18px; font-weight: 700; color: #2563eb; margin: 0;">
                    {{ $rma->id }}
                </p>
            </div>

            <!-- Order ID -->
            <div style="flex: 1; min-width: 200px;">
                <p style="font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">
                    @lang('shop::app.rma.mail.customer-rma-create.order-id')
                </p>
                <p style="font-size: 18px; font-weight: 700; color: #121A26; margin: 0;">
                    #{{ $rma->order_id }}
                </p>
            </div>

            <!-- Order Status -->
            <div style="flex: 1; min-width: 200px;">
                <p style="font-size: 13px; font-weight: 600; color: #718096; text-transform: uppercase; margin: 0 0 8px 0; letter-spacing: 0.5px;">
                    @lang('shop::app.rma.mail.customer-rma-create.order-status')
                </p>
                @if ($rma->order_status == '1')
                    <span style="display: inline-block; padding: 6px 12px; background-color: #d1fae5; color: #065f46; border-radius: 4px; font-size: 14px; font-weight: 600;">
                        @lang('shop::app.rma.customer.delivered')
                    </span>
                @else
                    <span style="display: inline-block; padding: 6px 12px; background-color: #fecaca; color: #991b1b; border-radius: 4px; font-size: 14px; font-weight: 600;">
                        @lang('shop::app.rma.customer.undelivered')
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div style="margin-bottom: 32px; padding: 20px; background-color: #fff5f5; border-radius: 8px;">
        <h3 style="font-size: 16px; font-weight: 700; color: #121A26; margin: 0 0 12px 0;">
            @lang('shop::app.rma.mail.customer-rma-create.additional-information')
        </h3>
        <div style="font-size: 15px; color: #5E5E5E; line-height: 26px; margin: 0;">
            {!! $rma->information !!}
        </div>
    </div>

    <!-- Products Section -->
    <div style="margin-bottom: 40px;">
        <h2 style="font-size: 18px; font-weight: 700; color: #121A26; margin: 0 0 20px 0;">
            @lang('shop::app.rma.mail.customer-rma-create.requested-rma-product')
        </h2>

        <div style="width: 100%; overflow-x: auto;">
            <table style="margin-top: 8px; width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr>
                        @php($lang = Lang::get('shop::app.rma.mail.customer-data-table-heading'))
                        @foreach ($lang as $tableHeading)
                            <th style="background-color: #f7fafc; padding: 12px 16px; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">
                                {{ $tableHeading }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($rma->items as $key => $item)
                        <tr>
                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                {!! $item->orderItem->name !!}
                            </td>

                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                {!! $item->quantity !!}
                            </td>

                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                {!! $item->reason->title !!}
                            </td>

                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                {!! $item->orderItem->sku !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endcomponent
