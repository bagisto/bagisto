import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export interface CustomerGroupData {
    name: string;
    code: string;
}

export class CustomerGroupsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/customers/groups";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Group" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Group" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.nameInput).toBeVisible();
    }

    private async openEditModal(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();

        await expect(this.nameInput).toHaveValue(name);
    }

    async createGroup(data: CustomerGroupData): Promise<void> {
        await this.openCreateModal();
        await this.nameInput.fill(data.name);
        await this.codeInput.fill(data.code);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Group created successfully"),
        ).toBeVisible();
    }

    async attemptCreateGroup(data: CustomerGroupData): Promise<void> {
        await this.openCreateModal();
        await this.nameInput.fill(data.name);
        await this.codeInput.fill(data.code);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async renameGroup(name: string, newName: string): Promise<void> {
        await this.openEditModal(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Group Updated Successfully"),
        ).toBeVisible();
    }

    async deleteGroup(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Group Deleted Successfully");
    }

    async attemptDeleteGroup(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.rowWithCell(name).locator("span.icon-delete").click();
        await this.agreeButton.click();
    }

    async deleteGroupsIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Group Deleted Successfully");
    }

    async expectGroupListed(data: CustomerGroupData): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.code);
    }

    async expectGroupAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectGroupCodeListedOnce(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);

        await expect(this.rowWithCell(code)).toHaveCount(1);
    }

    async expectDefaultGroupListed(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);

        await expect(this.rowWithCell(name)).toHaveCount(1);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditModal(name);
    }

    async expectCreateUnavailable(): Promise<void> {
        await this.openGrid();

        await expect(this.createButton).toHaveCount(0);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectErrorMessage(message: string): Promise<void> {
        await expect(this.flashMessage(message)).toBeVisible();
    }
}
