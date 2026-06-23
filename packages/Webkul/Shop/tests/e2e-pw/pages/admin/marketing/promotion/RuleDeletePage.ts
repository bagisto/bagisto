import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class RuleDeletePage extends BasePage {
    readonly couponCode: string;

    constructor(page: Page) {
        super(page);
        this.couponCode = `CP-${Date.now()}`;
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agree() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get selectRowBtn() {
        return this.page.locator(".icon-uncheckbox");
    }

    get selectAction() {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    get selectDelete() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    get productDeleteSuccess() {
        return this.page.getByText("Selected Products Deleted Successfully");
    }

    async deleteRuleAndProduct() {
        await this.visit("admin/marketing/promotions/cart-rules");
        await this.deleteIcon.first().click();
        await this.agree.click();
        await expect(
            this.page.getByText("Cart Rule Deleted Successfully"),
        ).toBeVisible();

        await this.visit("admin/catalog/products");
        await this.selectRowBtn.nth(2).click();
        await this.selectAction.click();
        await this.selectDelete.click();
        await this.agree.click();
        await expect(this.productDeleteSuccess).toBeVisible();
    }

    async deleteCatalogRuleAndProduct() {
        await this.visit("admin/marketing/promotions/catalog-rules");
        await this.deleteIcon.first().click();
        await this.agree.click();
        await expect(
            this.page.getByText("Catalog Rule Deleted Successfully"),
        ).toBeVisible();

        await this.visit("admin/catalog/products");
        await this.selectRowBtn.nth(2).click();
        await this.selectAction.click();
        await this.selectDelete.click();
        await this.agree.click();
        await expect(this.productDeleteSuccess).toBeVisible();
    }
}