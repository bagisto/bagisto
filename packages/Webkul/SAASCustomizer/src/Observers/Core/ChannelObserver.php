<?php

namespace Webkul\SAASCustomizer\Observers\Core;

use Webkul\SAASCustomizer\Models\Core\Channel;

use Company;

class ChannelObserver
{
    public function creating(Channel $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            if ($model->count() == 0) {
                $model->company_id = Company::getCurrent()->id;
            } else {
                session()->flash('error', 'Creating more than one channel is not allowed');

                abort(404);
            }
        }
    }

    public function updating(Channel $channel)
    {
        if (! auth()->guard('super-admin')->check()) {
            if ($channel->hostname != Company::getCurrent()->domain) {
                session()->flash('warning', 'Kindly contact admin to change your hostname');

                abort(404);
            }
        }
    }
}