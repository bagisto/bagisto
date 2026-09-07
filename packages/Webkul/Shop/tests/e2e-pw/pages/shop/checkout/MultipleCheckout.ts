import { Page } from "@playwright/test";
import { CheckoutHelper } from "./CheckoutHelper";
import { BundleProductCheckout } from "./product-types/BundleProductCheckout";
import { ConfigurableProductCheckout } from "./product-types/ConfigurableProductCheckout";
import { DownloadableProductCheckout } from "./product-types/DownloadableProductCheckout";
import { GroupProductCheckout } from "./product-types/GroupProductCheckout";

export type CartItemType =
    | "simple"
    | "virtual"
    | "configurable"
    | "grouped"
    | "bundle"
    | "downloadable";

export interface CartItem {
    type: CartItemType;
    name: string;
}

export class MultipleCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    async addItems(items: CartItem[]): Promise<void> {
        for (const item of items) {
            switch (item.type) {
                case "simple":
                case "virtual":
                    await this.addSimpleProductToCart(item.name);
                    break;

                case "configurable":
                    await new ConfigurableProductCheckout(this.page).addToCart(item.name);
                    break;

                case "grouped":
                    await new GroupProductCheckout(this.page).addToCart(item.name);
                    break;

                case "bundle":
                    await new BundleProductCheckout(this.page).addToCart(item.name);
                    break;

                case "downloadable":
                    await new DownloadableProductCheckout(this.page).addToCart(item.name);
                    break;
            }
        }
    }

    async checkout(items: CartItem[]): Promise<string> {
        await this.addItems(items);

        return this.completeCheckout({ shipping: "free", payment: "moneytransfer" });
    }
}
