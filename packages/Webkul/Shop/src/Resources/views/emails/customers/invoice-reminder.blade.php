@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $invoice->order->customer_full_name]), 👋
        </p>
    </div>

    <div>
        <p>@lang('shop::app.emails.customers.reminder.invoice-overdue')</p>

        <p style="margin-top: 20px;">@lang('shop::app.emails.customers.reminder.already-paid')</p>
    </div>
@endcomponent