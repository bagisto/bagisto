import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class OmnibusAdminPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    protected get omnibusActionPage() {
        return {
            enableToggle: this.page.locator(
                'label[for="catalog\\[products\\]\\[omnibus\\]\\[is_enabled\\]"]'
            ),
            enableCheckbox: this.page.locator(
                'input[type="checkbox"][name="catalog[products][omnibus][is_enabled]"]'
            ),
            saveButton: this.page.locator(
                'button[type="submit"].primary-button:visible'
            ),
            successMessage: this.page.locator("#app p", {
                hasText: "Configuration saved successfully",
            }),
        };
    }

    async visitConfig() {
        await super.visit("admin/configuration/catalog/products");

        await this.page.waitForLoadState("networkidle");
    }

    async enableOmnibus() {
        await this.visitConfig();

        const isChecked = await this.omnibusActionPage.enableCheckbox.isChecked();

        if (!isChecked) {
            await this.omnibusActionPage.enableToggle.click();

            await this.page.waitForTimeout(300);
        }
    }

    async disableOmnibus() {
        await this.visitConfig();

        const isChecked = await this.omnibusActionPage.enableCheckbox.isChecked();

        if (isChecked) {
            await this.omnibusActionPage.enableToggle.click();

            await this.page.waitForTimeout(300);
        }
    }

    async saveConfig() {
        await this.omnibusActionPage.saveButton.click();

        await expect(this.omnibusActionPage.successMessage).toBeVisible();
    }

    async isOmnibusEnabled(): Promise<boolean> {
        return this.omnibusActionPage.enableCheckbox.isChecked();
    }
}
