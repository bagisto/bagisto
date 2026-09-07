import { expect, Page } from "@playwright/test";
import { CheckoutHelper } from "../checkout/CheckoutHelper";

export type CouponType = "fixed" | "percentage" | "fixedAmmountWholeCart";

export class RuleApplyPage extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    private get applyCouponButton() {
        return this.page.getByRole("button", { name: "Apply Coupon" });
    }

    private get couponInput() {
        return this.page.locator('input[name="code"]:visible');
    }

    private get applyButton() {
        return this.page.getByRole("button", { name: "Apply", exact: true });
    }

    private get couponAppliedMessage() {
        return this.page.getByText("Coupon code applied successfully.").first();
    }

    private get updateCartButton() {
        return this.page.getByRole("button", { name: "Update Cart" });
    }

    private cartItem(productName: string) {
        return this.page
            .locator("div.grid.gap-y-6")
            .filter({ has: this.page.getByRole("link", { name: productName, exact: true }) });
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

    private async readSummaryAmount(label: string): Promise<number> {
        await expect(this.summaryRow(label)).toHaveCount(1);

        const text = await this.summaryAmount(label).innerText();

        return parseFloat(text.replace(/[^0-9.]/g, ""));
    }

    async addSavedProductToCart(productName: string, quantity: number = 1): Promise<number> {
        await this.addSimpleProductToCart(productName);
        await this.visit("checkout/cart");

        if (quantity > 1) {
            const item = this.cartItem(productName);

            for (let step = 1; step < quantity; step++) {
                await item.getByLabel("Increase Quantity").click();
            }

            await this.updateCartButton.click();

            await expect(this.page.getByText("Quantity updated successfully").first()).toBeVisible();
        }

        return this.readSummaryAmount("Subtotal");
    }

    async applyCoupon(couponCode: string): Promise<void> {
        await this.applyCouponButton.click();
        await this.couponInput.fill(couponCode);
        await this.applyButton.click();

        await expect(this.couponAppliedMessage).toBeVisible();
    }

    async attemptCoupon(couponCode: string): Promise<void> {
        await this.applyCouponButton.click();
        await this.couponInput.fill(couponCode);
        await this.applyButton.click();
    }

    async expectCouponRejected(): Promise<void> {
        await expect(this.page.getByText("Coupon code is invalid.").first()).toBeVisible();
    }

    async expectCouponNotApplicable(subtotal: number): Promise<void> {
        await expect(this.page.getByText("Coupon not found.").first()).toBeVisible();
        await expect(this.summaryRow("Discount Amount")).toHaveCount(0);
        await this.expectGrandTotal(subtotal);
    }

    async expectCouponNotApplicableWithGrandTotal(options: {
        productName: string;
        couponCode: string;
    }): Promise<void> {
        const subtotal = await this.addSavedProductToCart(options.productName);

        await this.proceedAsGuest();
        await this.chooseShipping("free");
        await this.choosePayment("moneytransfer");
        await this.attemptCoupon(options.couponCode);

        await this.expectCouponNotApplicable(subtotal);
    }

    expectedDiscountedTotal(
        subtotal: number,
        discountValue: number,
        couponType: CouponType,
        quantity: number,
    ): number {
        if (couponType === "percentage") {
            return round(subtotal - (subtotal * discountValue) / 100);
        }

        if (couponType === "fixedAmmountWholeCart") {
            return subtotal < discountValue ? 0 : round(subtotal - discountValue);
        }

        return subtotal < discountValue ? 0 : round(Math.max(subtotal - quantity * discountValue, 0));
    }

    async expectGrandTotal(amount: number): Promise<void> {
        const formatted = new Intl.NumberFormat("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(amount);

        await expect(this.summaryAmount("Grand Total")).toHaveText(`$${formatted}`);
    }

    async expectCouponAppliedWithGrandTotal(options: {
        productName: string;
        couponCode: string;
        discountValue: number;
        couponType: CouponType;
        incrementTimes?: number;
        allowShipping?: string;
    }): Promise<void> {
        const quantity = 1 + (options.incrementTimes ?? 0);
        const subtotal = await this.addSavedProductToCart(options.productName, quantity);
        const expected = this.expectedDiscountedTotal(
            subtotal,
            options.discountValue,
            options.couponType,
            quantity,
        );

        await this.proceedAsGuest();
        await this.chooseShipping(options.allowShipping === "yes" ? "flatrate" : "free");
        await this.choosePayment("moneytransfer");
        await this.applyCoupon(options.couponCode);

        await this.expectGrandTotal(expected);
    }

    async verifyCatalogRule(options: {
        productName: string;
        price: number;
        value: number;
        type: string;
    }): Promise<void> {
        await this.searchProduct(options.productName);

        const discounted =
            options.type === "percentage"
                ? round(options.price - (options.price * options.value) / 100)
                : Math.max(round(options.price - options.value), 0);

        await expect(this.cardSellingPrice(options.productName)).toHaveText(
            `$${discounted.toFixed(2)}`,
        );

        if (discounted < options.price) {
            await expect(this.cardStruckPrice(options.productName)).toHaveText(
                `$${options.price.toFixed(2)}`,
            );
        } else {
            await expect(this.cardStruckPrice(options.productName)).toHaveCount(0);
        }
    }

    async expectNoCatalogDiscount(productName: string, price: number): Promise<void> {
        await this.searchProduct(productName);

        await expect(this.cardSellingPrice(productName)).toHaveText(`$${price.toFixed(2)}`);
        await expect(this.cardStruckPrice(productName)).toHaveCount(0);
    }
}

function round(amount: number): number {
    return Math.round(amount * 100) / 100;
}
