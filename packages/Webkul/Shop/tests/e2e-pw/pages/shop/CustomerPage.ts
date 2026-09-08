import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export interface ProfileChanges {
    firstName?: string;
    lastName?: string;
    email?: string;
    phone?: string;
    gender?: "Male" | "Female" | "Other";
    dateOfBirth?: string;
}

export class CustomerPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get firstNameInput() {
        return this.page.locator('input[name="first_name"]');
    }

    private get lastNameInput() {
        return this.page.locator('input[name="last_name"]');
    }

    private get emailInput() {
        return this.page.locator('input[name="email"]');
    }

    private get phoneInput() {
        return this.page.locator('input[name="phone"]');
    }

    private get genderSelect() {
        return this.page.locator('select[name="gender"]');
    }

    private get dateOfBirthInput() {
        return this.page.locator('input[name="date_of_birth"]');
    }

    private get imageInput() {
        return this.page.locator('input[type="file"][name="image[]"]');
    }

    private get currentPasswordInput() {
        return this.page.locator('input[name="current_password"]');
    }

    private get newPasswordInput() {
        return this.page.locator('input[name="new_password"]');
    }

    private get confirmPasswordInput() {
        return this.page.locator('input[name="new_password_confirmation"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get deleteProfileTrigger() {
        return this.page.getByText("Delete Profile").filter({ visible: true });
    }

    private get deletePasswordInput() {
        return this.page.getByPlaceholder("Enter your password");
    }

    private get deleteButton() {
        return this.page.getByRole("button", { name: "Delete", exact: true });
    }

    private get uploadedImage() {
        return this.page.locator('img[alt="Uploaded Image"]');
    }

    async openProfile(): Promise<void> {
        await this.visit("customer/account/profile");

        await expect(this.page).toHaveURL(/customer\/account\/profile$/);
    }

    async openEditProfile(): Promise<void> {
        await this.visit("customer/account/profile/edit");

        await expect(this.firstNameInput).toBeVisible();
    }

    private async fillRequiredExtras(changes: ProfileChanges): Promise<void> {
        if (changes.phone !== undefined) {
            await this.phoneInput.fill(changes.phone);
        }

        if (changes.gender !== undefined) {
            await this.genderSelect.selectOption(changes.gender);
        }
    }

    async updateProfile(changes: ProfileChanges): Promise<void> {
        await this.openEditProfile();

        if (changes.firstName !== undefined) {
            await this.firstNameInput.fill(changes.firstName);
        }

        if (changes.lastName !== undefined) {
            await this.lastNameInput.fill(changes.lastName);
        }

        if (changes.email !== undefined) {
            await this.emailInput.fill(changes.email);
        }

        await this.fillRequiredExtras(changes);

        if (changes.dateOfBirth !== undefined) {
            await this.dateOfBirthInput.fill(changes.dateOfBirth);
        }

        await this.saveButton.click();

        await expect(this.page.getByText("Profile updated successfully").first()).toBeVisible();
    }

    async uploadProfileImage(imagePath: string, extras: ProfileChanges): Promise<void> {
        await this.openEditProfile();
        await this.imageInput.setInputFiles(imagePath);
        await this.fillRequiredExtras(extras);
        await this.saveButton.click();

        await expect(this.page.getByText("Profile updated successfully").first()).toBeVisible();
    }

    async changePassword(
        currentPassword: string,
        newPassword: string,
        extras: ProfileChanges,
    ): Promise<void> {
        await this.openEditProfile();
        await this.fillRequiredExtras(extras);
        await this.currentPasswordInput.fill(currentPassword);
        await this.newPasswordInput.fill(newPassword);
        await this.confirmPasswordInput.fill(newPassword);
        await this.saveButton.click();

        await expect(this.page.getByText("Profile updated successfully").first()).toBeVisible();
    }

    async deleteProfile(password: string): Promise<void> {
        await this.openProfile();
        await this.deleteProfileTrigger.click();
        await this.deletePasswordInput.fill(password);
        await this.deleteButton.click();

        await expect(this.page.getByText("Customer deleted successfully").first()).toBeVisible();
    }

    async expectProfileShows(values: string[]): Promise<void> {
        await this.openProfile();

        for (const value of values) {
            await expect(this.page.getByText(value, { exact: true })).toBeVisible();
        }
    }

    async expectEditFormValues(changes: ProfileChanges): Promise<void> {
        await this.openEditProfile();

        if (changes.firstName !== undefined) {
            await expect(this.firstNameInput).toHaveValue(changes.firstName);
        }

        if (changes.lastName !== undefined) {
            await expect(this.lastNameInput).toHaveValue(changes.lastName);
        }

        if (changes.phone !== undefined) {
            await expect(this.phoneInput).toHaveValue(changes.phone);
        }

        if (changes.gender !== undefined) {
            await expect(this.genderSelect).toHaveValue(changes.gender);
        }

        if (changes.dateOfBirth !== undefined) {
            await expect(this.dateOfBirthInput).toHaveValue(changes.dateOfBirth);
        }
    }

    async expectProfileImageShown(): Promise<void> {
        await this.openEditProfile();

        await expect(this.uploadedImage).toBeVisible();
    }
}
