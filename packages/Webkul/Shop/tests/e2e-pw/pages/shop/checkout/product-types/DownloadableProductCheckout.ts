import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";

export class DownloadableProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    private async placeDownloadableOrder(): Promise<string> {
        await this.proceedWithSavedAddress();
        await this.expectNoShippingStep();
        await this.choosePayment("moneytransfer");

        return this.placeOrder();
    }

    async addToCart(productName: string): Promise<void> {
        await this.openProduct(productName);

        await expect(this.downloadableLinkOption).toBeVisible();

        await this.downloadableLinkOption.click();
        await this.addOpenProductToCart();
    }

    async addToCartWithLink(
        productName: string,
        linkTitle: string,
    ): Promise<void> {
        await this.openProduct(productName);

        await expect(this.downloadableLinkByTitle(linkTitle)).toBeVisible();

        await this.downloadableLinkByTitle(linkTitle).click();
        await this.addOpenProductToCart();
    }

    async checkout(productName: string): Promise<string> {
        await this.addToCart(productName);

        return this.placeDownloadableOrder();
    }

    async checkoutWithLink(
        productName: string,
        linkTitle: string,
    ): Promise<string> {
        await this.addToCartWithLink(productName, linkTitle);

        return this.placeDownloadableOrder();
    }
}
