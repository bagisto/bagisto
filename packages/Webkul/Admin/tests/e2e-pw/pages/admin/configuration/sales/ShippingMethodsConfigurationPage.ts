import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { setBooleanSetting } from "../../../../utils/configuration";

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
        await setBooleanSetting(this.page, "sales[carriers][free][active]");
    }

    async configureFlatRate(type: string): Promise<void> {
        await this.getFlatRateTypeSelect().selectOption(type);
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
