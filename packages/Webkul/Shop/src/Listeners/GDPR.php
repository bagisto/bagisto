<?php

namespace Webkul\Shop\Listeners;
use Webkul\Shop\Mail\Customer\GDPR\NewRequestMail;
use Webkul\Shop\Mail\Customer\GDPR\StatusUpdateNotification;
use Illuminate\Support\Facades\Mail;

class GDPR extends Base
{
    /**
     * Send mail on creating GDPR request
     *
     * @param  \Webkul\GDPR\Models\GDPRDataRequest  $gdprRequest
     * @return void
     */
    public function afterGdprRequestCreated($gdprRequest)
    {
        if ($gdprRequest) {
            try {
                Mail::queue(new NewRequestMail($gdprRequest));

                session()->flash('success', trans('shop::app.customers.account.gdpr.success-verify'));
            } catch (\Exception) {
                session()->flash('warning', trans('shop::app.customers.account.gdpr.success-verify-email-unsent'));
            }
        } else {
            session()->flash('error', trans('shop::app.customers.account.gdpr.unable-to-sent'));
        }
    }

    /**
     * Send mail on creating GDPR request
     *
     * @param  \Webkul\GDPR\Models\GDPRDataRequest  $gdprRequest
     * @return void
     */
    public function afterGdprRequestUpdated($gdprRequest)
    {
        try {
            Mail::queue(new StatusUpdateNotification($gdprRequest));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
