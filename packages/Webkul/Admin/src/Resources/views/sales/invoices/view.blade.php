@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.invoices.view-title', ['invoice_id' => $invoice->id]) }}
@stop

@section('content')

    <?php $order = $invoice->order; ?>

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {!! view_render_event('sales.invoice.title.before', ['order' => $order]) !!}

                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ route('admin.dashboard.index') }}';"></i>
                    {{ __('admin::app.sales.invoices.view-title', ['invoice_id' => $invoice->id]) }}

                    {!! view_render_event('sales.invoice.title.after', ['order' => $order]) !!}
                </h1>

                @if($invoice->state == 'paid')
                        <small><span class="badge badge-md badge-success">{{ __('admin::app.sales.orders.invoice-status-paid') }}</span></small>
                    @elseif($invoice->state == 'pending')
                        <span class="badge badge-md badge-warning">{{ __('admin::app.sales.orders.invoice-status-pending') }}</span>
                    @else
                        <span class="badge badge-md badge-danger">{{ __('admin::app.sales.orders.invoice-status-overdue') }}</span>
                @endif
            </div>

            <div class="page-action">
                {!! view_render_event('sales.invoice.page_action.before', ['order' => $order]) !!}

                <a href="{{ route('admin.sales.invoices.print', $invoice->id) }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.sales.invoices.print') }}
                </a>

                @if($invoice->state == "pending" || $invoice->state == "overdue")
                    <a href="#" id="ChangeStatus" class="btn btn-lg btn-primary" @click="showModal('changeInvoiceState')">{{ __('admin::app.sales.orders.invoices-change-title') }}</a>
                @endif

                {!! view_render_event('sales.invoice.page_action.after', ['order' => $order]) !!}
            </div>
        </div>

        <div class="page-content">
            <div class="sale-container">

                <accordian :title="'{{ __('admin::app.sales.orders.order-and-account') }}'" :active="true">
                    <div slot="body" style="display: flex; overflow:auto;">

                        <div class="sale-section">
                            <div class="secton-title">
                                <span>{{ __('admin::app.sales.orders.order-info') }}</span>
                            </div>

                            <div class="section-content">
                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.invoices.order-id') }}</span>
                                    <span class="value">
                                        <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                                    </span>
                                </div>

                                {!! view_render_event('sales.invoice.increment_id.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.order-date') }}</span>
                                    <span class="value">{{ $order->created_at }}</span>
                                </div>

                                {!! view_render_event('sales.invoice.created_at.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.order-status') }}</span>
                                    <span class="value">{{ $order->status_label }}</span>
                                </div>

                                {!! view_render_event('sales.invoice.status_label.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.channel') }}</span>
                                    <span class="value">{{ $order->channel_name }}</span>
                                </div>

                                {!! view_render_event('sales.invoice.channel_name.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.payment-method') }}</span>
                                    <span class="value">{{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</span>
                                </div>

                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.shipping-method') }}</span>
                                    <span class="value">{{ $order->shipping_title }}</span>
                                </div>
                                {!! view_render_event('sales.invoice.shipping-method.after', ['order' => $order]) !!}
                            </div>
                        </div>

                        <div class="sale-section" style="margin: 0 0 0 300px;">
                            <div class="secton-title">
                                <span>{{ __('admin::app.sales.orders.account-info') }}</span>
                            </div>

                            <div class="section-content">
                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.customer-name') }}</span>
                                    <span class="value">{{ $invoice->order->customer_full_name }}</span>
                                </div>

                                {!! view_render_event('sales.invoice.customer_name.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.email') }}</span>
                                    <span class="value">{{ $invoice->order->customer_email }}</span>
                                </div>

                                {!! view_render_event('sales.invoice.customer_email.after', ['order' => $order]) !!}
                            </div>
                        </div>

                    </div>
                </accordian>

                @if ($order->billing_address || $order->shipping_address)
                    <accordian :title="'{{ __('admin::app.sales.orders.address') }}'" :active="true">
                        <div slot="body" style="display: flex; overflow:auto;">

                            @if ($order->billing_address)
                                <div class="sale-section">
                                    <div class="secton-title" style="width: 380px;">
                                        <span>{{ __('admin::app.sales.orders.billing-address') }}</span>
                                    </div>

                                    <div class="section-content" style="width: 380px;">
                                        @include ('admin::sales.address', ['address' => $order->billing_address])

                                        {!! view_render_event('sales.invoice.billing_address.after', ['order' => $order]) !!}
                                    </div>
                                </div>
                            @endif

                            @if ($order->shipping_address)
                                <div class="sale-section" style="margin: 0 0 0 300px;">
                                    <div class="secton-title" style="width: 400px;">
                                        <span>{{ __('admin::app.sales.orders.shipping-address') }}</span>
                                    </div>

                                    <div class="section-content" style="width: 400px;">
                                        @include ('admin::sales.address', ['address' => $order->shipping_address])

                                        {!! view_render_event('sales.invoice.shipping_address.after', ['order' => $order]) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </accordian>
                @endif

                <accordian :title="'{{ __('admin::app.sales.orders.products-ordered') }}'" :active="true">
                    <div slot="body">

                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                                        <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                                        <th>{{ __('admin::app.sales.orders.price') }}</th>
                                        <th>{{ __('admin::app.sales.orders.qty') }}</th>
                                        <th>{{ __('admin::app.sales.orders.subtotal') }}</th>
                                        <th>{{ __('admin::app.sales.orders.tax-amount') }}</th>
                                        @if ($invoice->base_discount_amount > 0)
                                            <th>{{ __('admin::app.sales.orders.discount-amount') }}</th>
                                        @endif
                                        <th>{{ __('admin::app.sales.orders.grand-total') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>
                                            <td>{{ $item->name }}

                                                @if (isset($item->additional['attributes']))
                                                    <div class="item-options">

                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>

                                            <td>{{ core()->formatBasePrice($item->base_price) }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ core()->formatBasePrice($item->base_total) }}</td>
                                            <td>{{ core()->formatBasePrice($item->base_tax_amount) }}</td>
                                            @if ($invoice->base_discount_amount > 0)
                                                <td>{{ core()->formatBasePrice($item->base_discount_amount) }}</td>
                                            @endif

                                            <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <table class="sale-summary">
                            <tr>
                                <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_sub_total) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('admin::app.sales.orders.shipping-handling') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_shipping_amount) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('admin::app.sales.orders.tax') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_tax_amount) }}</td>
                            </tr>

                            @if ($invoice->base_discount_amount > 0)
                                <tr>
                                    <td>{{ __('admin::app.sales.orders.discount') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatBasePrice($invoice->base_discount_amount) }}</td>
                                </tr>
                            @endif

                            <tr class="bold">
                                <td>{{ __('admin::app.sales.orders.grand-total') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_grand_total) }}</td>
                            </tr>
                        </table>
                    </div>
                </accordian>
            </div>
        </div>
    </div>
 </div>

 <modal id="changeInvoiceState" :is-open="modalIds.changeInvoiceState">
     <h3 slot="header">{{ __('admin::app.sales.orders.invoices-change-title') }}</h3>
        <div slot="body">
            <option-wrapper></option-wrapper>
        </div>
