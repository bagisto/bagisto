import { Page, expect } from "@playwright/test";
import { CustomerCheckoutActionPage } from "../locators/shop/CustomerCheckoutActionPage"; //
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
    readonly customerCheckoutActionPage: CustomerCheckoutActionPage;

    constructor(page: Page) {
        this.page = page;

        this.customerCheckoutActionPage = new CustomerCheckoutActionPage(page);
    }

    /**
     * Common product search flow
     */
    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.customerCheckoutActionPage.searchInput.fill(productName);
        await this.customerCheckoutActionPage.searchInput.press("Enter");
    }

    /**
     * Common steps to reach checkout page
     */
    async proceedToCheckout() {
        await this.customerCheckoutActionPage.shoppingCartIcon.click();
        await this.customerCheckoutActionPage.continueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.customerCheckoutActionPage.clickProcessButton.click();
    }

    /**
     * Common place order flow
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.customerCheckoutActionPage.clickPlaceOrderButton.click();
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
        await this.customerCheckoutActionPage.addToCartButton.click();
        await expect(
            this.customerCheckoutActionPage.addCartSuccess.first(),
        ).toBeVisible();
        await this.proceedToCheckout();
        await this.customerCheckoutActionPage.chooseShippingMethod.click();
        await this.customerCheckoutActionPage.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
