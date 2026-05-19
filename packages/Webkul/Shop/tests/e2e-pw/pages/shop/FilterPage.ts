import { Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class FilterPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async gotoCategory(category: string = "mens"): Promise<void> {
        await this.visit(category);
    }

    async openSortDropdown(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Expensive First " })
            .click();
    }

    async selectSortOption(optionText: string): Promise<void> {
        await this.page
            .getByRole("listitem")
            .filter({ hasText: optionText })
            .click();
    }

    async getProductNames(): Promise<string[]> {
        return this.page.$$eval("p.break-all", (elements) =>
            elements.map((el) => el.textContent?.trim() || ""),
        );
    }

    async getProductPrices(): Promise<number[]> {
        return this.page.$$eval("p.final-price", (elements) =>
            elements.map((el) => {
                const text = el.textContent?.replace(/[^0-9.]/g, "") || "0";
                return parseFloat(text);
            }),
        );
    }
}
