import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import { generatePhoneNumber } from "../../utils/faker";

export class CustomerPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get profileMenu() {
        return this.page.getByLabel("Profile");
    }

    private get profileLink() {
        return this.page.getByRole("link", { name: "Profile" });
    }

    private get ordersLink() {
        return this.page.getByRole("link", { name: "Orders", exact: true });
    }

    private get wishlistLink() {
        return this.page.getByRole("link", { name: "Wishlist", exact: true });
    }

    private get addressesLink() {
        return this.page.getByRole("link", { name: "Addresses" });
    }

    // Profile form fields
    private get editProfileLink() {
        return this.page.getByRole("link", { name: "Edit" });
    }

    private get firstNameInput() {
        return this.page.getByPlaceholder("First Name");
    }

    private get lastNameInput() {
        return this.page.getByPlaceholder("Last Name");
    }

    private get emailInput() {
        return this.page.getByPlaceholder("Email", { exact: true });
    }

    private get phoneInput() {
        return this.page.getByPlaceholder("Phone");
    }

    private get genderSelect() {
        return this.page.getByLabel("shop::app.customers.account.");
    }

    private get dateOfBirthInput() {
        return this.page.getByRole("textbox", { name: "Date of Birth" });
    }

    private get profileImageInput() {
        return this.page.getByLabel("Add Image/Video");
    }

    private get currentPasswordInput() {
        return this.page.getByPlaceholder("Current Password");
    }

    private get newPasswordInput() {
        return this.page.getByPlaceholder("New Password");
    }

    private get confirmPasswordInput() {
        return this.page.getByPlaceholder("Confirm Password");
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get deleteProfileButton() {
        return this.page.getByText("Delete Profile").first();
    }

    private get phone() {
        return this.page.locator('input[name="phone"]');
    }

    private get gender() {
        return this.page.locator('select[name="gender"]');
    }

    private get deletePasswordInput() {
        return this.page.getByPlaceholder("Enter your password");
    }

    private get deleteButton() {
        return this.page.getByRole("button", { name: "Delete" });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get searchProductInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private get addToWishlistButton() {
        return this.page.getByRole("button", { name: "Add To Wishlist" });
    }

    private get moveToCartButton() {
        return this.page.getByRole("button", { name: "Move To Cart" });
    }

    private get successMessage() {
        return this.page.getByText("Profile updated successfully").first();
    }

    private get customerDeletedMessage() {
        return this.page.getByText("Customer deleted successfully").first();
    }

    // Address
    private get addAddressButton() {
        return this.page.getByRole("link", { name: "Add Address" });
    }

    private get moreOptionsButton() {
        return this.page.getByLabel("More Options").first();
    }

    private get updateButton() {
        return this.page.getByRole("button", { name: "Update" });
    }

    private get deleteAddressLink() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    private get setDefaultButton() {
        return this.page.getByRole("button", { name: "Set as Default" });
    }

    private get uploadedImage() {
        return this.page.locator('img[alt="Uploaded Image"]');
    }

    // Navigation methods
    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async openProfile(): Promise<void> {
        await this.profileMenu.click();
    }

    async clickProfileLink(linkText: string): Promise<void> {
        await this.page.getByRole("link", { name: linkText }).click();
    }

    async seeProfile() {
        await this.page.getByRole("link", { name: "Profile" }).click();
    }

    async confirmDialog(): Promise<void> {
        await this.agreeButton.click();
    }

    async gotoProfilePage(): Promise<void> {
        await this.gotoHome();
        await this.openProfile();
        await this.profileLink.click();
    }

    async gotoOrdersPage(): Promise<void> {
        await this.gotoHome();
        await this.openProfile();
        await this.ordersLink.click();
    }

    async gotoWishlistPage(): Promise<void> {
        await this.gotoHome();
        await this.openProfile();
        await this.wishlistLink.click();
    }

    // Profile form methods
    async editProfile(data: {
        email?: string;
        firstName?: string;
        lastName?: string;
        phone?: string;
        gender?: string;
        dob?: string;
    }): Promise<void> {
        await this.editProfileLink.click();

        if (data.firstName) {
            await this.firstNameInput.click();
            await this.firstNameInput.clear();
            await this.firstNameInput.fill(data.firstName);
        }

        if (data.lastName) {
            await this.lastNameInput.click();
            await this.lastNameInput.clear();
            await this.lastNameInput.fill(data.lastName);
        }

        if (data.email) {
            await this.emailInput.click();
            await this.emailInput.clear();
            await this.emailInput.fill(data.email);
        }

        if (data.phone) {
            await this.phoneInput.click();
            await this.phoneInput.fill(data.phone);
        }

        if (data.gender) {
            await this.genderSelect.selectOption(data.gender);
        }

        if (data.dob) {
            await this.dateOfBirthInput.click();
            await this.dateOfBirthInput.fill(data.dob);
        }

        await this.saveButton.click();
        await expect(this.successMessage).toBeVisible();
    }

    async uploadProfileImage(imagePath: string): Promise<void> {
        await this.editProfileLink.click();
        await this.profileImageInput.setInputFiles(imagePath);
        await this.phone.fill(generatePhoneNumber());
        await this.gender.selectOption({ value: "Male" });
        await this.saveButton.click();
        await expect(this.successMessage).toBeVisible();
    }

    async verifyImageUploaded(): Promise<void> {
        await this.editProfileLink.click();
        await expect(this.uploadedImage).toBeVisible();
    }

    async changePassword(data: {
        currentPassword: string;
        newPassword: string;
        confirmPassword: string;
    }): Promise<void> {
        await this.editProfileLink.click();
        await this.currentPasswordInput.click();
        await this.currentPasswordInput.fill(data.currentPassword);
        await this.phone.fill("1234567890");
        await this.gender.selectOption({ value: "Male" });
        await this.newPasswordInput.click();
        await this.newPasswordInput.fill(data.newPassword);
        await this.confirmPasswordInput.click();
        await this.confirmPasswordInput.fill(data.confirmPassword);
        await this.saveButton.click();
        await expect(this.successMessage).toBeVisible();
    }

    async deleteProfile(password: string): Promise<void> {
        await this.deleteProfileButton.click();
        await this.deletePasswordInput.click();
        await this.deletePasswordInput.fill(password);
        await this.deleteButton.click();
        await expect(this.customerDeletedMessage).toBeVisible();
    }

    // Wishlist methods
    async searchProduct(term: string): Promise<void> {
        await this.searchProductInput.fill(term);
        await this.searchProductInput.press("Enter");
    }

    async addFirstProductToWishlist(): Promise<void> {
        await this.addToWishlistButton.first().click();
        await this.page.waitForTimeout(2000);
    }

    async moveFirstWishlistItemToCart(): Promise<void> {
        await this.moveToCartButton.first().click();
    }

    async removeFirstWishlistItem(): Promise<void> {
        await this.page.locator(".max-md\\:hidden > .flex").first().click();
        await this.agreeButton.click();
        await expect(
            this.page
                .getByText("Item Successfully Removed From Wishlist")
                .first(),
        ).toBeVisible();
    }

    async addFirstProductToCart(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
    }
}
