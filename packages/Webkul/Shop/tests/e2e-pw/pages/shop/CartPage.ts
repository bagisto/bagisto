import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class CartPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async searchProduct(term: string): Promise<void> {
        await this.page.getByPlaceholder("Search products here").fill(term);
        await this.page.getByPlaceholder("Search products here").press("Enter");
    }

    async addFirstProductToCart(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
    }

    async expectItemAdded(): Promise<void> {
        await expect(
            this.page.getByText("Item Added Successfully").first(),
        ).toBeVisible();
    }

    async openMiniCart(): Promise<void> {
        await this.page.getByRole("button", { name: "Shopping Cart" }).click();
    }

    async increaseQuantityFromMiniCart(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Increase Quantity" })
            .click();
        await this.waitForCartUpdate();
    }

    async decreaseQuantityFromMiniCart(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Decrease Quantity" })
            .click();
        await this.waitForCartUpdate();
    }

    async removeProductFromMiniCart(): Promise<void> {
        await this.page.getByRole("button", { name: "Remove" }).click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async goToCartView(): Promise<void> {
        await this.visit("checkout/cart");
    }

    async increaseQuantityFromCartView(): Promise<void> {
        await this.page.getByLabel("Increase Quantity").first().click();
    }

    async decreaseQuantityFromCartView(): Promise<void> {
        await this.page.getByLabel("Decrease Quantity").first().click();
    }

    async updateCart(): Promise<void> {
        await this.page.getByRole("button", { name: "Update Cart" }).click();
    }

    async removeProductFromCartView(): Promise<void> {
        await this.page.getByRole("button", { name: "Remove" }).first().click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async removeAllFromCartView(): Promise<void> {
        await this.page
            .locator(".icon-uncheck")
            .first()
            .waitFor({ state: "visible" });
        await this.page.locator(".icon-uncheck").first().click();
        await this.page
            .getByRole("button", { name: "Remove" })
            .first()
            .waitFor({ state: "visible" });
        await this.page.getByRole("button", { name: "Remove" }).first().click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async applyCoupon(code: string): Promise<void> {
        await this.page.getByRole("button", { name: "Apply Coupon" }).click();
        await this.page.getByPlaceholder("Enter your code").fill(code);
        await this.page
            .getByRole("button", { name: "Apply", exact: true })
            .click();
    }

    async expectCouponApplied(): Promise<void> {
        await expect(
            this.page
                .getByRole("paragraph")
                .filter({ hasText: "Coupon code applied" }),
        ).toBeVisible();
    }

    async waitForCartUpdate(): Promise<void> {
        await expect(
            this.page.locator("svg.text-blue.animate-spin.font-semibold"),
        ).toBeVisible();
    }
}
