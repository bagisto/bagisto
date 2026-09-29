import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface AttributeFamilyData {
    code: string;
    name: string;
}

export class AttributeFamilyPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/catalog/families";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Attribute Family" });
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Attribute Family" });
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.codeInput).toBeVisible();
    }

    private async openEditForm(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();
        await this.openFormPage();

        await expect(this.nameInput).toHaveValue(name);
    }

    private async readCreateFormFields(): Promise<Record<string, string>> {
        return this.page.evaluate(() => {
            const fields: Record<string, string> = {};

            document
                .querySelectorAll<HTMLInputElement>(
                    'input[name^="attribute_groups"], input[name="_token"]',
                )
                .forEach((input) => {
                    fields[input.name] = input.value;
                });

            return fields;
        });
    }

    async createFamilyWithAttribute(
        data: AttributeFamilyData,
        attributeId: string,
        groupId: string,
    ): Promise<void> {
        await this.openCreateForm();

        const fields = await this.readCreateFormFields();

        const assigned = Object.keys(fields).filter(
            (name) =>
                name.startsWith(`attribute_groups[${groupId}][custom_attributes]`)
                && name.endsWith("[id]"),
        ).length;

        const form = {
            ...fields,
            code: data.code,
            name: data.name,
            [`attribute_groups[${groupId}][custom_attributes][${assigned}][id]`]: attributeId,
            [`attribute_groups[${groupId}][custom_attributes][${assigned}][position]`]: String(
                assigned + 1,
            ),
        };

        const response = await this.page.request.post("admin/catalog/families/create", {
            form,
        });

        expect(response.ok()).toBeTruthy();

        await this.expectFamilyListed(data);
    }

    async createFamily(data: AttributeFamilyData): Promise<void> {
        await this.openCreateForm();
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Family created successfully."),
        ).toBeVisible();
    }

    async attemptCreateFamily(data: AttributeFamilyData): Promise<void> {
        await this.openCreateForm();
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameFamily(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Family updated successfully."),
        ).toBeVisible();
    }

    async deleteFamily(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Family deleted successfully.");
    }

    async attemptDeleteFamily(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.rowWithCell(name).locator("span.icon-delete").click();
        await this.agreeButton.click();
    }

    async deleteFamiliesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Family deleted successfully.");
    }

    async expectFamilyListed(data: AttributeFamilyData): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.code);
    }

    async expectFamilyAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectFamilyCodeListedOnce(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);

        await expect(this.rowWithCell(code)).toHaveCount(1);
    }

    async expectDefaultFamilyListed(): Promise<void> {
        await this.openGrid();
        await this.searchFor("default");

        await expect(this.rowWithCell("default")).toHaveCount(1);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectErrorMessage(message: string): Promise<void> {
        await expect(this.flashMessage(message)).toBeVisible();
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/catalog\/families\/create/);
    }
}
