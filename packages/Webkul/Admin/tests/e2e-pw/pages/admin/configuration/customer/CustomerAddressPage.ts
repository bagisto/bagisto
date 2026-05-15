import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class CustomerAddressPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get countryToggle() {
        return this.page.locator(
            'label[for="customer[address][requirements][country]"]',
        );
    }

    private get stateToggle() {
        return this.page.locator(
            'label[for="customer[address][requirements][state]"]',
        );
    }

    private get postcodeToggle() {
        return this.page.locator(
            'label[for="customer[address][requirements][postcode]"]',
        );
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully");
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/customer/address");
    }

    async requireCountryStateZip(): Promise<void> {
        await this.countryToggle.click();
        await this.stateToggle.click();
        await this.postcodeToggle.click();
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
