import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { generateEmail, generateFullName } from "../../../utils/faker";

export class UsersPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createUserButton() {
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

    private get statusLabel() {
        return this.page.locator('label[for="status"]');
    }

    private get statusInput() {
        return this.page.locator('input[name="status"]');
    }

    private get saveUserButton() {
        return this.page.getByRole("button", { name: "Save User" });
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

    async open(): Promise<void> {
        await this.visit("admin/settings/users");
    }

    async createUser(): Promise<{ name: string; email: string }> {
        const name = generateFullName();
        const email = generateEmail();
        await this.open();
        await this.createUserButton.click();
        await this.nameInput.fill(name);
        await this.emailInput.fill(email);
        await this.passwordInput.fill("admin123");
        await this.passwordConfirmationInput.fill("admin123");
        await this.roleSelect.selectOption("1");
        await this.statusLabel.click();
        await expect(this.statusInput).toBeChecked();
        await this.saveUserButton.click();
        await expect(
            this.page.getByText("User created successfully."),
        ).toBeVisible();
        return { name, email };
    }

    async editFirstUser(): Promise<{ name: string; email: string }> {
        const name = generateFullName();
        const email = generateEmail();
        await this.open();
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await this.nameInput.fill(name);
        await this.emailInput.fill(email);
        await this.saveUserButton.click();
        await expect(
            this.page.getByText("User updated successfully."),
        ).toBeVisible();
        await expect(this.page.getByText(name)).toBeVisible();
        await expect(this.page.getByText(email)).toBeVisible();
        return { name, email };
    }

    async deleteFirstUser(): Promise<void> {
        await this.open();
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
            this.page.getByText("User deleted successfully."),
        ).toBeVisible();
    }
}
