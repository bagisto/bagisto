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
                session()->flash('error', trans('saas::app.custom-errors.channel-creating'));

                throw new \Exception('illegal_action');
            }
        }
    }

    public function updating(Channel $channel)
    {
        if (! auth()->guard('super-admin')->check()) {
            if ($channel->hostname != Company::getCurrent()->domain) {
                session()->flash('warning', trans('saas::app.custom-errors.channel-hostname'));

                throw new \Exception('illegal_action');
            }
        }
    }
}