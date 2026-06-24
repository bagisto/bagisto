import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { TaxApplyOnMode, TaxPricingMode } from "../../../../utils/tax";

/**
 * Page object for the tax calculation configuration
 * (admin/configuration/sales/taxes).
 *
 * Focuses on the product price tax mode (`product_prices`) that switches the
 * storefront between tax-exclusive and tax-inclusive pricing, plus helpers to
 * toggle the shipping price mode if a scenario needs it.
 */
export class TaxConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get productPricesSelect() {
        return this.page.locator(
            'select[name="sales[taxes][calculation][product_prices]"]',
        );
    }

    private get shippingPricesSelect() {
        return this.page.locator(
            'select[name="sales[taxes][calculation][shipping_prices]"]',
        );
    }

    private get applyTaxOnSelect() {
        return this.page.locator(
            'select[name="sales[taxes][calculation][apply_tax_on]"]',
        );
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully").first();
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/sales/taxes");
        await expect(this.productPricesSelect).toBeVisible();
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.first().click();
        await expect(this.successNotification).toBeVisible();
    }

    /**
     * Set how catalog product prices are interpreted (tax-exclusive vs
     * tax-inclusive) and persist the configuration.
     */
    async setProductPricesMode(mode: TaxPricingMode): Promise<void> {
        await this.open();
        await this.productPricesSelect.selectOption(mode);
        await this.saveAndVerify();
    }

    /**
     * Set how shipping prices are interpreted and persist the configuration.
     */
    async setShippingPricesMode(mode: TaxPricingMode): Promise<void> {
        await this.open();
        await this.shippingPricesSelect.selectOption(mode);
        await this.saveAndVerify();
    }

    /**
     * Set whether tax is calculated before or after a cart-rule discount and
     * persist the configuration.
     */
    async setApplyTaxOn(mode: TaxApplyOnMode): Promise<void> {
        await this.open();
        await this.applyTaxOnSelect.selectOption(mode);
        await this.saveAndVerify();
    }

    /**
     * Restore the Bagisto default (tax-exclusive) so global config state does
     * not leak into other tests.
     */
    async resetToDefault(): Promise<void> {
        await this.setProductPricesMode("excluding_tax");
    }

    /**
     * Restore both calculation defaults Bagisto ships with: tax-exclusive
     * product prices and tax applied after the discount.
     */
    async resetCalculationDefaults(): Promise<void> {
        await this.open();
        await this.productPricesSelect.selectOption("excluding_tax");
        await this.applyTaxOnSelect.selectOption("after_discount");
        await this.saveAndVerify();
    }
}
