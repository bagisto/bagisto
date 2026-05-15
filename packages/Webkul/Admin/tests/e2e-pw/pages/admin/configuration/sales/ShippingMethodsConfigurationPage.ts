import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class ShippingMethodsConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully").first();
    }

    private getFreeShippingDescription() {
        return this.page.locator(
            'textarea[name="sales[carriers][free][description]"]',
        );
    }

    private getFreeShippingToggle() {
        return this.page.locator('label[for="sales[carriers][free][active]"]');
    }

    private getFlatRateTypeSelect() {
        return this.page.locator(
            'select[name="sales[carriers][flatrate][type]"]',
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/sales/carriers");
    }

    async configureFreeShipping(description: string): Promise<void> {
        await this.getFreeShippingDescription().fill(description);
        await this.getFreeShippingToggle().click();
    }

    async configureFlatRate(type: string): Promise<void> {
        await this.getFlatRateTypeSelect().selectOption(type);
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
