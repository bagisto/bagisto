import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class AttributeEditPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get submitButton() {
        return this.page.locator('button[type="submit"]');
    }

    private get updateSuccessMessage() {
        return this.page.getByText("Attribute Updated Successfully").first();
    }

    async visit() {
        await super.visit("admin/catalog/attributes");
        await expect(this.editIcons.first()).toBeVisible();
    }

    async openFirstAttributeForEdit() {
        await this.editIcons.first().click();
    }

    async saveAttribute() {
        await this.submitButton.click();
    }

    async verifyAttributeUpdated() {
        await expect(this.updateSuccessMessage).toBeVisible();
    }

    async editAttribute() {
        await this.visit();
        await this.openFirstAttributeForEdit();
        await this.saveAttribute();
        await this.verifyAttributeUpdated();
    }
}
