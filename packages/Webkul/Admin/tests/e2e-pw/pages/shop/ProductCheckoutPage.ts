import fs from "fs";
import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

function readProductData() {
    const product = JSON.parse(fs.readFileSync("product-data.json", "utf-8"));

    return product.name;
}

export class ProductCheckoutPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get acceptButton() {
        return this.page.getByRole("button", { name: "Accept" });
    }

    private get searchInput() {
        return this.page.getByRole("textbox", {
            name: "Search products here",
        });
    }

    private get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class,'secondary-button')])[2]",
        );
    }

    private get shoppingCartIcon() {
        return this.page.locator("(//span[contains(@class,'icon-cart')])[1]");
    }

    private get addCartSuccess() {
        return this.page.getByText("Item Added Successfully");
    }

    private get continueButton() {
        return this.page.locator(
            '(//a[contains(.," Continue to Checkout ")])[1]',
        );
    }

    private get addressRadio() {
        return this.page.locator(".icon-radio-unselect").first();
    }

    private get proceedButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    private get shippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    private get paymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }

    private get placeOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }

    async searchProduct(productName: string) {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
    }

    async proceedToCheckout() {
        await this.shoppingCartIcon.click();
        await this.continueButton.click();
        await this.addressRadio.click();
        await this.proceedButton.click();
    }

    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.placeOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    async customerCheckout() {
        if (await this.acceptButton.isVisible()) {
            await this.acceptButton.click();
        }

        const productName = readProductData();

        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.shippingMethod.click();
        await this.paymentMethod.click();
        await this.placeOrder();
    }
}
