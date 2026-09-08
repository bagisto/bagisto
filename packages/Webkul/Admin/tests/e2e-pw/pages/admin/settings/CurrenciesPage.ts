import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";
import { generateCurrencyCode, uniqueStamp } from "../../../utils/faker";

export interface CurrencyData {
    code: string;
    name: string;
    symbol: string;
}

export function buildCurrency(overrides: Partial<CurrencyData> = {}): CurrencyData {
    return {
        code: generateCurrencyCode(),
        name: `Currency ${uniqueStamp()}`,
        symbol: "¤",
        ...overrides,
    };
}

export class CurrenciesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/currencies";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Currency" });
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get symbolInput() {
        return this.page.locator('input[name="symbol"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Currency" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.codeInput).toBeVisible();
    }

    private async openEditModal(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();

        await expect(this.nameInput).toHaveValue(name);
    }

    private async fillForm(data: CurrencyData): Promise<void> {
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.symbolInput.fill(data.symbol);
    }

    async createCurrency(data: CurrencyData): Promise<void> {
        await this.openCreateModal();
        await this.fillForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Currency created successfully."),
        ).toBeVisible();
    }

    async attemptCreateCurrency(data: CurrencyData): Promise<void> {
        await this.openCreateModal();
        await this.fillForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async updateCurrency(
        name: string,
        changes: { name: string; symbol: string },
    ): Promise<void> {
        await this.openEditModal(name);
        await this.nameInput.fill(changes.name);
        await this.symbolInput.fill(changes.symbol);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Currency updated successfully."),
        ).toBeVisible();
    }

    async deleteCurrency(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Currency deleted successfully.");
    }

    async deleteCurrenciesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            names,
            "Currency deleted successfully.",
        );
    }

    async expectCurrencyListed(data: CurrencyData): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.code);
    }

    async expectCurrencyAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectCurrencyCodeListedOnce(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);

        await expect(this.rowWithCell(code)).toHaveCount(1);
    }

    async expectSymbolInEditForm(name: string, symbol: string): Promise<void> {
        await this.openEditModal(name);

        await expect(this.symbolInput).toHaveValue(symbol);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
