import { Page } from "@playwright/test";
import { CheckoutShopPage } from "../locators/shop/PayGlocalCheckoutPage";

export class ProductCheckout {
    readonly checkoutShopPage: CheckoutShopPage;

    constructor(private page: Page) {
        this.checkoutShopPage = new CheckoutShopPage(page);
    }

    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");

        await this.checkoutShopPage.searchInput.fill(productName);
        await this.checkoutShopPage.searchInput.press("Enter");
        await this.page.waitForLoadState("networkidle");

        await this.page.getByRole("link", { name: new RegExp(productName, "i") }).first().click();
        await this.page.waitForLoadState("networkidle");
    }

    async addToCart() {
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForTimeout(2000);
    }

    async proceedToCheckout() {
        await this.page.goto("checkout/cart");
        await this.page.waitForLoadState("networkidle");

        await this.checkoutShopPage.continueButton.click();
        await this.page.waitForLoadState("networkidle");
    }

    /**
     * Fill the guest address form. The state select is only populated once a country is chosen,
     * so the two have to be set in order.
     */
    async fillGuestAddress() {
        await this.page.getByRole("textbox", { name: /first name/i }).fill("Test");
        await this.page.getByRole("textbox", { name: /last name/i }).fill("User");
        await this.page.getByRole("textbox", { name: /email/i }).fill("test@example.com");
        await this.page.getByRole("textbox", { name: /address/i }).first().fill("123 Test Street");
        await this.page.getByRole("textbox", { name: /city/i }).fill("Test City");
        await this.page.getByRole("textbox", { name: /zip|postcode/i }).first().fill("12345");
        await this.page.getByRole("textbox", { name: /phone|telephone/i }).first().fill("1234567890");

        await this.page.locator("select[name*='country']").first().selectOption("IN");
        await this.page.waitForTimeout(1000);
        await this.page.locator("select[name*='state']").first().selectOption("UP");

        await this.checkoutShopPage.proceedButton.click();
        await this.page.waitForLoadState("networkidle");
    }

    async selectShippingMethod() {
        await this.checkoutShopPage.freeShipping.click();
        await this.page.waitForTimeout(1500);
    }

    async selectPayGlocal() {
        const radio = this.checkoutShopPage.payGlocalPayment;

        if (await radio.isVisible().catch(() => false)) {
            await radio.check();
        } else {
            await this.checkoutShopPage.payGlocalLabel.click();
        }

        await this.page.waitForTimeout(1000);
    }

    async placeOrder() {
        await this.checkoutShopPage.placeOrderButton.click();
    }

    /**
     * Walk a guest all the way to the payment step with PayGlocal selected.
     */
    async reachPaymentStep(productName: string) {
        await this.searchProduct(productName);
        await this.addToCart();
        await this.proceedToCheckout();
        await this.fillGuestAddress();
        await this.selectShippingMethod();
    }
}
