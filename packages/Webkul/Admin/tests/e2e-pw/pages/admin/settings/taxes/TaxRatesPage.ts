import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";
import { numericCellPattern, numericValuePattern } from "../../../../utils/numbers";
import type { TaxRateData } from "../../../../utils/tax";

export class TaxRatesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/taxes/rates";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Tax Rate" });
    }

    private get identifierInput() {
        return this.page.locator('input[name="identifier"]');
    }

    private get countrySelect() {
        return this.page.locator('select[name="country"]');
    }

    private get stateSelect() {
        return this.page.locator('select[name="state"]');
    }

    private get stateInput() {
        return this.page.locator('input[name="state"]');
    }

    private get taxRateInput() {
        return this.page.locator('input[name="tax_rate"]');
    }

    private get zipRangeToggle() {
        return this.page.locator('label[for="is_zip"]');
    }

    private get zipRangeInput() {
        return this.page.locator('input[type="checkbox"][name="is_zip"]');
    }

    private get zipCodeInput() {
        return this.page.locator('input[name="zip_code"]');
    }

    private get zipFromInput() {
        return this.page.locator('input[name="zip_from"]');
    }

    private get zipToInput() {
        return this.page.locator('input[name="zip_to"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Tax Rate" });
    }

    private rateCell(identifier: string, taxRate: string) {
        return this.row(identifier)
            .locator("p")
            .filter({ hasText: numericCellPattern(taxRate) });
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.identifierInput).toBeVisible();
    }

    private async openEditForm(identifier: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(identifier);
        await this.editIcon(identifier).click();
        await this.openFormPage();

        await expect(this.identifierInput).toHaveValue(identifier);
    }

    private async fillForm(data: TaxRateData): Promise<void> {
        await this.identifierInput.fill(data.identifier);
        await this.countrySelect.selectOption(data.country);

        if (data.state) {
            if (await this.stateSelect.count()) {
                await this.stateSelect.selectOption(data.state);
            } else {
                await this.stateInput.fill(data.state);
            }
        }

        if (data.isZip) {
            await this.setSwitch(this.zipRangeToggle, this.zipRangeInput, true);
            await this.zipFromInput.fill(data.zipFrom ?? "");
            await this.zipToInput.fill(data.zipTo ?? "");
        } else if (data.zipCode) {
            await this.zipCodeInput.fill(data.zipCode);
        }

        await this.taxRateInput.fill(data.taxRate);
    }

    async createTaxRate(data: TaxRateData): Promise<void> {
        await this.openCreateForm();
        await this.fillForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Tax rate created successfully."),
        ).toBeVisible();
    }

    async attemptCreateTaxRate(data: TaxRateData): Promise<void> {
        await this.openCreateForm();
        await this.fillForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async updateTaxRate(
        identifier: string,
        changes: { identifier?: string; taxRate?: string },
    ): Promise<void> {
        await this.openEditForm(identifier);

        if (changes.identifier !== undefined) {
            await this.identifierInput.fill(changes.identifier);
        }

        if (changes.taxRate !== undefined) {
            await this.taxRateInput.fill(changes.taxRate);
        }

        await this.saveButton.click();

        await expect(
            this.flashMessage("Tax Rate Update Successfully"),
        ).toBeVisible();
    }

    async deleteTaxRate(identifier: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(identifier);
        await this.deleteRow(identifier, "Tax rate deleted successfully");
    }

    async deleteTaxRatesIfPresent(identifiers: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            identifiers,
            "Tax rate deleted successfully",
        );
    }

    async expectTaxRateListed(data: TaxRateData): Promise<void> {
        await this.openGrid();
        await this.searchFor(data.identifier);

        await expect(this.rowWithCell(data.identifier)).toHaveCount(1);
        await expect(this.row(data.identifier)).toContainText(data.country);
        await expect(this.rateCell(data.identifier, data.taxRate)).toHaveCount(1);

        if (data.isZip) {
            await expect(this.row(data.identifier)).toContainText(data.zipFrom ?? "");
            await expect(this.row(data.identifier)).toContainText(data.zipTo ?? "");
        }
    }

    async expectTaxRateAbsent(identifier: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(identifier);

        await expect(this.rowWithCell(identifier)).toHaveCount(0);
    }

    async expectRateInEditForm(identifier: string, taxRate: string): Promise<void> {
        await this.openEditForm(identifier);

        await expect(this.taxRateInput).toHaveValue(numericValuePattern(taxRate));
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/taxes\/rates\/create/);
    }
}
