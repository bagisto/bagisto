import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

type ContentConfig = {
    headerOfferTitle: string;
    redirectionTitle: string;
    redirectionLink: string;
    customCss: string;
    customJs: string;
};

export class ContentConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.locator("#app p", {
            hasText: "Configuration saved successfully",
        });
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/general/content");
    }

    async fillHeaderOffer(
        title: string,
        redirectionTitle: string,
        redirectionLink: string,
    ): Promise<void> {
        await this.page
            .locator('input[name="general[content][header_offer][title]"]')
            .fill(title);
        await this.page
            .locator(
                'input[name="general[content][header_offer][redirection_title]"]',
            )
            .fill(redirectionTitle);
        await this.page
            .locator(
                'input[name="general[content][header_offer][redirection_link]"]',
            )
            .fill(redirectionLink);
    }

    async fillCustomScripts(css: string, js: string): Promise<void> {
        await this.page
            .locator(
                'textarea[name="general[content][custom_scripts][custom_css]"]',
            )
            .fill(css);
        await this.page
            .locator(
                'textarea[name="general[content][custom_scripts][custom_javascript]"]',
            )
            .fill(js);
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
