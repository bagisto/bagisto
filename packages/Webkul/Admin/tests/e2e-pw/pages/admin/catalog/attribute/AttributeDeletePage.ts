import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class AttributeDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get deleteConfirmButton() {
        return this.page.locator(
            "button.transparent-button + button.primary-button:visible",
        );
    }

    private get rowsPerPageButton() {
        return this.page.getByRole("button", { name: "" });
    }

    private get rowsPerPageOption() {
        return this.page.getByText("50", { exact: true }).first();
    }

    private get rowCheckboxes() {
        return this.page.locator(".icon-uncheckbox:visible");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    private get deleteActionLink() {
        return this.page.locator('a:has-text("Delete")');
    }

    private get deleteResultMessage() {
        return this.page
            .getByText(/Attribute Deleted Successfully|Attribute Deleted Failed/)
            .first();
    }

    private get singleDeleteSuccessMessage() {
        return this.page.getByText("Attribute Deleted Successfully").first();
    }

    async visit() {
        await super.visit("admin/catalog/attributes");
    }

    async deleteFirstAttribute() {
        await this.visit();
        await expect(this.deleteIcons.first()).toBeVisible();
        await this.deleteIcons.first().click();
        await this.deleteConfirmButton.click();
        await expect(this.singleDeleteSuccessMessage).toBeVisible();
    }

    async massDeleteAttributes(count: number = 14) {
        await this.visit();
        await this.rowsPerPageButton.click();
        await this.rowsPerPageOption.click();
        await expect(this.rowCheckboxes.first()).toBeVisible();

        const totalCheckboxes = await this.rowCheckboxes.count();
        const limit = Math.min(count, totalCheckboxes - 1);

        for (let index = 1; index <= limit; index++) {
            await this.rowCheckboxes.nth(index).click();
        }

        const selectActionButton = this.page.locator(
            'button:has-text("Select Action")',
        );

        await selectActionButton.click();
        await this.deleteActionLink.click();

        if (await this.agreeButton.isVisible()) {
            await this.agreeButton.click();
        }

        await expect(this.deleteResultMessage).toBeVisible();
    }
}
