import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class CategoryDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get confirmDeleteButton() {
        return this.page.locator(
            "button.transparent-button + button.primary-button:visible",
        );
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

    private get deleteSuccessMessage() {
        return this.page.locator("#app p", {
            hasText: "The category has been successfully deleted.",
        });
    }

    async visit() {
        await super.visit("admin/catalog/categories");
    }

    async deleteCategory() {
        await this.visit();
        await expect(this.deleteIcons.first()).toBeVisible();
        await this.deleteIcons.first().click();
        await this.confirmDeleteButton.click();
        await expect(this.deleteSuccessMessage).toBeVisible();
    }

    async massDeleteCategories() {
        await this.visit();
        await expect(this.rowCheckboxes.nth(1)).toBeVisible();
        await this.rowCheckboxes.nth(1).click();
        await this.massActionButton.click();
        await this.page.click('a:has-text("Delete")', { timeout: 1000 });
        await this.page.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        await expect(this.confirmAgreeButton).toBeVisible();
        await this.confirmAgreeButton.click();
        await expect(this.deleteSuccessMessage).toBeVisible();
    }
}
