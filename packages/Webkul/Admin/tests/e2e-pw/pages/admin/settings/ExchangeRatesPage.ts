import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";
import { numericValuePattern } from "../../../utils/numbers";

export class ExchangeRatesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/exchange-rates";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Exchange Rate" });
    }

    private get targetCurrencySelect() {
        return this.page.locator('select[name="target_currency"]');
    }

    private get rateInput() {
        return this.page.locator('input[name="rate"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Exchange Rate" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.rateInput).toBeVisible();
    }

    private async openEditModal(currencyName: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(currencyName);
        await this.editIcon(currencyName).click();

        await expect(this.rateInput).toBeVisible();
    }

    private async fillCreateForm(
        currencyName: string,
        rate: string,
    ): Promise<void> {
        await this.targetCurrencySelect.selectOption({ label: currencyName });
        await this.rateInput.fill(rate);
    }

    async createExchangeRate(currencyName: string, rate: string): Promise<void> {
        await this.openCreateModal();
        await this.fillCreateForm(currencyName, rate);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Exchange Rate Created Successfully"),
        ).toBeVisible();
    }

    async attemptCreateExchangeRate(
        currencyName: string,
        rate: string,
    ): Promise<void> {
        await this.openCreateModal();
        await this.fillCreateForm(currencyName, rate);
        await this.saveButton.click();
    }

    async updateExchangeRate(currencyName: string, rate: string): Promise<void> {
        await this.openEditModal(currencyName);
        await this.rateInput.fill(rate);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Exchange Rate Updated Successfully"),
        ).toBeVisible();
    }

    async deleteExchangeRate(currencyName: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(currencyName);
        await this.deleteRow(currencyName, "Exchange Rate Deleted Successfully");
    }

    async deleteExchangeRatesIfPresent(currencyNames: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            currencyNames,
            "Exchange Rate Deleted Successfully",
        );
    }

    async expectExchangeRateListed(
        currencyName: string,
        rate: string,
    ): Promise<void> {
        await this.expectSearchedRowCount(currencyName, 1);

        await expect(this.row(currencyName)).toContainText(rate);
    }

    async expectExchangeRateAbsent(currencyName: string): Promise<void> {
        await this.expectSearchedRowCount(currencyName, 0);
    }

    async expectRateInEditForm(currencyName: string, rate: string): Promise<void> {
        await this.openEditModal(currencyName);

        await expect(this.rateInput).toHaveValue(numericValuePattern(rate));
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
