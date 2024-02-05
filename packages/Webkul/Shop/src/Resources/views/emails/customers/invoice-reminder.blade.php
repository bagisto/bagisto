@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $customer['name']]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.emails.customers.invoice-reminder.overdue-invoice', [
                'invoice' => $invoice->increment_id,
                'time' => $invoice->created_at->diffForHumans()
            ])
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.emails.customers.invoice-reminder.please-make-payment')
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.emails.customers.invoice-reminder.disregard-this-email')
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('shop::app.emails.customers.invoice-reminder.thanks')
        </p>
    </div>
@endcomponent