import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import { escapeRegExp } from "@shared/regex";

export class CartPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private productCard(productName: string) {
        return this.page
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) });
    }

    private get productForm() {
        return this.page.locator('form:has(input[name="product_id"])');
    }

    private get miniCartToggle() {
        return this.page.getByLabel("Shopping Cart");
    }

    private get miniCartDrawer() {
        return this.page
            .locator("div.fixed")
            .filter({ has: this.page.locator("p", { hasText: /^\s*Shopping Cart\s*$/ }) })
            .filter({ visible: true });
    }

    private miniCartItem(productName: string) {
        return this.miniCartDrawer
            .locator("div.flex.gap-x-5")
            .filter({ has: this.page.getByRole("link", { name: productName, exact: true }) });
    }

    private cartItem(productName: string) {
        return this.page
            .locator("div.grid.gap-y-6")
            .filter({ has: this.page.getByRole("link", { name: productName, exact: true }) });
    }

    private increaseButton(scope: Locator) {
        return scope.getByLabel("Increase Quantity");
    }

    private decreaseButton(scope: Locator) {
        return scope.getByLabel("Decrease Quantity");
    }

    private binButton(scope: Locator) {
        return scope.getByLabel("Remove Item");
    }

    private quantityDisplay(scope: Locator) {
        return this.increaseButton(scope).locator("xpath=..").locator("p");
    }

    private removeButton(scope: Locator) {
        return scope.getByRole("button", { name: "Remove", exact: true });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get selectAllLabel() {
        return this.page.locator('label[for="select-all"]');
    }

    private get removeSelectedButton() {
        return this.selectAllLabel
            .locator('xpath=ancestor::div[.//span[@role="button"][normalize-space()="Remove"]][1]')
            .getByRole("button", { name: "Remove", exact: true });
    }

    private get updateCartButton() {
        return this.page.getByRole("button", { name: "Update Cart" });
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

    private summaryRow(label: string) {
        return this.page
            .locator("div.flex.justify-between")
            .filter({
                has: this.page.locator(`xpath=./p[normalize-space()="${label}"]`),
            })
            .filter({ visible: true });
    }

    private summaryAmount(label: string) {
        return this.summaryRow(label)
            .locator("p")
            .filter({ hasText: /\d/ })
            .filter({ hasNotText: /excl/i });
    }

    async addProductToCart(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        const card = this.productCard(productName);

        await expect(card).toHaveCount(1);
        await card.hover();
        await card.getByRole("button", { name: "Add To Cart" }).click();

        await expect(this.page.getByText("Item Added Successfully").first()).toBeVisible();
    }

    async openProduct(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        const card = this.productCard(productName);

        await expect(card).toHaveCount(1);
        await card.getByRole("link", { name: productName }).click();

        await expect(this.productForm).toBeVisible();
    }

    async openMiniCart(): Promise<void> {
        await this.miniCartToggle.click();

        await expect(this.miniCartDrawer).toBeVisible();
    }

    async openCart(): Promise<void> {
        await this.visit("checkout/cart");

        await expect(this.page).toHaveURL(/checkout\/cart/);
    }

    async setMiniCartQuantity(productName: string, quantity: number): Promise<void> {
        const item = this.miniCartItem(productName);
        const current = Number(await this.quantityDisplay(item).innerText());

        for (let step = current; step < quantity; step++) {
            await Promise.all([
                this.page.waitForResponse((response) =>
                    response.url().includes("/api/checkout/cart") && response.request().method() === "PUT",
                ),
                this.increaseButton(item).click(),
            ]);
        }

        for (let step = current; step > quantity; step--) {
            await Promise.all([
                this.page.waitForResponse((response) =>
                    response.url().includes("/api/checkout/cart") && response.request().method() === "PUT",
                ),
                this.decreaseButton(item).click(),
            ]);
        }

        await expect(this.quantityDisplay(item)).toHaveText(String(quantity));
    }

    async setCartQuantity(productName: string, quantity: number): Promise<void> {
        const item = this.cartItem(productName);
        const current = Number(await this.quantityDisplay(item).innerText());

        for (let step = current; step < quantity; step++) {
            await this.increaseButton(item).click();
        }

        for (let step = current; step > quantity; step--) {
            await this.decreaseButton(item).click();
        }

        await expect(this.quantityDisplay(item)).toHaveText(String(quantity));
    }

    async setProductPageQuantity(quantity: number): Promise<void> {
        const current = Number(await this.quantityDisplay(this.productForm).innerText());

        for (let step = current; step < quantity; step++) {
            await this.increaseButton(this.productForm).click();
        }

        await expect(this.quantityDisplay(this.productForm)).toHaveText(String(quantity));
    }

    async updateCart(): Promise<void> {
        await this.updateCartButton.click();

        await expect(this.page.getByText("Quantity updated successfully").first()).toBeVisible();
    }

    async removeFromMiniCartWithBin(productName: string): Promise<void> {
        await this.binButton(this.miniCartItem(productName)).click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Item is successfully removed from the cart.").first(),
        ).toBeVisible();
    }

    async removeFromMiniCart(productName: string): Promise<void> {
        await this.removeButton(this.miniCartItem(productName)).click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Item is successfully removed from the cart.").first(),
        ).toBeVisible();
    }

    async removeFromCartWithBin(productName: string): Promise<void> {
        await this.binButton(this.cartItem(productName)).click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Item is successfully removed from the cart.").first(),
        ).toBeVisible();
    }

    async removeFromCart(productName: string): Promise<void> {
        await this.cartItem(productName)
            .getByRole("button", { name: "Remove", exact: true })
            .click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Item is successfully removed from the cart.").first(),
        ).toBeVisible();
    }

    async removeAllFromCart(): Promise<void> {
        await this.selectAllLabel.click();

        await expect(this.page.locator("input#select-all")).toBeChecked();

        await this.removeSelectedButton.click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Selected items successfully removed from cart.").first(),
        ).toBeVisible();
    }

    async applyCoupon(code: string): Promise<void> {
        await this.applyCouponButton.click();
        await this.couponInput.fill(code);
        await this.applyButton.click();

        await expect(
            this.page.getByText("Coupon code applied successfully.").first(),
        ).toBeVisible();
    }

    async attemptCoupon(code: string): Promise<void> {
        await this.applyCouponButton.click();
        await this.couponInput.fill(code);
        await this.applyButton.click();
    }

    async expectCouponRejected(): Promise<void> {
        await expect(this.page.getByText("Coupon code is invalid.").first()).toBeVisible();
    }

    async expectMiniCartQuantity(productName: string, quantity: number): Promise<void> {
        await expect(this.quantityDisplay(this.miniCartItem(productName))).toHaveText(
            String(quantity),
        );
    }

    async expectCartQuantity(productName: string, quantity: number): Promise<void> {
        await expect(this.quantityDisplay(this.cartItem(productName))).toHaveText(
            String(quantity),
        );
    }

    async expectMiniCartBinOffered(productName: string, offered: boolean): Promise<void> {
        await expect(this.binButton(this.miniCartItem(productName))).toHaveCount(
            offered ? 1 : 0,
        );
    }

    async expectCartBinOffered(productName: string, offered: boolean): Promise<void> {
        await expect(this.binButton(this.cartItem(productName))).toHaveCount(
            offered ? 1 : 0,
        );
    }

    async expectProductPageBinOffered(offered: boolean): Promise<void> {
        await expect(this.binButton(this.productForm)).toHaveCount(offered ? 1 : 0);
    }

    async expectProductPageDecreaseDisabled(disabled: boolean): Promise<void> {
        await expect(this.decreaseButton(this.productForm)).toHaveAttribute(
            "aria-disabled",
            String(disabled),
        );
    }

    async expectMiniCartItemAbsent(productName: string): Promise<void> {
        await expect(this.miniCartItem(productName)).toHaveCount(0);
    }

    async expectCartItemAbsent(productName: string): Promise<void> {
        await expect(this.cartItem(productName)).toHaveCount(0);
    }

    async expectCartEmpty(): Promise<void> {
        await expect(
            this.page.getByText(/You don.t have a product in your cart/).first(),
        ).toBeVisible();
    }

    async expectSummaryAmount(label: string, amount: string): Promise<void> {
        await expect(this.summaryAmount(label)).toHaveText(new RegExp(escapeRegExp(amount)));
    }
}
