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
        return this.page.getByRole("textbox", { name: "Email" });
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

    async addProductToCart(productName: string): Promise<void> {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
        await this.addToCartButton.first().click();
        await expect(this.addToCartSuccess).toBeVisible();
    }

    async verifyCartSubtotal(price: number): Promise<void> {
        await this.visit("checkout/cart");

        const subtotal = await this.readSummaryAmount("Subtotal");

        expect(subtotal).toBeCloseTo(price, 2);
    }

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

        expect(tax).toBeCloseTo(expectedTax, 2);
        expect(grandTotal).toBeCloseTo(expectedTotal, 2);

        const applied = appliedPercentage(grandTotal, tax);
        expect(Math.abs(applied - taxPercent)).toBeLessThan(0.5);

        await expect(
            this.page
                .locator("div.flex.justify-between", { hasText: "Grand Total" })
                .first(),
        ).toContainText(formatPrice(expectedTotal));
    }

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

    async applyCoupon(couponCode: string): Promise<void> {
        await this.applyCouponButton.click();
        await this.couponInput.fill(couponCode);
        await this.couponSubmitButton.click();

        await expect(
            this.page.getByText("Coupon code applied successfully.").first(),
        ).toBeVisible();
    }

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

        expect(discount).toBeCloseTo(expected.discount, 2);

        expect(tax).toBeCloseTo(expected.tax, 2);
        expect(grandTotal).toBeCloseTo(expected.grandTotal, 2);

        const applied = (tax / expected.taxBase) * 100;
        expect(Math.abs(applied - taxPercent)).toBeLessThan(0.5);

        await expect(
            this.page
                .locator("div.flex.justify-between", { hasText: "Grand Total" })
                .first(),
        ).toContainText(formatPrice(expected.grandTotal));
    }

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
