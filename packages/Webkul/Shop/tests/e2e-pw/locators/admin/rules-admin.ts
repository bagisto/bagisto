import { Locator, Page } from "@playwright/test";

export class RulesAdminLocators {
    constructor(private page: Page) {}

// Auth
get emailInput() {
    return this.page.locator('input[name="email"]');
}

get passwordInput() {
    return this.page.locator('input[name="password"]');
}

get signInButton() {
    return this.page.locator('button:has-text("Sign In")');
}

// Cart Rule
get createCartRuleButton() {
    return this.page.locator('a.primary-button:has-text("Create Cart Rule")');
}

get cartRuleForm() {
    return this.page.locator('form[action*="/promotions/cart-rules/create"]');
}

// Catalog Rule
get createCatalogRuleButton() {
    return this.page.locator('a.primary-button:has-text("Create Catalog Rule")');
}

get catalogRuleButton() {
    return this.page.locator('button.primary-button:has-text("Save Catalog Rule")');
}

// General
get nameInput() {
    return this.page.locator("#name");
}

get descriptionInput() {
    return this.page.locator("#description");
}

get couponTypeSelect() {
    return this.page.locator("#coupon_type");
}

get autoGenerationSelect() {
    return this.page.locator("#use_auto_generation");
}

get couponCodeInput() {
    return this.page.getByRole("textbox", { name: "Coupon Code" });
}

get usesPerCouponInput() {
    return this.page.getByRole("textbox", { name: "Uses Per Coupon" });
}

get usesPerCustomerInput() {
    return this.page.getByRole("textbox", { name: "Uses Per Customer" });
}

// Conditions
get addConditionButton() {
    return this.page.locator('div.secondary-button:has-text("Add Condition")');
}

get conditionAttributeSelect() {
    return this.page.locator('select[id="conditions\\[0\\]\\[attribute\\]"]');
}

get conditionOperatorSelect() {
    return this.page.locator('select[name="conditions\\[0\\]\\[operator\\]"]');
}

get conditionValueInput() {
    return this.page.locator('input[name="conditions\\[0\\]\\[value\\]"]');
}

get selectCodintionOption() {
    return this.page.locator('select[name="conditions[0][value]"]');
}

// Actions
get actionTypeSelect() {
    return this.page.locator("#action_type");
}

get discountAmountInput() {
    return this.page.locator('input[name="discount_amount"]');
}

// Settings
get sortOrderInput() {
    return this.page.locator('input[name="sort_order"]');
}

get channelCheckbox() {
    return this.page.locator('label[for="channel__1"]');
}

get customerGroupCheckbox() {
    return this.page.locator("#customer_group__1");
}

get customerGroupCheckbox2() {
    return this.page.locator('label[for="customer_group__2"]');
}

get statusToggle() {
    return this.page.locator('label[for="status"]');
}

// Save
get saveCartRuleButton() {
    return this.page.locator('button.primary-button:has-text("Save Cart Rule")');
}

get successMessage() {
    return this.page.locator("#app");
}

// Product search & cart
get searchInput() {
    return this.page.getByRole("textbox", {
        name: "Search products here",
    });
}

get addToCartButton() {
    return this.page.locator(
        "(//button[contains(@class, 'secondary-button')])[2]"
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
    return this.page.locator(
        "(//span[contains(@class, 'icon-cart')])[1]"
    );
}

get ContinueButton() {
    return this.page.locator('(//a[contains(., " Continue to Checkout ")])[1]');
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

// Delete
get deleteIcon() {
    return this.page.locator(".icon-delete");
}

get agree() {
    return this.page.getByRole("button", {
        name: "Agree",
        exact: true,
    });
}

get selectRowBtn() {
    return this.page.locator(".icon-uncheckbox");
}

get selectAction() {
    return this.page.getByRole("button", { name: "Select Action" });
}

get selectDelete() {
    return this.page.getByRole("link", { name: "Delete" });
}

get productDeleteSuccess() {
    return this.page.getByText(
        "Selected Products Deleted Successfully",
    );
}
}
