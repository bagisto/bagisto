import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class GDPRConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Configuration" });
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully");
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/general/gdpr");
    }

    async fillCookieConsentText(message: string): Promise<void> {
        const selectors = [
            "#general_gdpr__cookie_consent__strictly_necessary__ifr",
            "#general_gdpr__cookie_consent__basic_interaction__ifr",
            "#general_gdpr__cookie_consent__experience_enhancement__ifr",
            "#general_gdpr__cookie_consent__measurements__ifr",
            "#general_gdpr__cookie_consent__targeting_advertising__ifr",
        ];

        for (const selector of selectors) {
            await this.page.fillInTinymce(selector, message);
        }
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
