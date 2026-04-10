import { Page, expect } from "@playwright/test";
import { CheckoutShopPage } from "../locators/shop/checkout"; //
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
    readonly checkoutShopPage: CheckoutShopPage;

    constructor(page: Page) {
        this.page = page;

        this.checkoutShopPage = new CheckoutShopPage(page);
    }

    /**
     * Common product search flow
     */
    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopPage.searchInput.fill(productName);
        await this.checkoutShopPage.searchInput.press("Enter");
    }

    /**
     * Common steps to reach checkout page
     */
    async proceedToCheckout() {
        await this.checkoutShopPage.shoppingCartIcon.click();
        await this.checkoutShopPage.continueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.checkoutShopPage.clickProcessButton.click();
    }

    /**
     * Common place order flow
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.checkoutShopPage.clickPlaceOrderButton.click();
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
        await this.checkoutShopPage.addToCartButton.click();
        await expect(
            this.checkoutShopPage.addCartSuccess.first(),
        ).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
