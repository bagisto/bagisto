import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { TaxRateListPage } from "./TaxRateListPage";

/**
 * Page object for editing an existing tax rate
 * (admin/settings/taxes/rates/edit/{id}).
 *
 * Reuses {@link TaxRateListPage} to locate the record by identifier so edits
 * target an unambiguous row.
 */
export class TaxRateEditPage extends BasePage {
    private readonly listPage: TaxRateListPage;

    constructor(page: Page) {
        super(page);
        this.listPage = new TaxRateListPage(page);
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get identifierInput() {
        return this.page.locator('input[name="identifier"]');
    }

    private get taxRateInput() {
        return this.page.locator('input[name="tax_rate"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Tax Rate" });
    }

    /**
     * Open the edit form for the rate matching `identifier`.
     */
    async openForEdit(identifier: string): Promise<void> {
        await this.listPage.open();
        await this.listPage.search(identifier);
        await this.editIcons.first().click();
        await expect(this.taxRateInput).toBeVisible();
    }

    /**
     * Edit a rate's percentage (and optionally its identifier) and assert the
     * update succeeds.
     */
    async updateTaxRate(
        identifier: string,
        changes: { taxRate?: string; newIdentifier?: string },
    ): Promise<void> {
        await this.openForEdit(identifier);

        if (changes.newIdentifier) {
            await this.identifierInput.fill(changes.newIdentifier);
        }

        if (changes.taxRate) {
            await this.taxRateInput.fill(changes.taxRate);
        }

        await this.saveButton.click();

        await expect(
            this.page.getByText("Tax Rate Update Successfully").first(),
        ).toBeVisible();
    }

    /**
     * Assert the updated percentage is reflected in the grid row. The column is
     * a `decimal(12,4)`, so it renders as e.g. `22.0000`.
     */
    async expectGridValue(identifier: string, taxRate: string): Promise<void> {
        await this.listPage.open();
        await this.listPage.search(identifier);
        await this.listPage.expectRowVisible(identifier);

        const decimalPattern = new RegExp(`^${taxRate}(\\.0+)?$`);
        await expect(this.page.getByText(decimalPattern).first()).toBeVisible();
    }

    /**
     * Assert the edit form is pre-filled with the expected percentage. The
     * stored decimal may carry trailing zeros (e.g. `22.0000`), so the value is
     * compared numerically.
     */
    async expectFormValue(identifier: string, taxRate: string): Promise<void> {
        await this.openForEdit(identifier);

        const value = await this.taxRateInput.inputValue();
        expect(parseFloat(value)).toBe(parseFloat(taxRate));
    }
}
