import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class GeneralConfigurationPage extends BasePage {
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

    private get weightUnitSelect() {
        return this.page.locator(
            'select[name="general[general][locale_options][weight_unit]"]',
        );
    }

    private get breadcrumbsToggle() {
        return this.page.locator(
            'label[for="general[general][breadcrumbs][shop]"]',
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/general/general");
    }

    async updateWeightUnit(value: string): Promise<void> {
        await this.weightUnitSelect.selectOption(value);
        await expect(this.weightUnitSelect).toHaveValue(value);
    }

    async toggleBreadcrumbs(): Promise<void> {
        await this.breadcrumbsToggle.click();
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
