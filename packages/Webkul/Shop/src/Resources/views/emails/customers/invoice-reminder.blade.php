@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $customer['name']]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.mail.invoice.reminder.your-invoice-is-overdue', [
                'invoice' => $invoice->increment_id,
                'time' => $invoice->created_at->diffForHumans()
            ])
        </p>

        <p style="">
            @lang('shop::app.mail.invoice.reminder.please-make-your-payment-as-soon-as-possible')
        </p>

        <p style="">
            @lang('shop::app.mail.invoice.reminder.if-you-ve-already-paid-just-disregard-this-email')
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('shop::app.mail.customer.new.thanks')
        </p>
    </div>
@endcomponent