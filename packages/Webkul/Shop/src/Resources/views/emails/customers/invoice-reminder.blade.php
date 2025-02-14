@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $customer['name']]), 👋
        </p>
    </div>

    <div>
        @lang('shop::app.emails.customers.reminder.invoice-overdue', [
            'invoice' => $invoice->increment_id,
            'time' => $invoice->created_at->diffForHumans()]
        )

        <p>@lang('shop::app.emails.customers.reminder.make-payment')</p>

        <p>@lang('shop::app.emails.customers.reminder.already-paid')</p>
    </div>
@endcomponent