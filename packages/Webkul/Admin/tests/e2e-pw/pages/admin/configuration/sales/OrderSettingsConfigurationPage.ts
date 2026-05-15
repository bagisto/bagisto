import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class OrderSettingsConfigurationPage extends BasePage {
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

    private get orderPrefixInput() {
        return this.page.locator(
            'input[name="sales[order_settings][order_number][order_number_prefix]"]',
        );
    }

    private get orderLengthInput() {
        return this.page.locator(
            'input[name="sales[order_settings][order_number][order_number_length]"]',
        );
    }

    private get orderSuffixInput() {
        return this.page.locator(
            'input[name="sales[order_settings][order_number][order_number_suffix]"]',
        );
    }

    private get adminReorderToggle() {
        return this.page.locator(
            'label[for="sales[order_settings][reorder][admin]"]',
        );
    }

    private get shopReorderToggle() {
        return this.page.locator(
            'label[for="sales[order_settings][reorder][shop]"]',
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/sales/order_settings");
    }

    async fillOrderNumberSettings(
        prefix: string,
        length: string,
        suffix: string,
    ): Promise<void> {
        await this.orderPrefixInput.fill(prefix);
        await this.orderLengthInput.fill(length);
        await this.orderSuffixInput.fill(suffix);
    }

    async enableReorderOptions(): Promise<void> {
        await this.adminReorderToggle.click();
        await this.shopReorderToggle.click();
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
