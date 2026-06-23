import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class CategoryEditPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get saveCategoryButton() {
        return this.page.locator('button:has-text("Save Category")');
    }

    private get massActionButton() {
        return this.page.locator('button:has-text("Select Action")');
    }

    private get rowCheckboxes() {
        return this.page.locator(".icon-uncheckbox:visible");
    }

    private get confirmAgreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    async visit() {
        await super.visit("admin/catalog/categories");
        await expect(this.editIcons.first()).toBeVisible();
    }

    async editCategory() {
        await this.visit();
        await this.editIcons.first().click();
        await this.page.waitForSelector('form[action*="/catalog/categories/edit"]');
        await this.saveCategoryButton.click();
    }

    async massUpdateCategories(status: "Active" | "Inactive" = "Active") {
        await this.visit();
        await expect(this.rowCheckboxes.nth(1)).toBeVisible();
        await this.rowCheckboxes.nth(1).click();
        await this.massActionButton.click();
        await this.page.hover('a:has-text("Update Status")');
        await this.page.waitForSelector(
            'a:has-text("Active"), a:has-text("Inactive")',
            { state: "visible", timeout: 1000 },
        );
        await this.page.click(`a:has-text("${status}")`);
        await this.page.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        await expect(this.confirmAgreeButton).toBeVisible();
        await this.confirmAgreeButton.click();
    }
}
