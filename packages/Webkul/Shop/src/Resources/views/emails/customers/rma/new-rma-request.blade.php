@component('shop::emails.layout')
    <!-- heading -->
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px; font-weight: 600; color: #121A26;">
            @lang('shop::app.rma.mail.customer-rma-create.heading') !
        </span><br>

        <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
            @lang('shop::app.rma.mail.customer-rma-create.hello', ['name' => $customerRmaData['name']]),👋
        </p>

        <p style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
            @lang('shop::app.rma.mail.customer-rma-create.greeting', [
                'order_id' =>
                    '<a href="' .
                    route('shop.customers.account.orders.view', $customerRmaData['order_id']) .
                    '" style="font-weight: bold; color: #2563eb; text-decoration: underline;">#' .
                    $customerRmaData['order_id'] .
                    '</a>',
            ])
        </p>

        <!-- summary -->
        <div style="margin-bottom: 10px; font-size: 18px; font-weight: bold; color: #2d3748;">
            @lang('shop::app.rma.mail.customer-rma-create.summary')
        </div>

        <!-- RMA id -->
        <div style="margin-bottom: 20px; margin-top: 20px; display: flex; flex-direction: row; justify-content: space-between;">
            <div style="line-height: 25px;">
                <!-- RMA ID -->
                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">
                    @lang('shop::app.rma.mail.customer-rma-create.rma-id')

                    <span style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                        {{ $customerRmaData['rma_id'] }}
                    </span>
                </div>

                <!-- order status -->
                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">
                    @if ($customerRmaData['order_status'] == '1')
                        @lang('shop::app.rma.mail.customer-rma-create.order-status')

                        <span style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                            @lang('shop::app.rma.customer.delivered')
                        </span>
                    @else
                        @lang('shop::app.rma.mail.customer-rma-create.order-status')

                        <span style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                            @lang('shop::app.rma.customer.undelivered')
                        </span>
                    @endif
                </div>
            </div>

            <div style="line-height: 25px;">
                <!-- order Id -->
                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">
                    @lang('shop::app.rma.mail.customer-rma-create.order-id')

                    <span style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                        {{ $customerRmaData['order_id'] }}
                    </span>
                </div>

                <!-- Resolution Type -->
                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">
                    @lang('shop::app.rma.mail.customer-rma-create.resolution-type')

                    <span style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                        @foreach ($customerRmaData['resolution_type'] as $resolution)
                            {{ $resolution }}
                        @endforeach
                    </span>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div style="margin-bottom: 5px; font-size: 16px; font-weight: bold; color: #2d3748;">
            @lang('shop::app.rma.mail.customer-rma-create.additional-information')

            <span style="font-size: 16px; color: #5E5E5E; line-height: 24px;">
                {!! $customerRmaData['information'] !!}
            </span>
        </div>

        <div style="margin-top: 10px; font-size: 16px; font-weight: bold; color: #2d3748;">
            @lang('shop::app.rma.mail.customer-rma-create.requested-rma-product')
        </div>
        <br/>

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
                    @foreach ($customerRmaData['order_items'] as $key => $ordered_item)
                        <tr>
                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                {!! $ordered_item->name !!}
                            </td>

                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                @foreach ($customerRmaData['rma_qty'] as $rmaQty)
                                    {!! $rmaQty !!}
                                @endforeach
                            </td>

                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                @if (count($customerRmaData['reason']) > 1)
                                    {!! $customerRmaData['reason'][$key] !!}
                                @else
                                    {!! $customerRmaData['reason'][0] !!}
                                @endif
                            </td>

                            <td style="border-bottom: 1px solid #e2e8f0; padding: 12px 16px; color: #4a5568;">
                                @if ($ordered_item->type == 'configurable')
                                    @foreach ($customerRmaData['skus'] as $sku)
                                        @if ($sku['parent_id'] == $ordered_item->id)
                                            {!! $sku['sku'] !!}
                                        @endif
                                    @endforeach
                                @else
                                    {!! $ordered_item->sku !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div><br><br><br>
    </div>
@endcomponent
