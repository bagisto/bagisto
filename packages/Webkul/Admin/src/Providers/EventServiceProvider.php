<?php

namespace Webkul\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'user.admin.update-password' => [
            'Webkul\Admin\Listeners\PasswordChange@sendUpdatePasswordMail'
        ],
        'checkout.order.save.after' => [
            'Webkul\Admin\Listeners\Order@sendNewOrderMail'
        ],
        'sales.invoice.save.after' => [
            'Webkul\Admin\Listeners\Order@sendNewInvoiceMail'
        ],
        'sales.shipment.save.after' => [
            'Webkul\Admin\Listeners\Order@sendNewShipmentMail'
        ],
        'sales.order.cancel.after' => [
            'Webkul\Admin\Listeners\Order@sendCancelOrderMail'
        ],
        'sales.refund.save.after' => [
            'Webkul\Admin\Listeners\Order@refundOrder',
            'Webkul\Admin\Listeners\Order@sendNewRefundMail',
        ],
        'sales.order.comment.create.after' => [
            'Webkul\Admin\Listeners\Order@sendOrderCommentMail'
        ],
        'core.channel.update.after' => [
            'Webkul\Admin\Listeners\ChannelSettingsChange@checkForMaintenaceMode'
        ],
    ];
}
