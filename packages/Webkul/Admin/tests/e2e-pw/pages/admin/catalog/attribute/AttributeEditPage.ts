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

    private optionRowInput(field: "swatch_alt" | "swatch_file_name") {
        return this.page
            .locator(`input[name^="options["][name$="[${field}]"]`)
            .first();
    }

    private get optionEditIcon() {
        return this.page.locator("span.icon-edit").first();
    }

    private get optionSaveButton() {
        return this.page.getByRole("button", { name: "Save Option" });
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

    async fillSwatchSeo(altText: string, fileName: string) {
        await this.optionRowInput("swatch_alt").fill(altText);
        await this.optionRowInput("swatch_file_name").fill(fileName);
    }

    async reopenAndSaveFirstOption() {
        await this.optionEditIcon.click();
        await this.optionSaveButton.click();
        await expect(this.optionSaveButton).toBeHidden();
    }

    async verifySwatchSeo(altText: string, fileName: string) {
        await expect(this.optionRowInput("swatch_alt")).toHaveValue(altText);
        await expect(this.optionRowInput("swatch_file_name")).toHaveValue(
            fileName,
        );
    }

    async editAttribute() {
        await this.visit();
        await this.openFirstAttributeForEdit();
        await this.saveAttribute();
        await this.verifyAttributeUpdated();
    }
}
