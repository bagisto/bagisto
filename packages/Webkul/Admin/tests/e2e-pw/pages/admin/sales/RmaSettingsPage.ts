import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export class RmaReasonsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/reasons";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create RMA Reason" });
    }

    private get titleInput() {
        return this.page.locator('input[name="title"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get positionInput() {
        return this.page.locator('input[name="position"]');
    }

    private get resolutionTypeSelect() {
        return this.page.locator('select[name="resolution_type[]"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Reason" });
    }

    async createReason(title: string): Promise<void> {
        await this.openGrid();
        await this.createButton.click();
        await expect(this.titleInput).toBeVisible();
        await this.titleInput.fill(title);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.positionInput.fill("1");
        await this.resolutionTypeSelect.selectOption("return");
        await this.saveButton.click();

        await expect(
            this.flashMessage("Reason created successfully."),
        ).toBeVisible();
    }

    async deleteReasonsIfPresent(titles: string[]): Promise<void> {
        await this.deleteRowsIfPresent(titles, "Reason deleted successfully.");
    }

    async expectReasonListed(title: string): Promise<void> {
        await this.expectSearchedRowCount(title, 1);

        await expect(this.row(title)).toContainText("Active");
    }
}

export class RmaRulesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/rules";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create RMA Rules" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get descriptionInput() {
        return this.page.locator('textarea[name="description"]');
    }

    private get returnPeriodInput() {
        return this.page.locator('input[name="return_period"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save RMA Rules" });
    }

    async createRule(name: string, returnPeriod: string): Promise<void> {
        await this.openGrid();
        await this.createButton.click();
        await expect(this.nameInput).toBeVisible();
        await this.nameInput.fill(name);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.descriptionInput.fill(`Rule ${name}`);
        await this.returnPeriodInput.fill(returnPeriod);
        await this.saveButton.click();

        await expect(
            this.flashMessage("RMA Rules created successfully."),
        ).toBeVisible();
    }

    async deleteRulesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "RMA Rules deleted successfully.");
    }

    async expectRuleListed(name: string, returnPeriod: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText(returnPeriod);
    }
}

export type RmaCustomFieldType =
    | "text"
    | "textarea"
    | "select"
    | "multiselect"
    | "checkbox"
    | "radio"
    | "date";

export type RmaCustomFieldData = {
    label: string;
    code: string;
    type: RmaCustomFieldType;
    required?: boolean;
    options?: string[];
};

export class RmaCustomFieldsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/custom-fields";
    }

    private get createButton() {
        return this.primaryTrigger("Add New Field");
    }

    private get labelInput() {
        return this.page.locator('input[name="label"]');
    }

    private get codeInput() {
        return this.page.locator("input#code");
    }

    private get positionInput() {
        return this.page.locator('input[name="position"]');
    }

    private get typeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get requiredInput() {
        return this.page.locator('input[type="checkbox"][name="is_required"]');
    }

    private get requiredToggle() {
        return this.checkboxLabel("is_required");
    }

    private get addOptionButton() {
        return this.page.getByRole("button", { name: "Add Option" });
    }

    private get saveButton() {
        return this.primaryTrigger("Save");
    }

    private optionNameInput(index: number) {
        return this.page.locator(`input[name="options[${index}]"]`);
    }

    private optionValueInput(index: number) {
        return this.page.locator(`input[name="value[${index}]"]`);
    }

    private async fillOptions(options: string[]): Promise<void> {
        for (const [index, option] of options.entries()) {
            if (index) {
                await this.addOptionButton.click();
            }

            await expect(this.optionNameInput(index)).toBeVisible();

            await this.optionNameInput(index).fill(option);
            await this.optionValueInput(index).fill(option);
        }
    }

    async createCustomField(field: RmaCustomFieldData): Promise<void> {
        await this.openGrid();
        await this.createButton.click();
        await this.openFormPage();

        await expect(this.labelInput).toBeVisible();

        await this.labelInput.fill(field.label);
        await this.codeInput.fill(field.code);

        await expect(this.codeInput).toHaveValue(field.code);

        await this.positionInput.fill("1");
        await this.typeSelect.selectOption(field.type);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.setSwitch(
            this.requiredToggle,
            this.requiredInput,
            field.required ?? false,
        );
        await this.fillOptions(field.options ?? []);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Custom Field created successfully."),
        ).toBeVisible();
    }

    async deleteCustomFieldsIfPresent(labels: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            labels,
            "Custom Fields deleted successfully.",
        );
    }

    async expectCustomFieldListed(field: RmaCustomFieldData): Promise<void> {
        await this.expectSearchedRowCount(field.label, 1);

        await expect(this.row(field.label)).toContainText(field.code);
        await expect(this.row(field.label)).toContainText(field.type);
        await expect(this.row(field.label)).toContainText("Active");
        await expect(this.row(field.label)).toContainText(
            field.required ? "Yes" : "No",
        );
    }
}

export class RmaStatusesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/rma-status";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create RMA Status" });
    }

    private get titleInput() {
        return this.page.locator('input[name="title"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save RMA Status" });
    }

    async createStatus(title: string): Promise<void> {
        await this.openGrid();
        await this.createButton.click();
        await expect(this.titleInput).toBeVisible();
        await this.titleInput.fill(title);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.saveButton.click();

        await expect(
            this.flashMessage("RMA Status created successfully."),
        ).toBeVisible();
    }

    async deleteStatusesIfPresent(titles: string[]): Promise<void> {
        await this.deleteRowsIfPresent(titles, "RMA Status deleted successfully.");
    }

    async expectStatusListed(title: string): Promise<void> {
        await this.expectSearchedRowCount(title, 1);
    }
}
