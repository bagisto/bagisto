import { Locator, Page } from "@playwright/test";

export class ACLShopPage {
    constructor(private page: Page) {}
    
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
            '(//a[contains(.," Continue to Checkout ")])[1]',
        );
    }

    get clickProcessButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    get chooseShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    get chooseFlatShippingMeathod() {
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

    get checkoutsuccessPage() {
        return this.page.locator("text=Thank you for your order!");
    }
}
