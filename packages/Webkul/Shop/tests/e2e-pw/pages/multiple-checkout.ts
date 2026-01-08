import { Page, expect, Locator } from "@playwright/test";
import { WebLocators } from "../locators/locator";

export class MultipleCheckout {
    readonly page: Page;
    readonly locators: WebLocators;

    constructor(page: Page) {
        this.page = page;
        this.locators = new WebLocators(page);
    }

    async customerCheckoutSimpleAndConfig() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill("simple");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("config");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
        // await expect(this.locators.CheckoutsuccessPage).toBeVisible();
    }

    async customerCheckoutVirtualAndGroup() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill("virtual");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("config");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
        // await expect(this.locators.CheckoutsuccessPage).toBeVisible();
    }

    async customerCheckoutSimpleConfigVirtualGroup() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill("group");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("virtual");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("config");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
        // await expect(this.locators.CheckoutsuccessPage).toBeVisible();
    }
    async customerCheckoutSimpleConfigVirtulGroup() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill("group");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(3000);
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("virtual");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("simple");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("config");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.getByLabel("Color").selectOption("4");
        await this.page.getByLabel("Size").selectOption("8");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
        // await expect(this.locators.CheckoutsuccessPage).toBeVisible();
    }

    async customerCheckoutSimpleAndDownloadable() {
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill("simple");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.searchInput.fill("down");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickLink.click();
        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
        // await expect(this.locators.CheckoutsuccessPage).toBeVisible();
    }
}
