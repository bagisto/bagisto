<?php

namespace Webkul\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;
use Webkul\Marketing\Contracts\Campaign as CampaignContract;

class Campaign extends Model implements CampaignContract
{
    protected $table = 'marketing_campaigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'subject',
        'status',
        'channel_id',
        'customer_group_id',
        'marketing_template_id',
        'spooling',
        'marketing_event_id',
    ];

    /**
     * Get the event
     */
    public function event()
    {
        return $this->belongsTo(EventProxy::modelClass(), 'marketing_event_id');
    }

    /**
     * Get the channel
     */
    public function channel()
    {
        return $this->belongsTo(ChannelProxy::modelClass(), 'channel_id');
    }

    /**
     * Get the customer group
     */
    public function customer_group()
    {
        return $this->belongsTo(CustomerGroupProxy::modelClass(), 'customer_group_id');
    }

    /**
     * Get the email template
     */
    public function email_template()
    {
        return $this->belongsTo(TemplateProxy::modelClass(), 'marketing_template_id');
    }
}