import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import {
    generateTaxCategoryData,
    TaxCategoryData,
} from "../../../../utils/tax";

export class TaxCategoryPage extends BasePage {
    constructor(page: Page) {
        super(page);
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

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    async createTaxCategory(
        taxRateIdentifier: string,
        overrides: Partial<TaxCategoryData> = {},
    ): Promise<TaxCategoryData> {
        const data = generateTaxCategoryData(overrides);

        await this.visit("admin/settings/taxes/categories");
        await this.createButton.click();
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.descriptionInput.fill(data.description);
        await this.taxRatesSelect.selectOption({ label: taxRateIdentifier });
        await this.saveButton.click();

        await expect(
            this.page.getByText("Tax category created successfully.").first(),
        ).toBeVisible();

        return data;
    }

    async openForEdit(name: string): Promise<void> {
        await this.visit("admin/settings/taxes/categories");
        await this.page
            .getByText(name, { exact: true })
            .first()
            .waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await expect(this.taxRatesSelect).toBeVisible();
    }

    async expectRateAssigned(
        categoryName: string,
        taxRateIdentifier: string,
    ): Promise<void> {
        await this.openForEdit(categoryName);

        const selected = await this.taxRatesSelect.evaluate((el) =>
            Array.from((el as HTMLSelectElement).selectedOptions).map(
                (option) => option.text.trim(),
            ),
        );

        expect(selected).toContain(taxRateIdentifier);
    }
}
