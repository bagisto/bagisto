import { Locator, Page } from "@playwright/test";

export class RulesShopPage {
    constructor(private page: Page) {}

    // Product search & cart
    get searchInput() {
        return this.page.getByRole("textbox", {
            name: "Search products here",
        });
    }

    get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class, 'secondary-button')])[2]",
        );
    }

    get addToCartSuccessMessage() {
        return this.page.getByText("Item Added Successfully").first();
    }

    get incrementQtyButton() {
        return this.page.locator(".icon-plus");
    }

    get updateCart() {
        return this.page.getByRole("button", { name: "Update Cart" });
    }

    get cartUpdateSuccess() {
        return this.page.getByText("Quantity updated successfully");
    }

    // Coupon
    get applyCouponButton() {
        return this.page.getByRole("button", {
            name: "Apply Coupon",
        });
    }

    get couponInput() {
        return this.page.locator('input[name="code"]:visible');
    }

    get applyButton() {
        return this.page.getByRole("button", {
            name: "Apply",
            exact: true,
        });
    }

    get couponSuccessMessage() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Coupon code applied" });
    }

    // Checkout
    get ShoppingCartIcon() {
        return this.page.locator("(//span[contains(@class, 'icon-cart')])[1]");
    }

    get ContinueButton() {
        return this.page.locator(
            '(//a[contains(., " Continue to Checkout ")])[1]',
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
        return this.page.getByRole("textbox", {
            name: "email@example.com",
        });
    }

    get streetAddress() {
        return this.page.getByRole("textbox", {
            name: "Street Address",
        });
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
        return this.page.getByRole("textbox", {
            name: "Telephone",
        });
    }

    get clickProcessButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    get chooseShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    get choosePaymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }
}
