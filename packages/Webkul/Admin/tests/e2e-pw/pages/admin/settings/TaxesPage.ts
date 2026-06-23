import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    generateDescription,
    generateName,
    generateSlug,
} from "../../../utils/faker";

interface TaxRateData {
    identifier: string;
    country: string;
    state: string;
}

export class TaxesPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createTaxRateButton() {
        return this.page.locator("a.primary-button");
    }

    private get createTaxCategoryButton() {
        return this.page.getByRole("button", { name: "Create Tax Category" });
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

    private get taxRateInput() {
        return this.page.locator('input[name="tax_rate"]');
    }

    private get saveTaxRateButton() {
        return this.page.getByRole("button", { name: "Save Tax Rate" });
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
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

    private get saveTaxCategoryButton() {
        return this.page.getByRole("button", { name: "Save Tax Category" });
    }

    async createTaxRate(): Promise<TaxRateData> {
        const taxRate: TaxRateData = {
            identifier: generateSlug("_"),
            country: "IN",
            state: "DL",
        };

        await this.visit("admin/settings/taxes/rates");
        await this.createTaxRateButton.first().click();
        await this.page.waitForSelector(
            'form[action*="/settings/taxes/rates/create"]',
        );
        await this.identifierInput.fill(taxRate.identifier);
        await this.countrySelect.selectOption(taxRate.country);
        await this.stateSelect.selectOption(taxRate.state);
        await this.taxRateInput.fill("18");
        await this.saveTaxRateButton.first().click();

        await expect(
            this.page.getByText("Tax rate created successfully.").first(),
        ).toBeVisible();

        return taxRate;
    }

    async editFirstTaxRate(): Promise<void> {
        await this.visit("admin/settings/taxes/rates");
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await this.page.waitForSelector(
            'form[action*="/settings/taxes/rates/edit"]',
        );
        await this.taxRateInput.fill("36");
        await this.saveTaxRateButton.click();

        await expect(
            this.page.getByText("Tax Rate Update Successfully").first(),
        ).toBeVisible();
    }

    async deleteFirstTaxRate(): Promise<void> {
        await this.visit("admin/settings/taxes/rates");
        await this.deleteIcons.first().waitFor({ state: "visible" });
        await this.deleteIcons.first().click();
        await this.page.waitForSelector("text=Are you sure");
        const agreeButton = this.agreeButton;

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            this.page.getByText("Tax rate deleted successfully"),
        ).toBeVisible();
    }

    async createTaxCategory(): Promise<void> {
        const taxRate = await this.createTaxRate();

        await this.visit("admin/settings/taxes/categories");
        await this.createTaxCategoryButton.click();
        await this.codeInput.fill(generateSlug("_"));
        await this.nameInput.fill(generateName());
        await this.descriptionInput.fill(generateDescription());
        await this.taxRatesSelect.selectOption([
            {
                label: taxRate.identifier,
            },
        ]);
        await this.saveTaxCategoryButton.click();

        await expect(
            this.page.getByText("Tax category created successfully.").first(),
        ).toBeVisible();
    }

    async editFirstTaxCategory(): Promise<void> {
        await this.createTaxCategory();
        await this.visit("admin/settings/taxes/categories");
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await this.saveTaxCategoryButton.click();

        await expect(
            this.page.getByText("Tax category updated successfully.").first(),
        ).toBeVisible();
    }
}
