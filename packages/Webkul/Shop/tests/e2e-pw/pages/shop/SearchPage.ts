import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class SearchPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async search(term: string): Promise<void> {
        await this.page.getByLabel("Search products here").click();
        await this.page.getByLabel("Search products here").fill(term);
        await this.page.getByLabel("Search products here").press("Enter");
    }

    async expectResults(term: string): Promise<void> {
        await expect(
            this.page.getByText(`These are results for : ${term}`).first(),
        ).toBeVisible();
    }
}
