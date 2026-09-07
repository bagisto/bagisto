import { expect, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class ComparePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private productCard(productName: string) {
        return this.page
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) });
    }

    private productColumn(productName: string) {
        return this.page.locator("div.relative.w-77\\.75").filter({
            has: this.page.getByRole("link", { name: productName, exact: true }),
        });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get deleteAllButton() {
        return this.page.getByRole("button", { name: "Delete All" });
    }

    private productLink(productName: string) {
        return this.page.getByRole("link", { name: productName, exact: true });
    }

    async addToCompareFromListing(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        const card = this.productCard(productName);

        await expect(card).toHaveCount(1);
        await card.hover();
        await card.getByLabel("Add To Compare").filter({ visible: true }).click();

        await expect(
            this.page.getByText("Item added successfully to compare list").first(),
        ).toBeVisible();
    }

    async open(): Promise<void> {
        await this.visit("compare");

        await expect(this.page).toHaveURL(/compare/);
    }

    async removeProduct(productName: string): Promise<void> {
        const column = this.productColumn(productName);

        await expect(column, `"${productName}" is not in the compare list`).toHaveCount(1);

        await column.locator("button.icon-cancel").click();
        await this.agreeButton.click();

        await expect(column).toHaveCount(0);
    }

    async deleteAll(): Promise<void> {
        await this.deleteAllButton.click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("All items removed successfully.").first(),
        ).toBeVisible();
    }

    async expectProductListed(productName: string): Promise<void> {
        await expect(this.productLink(productName)).toBeVisible();
    }

    async expectProductAbsent(productName: string): Promise<void> {
        await expect(this.productLink(productName)).toHaveCount(0);
    }

    async expectEmpty(): Promise<void> {
        await expect(
            this.page.getByText("You have no items in your compare list"),
        ).toBeVisible();
        await expect(
            this.page.locator("button.icon-cancel").filter({ visible: true }),
        ).toHaveCount(0);
    }
}
