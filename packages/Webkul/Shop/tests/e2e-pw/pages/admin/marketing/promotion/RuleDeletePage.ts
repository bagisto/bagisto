import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { escapeRegExp } from "@shared/regex";

const DELETE_TIMEOUT = 90 * 1000;

export class RuleDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.locator('input[name="search"]');
    }

    private get emptyState() {
        return this.page.getByText("No Records Available.");
    }

    private get gridRows() {
        return this.page.locator("div.row:not(.datagrid-head):not(:has(.shimmer))");
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

    private async openGrid(path: string): Promise<void> {
        await this.visit(path);

        await expect
            .poll(async () => (await this.gridRows.count()) > 0)
            .toBe(true);
    }

    private async searchFor(name: string): Promise<void> {
        await this.searchInput.fill(name);

        await Promise.all([
            this.page.waitForResponse((response) =>
                decodeURIComponent(response.url()).replace(/\+/g, " ").includes(name),
            ),
            this.searchInput.press("Enter"),
        ]);
    }

    private async deleteRulesIfPresent(
        path: string,
        names: string[],
        successMessage: string,
    ): Promise<void> {
        const failures: string[] = [];

        for (const name of names) {
            try {
                await this.openGrid(path);
                await this.searchFor(name);

                await expect
                    .poll(
                        async () =>
                            (await this.row(name).count()) > 0 ||
                            (await this.emptyState.count()) > 0,
                    )
                    .toBe(true);

                if (await this.row(name).count()) {
                    await this.row(name).locator("span.icon-delete").click();

                    const deleted = this.page.waitForResponse(
                        (response) => response.request().method() === "DELETE",
                        { timeout: DELETE_TIMEOUT },
                    );

                    await this.agreeButton.click();

                    expect((await deleted).ok()).toBe(true);

                    await expect(this.page.getByText(successMessage)).toBeVisible();
                    await expect(this.row(name)).toHaveCount(0);
                }
            } catch (error) {
                failures.push(`${name}: ${error}`);
            }
        }

        if (failures.length) {
            throw new Error(`Cleanup failed for:\n${failures.join("\n")}`);
        }
    }

    async deleteCartRulesIfPresent(names: string[]): Promise<void> {
        await this.deleteRulesIfPresent(
            "admin/marketing/promotions/cart-rules",
            names,
            "Cart Rule Deleted Successfully",
        );
    }

    async deleteCatalogRulesIfPresent(names: string[]): Promise<void> {
        await this.deleteRulesIfPresent(
            "admin/marketing/promotions/catalog-rules",
            names,
            "Catalog Rule Deleted Successfully",
        );
    }
}
