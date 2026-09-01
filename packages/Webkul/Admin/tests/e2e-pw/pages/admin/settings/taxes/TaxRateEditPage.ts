import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { TaxRateListPage } from "./TaxRateListPage";

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

    async openForEdit(identifier: string): Promise<void> {
        await this.listPage.open();
        await this.listPage.search(identifier);
        await this.editIcons.first().click();
        await expect(this.taxRateInput).toBeVisible();
    }

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

    async expectGridValue(identifier: string, taxRate: string): Promise<void> {
        await this.listPage.open();
        await this.listPage.search(identifier);
        await this.listPage.expectRowVisible(identifier);

        const decimalPattern = new RegExp(`^${taxRate}(\\.0+)?$`);
        await expect(this.page.getByText(decimalPattern).first()).toBeVisible();
    }

    async expectFormValue(identifier: string, taxRate: string): Promise<void> {
        await this.openForEdit(identifier);

        const value = await this.taxRateInput.inputValue();
        expect(parseFloat(value)).toBe(parseFloat(taxRate));
    }
}
