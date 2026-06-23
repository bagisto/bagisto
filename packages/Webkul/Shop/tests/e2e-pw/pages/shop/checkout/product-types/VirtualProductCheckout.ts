import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";
import { ProductDataManager } from "../../../admin/catalog/products/ProductDataManager";

/**
 * Virtual product checkout flow
 * Handles virtual products (no shipping required)
 */
export class VirtualProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    /**
     * Virtual product checkout (no shipping address, only payment)
     */
    async checkout() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
