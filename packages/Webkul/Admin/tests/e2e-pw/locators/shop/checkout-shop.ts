import { Locator, Page } from "@playwright/test";

export class CheckoutShopLocators {
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

    get ShoppingCartIcon() {
        return this.page.locator("(//span[contains(@class,'icon-cart')])[1]");
    }

    get addCartSuccess() {
        return this.page.getByText("Item Added Successfully");
    }

    get ContinueButton() {
        return this.page.locator(
            '(//a[contains(.," Continue to Checkout ")])[1]',
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

    get CheckoutsuccessPage() {
        return this.page.locator("text=Thank you for your order!");
    }
}
