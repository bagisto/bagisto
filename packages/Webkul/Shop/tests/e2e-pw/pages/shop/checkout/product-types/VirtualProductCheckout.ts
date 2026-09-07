import { Page } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";

export class VirtualProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    async checkout(productName: string): Promise<string> {
        await this.addSimpleProductToCart(productName);
        await this.proceedWithSavedAddress();
        await this.expectNoShippingStep();
        await this.choosePayment("moneytransfer");

        return this.placeOrder();
    }
}
