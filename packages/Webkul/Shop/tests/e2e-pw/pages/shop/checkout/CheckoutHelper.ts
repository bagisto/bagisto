import { Page, expect, Locator } from "@playwright/test";
import { BasePage } from "../../BasePage";

/**
 * Base checkout helper for common checkout operations
 * Includes all locators needed for shop checkout flow
 */
export class CheckoutHelper extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    get searchInput() {
        return this.page.getByRole("textbox", {
            name: "Search products here",
        });
    }

    get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class,'secondary-button')])[2]",
        );
    }

    get shoppingCartIcon() {
        return this.page.locator("(//span[contains(@class,'icon-cart')])[1]");
    }

    get addCartSuccess() {
        return this.page.getByText("Item Added Successfully");
    }

    get continueButton() {
        return this.page.locator(
            '//a[contains(.," Continue to Checkout ")][1]',
        );
    }

    get companyName() {
        return this.page.getByRole("textbox", { name: "Company Name" });
    }

    get firstName() {
        return this.page.getByRole("textbox", { name: "First Name" });
    }

    get lastName() {
        return this.page.getByRole("textbox", { name: "Last Name" });
    }

    get shippingEmail() {
        return this.page.getByRole("textbox", { name: "email@example.com" });
    }

    get streetAddress() {
        return this.page.getByRole("textbox", { name: "Street Address" });
    }

    get addNewAddress() {
        return this.page.getByText("Add new address");
    }

    get billingCountry() {
        return this.page.locator('select[name="billing\\.country"]');
    }

    get billingState() {
        return this.page.locator('select[name="billing\\.state"]');
    }

    get billingCity() {
        return this.page.getByRole("textbox", { name: "City" });
    }

    get billingZip() {
        return this.page.getByRole("textbox", { name: "Zip/Postcode" });
    }

    get billingTelephone() {
        return this.page.getByRole("textbox", { name: "Telephone" });
    }

    get clickSaveAddressButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    get clickProcessButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    get chooseShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    get chooseFlatShippingMethod() {
        return this.page.getByText("Flat Rate").first();
    }

    get choosePaymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }

    get choosePaymentMethodCOD() {
        return this.page.getByAltText("Cash On Delivery");
    }

    get clickPlaceOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }

    get clickLink() {
        return this.page.locator('label.icon-uncheck');
    }

    get eventTicket() {
        return this.page.locator('div.place-items-end>div.flex>span.icon-plus[aria-label="Increase Quantity"]')
    }

    get hourlyRadio() {
        return this.page.locator('span.flex>label[for="booking[hourly]"].icon-radio-unselect')
    }

    get dailyRadio() {
        return this.page.locator('span.flex>label[for="booking[daily]"].icon-radio-unselect')
    }

    getMinimizebtn(){
        return this.page.locator('a.phpdebugbar-minimize-btn')
    }

    /**
     * Search for product by name
     */
    async searchProduct(productName: string) {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
    }

    /**
     * Navigate from cart to checkout (billing address step)
     */
    async proceedToCheckout() {
        if (await this.shoppingCartIcon.isVisible()) {
            await this.shoppingCartIcon.click();
        }
        await this.continueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.clickProcessButton.click();
    }

    /**
     * Place order and wait for processing
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(5000);
    }

    /**
     * Common guest checkout address entry
     */
    async fillGuestCheckoutAddress() {
        await this.companyName.fill("Web");
        await this.firstName.fill("demo");
        await this.lastName.fill("guest");
        await this.shippingEmail.fill("demo@example.com");
        await this.streetAddress.fill("north street");
        await this.billingCountry.selectOption({ value: "IN" });
        await this.billingState.selectOption({ value: "UP" });
        await this.billingCity.fill("test city");
        await this.billingZip.fill("123456");
        await this.billingTelephone.fill("2365432789");
    }

    /**
     * Complete guest checkout end-to-end
     */
    async guestCheckoutComplete() {
        await this.shoppingCartIcon.click();
        await this.continueButton.click();
        await this.fillGuestCheckoutAddress();
        await this.clickProcessButton.click();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Complete checkout with new address
     */
    async checkoutWithNewAddress() {
        await this.shoppingCartIcon.click();
        await this.continueButton.click();
        await this.addNewAddress.click();
        await this.fillGuestCheckoutAddress();
        await this.clickSaveAddressButton.click();
        await this.clickProcessButton.click();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
