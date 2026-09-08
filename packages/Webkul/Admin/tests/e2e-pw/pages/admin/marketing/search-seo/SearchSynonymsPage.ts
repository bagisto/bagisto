import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface SearchSynonymData {
    name: string;
    terms: string;
}

export class SearchSynonymsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/search-seo/search-synonyms";
    }

    private get createButton() {
        return this.primaryTrigger("Create Search Synonym");
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get termsInput() {
        return this.page.locator('textarea[name="terms"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Search Synonym" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.nameInput).toBeVisible();
    }

    private async openEditModal(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();

        await expect(this.nameInput).toHaveValue(name);
    }

    async createSynonym(data: SearchSynonymData): Promise<void> {
        await this.openCreateModal();
        await this.nameInput.fill(data.name);
        await this.termsInput.fill(data.terms);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Search Synonym created successfully"),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async updateSynonym(
        name: string,
        changes: Partial<SearchSynonymData>,
    ): Promise<void> {
        await this.openEditModal(name);

        if (changes.name !== undefined) {
            await this.nameInput.fill(changes.name);
        }

        if (changes.terms !== undefined) {
            await this.termsInput.fill(changes.terms);
        }

        await this.saveButton.click();

        await expect(
            this.flashMessage("Search Synonym updated successfully"),
        ).toBeVisible();
    }

    async deleteSynonym(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Search Synonym deleted successfully");
    }

    async deleteSynonymsIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            names,
            "Search Synonym deleted successfully",
        );
    }

    async massDeleteSynonyms(names: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(names);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected Search Synonyms Deleted Successfully"),
        ).toBeVisible();
    }

    async expectSynonymListed(data: SearchSynonymData): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.terms);
    }

    async expectSynonymAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
