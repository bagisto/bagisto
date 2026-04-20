import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";
import { ProductDataManager } from "../../../admin/catalog/products/ProductDataManager";

/**
 * Downloadable product checkout flow
 * Handles downloadable link selection and checkout
 */
export class DownloadableProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    /**
     * Downloadable product checkout
     */
    async checkout() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.clickLink.click();
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
