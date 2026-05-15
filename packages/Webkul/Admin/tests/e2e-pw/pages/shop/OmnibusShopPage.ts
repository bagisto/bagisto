import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class OmnibusShopPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    protected get shopActionPage() {
        return {
            searchInput: this.page.getByRole("textbox", {
                name: "Search products here",
            }),
            omnibusPriceInfo: this.page.locator(".omnibus-price-info"),
        };
    }

    private productImage(productName: string) {
        return this.page.locator(`img[alt="${productName}"]`);
    }

    async visitHome() {
        await super.visit("");
    }

    async searchProduct(productName: string) {
        await this.visitHome();
        await this.shopActionPage.searchInput.fill(productName);
        await this.shopActionPage.searchInput.press("Enter");
    }

    async openProductPage(productName: string) {
        await this.searchProduct(productName);
        const image = this.productImage(productName);
        await image.first().waitFor({ state: "visible" });
        await image.first().click();
        await this.page.waitForLoadState("domcontentloaded");
    }

    async verifyOmnibusVisible() {
        await expect(
            this.shopActionPage.omnibusPriceInfo.first(),
        ).toBeVisible();
    }

    async verifyOmnibusNotVisible() {
        await expect(
            this.shopActionPage.omnibusPriceInfo.first(),
        ).not.toBeVisible();
    }

    async getOmnibusText(): Promise<string> {
        return (
            (await this.shopActionPage.omnibusPriceInfo
                .first()
                .textContent()) || ""
        );
    }
}
