import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class ComparePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async addProductToCompare(index: number = 1): Promise<void> {
        await this.page
            .locator(
                `div:nth-child(${index + 1}) > .-mt-9 > .action-items > .icon-compare`,
            )
            .first()
            .click();
    }

    async openCompare(): Promise<void> {
        await this.page.getByRole("link", { name: "Compare" }).click();
    }

    async removeFirstProductFromCompare(): Promise<void> {
        await this.page.locator(".relative > .icon-cancel").first().click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async deleteAllProductsFromCompare(): Promise<void> {
        await this.page.getByText("Delete All", { exact: true }).click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async expectAddedSuccessfully(): Promise<void> {
        await expect(
            this.page
                .getByText("Item added successfully to compare list")
                .first(),
        ).toBeVisible();
    }

    async expectAllItemsRemoved(): Promise<void> {
        await expect(
            this.page.getByText("All items removed successfully.").first(),
        ).toBeVisible();
    }
}
