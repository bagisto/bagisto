import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";
import { ProductDataManager } from "../../../admin/catalog/products/ProductDataManager";

export class ConfigurableProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    private async selectProductOptions() {
        await this.page.getByLabel("Color").selectOption({ label: "Red" });
        await this.page.getByLabel("Size").selectOption({ label: "S" });
        await this.addToCartButton.click();
    }

    async checkoutWithDefaultShipping() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.selectProductOptions();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async checkoutWithFlatRateShipping() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.selectProductOptions();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseFlatShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async checkoutWithCOD() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.selectProductOptions();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.chooseFlatShippingMethod.click();
        await this.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async guestCheckout() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.selectProductOptions();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutComplete();
    }

    async checkoutWithNewAddress() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.selectProductOptions();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await super.checkoutWithNewAddress();
    }
}
