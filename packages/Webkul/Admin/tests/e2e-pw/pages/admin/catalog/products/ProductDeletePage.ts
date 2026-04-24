import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class ProductDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
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

    private get selectActionButton() {
        return this.page.locator('button:has-text("Select Action")');
    }

    private get deleteActionLink() {
        return this.page.locator('a:has-text("Delete")');
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    async visit() {
        await super.visit("admin/catalog/products");
    }

    async massDeleteProducts(count: number = 1) {
        await this.visit();
        await expect(this.rowsPerPageButton).toBeVisible();
        await this.rowsPerPageButton.click();
        await this.rowsPerPageOption.click();
        await expect(this.rowCheckboxes.first()).toBeVisible();
        // await this.rowCheckboxes.first().click();  // Only if you want to delete all lisiting product update
        await this.rowCheckboxes.nth(1).click();  
        await this.rowCheckboxes.nth(2).click();  

        await this.selectActionButton.click();
        await this.deleteActionLink.first().click();

        if (await this.agreeButton.isVisible()) {
            await this.agreeButton.click();
        }
    }
}
