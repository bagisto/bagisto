import { Page } from "@playwright/test";
import {
    CheckoutHelper,
    type PaymentMethod,
    type ShippingMethod,
} from "../CheckoutHelper";

export class SimpleProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    async checkout(
        productName: string,
        options: {
            shipping?: ShippingMethod;
            payment?: PaymentMethod;
            address?: "saved" | "guest" | "new";
        } = {},
    ): Promise<string> {
        await this.addSimpleProductToCart(productName);

        return this.completeCheckout(options);
    }
}
