<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Customer\GDPR\NewRequestNotification;
use Webkul\Admin\Mail\Customer\GDPR\StatusUpdateNotification;

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
            Mail::queue(new NewRequestNotification($gdprRequest));
        } catch (\Exception $e) {
            report($e);
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
