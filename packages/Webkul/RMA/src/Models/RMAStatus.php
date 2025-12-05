<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\RMAStatus as RMAStatusContract;

class RMAStatus extends Model implements RMAStatusContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_statuses';

    /**
     * RMA status when the return request is pending
     */
    const PENDING = 'Pending';

    /**
     * RMA status when the package has been received
     */
    const RECEIVED_PACKAGE = 'Received Package';

    /**
     * RMA status when the return request has been declined
     */
    const DECLINED = 'Declined';

    /**
     * RMA status when the item has been canceled
     */
    const ITEM_CANCELED = 'Item Canceled';

    /**
     * RMA status when the package has not been received yet
     */
    const AWAITING = 'Awaiting';

    /**
     * RMA status when the package has been dispatched
     */
    const DISPATCHED_PACKAGE = 'Dispatched Package';

    /**
     * RMA status when the return request is accepted
     */
    const ACCEPT = 'Accept';

    /**
     * RMA status Canceled
     */
    const CANCELED = 'canceled';

    /**
     * RMA Closed
     */
    const CLOSED = 'Closed';

    /**
     * RMA Closed
     */
    const SOLVED = 'solved';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
        'color',
    ];
}