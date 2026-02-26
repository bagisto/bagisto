import { Page, expect } from "@playwright/test";
import { WebLocators } from "../locators/locator";
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
    readonly locators: WebLocators;

    constructor(page: Page) {
        this.page = page;

        this.locators = new WebLocators(page);
    }

    /**
     * Common product search flow
     */
    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill(productName);
        await this.locators.searchInput.press("Enter");
    }

    /**
     * Common steps to reach checkout page
     */
    async proceedToCheckout() {
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
    }

    /**
     * Common place order flow
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    /**
     * Common guest checkout flow for all products
     */
    async guestCheckoutCommon() {
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.locators.companyName.fill("Web");
        await this.locators.firstName.fill("demo");
        await this.locators.lastName.fill("guest");
        await this.locators.shippingEmail.fill("demo@example.com");
        await this.locators.streetAddress.fill("north street");
        await this.locators.billingCountry.selectOption({ value: "IN" });
        await this.locators.billingState.selectOption({ value: "UP" });
        await this.locators.billingCity.fill("test city");
        await this.locators.billingZip.fill("123456");
        await this.locators.billingTelephone.fill("2365432789");
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Common checkout flow
     */
    async customerCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Simple product checkout flow
     */
    async simpleCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async simpleCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async shippingChangeCheckoutSimple() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.locators.addNewAddress.click();
        await this.locators.companyName.fill("Web");
        await this.locators.firstName.fill("demo");
        await this.locators.lastName.fill("guest");
        await this.locators.shippingEmail.fill("demo@example.com");
        await this.locators.streetAddress.fill("north street");
        await this.locators.billingCountry.selectOption({ value: "IN" });
        await this.locators.billingState.selectOption({ value: "UP" });
        await this.locators.billingCity.fill("test city");
        await this.locators.billingZip.fill("123456");
        await this.locators.billingTelephone.fill("2365432789");
        await this.locators.clickSaveAddressButton.click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async guestCheckoutSimple() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.locators.companyName.fill("Web");
        await this.locators.firstName.fill("demo");
        await this.locators.lastName.fill("guest");
        await this.locators.shippingEmail.fill("demo@example.com");
        await this.locators.streetAddress.fill("north street");
        await this.locators.billingCountry.selectOption({ value: "IN" });
        await this.locators.billingState.selectOption({ value: "UP" });
        await this.locators.billingCity.fill("test city");
        await this.locators.billingZip.fill("123456");
        await this.locators.billingTelephone.fill("2365432789");
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Configurable product checkout flow
     */
    async configCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async shippingChangeCheckoutConfig() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.locators.addNewAddress.click();
        await this.locators.companyName.fill("Web");
        await this.locators.firstName.fill("demo");
        await this.locators.lastName.fill("guest");
        await this.locators.shippingEmail.fill("demo@example.com");
        await this.locators.streetAddress.fill("north street");
        await this.locators.billingCountry.selectOption({ value: "IN" });
        await this.locators.billingState.selectOption({ value: "UP" });
        await this.locators.billingCity.fill("test city");
        await this.locators.billingZip.fill("123456");
        await this.locators.billingTelephone.fill("2365432789");
        await this.locators.clickSaveAddressButton.click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async guestCheckoutConfigurable() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    async configCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async configCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    /**
     * Booking product checkout flow
     */
    async bookingCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();

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
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Downloadable product checkout flow
     */
    async downloadableCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickLink.click();
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Group product checkout flow
     */
    async groupCheckout() {
        await this.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async groupCheckoutFlatRate() {
        await this.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async groupCheckoutCOD() {
        await this.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async guestCheckoutGroup() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    /**
     * Virtual product checkout flow
     */
    async virtualCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Bundle product checkout flow
     */
    async bundleCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async bundleCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async bundleCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.locators.chooseFlatShippingMeathod.click();
        await this.locators.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }
    
    async guestCheckoutBundle() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    async shippingChangeCheckoutBundle() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.locators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.locators.addNewAddress.click();
        await this.locators.companyName.fill("Web");
        await this.locators.firstName.fill("demo");
        await this.locators.lastName.fill("guest");
        await this.locators.shippingEmail.fill("demo@example.com");
        await this.locators.streetAddress.fill("north street");
        await this.locators.billingCountry.selectOption({ value: "IN" });
        await this.locators.billingState.selectOption({ value: "UP" });
        await this.locators.billingCity.fill("test city");
        await this.locators.billingZip.fill("123456");
        await this.locators.billingTelephone.fill("2365432789");
        await this.locators.clickSaveAddressButton.click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
