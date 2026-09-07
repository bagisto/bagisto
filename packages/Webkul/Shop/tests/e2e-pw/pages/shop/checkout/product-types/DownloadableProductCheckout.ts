import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";

export class DownloadableProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    async addToCart(productName: string): Promise<void> {
        await this.openProduct(productName);

        await expect(this.clickLink.first()).toBeVisible();

        await this.clickLink.first().click();
        await this.addOpenProductToCart();
    }

    async checkout(productName: string): Promise<string> {
        await this.addToCart(productName);
        await this.proceedWithSavedAddress();
        await this.expectNoShippingStep();
        await this.choosePayment("moneytransfer");

        return this.placeOrder();
    }
}
