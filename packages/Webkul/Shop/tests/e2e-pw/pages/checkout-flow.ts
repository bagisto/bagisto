import { Page, expect } from "@playwright/test";
import { CheckoutShopLocators } from "../locators/shop/shop-checkout-locator";
import fs from "fs";

/**
 * Reads product data from JSON file
 */
function readProductData() {
    const product = JSON.parse(fs.readFileSync("product-data.json", "utf-8"));

    const productName = product.name;

    return productName;
}

export class ProductCheckout {
    readonly page: Page;
    readonly checkoutShopLocators: CheckoutShopLocators;

    constructor(page: Page) {
        this.page = page;

        this.checkoutShopLocators = new CheckoutShopLocators(page);
    }

    /**
     * Common product search flow
     */
    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopLocators.searchInput.fill(productName);
        await this.checkoutShopLocators.searchInput.press("Enter");
    }

    /**
     * Common steps to reach checkout page
     */
    async proceedToCheckout() {
        await this.checkoutShopLocators.ShoppingCartIcon.click();
        await this.checkoutShopLocators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.checkoutShopLocators.clickProcessButton.click();
    }

    /**
     * Common place order flow
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.checkoutShopLocators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    /**
     * Common guest checkout flow for all products
     */
    async guestCheckoutCommon() {
        await this.checkoutShopLocators.ShoppingCartIcon.click();
        await this.checkoutShopLocators.ContinueButton.click();
        await this.checkoutShopLocators.companyName.fill("Web");
        await this.checkoutShopLocators.firstName.fill("demo");
        await this.checkoutShopLocators.lastName.fill("guest");
        await this.checkoutShopLocators.shippingEmail.fill("demo@example.com");
        await this.checkoutShopLocators.streetAddress.fill("north street");
        await this.checkoutShopLocators.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopLocators.billingState.selectOption({ value: "UP" });
        await this.checkoutShopLocators.billingCity.fill("test city");
        await this.checkoutShopLocators.billingZip.fill("123456");
        await this.checkoutShopLocators.billingTelephone.fill("2365432789");
        await this.checkoutShopLocators.clickProcessButton.click();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Common checkout flow
     */
    async customerCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Simple product checkout flow
     */
    async simpleCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async simpleCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async shippingChangeCheckoutSimple() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopLocators.ShoppingCartIcon.click();
        await this.checkoutShopLocators.ContinueButton.click();
        await this.checkoutShopLocators.addNewAddress.click();
        await this.checkoutShopLocators.companyName.fill("Web");
        await this.checkoutShopLocators.firstName.fill("demo");
        await this.checkoutShopLocators.lastName.fill("guest");
        await this.checkoutShopLocators.shippingEmail.fill("demo@example.com");
        await this.checkoutShopLocators.streetAddress.fill("north street");
        await this.checkoutShopLocators.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopLocators.billingState.selectOption({ value: "UP" });
        await this.checkoutShopLocators.billingCity.fill("test city");
        await this.checkoutShopLocators.billingZip.fill("123456");
        await this.checkoutShopLocators.billingTelephone.fill("2365432789");
        await this.checkoutShopLocators.clickSaveAddressButton.click();
        await this.checkoutShopLocators.clickProcessButton.click();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async guestCheckoutSimple() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopLocators.ShoppingCartIcon.click();
        await this.checkoutShopLocators.ContinueButton.click();
        await this.checkoutShopLocators.companyName.fill("Web");
        await this.checkoutShopLocators.firstName.fill("demo");
        await this.checkoutShopLocators.lastName.fill("guest");
        await this.checkoutShopLocators.shippingEmail.fill("demo@example.com");
        await this.checkoutShopLocators.streetAddress.fill("north street");
        await this.checkoutShopLocators.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopLocators.billingState.selectOption({ value: "UP" });
        await this.checkoutShopLocators.billingCity.fill("test city");
        await this.checkoutShopLocators.billingZip.fill("123456");
        await this.checkoutShopLocators.billingTelephone.fill("2365432789");
        await this.checkoutShopLocators.clickProcessButton.click();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Configurable product checkout flow
     */
    async configCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async shippingChangeCheckoutConfig() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopLocators.ShoppingCartIcon.click();
        await this.checkoutShopLocators.ContinueButton.click();
        await this.checkoutShopLocators.addNewAddress.click();
        await this.checkoutShopLocators.companyName.fill("Web");
        await this.checkoutShopLocators.firstName.fill("demo");
        await this.checkoutShopLocators.lastName.fill("guest");
        await this.checkoutShopLocators.shippingEmail.fill("demo@example.com");
        await this.checkoutShopLocators.streetAddress.fill("north street");
        await this.checkoutShopLocators.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopLocators.billingState.selectOption({ value: "UP" });
        await this.checkoutShopLocators.billingCity.fill("test city");
        await this.checkoutShopLocators.billingZip.fill("123456");
        await this.checkoutShopLocators.billingTelephone.fill("2365432789");
        await this.checkoutShopLocators.clickSaveAddressButton.click();
        await this.checkoutShopLocators.clickProcessButton.click();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async guestCheckoutConfigurable() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    async configCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async configCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    /**
     * Booking product checkout flow
     */
    async bookingCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();

        /**
         * Select NEXT SUNDAY
         */
        const today = new Date();
        const daysUntilSunday = (7 - today.getDay()) % 7 || 7;
        const nextSunday = new Date(today);
        nextSunday.setDate(today.getDate() + daysUntilSunday);

        /**
         * Format YYYY-MM-DD
         */
        const formattedSunday = nextSunday.toISOString().split("T")[0];

        const dateInput = this.page.locator('input[name="booking[date]"]');
        await dateInput.fill(formattedSunday);
        await dateInput.press("Enter");
        const slotSelect = this.page.locator('select[name="booking[slot]"]');
        await this.page.waitForTimeout(2000);
        await slotSelect.click();
        await slotSelect.press("ArrowDown");
        await dateInput.press("Enter");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Downloadable product checkout flow
     */
    async downloadableCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.checkoutShopLocators.clickLink.click();
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Group product checkout flow
     */
    async groupCheckout() {
        await this.searchProduct("group");
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async groupCheckoutFlatRate() {
        await this.searchProduct("group");
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async groupCheckoutCOD() {
        await this.searchProduct("group");
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async guestCheckoutGroup() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    /**
     * Virtual product checkout flow
     */
    async virtualCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Bundle product checkout flow
     */
    async bundleCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async bundleCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async bundleCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopLocators.chooseFlatShippingMeathod.click();
        await this.checkoutShopLocators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }
    
    async guestCheckoutBundle() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    async shippingChangeCheckoutBundle() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopLocators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopLocators.addToCartButton.click();
        await expect(this.checkoutShopLocators.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopLocators.ShoppingCartIcon.click();
        await this.checkoutShopLocators.ContinueButton.click();
        await this.checkoutShopLocators.addNewAddress.click();
        await this.checkoutShopLocators.companyName.fill("Web");
        await this.checkoutShopLocators.firstName.fill("demo");
        await this.checkoutShopLocators.lastName.fill("guest");
        await this.checkoutShopLocators.shippingEmail.fill("demo@example.com");
        await this.checkoutShopLocators.streetAddress.fill("north street");
        await this.checkoutShopLocators.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopLocators.billingState.selectOption({ value: "UP" });
        await this.checkoutShopLocators.billingCity.fill("test city");
        await this.checkoutShopLocators.billingZip.fill("123456");
        await this.checkoutShopLocators.billingTelephone.fill("2365432789");
        await this.checkoutShopLocators.clickSaveAddressButton.click();
        await this.checkoutShopLocators.clickProcessButton.click();
        await this.checkoutShopLocators.chooseShippingMethod.click();
        await this.checkoutShopLocators.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
