import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

type GoogleCaptchaSettings = {
    projectId: string;
    apiKey: string;
    siteKey: string;
};

export class ConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get googleCaptchaToggle() {
        return this.page.locator(".peer.h-5");
    }

    private get projectIdInput() {
        return this.page.getByRole("textbox", { name: "Project ID" });
    }

    private get apiKeyInput() {
        return this.page.getByRole("textbox", { name: "API Key" });
    }

    private get siteKeyInput() {
        return this.page.getByRole("textbox", { name: "Site Key" });
    }

    private get saveConfigurationButton() {
        return this.page.getByRole("button", { name: "Save Configuration" });
    }

    private get configurationSavedNotification() {
        return this.page
            .locator("#app")
            .getByText("Configuration saved successfully")
            .first();
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/customer/captcha");
    }

    async ensureGoogleCaptchaEnabled(
        settings: GoogleCaptchaSettings,
    ): Promise<void> {
        await this.open();

        if (!(await this.googleCaptchaToggle.isChecked())) {
            await this.googleCaptchaToggle.click();
        }

        await this.projectIdInput.fill(settings.projectId);
        await this.apiKeyInput.fill(settings.apiKey);
        await this.siteKeyInput.fill(settings.siteKey);
        await this.saveConfigurationButton.click();

        await expect(this.configurationSavedNotification).toBeVisible();
    }

    async verifyGoogleCaptchaSettings(
        settings: GoogleCaptchaSettings,
    ): Promise<void> {
        await this.open();

        await expect(this.googleCaptchaToggle).toBeChecked();
        await expect(this.projectIdInput).toHaveValue(settings.projectId);
        await expect(this.apiKeyInput).toHaveValue(settings.apiKey);
        await expect(this.siteKeyInput).toHaveValue(settings.siteKey);
    }

    async disableGoogleCaptcha(): Promise<void> {
        await this.open();

        if (await this.googleCaptchaToggle.isChecked()) {
            await this.googleCaptchaToggle.click();
        }

        await this.saveConfigurationButton.click();
        await expect(this.configurationSavedNotification).toBeVisible();
    }
}
