<?php

namespace Webkul\Sales\Traits;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\InvoiceOverdueReminder;

trait InvoiceReminder
{
    /**
     * Wether the core config have maximum limit of reminders.
     *
     * @return bool
     */
    private function hasOverdueRemindersLimit()
    {
        return $this->getOverdueRemindersLimit()
            ? true : false;
    }

    /**
     * Get maximum limit of reminders from the core config.
     *
     * @return int
     */
    private function getOverdueRemindersLimit()
    {
        static $remindersLimit = 0;

        if ($remindersLimit) {
            return $remindersLimit;
        }

        return $remindersLimit = (int) core()->getConfigData('sales.invoice_setttings.invoice_reminders.reminders_limit');
    }

    /**
     * Send an Invoice reminder
     *
     * @return void
     */
    public function sendInvoiceReminder()
    {
        if ($this->hasOverdueRemindersLimit()) {
            $limit = $this->getOverdueRemindersLimit();
            if ($this->reminders >= $limit) {
                return;
            }
        }
        /** @var Webkul\Customer\Models\Customer $customer */
        $customer = $this->customer;
        Mail::queue(new InvoiceOverdueReminder($customer, $this));
        $this->reminders++;
        $this->save();
    }

    /**
     * Scope a query to include only the overdue invoices and at the limit of reminders.
     */
    public function scopeOverdueReminders($query)
    {
        if ($this->hasOverdueRemindersLimit()) {
            $limit = $this->getOverdueRemindersLimit();
            return $query->where('reminders', '<', $limit);
        }

        return $query;
    }
}
