import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";
import { ProductDataManager } from "../../../admin/catalog/products/ProductDataManager";

/**
 * Bundle product checkout flow
 * Handles bundle products with multiple items
 */
export class BundleProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    /**
     * Bundle product checkout with default shipping
     */
    async checkoutWithDefaultShipping() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Bundle product checkout with flat rate shipping
     */
    async checkoutWithFlatRateShipping() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseFlatShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Bundle product checkout with Cash On Delivery
     */
    async checkoutWithCOD() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseFlatShippingMethod.click();
        await this.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    /**
     * Bundle product guest checkout
     */
    async guestCheckout() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutComplete();
    }

    /**
     * Bundle product checkout with new address
     */
    async checkoutWithNewAddress() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await super.checkoutWithNewAddress();
    }
}
