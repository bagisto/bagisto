import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export interface AdminUserData {
    name: string;
    email: string;
    password: string;
    role: string;
    active: boolean;
}

export class UsersPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/users";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create User" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get emailInput() {
        return this.page.locator('input[name="email"]');
    }

    private get passwordInput() {
        return this.page.locator('input[name="password"]');
    }

    private get passwordConfirmationInput() {
        return this.page.locator('input[name="password_confirmation"]');
    }

    private get roleSelect() {
        return this.page.locator('select[name="role_id"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save User" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.nameInput).toBeVisible();
    }

    private async openEditModal(email: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(email);
        await this.editIcon(email).click();

        await expect(this.emailInput).toHaveValue(email);
    }

    private async fillCreateForm(data: AdminUserData): Promise<void> {
        await this.nameInput.fill(data.name);
        await this.emailInput.fill(data.email);
        await this.passwordInput.fill(data.password);
        await this.passwordConfirmationInput.fill(data.password);
        await this.roleSelect.selectOption({ label: data.role });
        await this.setSwitch(this.statusToggle, this.statusInput, data.active);
    }

    async createUser(data: AdminUserData): Promise<void> {
        await this.openCreateModal();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("User created successfully."),
        ).toBeVisible();
    }

    async attemptCreateUser(data: AdminUserData): Promise<void> {
        await this.openCreateModal();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async renameUser(email: string, newName: string): Promise<void> {
        await this.openEditModal(email);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("User updated successfully."),
        ).toBeVisible();
    }

    async deleteUser(email: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(email);
        await this.deleteRow(email, "User deleted successfully.");
    }

    async attemptDeleteUser(email: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(email);
        await this.rowWithCell(email).locator("span.icon-delete").click();
        await this.agreeButton.click();
    }

    async deleteUsersIfPresent(emails: string[]): Promise<void> {
        await this.deleteRowsIfPresent(emails, "User deleted successfully.");
    }

    async expectUserListed(email: string, name: string): Promise<void> {
        await this.expectSearchedRowCount(email, 1);

        await expect(this.row(email)).toContainText(name);
    }

    async expectUserAbsent(email: string): Promise<void> {
        await this.expectSearchedRowCount(email, 0);
    }

    async expectNameInEditForm(email: string, name: string): Promise<void> {
        await this.openEditModal(email);

        await expect(this.nameInput).toHaveValue(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectErrorMessage(message: string): Promise<void> {
        await expect(this.flashMessage(message)).toBeVisible();
    }
}
