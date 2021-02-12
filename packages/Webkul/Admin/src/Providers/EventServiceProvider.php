<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('user.admin.update-password', 'Webkul\Admin\Listeners\PasswordChange@sendUpdatePasswordMail');

        Event::listen('checkout.order.save.after', 'Webkul\Admin\Listeners\Order@sendNewOrderMail');

        Event::listen('sales.invoice.save.after', 'Webkul\Admin\Listeners\Order@sendNewInvoiceMail');

        Event::listen('sales.shipment.save.after', 'Webkul\Admin\Listeners\Order@sendNewShipmentMail');

        Event::listen('sales.order.cancel.after', 'Webkul\Admin\Listeners\Order@sendCancelOrderMail');

        Event::listen('sales.refund.save.after', 'Webkul\Admin\Listeners\Order@refundOrder');

        Event::listen('sales.refund.save.after', 'Webkul\Admin\Listeners\Order@sendNewRefundMail');

        Event::listen('sales.order.comment.create.after', 'Webkul\Admin\Listeners\Order@sendOrderCommentMail');

        Event::listen('core.channel.update.after', 'Webkul\Admin\Listeners\ChannelSettingsChange@checkForMaintenaceMode');
    }
}