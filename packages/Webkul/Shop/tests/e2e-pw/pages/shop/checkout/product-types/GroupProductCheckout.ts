import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";
import { ProductDataManager } from "../../../admin/catalog/products/ProductDataManager";

/**
 * Group product checkout flow
 * Handles grouped product items and checkout
 */
export class GroupProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    /**
     * Select group product items
     */
    private async selectGroupItems() {
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
    }

    /**
     * Group product checkout with default shipping
     */
    async checkoutWithDefaultShipping() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.selectGroupItems();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Group product checkout with flat rate shipping
     */
    async checkoutWithFlatRateShipping() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.selectGroupItems();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseFlatShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Group product checkout with Cash On Delivery
     */
    async checkoutWithCOD() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.selectGroupItems();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseFlatShippingMethod.click();
        await this.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    /**
     * Group product guest checkout
     */
    async guestCheckout() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.selectGroupItems();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutComplete();
    }
}
