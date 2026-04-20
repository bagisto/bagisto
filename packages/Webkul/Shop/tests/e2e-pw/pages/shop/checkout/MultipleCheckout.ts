import { expect } from "@playwright/test";
import { CheckoutHelper } from "./CheckoutHelper";

/**
 * MultipleCheckout handles combination checkout flows for multiple product types.
 */
export class MultipleCheckout extends CheckoutHelper {
    constructor(page) {
        super(page);
    }

    async customerCheckoutSimpleAndConfig() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("simple");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("config");
        await this.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async customerCheckoutVirtualAndGroup() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("virtual");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("group");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async customerCheckoutSimpleAndBundle() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("simple");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("bundle");
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async customerCheckoutGroupAndBundle() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("bundle");
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("group");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async customerCheckoutDownloadableAndBundle() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("down");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.clickLink.click();
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("bundle");
        await this.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async customerCheckoutVirtualAndConfig() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("config");
        await this.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("virtual");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("group");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async customerCheckoutSimpleConfigVirtulGroup() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("simple");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("config");
        await this.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("virtual");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("group");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async customerCheckoutSimpleAndDownloadable() {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchProduct("simple");
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.searchProduct("down");
        await this.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.clickLink.click();
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();

        await this.proceedToCheckout();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
