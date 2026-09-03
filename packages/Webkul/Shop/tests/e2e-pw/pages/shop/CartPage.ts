import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class CartPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private searchResultLink(productName: string) {
        return this.page.getByRole("link", { name: productName }).first();
    }

    private get addToCartButton() {
        return this.page.getByRole("button", { name: "Add To Cart" }).first();
    }

    private get itemAddedMessage() {
        return this.page.getByText("Item Added Successfully").first();
    }

    private get shoppingCartButton() {
        return this.page.getByRole("button", { name: "Shopping Cart" });
    }

    private get increaseQuantityButton() {
        return this.page.getByLabel("Increase Quantity").first();
    }

    private get decreaseQuantityButton() {
        return this.page.getByLabel("Decrease Quantity").first();
    }

    private get quantityDisplay() {
        return this.increaseQuantityButton.locator("..").locator("p");
    }

    private get binIcon() {
        return this.page.locator(".icon-bin");
    }

    private get removeButton() {
        return this.page.getByRole("button", { name: "Remove" }).first();
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get uncheckIcon() {
        return this.page.locator(".icon-uncheck").first();
    }

    private get updateCartButton() {
        return this.page.getByRole("button", { name: "Update Cart" });
    }

    private get quantityUpdatedMessage() {
        return this.page.getByText("Quantity updated successfully").first();
    }

    private get applyCouponButton() {
        return this.page.getByRole("button", { name: "Apply Coupon" });
    }

    private get couponInput() {
        return this.page.getByPlaceholder("Enter your code");
    }

    private get applyButton() {
        return this.page.getByRole("button", { name: "Apply", exact: true });
    }

    private get couponAppliedMessage() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Coupon code applied" });
    }

    private get itemRemovedMessage() {
        return this.page
            .getByText("Item is successfully removed from the cart.")
            .first();
    }

    private get selectedItemsRemovedMessage() {
        return this.page
            .getByText("Selected items successfully removed from cart.")
            .first();
    }

    private get cartUpdateSpinner() {
        return this.page.locator("svg.text-blue.animate-spin.font-semibold");
    }

    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async goToCartView(): Promise<void> {
        await this.visit("checkout/cart");
    }

    async searchProduct(term: string): Promise<void> {
        await this.searchInput.fill(term);
        await this.searchInput.press("Enter");
    }

    async openProductFromSearch(productName: string): Promise<void> {
        await this.searchResultLink(productName).click();
    }

    async addFirstProductToCart(): Promise<void> {
        await this.addToCartButton.click();
    }

    async expectItemAdded(): Promise<void> {
        await expect(this.itemAddedMessage).toBeVisible();
    }

    async openMiniCart(): Promise<void> {
        await this.shoppingCartButton.click();
    }

    async expectQuantity(quantity: number): Promise<void> {
        await expect(this.quantityDisplay).toHaveText(String(quantity));
    }

    async increaseQuantityTo(quantity: number): Promise<void> {
        await this.increaseQuantityButton.click();
        await this.expectQuantity(quantity);
        await this.waitForCartUpdate();
    }

    async decreaseQuantityTo(quantity: number): Promise<void> {
        await this.decreaseQuantityButton.click();
        await this.expectQuantity(quantity);
        await this.waitForCartUpdate();
    }

    async increaseQuantityFromCartView(): Promise<void> {
        await this.increaseQuantityButton.click();
    }

    async decreaseQuantityFromCartView(): Promise<void> {
        await this.decreaseQuantityButton.click();
    }

    async expectBinIconOffered(): Promise<void> {
        await expect(this.binIcon.first()).toBeVisible();
    }

    async expectBinIconNotOffered(): Promise<void> {
        await expect(this.binIcon).toHaveCount(0);
    }

    async expectDecreaseQuantityDisabled(): Promise<void> {
        await expect(this.decreaseQuantityButton).toHaveAttribute(
            "aria-disabled",
            "true",
        );
    }

    async expectDecreaseQuantityEnabled(): Promise<void> {
        await expect(this.decreaseQuantityButton).toHaveAttribute(
            "aria-disabled",
            "false",
        );
    }

    async clickBinIcon(): Promise<void> {
        await this.binIcon.first().click();
        await this.agreeButton.click();
    }

    async removeProduct(): Promise<void> {
        await this.removeButton.click();
        await this.agreeButton.click();
    }

    async removeAllFromCartView(): Promise<void> {
        await this.uncheckIcon.waitFor({ state: "visible" });
        await this.uncheckIcon.click();
        await this.removeButton.waitFor({ state: "visible" });
        await this.removeButton.click();
        await this.agreeButton.click();
    }

    async updateCart(): Promise<void> {
        await this.updateCartButton.click();
    }

    async expectQuantityUpdated(): Promise<void> {
        await expect(this.quantityUpdatedMessage).toBeVisible();
    }

    async applyCoupon(code: string): Promise<void> {
        await this.applyCouponButton.click();
        await this.couponInput.fill(code);
        await this.applyButton.click();
    }

    async expectCouponApplied(): Promise<void> {
        await expect(this.couponAppliedMessage).toBeVisible();
    }

    async expectItemRemoved(): Promise<void> {
        await expect(this.itemRemovedMessage).toBeVisible();
    }

    async expectSelectedItemsRemoved(): Promise<void> {
        await expect(this.selectedItemsRemovedMessage).toBeVisible();
    }

    async waitForCartUpdate(): Promise<void> {
        await expect(this.cartUpdateSpinner).toHaveCount(0);
    }
}
