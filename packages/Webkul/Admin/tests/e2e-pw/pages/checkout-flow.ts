import { Page, expect } from "@playwright/test";
import { WebLocators } from "../locators/locator";
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
    readonly locators: WebLocators;

    constructor(page: Page) {
        this.page = page;

        this.locators = new WebLocators(page);
    }

    /**
     * Common product search flow
     */
    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill(productName);
        await this.locators.searchInput.press("Enter");
    }

    /**
     * Common steps to reach checkout page
     */
    async proceedToCheckout() {
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
    }

    /**
     * Common place order flow
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
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
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
