import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { setBooleanSettings } from "../../../../utils/configuration";

export class CustomerAddressPage extends BasePage {
    constructor(page: Page) {
        super(page);
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
        await setBooleanSettings(this.page, [
            "customer[address][requirements][country]",
            "customer[address][requirements][state]",
            "customer[address][requirements][postcode]",
        ]);
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
