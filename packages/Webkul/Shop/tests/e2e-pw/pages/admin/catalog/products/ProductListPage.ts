import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { escapeRegExp } from "@shared/regex";

export class ProductListPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.locator('input[name="search"]');
    }

    private get createProductButton() {
        return this.page.getByRole("button", { name: "Create Product" });
    }

    private get gridRows() {
        return this.page.locator(
            "div.row:not(.datagrid-head):not(:has(.shimmer))",
        );
    }

    private get selectActionButton() {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    private get deleteAction() {
        return this.page.getByRole("link", { name: "Delete", exact: true });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private row(name: string): Locator {
        return this.gridRows.filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*${escapeRegExp(name)}\\s*$`),
            }),
        });
    }

    private rowSelectToggle(name: string): Locator {
        return this.row(name)
            .locator('label[for^="mass_action_select_record_"]')
            .filter({ visible: true });
    }

    async open(): Promise<void> {
        await this.visit("admin/catalog/products");

        await expect(this.createProductButton).toBeVisible();
        await expect
            .poll(async () => (await this.gridRows.count()) > 0)
            .toBe(true);
    }

    async searchFor(name: string): Promise<void> {
        await this.searchInput.fill(name);

        await Promise.all([
            this.page.waitForResponse((response) =>
                decodeURIComponent(response.url()).replace(/\+/g, " ").includes(name),
            ),
            this.searchInput.press("Enter"),
        ]);
    }

    async deleteProduct(name: string): Promise<void> {
        await this.open();
        await this.searchFor(name);

        await expect(this.row(name)).toHaveCount(1);

        await this.rowSelectToggle(name).click();
        await this.selectActionButton.click();
        await this.deleteAction.click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Selected Products Deleted Successfully"),
        ).toBeVisible();
    }

    async deleteProductsIfPresent(names: string[]): Promise<void> {
        const failures: string[] = [];

        for (const name of names) {
            try {
                await this.open();
                await this.searchFor(name);

                if (await this.row(name).count()) {
                    await this.deleteProduct(name);
                }
            } catch (error) {
                failures.push(`${name}: ${error}`);
            }
        }

        if (failures.length) {
            throw new Error(`Cleanup failed for:\n${failures.join("\n")}`);
        }
    }

    async expectProductListed(name: string): Promise<void> {
        await this.open();
        await this.searchFor(name);

        await expect(this.row(name)).toHaveCount(1);
    }
}
