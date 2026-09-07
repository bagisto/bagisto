import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface SearchTermData {
    term: string;
    redirectUrl: string;
}

export class SearchTermsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/search-seo/search-terms";
    }

    private get createButton() {
        return this.primaryTrigger("Create Search Term");
    }

    private get termInput() {
        return this.page.locator('input[name="term"]');
    }

    private get redirectUrlInput() {
        return this.page.locator('input[name="redirect_url"]');
    }

    private get channelSelect() {
        return this.page.locator('select[name="channel_id"]');
    }

    private get localeSelect() {
        return this.page.locator('select[name="locale"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Search Term" });
    }

    private get storefrontSearchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.termInput).toBeVisible();
    }

    private async openEditModal(term: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(term);
        await this.editIcon(term).click();

        await expect(this.termInput).toHaveValue(term);
    }

    async createSearchTerm(data: SearchTermData): Promise<void> {
        await this.openCreateModal();
        await this.termInput.fill(data.term);
        await this.redirectUrlInput.fill(data.redirectUrl);
        await this.channelSelect.selectOption({ label: "Default" });
        await this.localeSelect.selectOption("en");
        await this.saveButton.click();

        await expect(
            this.flashMessage("Search Term created successfully"),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async renameSearchTerm(term: string, newTerm: string): Promise<void> {
        await this.openEditModal(term);
        await this.termInput.fill(newTerm);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Search Term updated successfully"),
        ).toBeVisible();
    }

    async deleteSearchTerm(term: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(term);
        await this.deleteRow(term, "Search Term deleted successfully");
    }

    async deleteSearchTermsIfPresent(terms: string[]): Promise<void> {
        await this.deleteRowsIfPresent(terms, "Search Term deleted successfully");
    }

    async massDeleteSearchTerms(terms: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(terms);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected Search Terms Deleted Successfully"),
        ).toBeVisible();
    }

    async expectSearchTermListed(data: SearchTermData): Promise<void> {
        await this.expectSearchedRowCount(data.term, 1);

        await expect(this.row(data.term)).toContainText(data.redirectUrl);
    }

    async expectSearchTermAbsent(term: string): Promise<void> {
        await this.expectSearchedRowCount(term, 0);
    }

    async expectStorefrontSearchRedirects(
        term: string,
        expectedPath: RegExp,
    ): Promise<void> {
        await this.visit("");
        await this.storefrontSearchInput.fill(term);
        await this.storefrontSearchInput.press("Enter");

        await expect(this.page).toHaveURL(expectedPath);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
