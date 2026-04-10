import { Page, expect } from "@playwright/test";
import { CheckoutShopLocators } from "../locators/shop/checkout-shop"; //
import fs from "fs";

/**
 * Reads product data from JSON file
 */
function readProductData() {
    const product = JSON.parse(fs.readFileSync("product-data.json", "utf-8"));

    const productName = product.name;

    return productName;
}

export class ProductCheckout {
    readonly page: Page;
    readonly checkoutShopLocators: CheckoutShopLocators;

    constructor(page: Page) {
        this.page = page;

        this.checkoutShopLocators = new CheckoutShopLocators(page);
    }

    /**
     * Common product search flow
     */
    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopLocators.searchInput.fill(productName);
        await this.checkoutShopLocators.searchInput.press("Enter");
    }

    /**
     * Common steps to reach checkout page
     */
    async proceedToCheckout() {
        await this.checkoutShopLocators.ShoppingCartIcon.click();
        await this.checkoutShopLocators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.checkoutShopLocators.clickProcessButton.click();
    }

    /**
     * Common place order flow
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.checkoutShopLocators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    /**
     * Common checkout flow
     */
    async customerCheckout() {
        const acceptButton = this.page.getByRole("button", { name: "Accept" });

        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }

        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(
            this.checkoutShopLocators.addCartSuccess.first(),
        ).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
