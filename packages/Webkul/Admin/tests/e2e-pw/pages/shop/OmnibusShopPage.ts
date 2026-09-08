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

    private productImage(productName: string) {
        return this.page.locator(`img[alt="${productName}"]`);
    }

    async openProductPage(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
        await this.productImage(productName).first().click();

        await expect(
            this.page.getByRole("heading", { name: productName }),
        ).toBeVisible();
    }

    async expectLowestPriceDisclosed(formattedPrice: string): Promise<void> {
        await expect(this.omnibusPriceInfo).toBeVisible();
        await expect(this.omnibusPriceInfo).toContainText(formattedPrice);
    }

    async expectNoPriceDisclosure(): Promise<void> {
        await expect(this.omnibusPriceInfo).toHaveCount(0);
    }
}
