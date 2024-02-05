<?php

namespace Webkul\Sales\Traits;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Order\InvoiceOverdueReminder;

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
        return (int) core()->getConfigData('sales.invoice_settings.invoice_reminders.reminders_limit');
    }

    /**
     * Get interval between reminders.
     *
     * @return string
     */
    private function getIntervalBetweenReminders()
    {
        return core()->getConfigData('sales.invoice_settings.invoice_reminders.interval_between_reminders') ?: 'P1D';
    }

    /**
     * Send an Invoice reminder.
     *
     * @return void
     */
    public function sendInvoiceReminder()
    {
        if ($this->skipReminder()) {
            return;
        }

        $customer = $this->customer;

        Mail::queue(new InvoiceOverdueReminder($customer, $this));

        $this->reminders++;

        $this->calculateNextReminderDate();

        $this->save();
    }

    /**
     * Return skip reminder.
     *
     * @return bool
     */
    protected function skipReminder()
    {
        if ($this->hasOverdueRemindersLimit() && $this->reminders >= $this->getOverdueRemindersLimit()) {
            return true;
        }

        return false;
    }

    /**
     * Calculate next reminder date from today.
     *
     * @return void
     */
    protected function calculateNextReminderDate()
    {
        $date = now();

        $interval = $this->getIntervalBetweenReminders();

        $date->add($interval);
        $date->setTime(0, 0, 0, 0);

        $this->next_reminder_date = $date;
    }

    /**
     * Scope a query to include only the overdue invoices and at the limit of reminders.
     */
    public function scopeInOverdueAndRemindersLimit($query)
    {
        return $query
            ->where('state', '=', 'overdue')
            ->where(function ($query) {
                $query->where('next_reminder_at', '<=', now())
                    ->orWhereNull('next_reminder_at');
            })
            ->when($this->hasOverdueRemindersLimit(), function ($query) {
                $limit = $this->getOverdueRemindersLimit();

                return $query->where('reminders', '<', $limit);
            });
    }
}