</modal>
@stop

@push('scripts')
    <script type="text/x-template" id="options-template">
    <form method="POST" action="{{ route('admin.sales.invoices.update.state', $invoice->id) }}">
        <div class="page-content">
            <p>Please select the new invoice state:</p>

            <div class="form-container">
                @csrf()
                <div>
                    <input type="radio" name="state" id="paid" value="paid">
                    <label for="paid">{{ __('admin::app.sales.orders.invoice-status-paid') }}</label>
                </div>

                <div>
                    <input type="radio" name="state" id="pending" value="pending" @if($invoice->state == "pending") checked @endif>
                    <label for="pending">{{ __('admin::app.sales.orders.invoice-status-pending') }}</label>
                </div>

                <div>
                    <input type="radio" name="state" id="overdue" value="overdue" @if($invoice->state == "overdue") checked @endif>
                    <label for="overdue">{{ __('admin::app.sales.orders.invoice-status-overdue') }}</label>
                </div>
            </div>

            <br />

            <button type="submit" class="btn btn-md btn-primary">{{ __('admin::app.sales.orders.invoice-status-update')}}</button>
        </div>
    </form>
</script>

<script>
    Vue.component('option-wrapper', {
        template: '#options-template',

        methods: {
            onSubmit: function(e) {
                // e.target.submit();
            }
        }
    });
</script>
@endpush
