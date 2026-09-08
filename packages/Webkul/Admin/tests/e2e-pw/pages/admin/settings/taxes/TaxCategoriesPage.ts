import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";
import type { TaxCategoryData } from "../../../../utils/tax";

export class TaxCategoriesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/taxes/categories";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Tax Category" });
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get descriptionInput() {
        return this.page.locator('textarea[name="description"]');
    }

    private get taxRatesSelect() {
        return this.page.locator('select[name="taxrates[]"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Tax Category" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.codeInput).toBeVisible();
        await expect(this.codeInput).toHaveValue("");
    }

    private async openEditModal(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();

        await expect(this.nameInput).toHaveValue(name);
    }

    async createTaxCategory(
        data: TaxCategoryData,
        rateIdentifiers: string[],
    ): Promise<void> {
        await this.openCreateModal();
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.descriptionInput.fill(data.description);
        await this.taxRatesSelect.selectOption(
            rateIdentifiers.map((label) => ({ label })),
        );
        await this.saveButton.click();

        await expect(
            this.flashMessage("Tax category created successfully."),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async updateTaxCategory(
        name: string,
        changes: Partial<Pick<TaxCategoryData, "name" | "description">>,
    ): Promise<void> {
        await this.openEditModal(name);

        if (changes.name !== undefined) {
            await this.nameInput.fill(changes.name);
        }

        if (changes.description !== undefined) {
            await this.descriptionInput.fill(changes.description);
        }

        await this.saveButton.click();

        await expect(
            this.flashMessage("Tax category updated successfully."),
        ).toBeVisible();
    }

    async deleteTaxCategory(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Tax category deleted successfully.");
    }

    async deleteTaxCategoriesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            names,
            "Tax category deleted successfully.",
        );
    }

    async expectTaxCategoryListed(data: TaxCategoryData): Promise<void> {
        await this.openGrid();
        await this.searchFor(data.name);

        await expect(this.rowWithCell(data.name)).toHaveCount(1);
        await expect(this.row(data.name)).toContainText(data.code);
    }

    async expectTaxCategoryAbsent(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);

        await expect(this.rowWithCell(name)).toHaveCount(0);
    }

    async expectRatesAssignedInEditForm(
        name: string,
        rateIdentifiers: string[],
    ): Promise<void> {
        await this.openEditModal(name);

        await expect
            .poll(() =>
                this.taxRatesSelect.evaluate((element) =>
                    Array.from((element as HTMLSelectElement).selectedOptions)
                        .map((option) => option.text.trim())
                        .sort(),
                ),
            )
            .toEqual([...rateIdentifiers].sort());
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
