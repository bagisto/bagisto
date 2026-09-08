import { Page } from "@playwright/test";
import {
    CheckoutHelper,
    type PaymentMethod,
    type ShippingMethod,
} from "../CheckoutHelper";

export class BundleProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    async addToCart(productName: string): Promise<void> {
        await this.openProduct(productName);
        await this.addOpenProductToCart();
    }

    async checkout(
        productName: string,
        options: {
            shipping?: ShippingMethod;
            payment?: PaymentMethod;
            address?: "saved" | "guest" | "new";
        } = {},
    ): Promise<string> {
        await this.addToCart(productName);

        return this.completeCheckout(options);
    }
}
