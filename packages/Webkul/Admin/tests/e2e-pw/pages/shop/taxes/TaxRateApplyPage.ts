import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    appliedPercentage,
    expectedDiscountedTotals,
    expectedGrandTotal,
    expectedGrandTotalForMode,
    expectedTaxAmount,
    expectedTaxForMode,
    formatPrice,
    TaxApplyOnMode,
    TaxPricingMode,
} from "../../../utils/tax";

interface CheckoutRegion {
    country: string;
    checkoutState: string;
}

/**
 * Storefront page object that drives a guest checkout and verifies tax is
 * applied for a product whose tax category matches the configured region.
 *
 * Tax is computed by Bagisto from the shipping address, which for a guest is
 * only known once the checkout address is submitted — so the taxable subtotal
 * is asserted on the cart page and the tax / grand-total are asserted on the
 * checkout (one-page) summary.
 */
export class TaxRateApplyPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByRole("textbox", { name: "Search products here" });
    }

    private get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class, 'secondary-button')])[2]",
        );
    }

    private get addToCartSuccess() {
        return this.page.getByText("Item Added Successfully").first();
    }

    private get companyName() {
        return this.page.getByRole("textbox", { name: "Company Name" });
    }

    private get firstName() {
        return this.page.getByRole("textbox", { name: "First Name" });
    }

    private get lastName() {
        return this.page.getByRole("textbox", { name: "Last Name" });
    }

    private get email() {
        return this.page.getByRole("textbox", { name: "email@example.com" });
    }

    private get streetAddress() {
        return this.page.getByRole("textbox", { name: "Street Address" });
    }

    private get billingCountry() {
        return this.page.locator('select[name="billing\\.country"]');
    }

    private get billingState() {
        return this.page.locator('select[name="billing\\.state"]');
    }

    private get billingCity() {
        return this.page.getByRole("textbox", { name: "City" });
    }

    private get billingZip() {
        return this.page.getByRole("textbox", { name: "Zip/Postcode" });
    }

    private get billingTelephone() {
        return this.page.getByRole("textbox", { name: "Telephone" });
    }

    private get proceedButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    private get freeShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    private get paymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }

    private get applyCouponButton() {
        return this.page.getByRole("button", { name: "Apply Coupon" });
    }

    private get couponInput() {
        return this.page.locator('input[name="code"]:visible');
    }

    private get couponSubmitButton() {
        return this.page.getByRole("button", { name: "Apply", exact: true });
    }

    /**
     * Read a money value from a summary row identified by its label (e.g.
     * "Subtotal", "Tax", "Grand Total"). The "+ " tax prefix and currency
     * symbol are stripped.
     *
     * The checkout renders several summary blocks (responsive duplicates and
     * hidden tax-display template branches), so the row is matched with
     * `:visible` to read the one actually shown. The exact label is matched to
     * avoid "Tax" colliding with "Estimate Shipping and Tax".
     */
    private async readSummaryAmount(label: string): Promise<number> {
        await this.page.waitForLoadState("networkidle");

        const row = this.page
            .locator("div.flex.justify-between:visible")
            .filter({
                has: this.page.getByText(label, { exact: true }),
            })
            .first();

        await row.waitFor({ state: "visible", timeout: 15000 });

        const text = await row.locator("p").last().innerText();

        return parseFloat(text.replace(/[^0-9.]/g, ""));
    }

    /**
     * Search the storefront and add the product to the cart.
     */
    async addProductToCart(productName: string): Promise<void> {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
        await this.addToCartButton.first().click();
        await expect(this.addToCartSuccess).toBeVisible();
    }

    /**
     * Assert the cart's taxable subtotal equals the product price (tax is not
     * yet applied at this stage because the guest address is unknown).
     */
    async verifyCartSubtotal(price: number): Promise<void> {
        await this.visit("checkout/cart");

        const subtotal = await this.readSummaryAmount("Subtotal");

        expect(subtotal).toBeCloseTo(price, 2);
    }

    /**
     * Move into the one-page guest checkout, filling a shipping address that
     * matches the tax region, then pick free shipping + money transfer so the
     * order summary settles with tax applied.
     *
     * Navigates straight to the one-page checkout (the cart page links there via
     * "Proceed To Checkout") so it works regardless of the current page.
     */
    async proceedToGuestCheckout(region: CheckoutRegion): Promise<void> {
        await this.visit("checkout/onepage");
        await this.page.waitForLoadState("networkidle");

        await this.companyName.fill("Webkul");
        await this.firstName.fill("Tax");
        await this.lastName.fill("Tester");
        await this.email.fill("tax.tester@example.com");
        await this.streetAddress.fill("North Street");
        await this.billingCountry.selectOption(region.country);
        await this.billingState.selectOption(region.checkoutState);
        await this.billingCity.fill("Test City");
        await this.billingZip.fill("123456");
        await this.billingTelephone.fill("9876543210");
        await this.proceedButton.click();

        await this.freeShippingMethod.click();
        await this.paymentMethod.click();
    }

    /**
     * Assert the checkout summary shows the mathematically expected tax line and
     * grand total for a tax-exclusive product with free shipping.
     */
    async verifyCheckoutTax(price: number, taxPercent: number): Promise<void> {
        const expectedTax = expectedTaxAmount(price, taxPercent);
        const expectedTotal = expectedGrandTotal(price, taxPercent);

        const tax = await this.readSummaryAmount("Tax");
        const grandTotal = await this.readSummaryAmount("Grand Total");

        expect(tax).toBeCloseTo(expectedTax, 2);
        expect(grandTotal).toBeCloseTo(expectedTotal, 2);

        await expect(
            this.page
                .locator("div.flex.justify-between", { hasText: "Grand Total" })
                .first(),
        ).toContainText(formatPrice(expectedTotal));
    }

    /**
     * Mode-aware checkout verification. Asserts the final tax amount, the grand
     * total (price + tax for exclusive, the gross price itself for inclusive)
     * and that the percentage actually applied — derived back from the summary
     * figures as `tax / net * 100` — matches the configured rate.
     */
    async verifyCheckoutTaxForMode(
        enteredPrice: number,
        taxPercent: number,
        mode: TaxPricingMode,
    ): Promise<void> {
        const expectedTax = expectedTaxForMode(enteredPrice, taxPercent, mode);
        const expectedTotal = expectedGrandTotalForMode(
            enteredPrice,
            taxPercent,
            mode,
        );

        const tax = await this.readSummaryAmount("Tax");
        const grandTotal = await this.readSummaryAmount("Grand Total");

        // Final tax and price land on the mode-specific expected figures.
        expect(tax).toBeCloseTo(expectedTax, 2);
        expect(grandTotal).toBeCloseTo(expectedTotal, 2);

        // The percentage actually applied (tax over net) matches the rate.
        const applied = appliedPercentage(grandTotal, tax);
        expect(Math.abs(applied - taxPercent)).toBeLessThan(0.5);

        await expect(
            this.page
                .locator("div.flex.justify-between", { hasText: "Grand Total" })
                .first(),
        ).toContainText(formatPrice(expectedTotal));
    }

    /**
     * Full storefront flow: add to cart, verify the taxable subtotal, run a
     * guest checkout for the region and verify the applied tax + grand total.
     */
    async verifyTaxApplication(
        productName: string,
        price: number,
        taxPercent: number,
        region: CheckoutRegion,
    ): Promise<void> {
        await this.addProductToCart(productName);
        await this.verifyCartSubtotal(price);
        await this.proceedToGuestCheckout(region);
        await this.verifyCheckoutTax(price, taxPercent);
    }

    /**
     * Apply a coupon on the checkout summary and assert it is accepted.
     */
    async applyCoupon(couponCode: string): Promise<void> {
        await this.applyCouponButton.click();
        await this.couponInput.fill(couponCode);
        await this.couponSubmitButton.click();

        await expect(
            this.page.getByText("Coupon code applied successfully.").first(),
        ).toBeVisible();
    }

    /**
     * Assert the checkout summary after a cart-rule discount, for the configured
     * "apply tax on" mode:
     *
     *   - before_discount: tax is charged on the full price.
     *   - after_discount:  tax is charged on the discounted price.
     *
     * The discount line, tax amount, grand total and the percentage applied on
     * the taxable base are all asserted.
     */
    async verifyDiscountedCheckoutTax(
        price: number,
        taxPercent: number,
        discountPercent: number,
        applyOn: TaxApplyOnMode,
    ): Promise<void> {
        const discountAmount = Math.round(price * discountPercent) / 100;
        const expected = expectedDiscountedTotals(
            price,
            taxPercent,
            discountAmount,
            applyOn,
        );

        const discount = await this.readSummaryAmount("Discount Amount");
        const tax = await this.readSummaryAmount("Tax");
        const grandTotal = await this.readSummaryAmount("Grand Total");

        // The discount itself is independent of the tax mode.
        expect(discount).toBeCloseTo(expected.discount, 2);

        // Tax is charged on the full price (before) or discounted price (after).
        expect(tax).toBeCloseTo(expected.tax, 2);
        expect(grandTotal).toBeCloseTo(expected.grandTotal, 2);

        // The rate applied on the taxable base matches the configured rate.
        const applied = (tax / expected.taxBase) * 100;
        expect(Math.abs(applied - taxPercent)).toBeLessThan(0.5);

        await expect(
            this.page
                .locator("div.flex.justify-between", { hasText: "Grand Total" })
                .first(),
        ).toContainText(formatPrice(expected.grandTotal));
    }

    /**
     * Full storefront flow for the before/after-discount scenario: add to cart,
     * guest checkout, apply the coupon, then verify the discounted tax figures.
     */
    async verifyTaxWithCartRule(options: {
        productName: string;
        price: number;
        taxPercent: number;
        discountPercent: number;
        couponCode: string;
        region: CheckoutRegion;
        applyOn: TaxApplyOnMode;
    }): Promise<void> {
        await this.addProductToCart(options.productName);
        await this.proceedToGuestCheckout(options.region);
        await this.applyCoupon(options.couponCode);
        await this.verifyDiscountedCheckoutTax(
            options.price,
            options.taxPercent,
            options.discountPercent,
            options.applyOn,
        );
    }

    /**
     * Full storefront flow parameterised by pricing mode. Tax-exclusive runs
     * also assert the cart's taxable subtotal equals the entered price; for
     * tax-inclusive prices the displayed subtotal is net-of-tax, so only the
     * checkout tax/grand-total are asserted.
     */
    async verifyTaxApplicationForMode(
        productName: string,
        enteredPrice: number,
        taxPercent: number,
        region: CheckoutRegion,
        mode: TaxPricingMode,
    ): Promise<void> {
        await this.addProductToCart(productName);

        if (mode === "excluding_tax") {
            await this.verifyCartSubtotal(enteredPrice);
        }

        await this.proceedToGuestCheckout(region);
        await this.verifyCheckoutTaxForMode(enteredPrice, taxPercent, mode);
    }
}
