import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class SitemapConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Configuration" });
    }

    private get successMessage() {
        return this.page.getByText("Configuration saved successfully Close");
    }

    private get toggle() {
        return this.page.locator("label > div").first();
    }

    private get maxUrlsTextbox() {
        return this.page.getByRole("textbox", {
            name: "Maximum no. of URLs per file",
        });
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/general/sitemap");
    }

    async setSitemapEnabled(enabled: boolean): Promise<void> {
        await this.open();

        const input = this.toggle;
        const isChecked = await input.isChecked();
        if (enabled !== isChecked) {
            await input.click();
        }
    }

    async setMaximumUrls(count: string): Promise<void> {
        await this.maxUrlsTextbox.click();
        await this.maxUrlsTextbox.fill(count);
    }

    async isSitemapEnabled(): Promise<boolean> {
        await this.open();
        return this.toggle.isChecked();
    }

    async getMaximumUrlsValue(): Promise<string> {
        return this.maxUrlsTextbox.inputValue();
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successMessage).toBeVisible();
    }
}
