import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export interface RoleData {
    name: string;
    description: string;
    permissionType?: "all" | "custom";
    permissions?: string[];
}

export class RolesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/roles";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Role" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get descriptionInput() {
        return this.page.locator('textarea[name="description"]');
    }

    private get permissionTypeSelect() {
        return this.page.locator('select[name="permission_type"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Role" });
    }

    private permissionInput(permission: string) {
        return this.page.locator(
            `input[name="permissions[]"][value="${permission}"]`,
        );
    }

    private permissionOption(permission: string) {
        return this.page.locator(
            `label:has(> input[name="permissions[]"][value="${permission}"])`,
        );
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

    private async grantPermission(permission: string): Promise<void> {
        await expect(this.permissionInput(permission)).toBeAttached();

        if (!(await this.permissionInput(permission).isChecked())) {
            await this.permissionOption(permission).click();
        }

        await expect(this.permissionInput(permission)).toBeChecked();
    }

    async createRole(data: RoleData): Promise<void> {
        await this.openCreateForm();
        await this.permissionTypeSelect.selectOption(data.permissionType ?? "all");
        await this.nameInput.fill(data.name);
        await this.descriptionInput.fill(data.description);

        for (const permission of data.permissions ?? []) {
            await this.grantPermission(permission);
        }

        await this.saveButton.click();

        await expect(
            this.flashMessage("Roles Created Successfully"),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameRole(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Roles is updated successfully"),
        ).toBeVisible();
    }

    async deleteRole(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Roles is deleted successfully");
    }

    async attemptDeleteRole(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteIcon(name).click();
        await this.agreeButton.click();
    }

    async deleteRolesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Roles is deleted successfully");
    }

    async expectRoleListed(name: string, permissionType: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText(permissionType);
    }

    async expectRoleAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
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
        await expect(this.page).toHaveURL(/settings\/roles\/create/);
    }
}
