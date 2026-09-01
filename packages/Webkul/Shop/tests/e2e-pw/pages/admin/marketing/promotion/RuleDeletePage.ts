import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class RuleDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    private get agree() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get selectRowBtn() {
        return this.page.locator(".icon-uncheckbox");
    }

    private get selectAction() {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    private get selectDelete() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    private get productDeleteSuccess() {
        return this.page.getByText("Selected Products Deleted Successfully");
    }

    private async deleteRuleIfPresent(path: string, successMessage: string) {
        await this.visit(path);

        try {
            await this.deleteIcon
                .first()
                .waitFor({ state: "visible", timeout: 5000 });
        } catch {
            return;
        }

        await this.deleteIcon.first().click();
        await this.agree.click();
        await expect(this.page.getByText(successMessage)).toBeVisible();
    }

    private async deleteLatestProduct() {
        await this.visit("admin/catalog/products");
        await this.selectRowBtn.nth(2).click();
        await this.selectAction.click();
        await this.selectDelete.click();
        await this.agree.click();
        await expect(this.productDeleteSuccess).toBeVisible();
    }

    async deleteRuleAndProduct() {
        try {
            await this.deleteRuleIfPresent(
                "admin/marketing/promotions/cart-rules",
                "Cart Rule Deleted Successfully",
            );
        } finally {
            await this.deleteLatestProduct();
        }
    }

    async deleteCatalogRuleAndProduct() {
        try {
            await this.deleteRuleIfPresent(
                "admin/marketing/promotions/catalog-rules",
                "Catalog Rule Deleted Successfully",
            );
        } finally {
            await this.deleteLatestProduct();
        }
    }
}
