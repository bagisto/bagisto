import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { ProductListPage } from "./ProductListPage";
import { escapeRegExp } from "@shared/regex";

export class ProductEditPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Product" });
    }

    private get skuInput() {
        return this.page.locator('input[name="sku"]');
    }

    private advancedSelectField(code: string) {
        return this.page.locator(`div.relative:has(> div[name="${code}"])`);
    }

    private advancedSelectTrigger(code: string) {
        return this.advancedSelectField(code).locator(`> div[name="${code}"]`);
    }

    private advancedSelectInput(code: string) {
        return this.advancedSelectField(code).locator(
            `input[type="hidden"][name="${code}"]`,
        );
    }

    private advancedSelectOption(code: string, label: string) {
        return this.advancedSelectField(code)
            .locator("div.max-h-60 > div")
            .filter({ hasText: new RegExp(`^\\s*${escapeRegExp(label)}\\s*$`) });
    }

    private categoryLabel(name: string) {
        return this.page.locator("label", {
            hasText: new RegExp(`^\\s*${escapeRegExp(name)}\\s*$`),
        });
    }

    private categoryCheckbox(name: string) {
        return this.categoryLabel(name).locator('input[type="checkbox"]');
    }

    async openProduct(name: string): Promise<void> {
        const listPage = new ProductListPage(this.page);

        await listPage.open();
        await listPage.searchFor(name);
        await this.page
            .locator("div.row:not(.datagrid-head)")
            .filter({
                has: this.page.locator("p", {
                    hasText: new RegExp(`^\\s*${escapeRegExp(name)}\\s*$`),
                }),
            })
            .locator("span.icon-sort-right")
            .filter({ visible: true })
            .click();

        await expect(this.page).toHaveURL(/catalog\/products\/edit\/\d+/);
        await this.waitForVueMount();
        await expect(this.saveButton).toBeVisible();
        await expect(this.skuInput).toHaveValue(/.+/);
    }

    async fillInput(name: string, value: string): Promise<void> {
        const input = this.page.locator(`input[name="${name}"]`);

        await input.fill(value);

        await expect(input).toHaveValue(value);
    }

    async selectOption(code: string, label: string): Promise<void> {
        await this.advancedSelectTrigger(code).click();
        await this.advancedSelectOption(code, label).click();

        await expect(this.advancedSelectInput(code)).toHaveValue(/.+/);
        await expect(this.advancedSelectTrigger(code)).toContainText(label);
    }

    async assignCategory(name: string): Promise<void> {
        await expect(this.categoryCheckbox(name)).toBeAttached();

        if (!(await this.categoryCheckbox(name).isChecked())) {
            await this.categoryLabel(name).click();
        }

        await expect(this.categoryCheckbox(name)).toBeChecked();
    }

    async setToggle(name: string, enabled: boolean): Promise<void> {
        const input = this.page.locator(`input[type="checkbox"][name="${name}"]`);

        if ((await input.isChecked()) !== enabled) {
            await this.page.locator(`label[for="${name}"]`).click();
        }

        await expect(input).toBeChecked({ checked: enabled });
    }

    async save(): Promise<void> {
        await this.saveButton.click();

        await expect(this.page.getByText("Product updated successfully")).toBeVisible();
    }
}
