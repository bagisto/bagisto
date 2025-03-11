<?php

namespace Webkul\Admin\Listeners;
use Webkul\Admin\Mail\Customer\GDPR\UpdateRequestMail;
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
        try {
            Mail::queue(new UpdateRequestMail($gdprRequest));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
