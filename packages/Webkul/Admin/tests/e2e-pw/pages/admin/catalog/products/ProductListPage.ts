import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class ProductListPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createProductButton() {
        return this.page.getByRole("button", { name: "Create Product" });
    }

    private get productExpandButtons() {
        return this.page.locator(".cursor-pointer.icon-sort-right");
    }

    private get rowCheckboxes() {
        return this.page.locator(".icon-uncheckbox:visible");
    }

    private get rowsPerPageButton() {
        return this.page.getByRole("button", { name: "" });
    }

    private get rowsPerPageOption() {
        return this.page.getByText("50", { exact: true }).first();
    }

    private get selectActionButton() {
        return this.page.locator('button:has-text("Select Action")');
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    private get selectedProductsUpdatedMessage() {
        return this.page.getByText("Selected Products Updated Successfully");
    }

    async visit() {
        await super.visit("admin/catalog/products");
        await expect(this.createProductButton).toBeVisible();
    }

    async openProductForEdit() {
        await this.visit();
        await this.productExpandButtons.nth(1).click();
    }

    async openFirstProductForEdit() {
        await this.openProductForEdit();
    }

    async setRowsPerPageTo50() {
        await this.visit();
        await this.rowsPerPageButton.click();
        await this.rowsPerPageOption.click();
        await expect(this.rowCheckboxes.first()).toBeVisible();
    }

    async selectProductCheckbox() {
        // await this.rowCheckboxes.first().click();   // Only if you want all lisiting product update
        await this.rowCheckboxes.nth(1).click();
        await this.rowCheckboxes.nth(2).click();
    }

    async massUpdateStatus(status: "Active" | "Disable" = "Active") {
        await this.visit();
        await this.selectProductCheckbox();

        await this.selectActionButton.click();
        await this.page.hover('a:has-text("Update Status")');
        await this.page.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible" },
        );
        await this.page.click(`a:has-text("${status}")`);

        if (await this.agreeButton.isVisible()) {
            await this.page.waitForTimeout(1000);
            await this.agreeButton.click();
        }

        await expect(this.selectedProductsUpdatedMessage).toBeVisible();
    }
}
