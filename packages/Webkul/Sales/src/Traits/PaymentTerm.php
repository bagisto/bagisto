<?php

namespace Webkul\Sales\Traits;

trait PaymentTerm
{
    /**
     * Wether the core config have payment term or not.
     *
     * @return bool
     */
    public function hasPaymentTerm()
    {
        return $this->getPaymentTerm()
            ? true : false;
    }

    /**
     * Get payment term from the core config in days.
     *
     * @return int
     */
    public function getPaymentTerm()
    {
        static $dueDuration = 0;

        if ($dueDuration) {
            return $dueDuration;
        }

        return $dueDuration = (int) core()->getConfigData('sales.invoice_setttings.payment_terms.due_duration');
    }

    /**
     * Formatted payment terms for invoice. Handled singular and plural case also.
     *
     * @return string
     */
    public function getFormattedPaymentTerm()
    {
        $dueDuration = $this->getPaymentTerm();

        if ($dueDuration > 1) {
            return __('admin::app.admin.system.due-duration-days', ['due-duration' => $dueDuration]);
        }

        return $dueDuration
            ? __('admin::app.admin.system.due-duration-day', ['due-duration' => $dueDuration])
            : __('admin::app.admin.system.due-duration-day', ['due-duration' => 0]);
    }
}
