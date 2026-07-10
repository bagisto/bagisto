import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { generateTaxRateData, TaxRateData } from "../../../../utils/tax";

/**
 * Page object for the "Create Tax Rate" screen
 * (admin/settings/taxes/rates/create).
 *
 * Exposes a high-level `createTaxRate` happy-path plus focused helpers for the
 * required-field, invalid-percentage and duplicate-identifier validations.
 */
export class TaxRateCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
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

    private get zipToggleLabel() {
        return this.page.locator('label[for="is_zip"]');
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

    private get validationErrors() {
        return this.page.locator("p.text-red-600:visible");
    }

    private get successMessage() {
        return this.page.getByText("Tax rate created successfully.");
    }

    /**
     * Open a fresh create form.
     */
    async open(): Promise<void> {
        await this.visit("admin/settings/taxes/rates/create");
        await expect(this.identifierInput).toBeVisible();
    }

    /**
     * Fill every field of the create form. The state control switches between a
     * `<select>` (countries with states) and a free-text `<input>`, so both are
     * handled. An empty `state` is left untouched to act as a wildcard.
     */
    async fillForm(data: TaxRateData): Promise<void> {
        await this.identifierInput.fill(data.identifier);
        await this.countrySelect.selectOption(data.country);

        if (data.state) {
            if (await this.stateSelect.isVisible()) {
                await this.stateSelect.selectOption(data.state);
            } else {
                await this.stateInput.fill(data.state);
            }
        }

        if (data.isZip) {
            await this.zipToggleLabel.click();
            await this.zipFromInput.fill(data.zipFrom ?? "100000");
            await this.zipToInput.fill(data.zipTo ?? "999999");
        } else if (data.zipCode) {
            await this.zipCodeInput.fill(data.zipCode);
        }

        await this.taxRateInput.fill(data.taxRate);
    }

    /**
     * Create a tax rate end-to-end and assert the success message. Returns the
     * generated data so callers can locate / clean up the record.
     */
    async createTaxRate(
        overrides: Partial<TaxRateData> = {},
    ): Promise<TaxRateData> {
        const data = generateTaxRateData(overrides);

        await this.open();
        await this.fillForm(data);
        await this.saveButton.click();

        await expect(this.successMessage.first()).toBeVisible();

        return data;
    }

    /**
     * Submit the form without filling any field and assert the required-field
     * validation blocks the save.
     */
    async expectRequiredFieldErrors(): Promise<void> {
        await this.open();
        await this.saveButton.click();

        await expect(this.validationErrors.first()).toBeVisible();
        await expect(this.successMessage).toHaveCount(0);
        await expect(this.page).toHaveURL(/taxes\/rates\/create/);
    }

    /**
     * Provide an out-of-range percentage (validation rule is
     * `numeric|min:0|max:100`) and assert the rate is rejected.
     */
    async expectInvalidPercentageError(
        invalidPercentage: string = "150",
    ): Promise<void> {
        const data = generateTaxRateData({ taxRate: invalidPercentage });

        await this.open();
        await this.fillForm(data);
        await this.saveButton.click();

        await expect(this.validationErrors.first()).toBeVisible();
        await expect(this.successMessage).toHaveCount(0);
    }

    /**
     * Attempt to create a second rate reusing an existing identifier and assert
     * the unique-identifier validation rejects it.
     */
    async expectDuplicateIdentifierError(identifier: string): Promise<void> {
        const data = generateTaxRateData({ identifier });

        await this.open();
        await this.fillForm(data);
        await this.saveButton.click();

        await expect(this.validationErrors.first()).toBeVisible();
        await expect(this.successMessage).toHaveCount(0);
    }
}
