import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

type EmailSettingsData = {
    senderName: string;
    senderEmail: string;
    adminName: string;
    adminEmail: string;
    contactName: string;
    contactEmail: string;
};

export class EmailConfigurationPage extends BasePage {
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

    async openSettings(): Promise<void> {
        await this.visit("admin/configuration/emails/configure");
    }

    async openNotifications(): Promise<void> {
        await this.visit("admin/configuration/emails/general");
    }

    async fillEmailSettings(data: EmailSettingsData): Promise<void> {
        await this.page
            .locator(
                'input[name="emails[configure][email_settings][sender_name]"]',
            )
            .fill(data.senderName);
        await this.page
            .locator(
                'input[name="emails[configure][email_settings][sender_email]"]',
            )
            .fill(data.senderEmail);
        await this.page
            .locator(
                'input[name="emails[configure][email_settings][admin_name]"]',
            )
            .fill(data.adminName);
        await this.page
            .locator(
                'input[name="emails[configure][email_settings][admin_email]"]',
            )
            .fill(data.adminEmail);
        await this.page
            .locator(
                'input[name="emails[configure][email_settings][contact_name]"]',
            )
            .fill(data.contactName);
        await this.page
            .locator(
                'input[name="emails[configure][email_settings][contact_email]"]',
            )
            .fill(data.contactEmail);
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
