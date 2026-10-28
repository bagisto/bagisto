import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class OmnibusShopPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByRole("textbox", { name: "Search products here" });
    }

    private get omnibusPriceInfo() {
        return this.page.locator(".omnibus-price-info");
    }

    private searchResultCard(productName: string) {
        return this.page.getByRole("link", {
            name: productName,
            exact: true,
        });
    }

    private searchResultImage(productName: string) {
        return this.searchResultCard(productName).getByRole("img");
    }

    private productHeading(productName: string) {
        return this.page.getByRole("heading", {
            level: 1,
            name: productName,
            exact: true,
        });
    }

    private async waitForSearchResultImageLoaded(
        productName: string,
    ): Promise<void> {
        await expect(this.searchResultImage(productName)).toBeVisible();
    }

    async openProductPage(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
        await this.waitForSearchResultImageLoaded(productName);
        await this.searchResultCard(productName).click();

        await expect(this.productHeading(productName)).toBeVisible();
    }

    async expectLowestPriceDisclosed(formattedPrice: string): Promise<void> {
        await expect(this.omnibusPriceInfo).toBeVisible();
        await expect(this.omnibusPriceInfo).toContainText(formattedPrice);
    }

    async expectNoPriceDisclosure(): Promise<void> {
        await expect(this.omnibusPriceInfo).toHaveCount(0);
    }
}
