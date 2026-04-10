import { Page, expect } from "@playwright/test";
import { CheckoutShopPage } from "../locators/shop/CheckoutShopPage";
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
    readonly checkoutShopPage: CheckoutShopPage;

    constructor(page: Page) {
        this.page = page;

        this.checkoutShopPage = new CheckoutShopPage(page);
    }

    /**
     * Common product search flow
     */
    async searchProduct(productName: string) {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopPage.searchInput.fill(productName);
        await this.checkoutShopPage.searchInput.press("Enter");
    }

    /**
     * Common steps to reach checkout page
     */
    async proceedToCheckout() {
        await this.checkoutShopPage.ShoppingCartIcon.click();
        await this.checkoutShopPage.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.checkoutShopPage.clickProcessButton.click();
    }

    /**
     * Common place order flow
     */
    async placeOrder() {
        await this.page.waitForTimeout(2000);
        await this.checkoutShopPage.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    /**
     * Common guest checkout flow for all products
     */
    async guestCheckoutCommon() {
        await this.checkoutShopPage.ShoppingCartIcon.click();
        await this.checkoutShopPage.ContinueButton.click();
        await this.checkoutShopPage.companyName.fill("Web");
        await this.checkoutShopPage.firstName.fill("demo");
        await this.checkoutShopPage.lastName.fill("guest");
        await this.checkoutShopPage.shippingEmail.fill("demo@example.com");
        await this.checkoutShopPage.streetAddress.fill("north street");
        await this.checkoutShopPage.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopPage.billingState.selectOption({ value: "UP" });
        await this.checkoutShopPage.billingCity.fill("test city");
        await this.checkoutShopPage.billingZip.fill("123456");
        await this.checkoutShopPage.billingTelephone.fill("2365432789");
        await this.checkoutShopPage.clickProcessButton.click();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Common checkout flow
     */
    async customerCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Simple product checkout flow
     */
    async simpleCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async simpleCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async shippingChangeCheckoutSimple() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopPage.ShoppingCartIcon.click();
        await this.checkoutShopPage.ContinueButton.click();
        await this.checkoutShopPage.addNewAddress.click();
        await this.checkoutShopPage.companyName.fill("Web");
        await this.checkoutShopPage.firstName.fill("demo");
        await this.checkoutShopPage.lastName.fill("guest");
        await this.checkoutShopPage.shippingEmail.fill("demo@example.com");
        await this.checkoutShopPage.streetAddress.fill("north street");
        await this.checkoutShopPage.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopPage.billingState.selectOption({ value: "UP" });
        await this.checkoutShopPage.billingCity.fill("test city");
        await this.checkoutShopPage.billingZip.fill("123456");
        await this.checkoutShopPage.billingTelephone.fill("2365432789");
        await this.checkoutShopPage.clickSaveAddressButton.click();
        await this.checkoutShopPage.clickProcessButton.click();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async guestCheckoutSimple() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopPage.ShoppingCartIcon.click();
        await this.checkoutShopPage.ContinueButton.click();
        await this.checkoutShopPage.companyName.fill("Web");
        await this.checkoutShopPage.firstName.fill("demo");
        await this.checkoutShopPage.lastName.fill("guest");
        await this.checkoutShopPage.shippingEmail.fill("demo@example.com");
        await this.checkoutShopPage.streetAddress.fill("north street");
        await this.checkoutShopPage.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopPage.billingState.selectOption({ value: "UP" });
        await this.checkoutShopPage.billingCity.fill("test city");
        await this.checkoutShopPage.billingZip.fill("123456");
        await this.checkoutShopPage.billingTelephone.fill("2365432789");
        await this.checkoutShopPage.clickProcessButton.click();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Configurable product checkout flow
     */
    async configCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async shippingChangeCheckoutConfig() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopPage.ShoppingCartIcon.click();
        await this.checkoutShopPage.ContinueButton.click();
        await this.checkoutShopPage.addNewAddress.click();
        await this.checkoutShopPage.companyName.fill("Web");
        await this.checkoutShopPage.firstName.fill("demo");
        await this.checkoutShopPage.lastName.fill("guest");
        await this.checkoutShopPage.shippingEmail.fill("demo@example.com");
        await this.checkoutShopPage.streetAddress.fill("north street");
        await this.checkoutShopPage.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopPage.billingState.selectOption({ value: "UP" });
        await this.checkoutShopPage.billingCity.fill("test city");
        await this.checkoutShopPage.billingZip.fill("123456");
        await this.checkoutShopPage.billingTelephone.fill("2365432789");
        await this.checkoutShopPage.clickSaveAddressButton.click();
        await this.checkoutShopPage.clickProcessButton.click();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async guestCheckoutConfigurable() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    async configCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async configCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    /**
     * Booking product checkout flow
     */
    async bookingCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();

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
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Downloadable product checkout flow
     */
    async downloadableCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.checkoutShopPage.clickLink.click();
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Group product checkout flow
     */
    async groupCheckout() {
        await this.searchProduct("group");
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async groupCheckoutFlatRate() {
        await this.searchProduct("group");
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async groupCheckoutCOD() {
        await this.searchProduct("group");
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async guestCheckoutGroup() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    /**
     * Virtual product checkout flow
     */
    async virtualCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    /**
     * Bundle product checkout flow
     */
    async bundleCheckout() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async bundleCheckoutFlatRate() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async bundleCheckoutCOD() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.checkoutShopPage.chooseFlatShippingMeathod.click();
        await this.checkoutShopPage.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }
    
    async guestCheckoutBundle() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.guestCheckoutCommon();
    }

    async shippingChangeCheckoutBundle() {
        const productName = readProductData();
        await this.searchProduct(productName);
        await this.checkoutShopPage.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.checkoutShopPage.addToCartButton.click();
        await expect(this.checkoutShopPage.addCartSuccess.first()).toBeVisible();
        await this.checkoutShopPage.ShoppingCartIcon.click();
        await this.checkoutShopPage.ContinueButton.click();
        await this.checkoutShopPage.addNewAddress.click();
        await this.checkoutShopPage.companyName.fill("Web");
        await this.checkoutShopPage.firstName.fill("demo");
        await this.checkoutShopPage.lastName.fill("guest");
        await this.checkoutShopPage.shippingEmail.fill("demo@example.com");
        await this.checkoutShopPage.streetAddress.fill("north street");
        await this.checkoutShopPage.billingCountry.selectOption({ value: "IN" });
        await this.checkoutShopPage.billingState.selectOption({ value: "UP" });
        await this.checkoutShopPage.billingCity.fill("test city");
        await this.checkoutShopPage.billingZip.fill("123456");
        await this.checkoutShopPage.billingTelephone.fill("2365432789");
        await this.checkoutShopPage.clickSaveAddressButton.click();
        await this.checkoutShopPage.clickProcessButton.click();
        await this.checkoutShopPage.chooseShippingMethod.click();
        await this.checkoutShopPage.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
