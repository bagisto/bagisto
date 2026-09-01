import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { TaxApplyOnMode, TaxPricingMode } from "../../../../utils/tax";

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

    async setProductPricesMode(mode: TaxPricingMode): Promise<void> {
        await this.open();
        await this.productPricesSelect.selectOption(mode);
        await this.saveAndVerify();
    }

    async setShippingPricesMode(mode: TaxPricingMode): Promise<void> {
        await this.open();
        await this.shippingPricesSelect.selectOption(mode);
        await this.saveAndVerify();
    }

    async setApplyTaxOn(mode: TaxApplyOnMode): Promise<void> {
        await this.open();
        await this.applyTaxOnSelect.selectOption(mode);
        await this.saveAndVerify();
    }

    async resetToDefault(): Promise<void> {
        await this.setProductPricesMode("excluding_tax");
    }

    async resetCalculationDefaults(): Promise<void> {
        await this.open();
        await this.productPricesSelect.selectOption("excluding_tax");
        await this.applyTaxOnSelect.selectOption("after_discount");
        await this.saveAndVerify();
    }
}
