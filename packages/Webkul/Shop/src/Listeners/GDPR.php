<?php

namespace Webkul\Shop\Listeners;
use Webkul\Shop\Mail\Customer\GDPR\UpdateRequestMail;
use Illuminate\Support\Facades\Mail;

class GDPR extends Base
{
    /**
     * Send mail on creating GDPR request
     *
     * @param  \Webkul\GDPR\Models\GDPRDataRequest  $gdprRequestData
     * @return void
     */
    public function afterGdprRequestCreated($gdprRequestData)
    {
        if ($gdprRequestData) {
            try {
                Mail::queue(new UpdateRequestMail($gdprRequestData));

                session()->flash('success', trans('shop::app.customers.account.gdpr.success-verify'));
            } catch (\Exception) {
                session()->flash('warning', trans('shop::app.customers.account.gdpr.success-verify-email-unsent'));
            }
        } else {
            session()->flash('error', trans('shop::app.customers.account.gdpr.unable-to-sent'));
        }
    }
}
