import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

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
        return this.page.locator("button.secondary-button", { hasText: "Apply Filters" });
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    private columnHeader(label: string) {
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

    async search(term: string): Promise<void> {
        await this.searchInput.fill(term);
        await this.searchInput.press("Enter");
        await this.page.waitForLoadState("networkidle");
    }

    async sortByColumn(label: string): Promise<void> {
        await this.columnHeader(label).click();
        await this.page.waitForLoadState("networkidle");
    }

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

    async expectRowVisible(identifier: string): Promise<void> {
        await expect(this.rowCell(identifier).first()).toBeVisible();
    }

    async expectRowAbsent(identifier: string): Promise<void> {
        await expect(this.rowCell(identifier)).toHaveCount(0);
    }

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
