import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";
import { generateName, uniqueStamp } from "../../../../utils/faker";

export interface CatalogRuleData {
    name: string;
    discountPercent: number;
}

export function buildCatalogRule(
    overrides: Partial<CatalogRuleData> = {},
): CatalogRuleData {
    return {
        name: `${generateName()} ${uniqueStamp()}`,
        discountPercent: 10,
        ...overrides,
    };
}

export class CatalogRulePage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/promotions/catalog-rules";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Catalog Rule" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get descriptionInput() {
        return this.page.locator('textarea[name="description"]');
    }

    private get actionTypeSelect() {
        return this.page.locator('select[name="action_type"]');
    }

    private get discountAmountInput() {
        return this.page.locator('input[name="discount_amount"]');
    }

    private get sortOrderInput() {
        return this.page.locator('input[name="sort_order"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Catalog Rule" });
    }

    private channelOption(id: number) {
        return this.checkboxLabel(`channel__${id}`);
    }

    private customerGroupOption(id: number) {
        return this.checkboxLabel(`customer_group__${id}`);
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.nameInput).toBeVisible();
    }

    private async openEditForm(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();
        await this.openFormPage();

        await expect(this.nameInput).toHaveValue(name);
    }

    private async fillCreateForm(data: CatalogRuleData): Promise<void> {
        await this.nameInput.fill(data.name);
        await this.descriptionInput.fill(`Catalog rule ${data.name}`);
        await this.actionTypeSelect.selectOption("by_percent");
        await this.discountAmountInput.fill(`${data.discountPercent}`);
        await this.sortOrderInput.fill("1");
        await this.channelOption(1).click();
        await this.customerGroupOption(1).click();
        await this.customerGroupOption(2).click();
        await this.setSwitch(this.statusToggle, this.statusInput, true);
    }

    async createCatalogRule(data: CatalogRuleData): Promise<CatalogRuleData> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Catalog rule created successfully"),
        ).toBeVisible();

        return data;
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameCatalogRule(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Catalog rule updated successfully"),
        ).toBeVisible();
    }

    async deleteCatalogRule(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Catalog rule deleted successfully");
    }

    async deleteCatalogRulesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            names,
            "Catalog rule deleted successfully",
        );
    }

    async expectCatalogRuleListed(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText("Active");
    }

    async expectCatalogRuleAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/catalog-rules\/create/);
    }
}
