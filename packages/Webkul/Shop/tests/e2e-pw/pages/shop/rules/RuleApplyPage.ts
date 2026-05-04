import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import fs from "fs";

export class RuleApplyPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }
    // Shop / Cart
    get searchInput() {
        return this.page.getByRole("textbox", { name: "Search products here" });
    }

    get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class, 'secondary-button')])[2]",
        );
    }

    get addToCartSuccessMessage() {
        return this.page.getByText("Item Added Successfully").first();
    }

    get incrementQtyButton() {
        return this.page.locator(".icon-plus");
    }

    get updateCart() {
        return this.page.getByRole("button", { name: "Update Cart" });
    }

    get cartUpdateSuccess() {
        return this.page.getByText("Quantity updated successfully");
    }

    // Coupon
    get applyCouponButton() {
        return this.page.getByRole("button", { name: "Apply Coupon" });
    }

    get couponInput() {
        return this.page.locator('input[name="code"]:visible');
    }

    get applyButton() {
        return this.page.getByRole("button", { name: "Apply", exact: true });
    }

    get shoppingCartIcon() {
        return this.page.locator("(//span[contains(@class, 'icon-cart')])[1]");
    }

    get continueButton() {
        return this.page.locator(
            '(//a[contains(., " Continue to Checkout ")])[1]',
        );
    }

    get companyName() {
        return this.page.getByRole("textbox", { name: "Company Name" });
    }

    get firstName() {
        return this.page.getByRole("textbox", { name: "First Name" });
    }

    get lastName() {
        return this.page.getByRole("textbox", { name: "Last Name" });
    }

    get shippingEmail() {
        return this.page.getByRole("textbox", { name: "email@example.com" });
    }

    get streetAddress() {
        return this.page.getByRole("textbox", { name: "Street Address" });
    }

    get billingCountry() {
        return this.page.locator('select[name="billing\\.country"]');
    }

    get billingState() {
        return this.page.locator('select[name="billing\\.state"]');
    }

    get billingCity() {
        return this.page.getByRole("textbox", { name: "City" });
    }

    get billingZip() {
        return this.page.getByRole("textbox", { name: "Zip/Postcode" });
    }

    get billingTelephone() {
        return this.page.getByRole("textbox", { name: "Telephone" });
    }

    get clickProcessButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    get chooseShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    get choosePaymentMethod() {
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

    async applyCoupon() {
        await this.applyCouponButton.click();
        await this.couponInput.fill("TEST50");
        await this.applyButton.click();
        await expect(
            this.page.getByText("Coupon code applied successfully.").first(),
        ).toBeVisible();
    }
    async applyCoupon2(allow: string) {
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

    async calculateDiscountedAmmount(
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
                console.log("enter");
                return 0;
            }
            const discount = Number(discountValue);

            return Math.max(subtotal - a * discount, 0);
        }

        if (couponType == "percentage") {
            return subtotal - (subtotal * discountValue) / 100;
        }

        return subtotal;
    }

    async verifyCatalogRule(value: number) {
        await this.visit("");

        const product = this.getSavedProduct();
        await this.searchInput.fill(product.name);
        await this.searchInput.press("Enter");

        const actualPrice = 199;
        const expectedDiscountedPrice = `$${(actualPrice * (1 - value / 100)).toFixed(2)}`;
        // console.log("expectedDiscountedPrice: ", expectedDiscountedPrice);

        await expect(
            this.page
                .locator("div.flex.items-center")
                .locator("p")
                .filter({ hasText: "$" })
                .last(),
        ).toHaveText(expectedDiscountedPrice);
    }

    async applyCouponAtCheckout() {
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
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();

        await this.applyCouponButton.click();
        await this.page.waitForTimeout(1000);
        await this.couponInput.fill("TEST50");
        await this.applyButton.click();
    }

    async applyCouponAtCheckout2() {
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
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();

        await this.applyCouponButton.click();
        await this.page.waitForTimeout(1000);
        await this.couponInput.fill("TEST50");
        await this.applyButton.click();
    }
}
