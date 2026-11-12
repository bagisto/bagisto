import { expect, type Page } from "@playwright/test";
import type { MediaFile } from "@shared/media-files";
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

    private get imageSearchControl() {
        return this.page
            .locator('label[for^="v-image-search-"]')
            .filter({ visible: true });
    }

    private get resultsHeading() {
        return this.page.getByRole("heading", { level: 2, name: /These are results for/ });
    }

    private get pageContent() {
        return this.page.locator("#main");
    }

    private get productCards() {
        return this.pageContent.locator("div.group").filter({
            has: this.page.locator("p.wrap-anywhere"),
        });
    }

    private productCard(productName: string) {
        return this.pageContent
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) });
    }

    private get sortToggle() {
        return this.page.getByRole("button", {
            name: /Sort By|From A-Z|From Z-A|Newest First|Oldest First|Cheapest First|Expensive First/,
        });
    }

    private readStoredSearchImageUrl(): Promise<string> {
        return this.page
            .evaluate(() => localStorage.getItem("searchedImageUrl") ?? "")
            .catch(() => "");
    }

    async uploadSearchImage(file: MediaFile): Promise<void> {
        await this.visit("search");

        await expect(this.imageSearchControl).toBeVisible();

        await this.imageSearchControl.setInputFiles(file);
    }

    async uploadSearchImageAgain(file: MediaFile): Promise<void> {
        await Promise.all([
            this.page.waitForResponse((response) =>
                response.url().includes("search/upload"),
            ),
            this.imageSearchControl.setInputFiles(file),
        ]);
    }

    async search(term: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(term);

        await Promise.all([
            this.page.waitForResponse(
                (response) =>
                    response.url().includes("/api/products")
                    && response.url().includes(term),
            ),
            this.searchInput.press("Enter"),
        ]);

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
        return (await this.productCards.locator("p.wrap-anywhere").allInnerTexts()).map(
            (text) => text.trim(),
        );
    }

    async readProductPrices(): Promise<number[]> {
        const texts = await this.productCards
            .locator("div.flex-wrap > p:not(.line-through):not(.price-label)")
            .allInnerTexts();

        return texts.map((text) => parseFloat(text.replace(/[^0-9.]/g, "")));
    }

    async expectSearchImageRefused(message: string): Promise<void> {
        await expect(this.page.getByText(message).first()).toBeVisible();
    }

    async expectSearchImageStored(): Promise<void> {
        await expect
            .poll(() => this.readStoredSearchImageUrl())
            .toContain("search-images");
    }

    async expectStoredSearchImageFreeOf(fragments: string[]): Promise<void> {
        await this.expectSearchImageStored();

        const stored = await this.page.request.get(
            await this.readStoredSearchImageUrl(),
        );

        expect(stored.ok()).toBe(true);

        const content = await stored.text();

        for (const fragment of fragments) {
            expect(content).not.toContain(fragment);
        }
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
