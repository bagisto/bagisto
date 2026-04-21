import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import fs from "fs";

export class RuleApplyPage extends BasePage {
    readonly couponCode: string;

    constructor(page: Page) {
        super(page);
        this.couponCode = `CP-${Date.now()}`;
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

    get couponSuccessMessage() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Coupon code applied successfully." });
    }

    // Checkout
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

    getSavedProduct() {
        const filePath = "product-data.json";
        const data = fs.readFileSync(filePath, "utf-8");
        return JSON.parse(data);
    }

    async applyCoupon(incrementTimes?: number) {
        await this.visit("");

        const product = this.getSavedProduct();
        await this.searchInput.fill(product.name);

        await this.searchInput.press("Enter");
        await this.addToCartButton.first().click();
        await expect(this.addToCartSuccessMessage).toBeVisible();

        await this.visit("checkout/cart");

        if (incrementTimes && incrementTimes > 0) {
            for (let i = 0; i < incrementTimes; i++) {
                await this.incrementQtyButton.first().click();
            }

            await this.updateCart.click();
            await expect(this.cartUpdateSuccess.first()).toBeVisible();
        }

        await this.applyCouponButton.click();
        await this.couponInput.fill(this.couponCode);
        await this.applyButton.click();
        await expect(this.couponSuccessMessage).toBeVisible();
    }

    async verifyCatalogRule() {
        await this.visit("");

        const product = this.getSavedProduct();
        await this.searchInput.fill(product.name);
        await this.searchInput.press("Enter");

        const actualPrice = 199;
        const expectedDiscountedPrice = `$${(actualPrice * 0.5).toFixed(2)}`;

        const productCard = this.page
            .locator("div")
            .filter({ hasText: product.name });

        await expect(
            productCard.locator("div.flex.items-center p").last(),
        ).toContainText(expectedDiscountedPrice);
    }

    async applyCouponAtCheckout() {
        await this.visit("");

        const product = this.getSavedProduct();
        await this.searchInput.fill(product.name);

        await this.searchInput.press("Enter");
        await this.addToCartButton.first().click();
        await expect(this.addToCartSuccessMessage).toBeVisible();

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
        await this.couponInput.fill(this.couponCode);
        await this.applyButton.click();
        await expect(this.couponSuccessMessage).toBeVisible();
    }
}
