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
        return (bool) $this->getOverdueRemindersLimit();
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
     * Get interval between reminders.
     *
     * @return string
     */
    private function getIntervalBetweenReminders()
    {
        static $intervalBetweenReminders;

        if ($intervalBetweenReminders) {
            return $intervalBetweenReminders;
        }

        return $intervalBetweenReminders = core()->getConfigData('sales.invoice_setttings.invoice_reminders.interval_between_reminders') ?: 'P1D';
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

        // Calculate next reminder date
        $date = now();

        $interval = $this->getIntervalBetweenReminders();

        $date->add($interval);
        $date->setTime(0, 0, 0, 0);

        $this->next_reminder_date = $date;

        $this->save();
    }

    /**
     * Scope a query to include only the overdue invoices and at the limit of reminders.
     */
    public function scopeInOverdueAndRemindersLimit($query)
    {
        $query->where('state', '=', 'overdue');
        
        // Filter by next_reminder_date
        $query->where(function($query) {
            $query->where('next_reminder_at', '<=', now())
            ->orWhereNull('next_reminder_at');
        });

        // If the core config have maximum limit of reminders
        if ($this->hasOverdueRemindersLimit()) {
            $limit = $this->getOverdueRemindersLimit();

            return $query->where('reminders', '<', $limit);
        }

        return $query;
    }
}
