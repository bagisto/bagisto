import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import fs from "fs";

export class RuleApplyPage extends BasePage {
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

    private get addToCartSuccessMessage() {
        return this.page.getByText("Item Added Successfully").first();
    }

    private get incrementQtyButton() {
        return this.page.locator(".icon-plus");
    }

    private get updateCart() {
        return this.page.getByRole("button", { name: "Update Cart" });
    }

    private get cartUpdateSuccess() {
        return this.page.getByText("Quantity updated successfully");
    }

    private get grandTotalAmount() {
        return this.page
            .locator("text=Grand Total")
            .locator("..")
            .locator("p")
            .nth(1);
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

    private get shoppingCartIcon() {
        return this.page.locator('[class*="icon-cart"]').first();
    }

    private get continueButton() {
        return this.page.locator(
            '(//a[contains(., " Continue to Checkout ")])[1]',
        );
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

    private get shippingEmail() {
        return this.page.locator('input[name="billing\\.email"]');
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

    private get clickProcessButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    private get chooseShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    private get chooseFlatShippingMethod() {
        return this.page.getByText("Flat Rate").first();
    }

    private get choosePaymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }

    async getSubTotalValue(): Promise<number> {
        await this.page.waitForLoadState("networkidle");

        const subtotalRow = this.page
            .locator("div.flex.justify-between.text-right", {
                hasText: "Subtotal",
            })
            .first();

        await subtotalRow.waitFor({ state: "visible", timeout: 15000 });

        const subtotalText = await subtotalRow.locator("p").last().innerText();
        return parseFloat(subtotalText.replace(/[^0-9.]/g, ""));
    }

    getSavedProduct() {
        const filePath = "product-data.json";
        const data = fs.readFileSync(filePath, "utf-8");
        return JSON.parse(data);
    }

    async applyCoupon(allow?: string) {
        if (allow == "yes") {
            await this.visit("");

            const product = this.getSavedProduct();
            await this.searchInput.fill(product.name);

            await this.searchInput.press("Enter");
            await this.addToCartButton.first().click();
            await expect(this.addToCartSuccessMessage).toBeVisible();

            await this.visit("checkout/cart");
        }
        await this.applyCouponButton.click();
        await this.couponInput.fill("TEST50");
        await this.applyButton.click();
        await expect(
            this.page.getByText("Coupon code applied successfully.").first(),
        ).toBeVisible();
    }

    async calculateDiscountedAmount(
        discountValue: number,
        couponType: string,
        incrementTimes?: number,
    ): Promise<number> {
        await this.visit("");

        const product = this.getSavedProduct();
        await this.searchInput.fill(product.name);

        await this.searchInput.press("Enter");
        await this.addToCartButton.first().click();
        await expect(this.addToCartSuccessMessage).toBeVisible();

        await this.visit("checkout/cart");

        var a = 1;
        if (incrementTimes && incrementTimes > 0) {
            for (let i = 0; i < incrementTimes; i++) {
                await this.incrementQtyButton.first().click();
                a++;
            }

            await this.updateCart.click();
            await expect(this.cartUpdateSuccess.first()).toBeVisible();
        }

        const subtotal = await this.getSubTotalValue();

        if (couponType == "fixed") {
            if (subtotal < Number(discountValue)) {
                return 0;
            }
            const discount = Number(discountValue);

            return Math.max(subtotal - a * discount, 0);
        }

        if (couponType == "percentage") {
            return subtotal - (subtotal * discountValue) / 100;
        }

        if (couponType == "fixedAmmountWholeCart") {
            if (subtotal < Number(discountValue)) {
                return 0;
            }
            const discount = Number(discountValue);

            return Math.max(subtotal - discount, 0);
        }

        return subtotal;
    }

    async addSavedProductToCart(quantity: number = 1): Promise<number> {
        await this.visit("");

        const product = this.getSavedProduct();

        await this.searchInput.fill(product.name);
        await this.searchInput.press("Enter");
        await this.addToCartButton.first().click();
        await expect(this.addToCartSuccessMessage).toBeVisible();

        await this.visit("checkout/cart");

        if (quantity > 1) {
            for (let i = 1; i < quantity; i++) {
                await this.incrementQtyButton.first().click();
            }

            await this.updateCart.click();
            await expect(this.cartUpdateSuccess.first()).toBeVisible();
        }

        return this.getSubTotalValue();
    }

    async expectGrandTotal(amount: number): Promise<void> {
        const formatted = new Intl.NumberFormat("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(amount);

        await expect(this.grandTotalAmount).toContainText(`$${formatted}`);
    }

    async verifyCatalogRule(value: number, type: string) {
        await this.visit("");

        const product = this.getSavedProduct();
        await this.searchInput.fill(product.name);
        await this.searchInput.press("Enter");

        const actualPrice = 199;

        let expectedDiscountedPrice = "";

        if (type === "percentage") {
            const discountedPrice = actualPrice - (actualPrice * value) / 100;

            expectedDiscountedPrice = `$${discountedPrice.toFixed(2)}`;
        }

        if (type === "fixed") {
            const discountedPrice = Math.max(actualPrice - value, 0);

            expectedDiscountedPrice = `$${discountedPrice.toFixed(2)}`;
        }

        await expect(
            this.page
                .locator("div.flex.items-center")
                .locator("p")
                .filter({ hasText: "$" })
                .last(),
        ).toHaveText(expectedDiscountedPrice);
    }

    async applyCouponAtCheckout(allowShipping?: string) {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.shoppingCartIcon.click();
        await this.continueButton.click();

        await this.companyName.fill("Web");
        await this.firstName.fill("demo");
        await this.lastName.fill("guest");
        await this.shippingEmail.fill("demo@example.com");
        await this.streetAddress.fill("north street");
        await this.billingCountry.selectOption({ value: "IN" });
        await this.billingState.selectOption({ value: "UP" });
        await this.billingCity.fill("test city");
        await this.billingZip.fill("123456");
        await this.billingTelephone.fill("2365432789");
        await this.clickProcessButton.click();

        if (allowShipping === "yes") {
            await this.chooseFlatShippingMethod.click();
        } else {
            await this.chooseShippingMethod.click();
        }

        await this.choosePaymentMethod.click();
        await this.applyCouponButton.click();
        await this.page.waitForTimeout(1000);
        await this.couponInput.fill("TEST50");
        await this.applyButton.click();
    }
}
