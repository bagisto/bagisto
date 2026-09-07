import { expect, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export type SortOption =
    | "From A-Z"
    | "From Z-A"
    | "Newest First"
    | "Oldest First"
    | "Cheapest First"
    | "Expensive First";

export class SearchPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private get resultsHeading() {
        return this.page.getByRole("heading", { level: 2, name: /These are results for/ });
    }

    private get productCards() {
        return this.page.locator("div.group").filter({
            has: this.page.locator("p.wrap-break-word"),
        });
    }

    private productCard(productName: string) {
        return this.page
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) });
    }

    private get sortToggle() {
        return this.page.getByRole("button", {
            name: /Sort By|From A-Z|From Z-A|Newest First|Oldest First|Cheapest First|Expensive First/,
        });
    }


    async search(term: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(term);
        await this.searchInput.press("Enter");

        await expect(this.page).toHaveURL(/search\?/);
    }

    async sortBy(option: SortOption): Promise<void> {
        await this.sortToggle.click();
        await this.page
            .getByRole("listitem")
            .filter({ hasText: new RegExp(`^\\s*${option}\\s*$`) })
            .filter({ visible: true })
            .click();

        await expect(this.sortToggle).toContainText(option);
    }

    async readProductNames(): Promise<string[]> {
        return (await this.productCards.locator("p.wrap-break-word").allInnerTexts()).map(
            (text) => text.trim(),
        );
    }

    async readProductPrices(): Promise<number[]> {
        const texts = await this.productCards
            .locator("div.flex-wrap > p:not(.line-through)")
            .allInnerTexts();

        return texts.map((text) => parseFloat(text.replace(/[^0-9.]/g, "")));
    }

    async expectResultsHeading(term: string): Promise<void> {
        await expect(this.resultsHeading).toHaveText(
            `These are results for : ${term}`,
        );
    }

    async expectProductShown(productName: string): Promise<void> {
        await expect(this.productCard(productName)).toHaveCount(1);
    }

    async expectProductAbsent(productName: string): Promise<void> {
        await expect(this.productCard(productName)).toHaveCount(0);
    }

    async expectNoResults(): Promise<void> {
        await expect(this.productCards).toHaveCount(0);
    }

    async expectProductCount(count: number): Promise<void> {
        await expect(this.productCards).toHaveCount(count);
    }
}
