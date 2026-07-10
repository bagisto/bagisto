import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

/**
 * Page object for the Tax Rate datagrid (admin/settings/taxes/rates).
 *
 * Covers the grid operations the datagrid actually supports — search, sort and
 * per-column filter — plus row deletion and presence assertions.
 *
 * Note: `TaxRateDataGrid` declares no mass actions, so mass-action coverage is
 * intentionally omitted (the requirement scopes it to "if available").
 */
export class TaxRateListPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.locator("a.primary-button").first();
    }

    private get searchInput() {
        return this.page.locator('input[name="search"]');
    }

    private get filterToggle() {
        return this.page.locator(".icon-filter").first();
    }

    private get applyFiltersButton() {
        return this.page.getByRole("button", { name: "Apply Filters" });
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    private columnHeader(label: string) {
        // Datagrid headers are clickable <p> elements (no <th>); the sortable
        // ones carry the cursor-pointer/select-none classes.
        return this.page
            .locator("p.cursor-pointer.select-none", { hasText: label })
            .first();
    }

    private rowCell(value: string) {
        return this.page.getByText(value, { exact: true });
    }

    async open(): Promise<void> {
        await this.visit("admin/settings/taxes/rates");
        await expect(this.createButton).toBeVisible();
    }

    /**
     * Search the grid via the toolbar search box and wait for the grid to
     * re-query.
     */
    async search(term: string): Promise<void> {
        await this.searchInput.fill(term);
        await this.searchInput.press("Enter");
        await this.page.waitForLoadState("networkidle");
    }

    /**
     * Sort the grid by clicking a sortable column header.
     */
    async sortByColumn(label: string): Promise<void> {
        await this.columnHeader(label).click();
        await this.page.waitForLoadState("networkidle");
    }

    /**
     * Apply a per-column filter through the filter drawer. The string filter
     * input is keyed by the column label placeholder.
     */
    async filterByColumn(label: string, value: string): Promise<void> {
        await this.filterToggle.click();

        const filterInput = this.page.locator(
            `input[placeholder="${label}"]:visible`,
        );

        await filterInput.fill(value);
        await filterInput.press("Tab");
        await this.applyFiltersButton.click();
        await this.page.waitForLoadState("networkidle");
    }

    /**
     * Assert a row with the given identifier is present in the grid.
     */
    async expectRowVisible(identifier: string): Promise<void> {
        await expect(this.rowCell(identifier).first()).toBeVisible();
    }

    /**
     * Assert no row with the given identifier remains in the grid.
     */
    async expectRowAbsent(identifier: string): Promise<void> {
        await expect(this.rowCell(identifier)).toHaveCount(0);
    }

    /**
     * Delete the rate matching `identifier`. The grid is first searched so the
     * single matching row's delete action is unambiguous, then the success
     * message and the row's removal are asserted.
     */
    async deleteTaxRate(identifier: string): Promise<void> {
        await this.open();
        await this.search(identifier);

        await this.deleteIcons.first().click();
        await expect(this.agreeButton).toBeVisible();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Tax rate deleted successfully").first(),
        ).toBeVisible();

        await this.search(identifier);
        await this.expectRowAbsent(identifier);
    }
}
