import { Page, expect } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { ProductCheckout } from "./checkout-flow";

export class MultipleCheckout {
    readonly page: Page;
    readonly locators: WebLocators;
    readonly productCheckout: ProductCheckout;

    constructor(page: Page) {
        this.page = page;

        this.locators = new WebLocators(page);

        this.productCheckout = new ProductCheckout(page);
    }

    /**
     * Customer checkout flow: adds a simple product and a configurable product to cart,
     * proceeds to checkout, and places the order.
     */
    async customerCheckoutSimpleAndConfig() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("simple");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("config");
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }

    /**
     * Customer checkout flow: adds a virtual product and a group product,
     * then proceeds to checkout and places the order.
     */
    async customerCheckoutVirtualAndGroup() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("virtual");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }

    /**
     * Customer checkout flow: adds a simple product and a bundle product,
     * then proceeds to checkout and places the order.
     */
    async customerCheckoutSimpleAndBundle() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("simple");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("bundle");
        await this.locators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }

    /**
     * Customer checkout flow: adds a group product and a bundle product,
     * then proceeds to checkout and places the order.
     */
    async customerCheckoutGroupAndBundle() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("bundle");
        await this.locators.addToCartButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }

    /**
     * Customer checkout flow: adds a downloadable product and a bundle product,
     * then proceeds to checkout and places the order.
     */
    async customerCheckoutDownloadableAndBundle() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("down");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickLink.click();
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }

    /**
     * Customer checkout flow: adds virtual and configurable products,
     * proceeds through checkout, and places the order.
     */
    async customerCheckoutVirtualAndConfig() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("config");
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("virtual");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }

    /**
     * Customer checkout flow: adds simple, configurable, virtual, group and  products,
     * proceeds through checkout, and places the order.
     */
    async customerCheckoutSimpleConfigVirtulGroup() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("simple");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("config");
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("virtual");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("group");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }

    /**
     * Customer checkout flow: adds group, simple, and downloadable products,
     * proceeds through checkout, and places the order.
     */
    async customerCheckoutSimpleAndDownloadable() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.productCheckout.searchProduct("simple");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.searchProduct("down");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickLink.click();
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.productCheckout.proceedToCheckout();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.productCheckout.placeOrder();
    }
}
