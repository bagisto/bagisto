import { Page, expect, Locator } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import fs from "fs";

export class DownloadableProductCheckout {
    readonly page: Page;
    readonly locators: WebLocators;

    constructor(page: Page) {
        this.page = page;
        this.locators = new WebLocators(page);
    }

    async customerCheckout() {
        const product = JSON.parse(
            fs.readFileSync("product-data.json", "utf-8")
        );
        const productName = product.name;
        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");
        await this.locators.searchInput.fill(productName);
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
        await this.locators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
        // await expect(this.locators.CheckoutsuccessPage).toBeVisible();
    }
}
