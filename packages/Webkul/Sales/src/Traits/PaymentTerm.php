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
        return (bool) $this->getPaymentTerm();
    }

    /**
     * Get payment term from the core config in days.
     *
     * @return int
     */
    public function getPaymentTerm()
    {
        return (int) core()->getConfigData('sales.invoice_settings.payment_terms.due_duration');
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
            return __('admin::app.configuration.index.sales.invoice-settings.payment-terms.due-duration-days', ['due-duration' => $dueDuration]);
        }

        return $dueDuration
            ? __('admin::app.configuration.index.sales.invoice-settings.payment-terms.due-duration-day', ['due-duration' => $dueDuration])
            : __('admin::app.configuration.index.sales.invoice-settings.payment-terms.due-duration-day', ['due-duration' => 0]);
    }
}
