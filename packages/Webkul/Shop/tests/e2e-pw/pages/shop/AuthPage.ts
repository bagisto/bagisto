import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import type { CustomerCredentials } from "../../utils/customer";

export type { CustomerCredentials };

export type PasswordField = "password" | "confirmPassword";

export class AuthPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get profileMenu() {
        return this.page.getByLabel("Profile");
    }

    private get firstNameInput() {
        return this.page.getByPlaceholder("First Name");
    }

    private get lastNameInput() {
        return this.page.getByPlaceholder("Last Name");
    }

    private get emailInput() {
        return this.page.getByPlaceholder("email@example.com");
    }

    private get passwordInput() {
        return this.page.getByPlaceholder("Password", { exact: true });
    }

    private get confirmPasswordInput() {
        return this.page.getByPlaceholder("Confirm Password");
    }

    private get agreementLabel() {
        return this.page.locator('label[for="agreement"]').filter({ hasText: /\S/ });
    }

    private get registerButton() {
        return this.page.getByRole("button", { name: "Register" });
    }

    private get signInButton() {
        return this.page.getByRole("button", { name: "Sign In" });
    }

    private get logoutLink() {
        return this.page.getByRole("link", { name: "Logout" });
    }

    private get welcomeGuestText() {
        return this.page.getByText("Welcome Guest");
    }

    private passwordField(field: PasswordField) {
        return field === "password" ? this.passwordInput : this.confirmPasswordInput;
    }

    private passwordVisibilityToggle(field: PasswordField) {
        return this.passwordField(field)
            .locator("..")
            .getByRole("button", { name: "Show Password" });
    }

    async openSignUpForm(): Promise<void> {
        await this.visit("customer/register");
    }

    async register(
        credentials: CustomerCredentials,
        expectedMessage = "Account created successfully.",
    ): Promise<void> {
        await this.openSignUpForm();
        await this.firstNameInput.fill(credentials.firstName);
        await this.lastNameInput.fill(credentials.lastName);
        await this.emailInput.fill(credentials.email);
        await this.passwordInput.fill(credentials.password);
        await this.confirmPasswordInput.fill(credentials.password);

        if (await this.agreementLabel.count()) {
            await this.agreementLabel.click();

            await expect(this.page.locator("input#agreement")).toBeChecked();
        }

        await this.registerButton.click();

        await expect(this.page.getByText(expectedMessage).first()).toBeVisible();
    }

    async attemptRegister(credentials: CustomerCredentials): Promise<void> {
        await this.openSignUpForm();
        await this.firstNameInput.fill(credentials.firstName);
        await this.lastNameInput.fill(credentials.lastName);
        await this.emailInput.fill(credentials.email);
        await this.passwordInput.fill(credentials.password);
        await this.confirmPasswordInput.fill(credentials.password);

        if (await this.agreementLabel.count()) {
            await this.agreementLabel.click();
        }

        await this.registerButton.click();
    }

    async openSignInForm(): Promise<void> {
        await this.visit("customer/login");
        await this.waitForBackgroundRequestsToSettle();
    }

    async fillSignInForm(email: string, password: string): Promise<void> {
        await this.emailInput.fill(email);
        await this.passwordInput.fill(password);
    }

    async submitSignInForm(): Promise<void> {
        await this.signInButton.click();
    }

    async attemptLogin(email: string, password: string): Promise<void> {
        await this.openSignInForm();
        await this.fillSignInForm(email, password);
        await this.submitSignInForm();
    }

    async login(credentials: CustomerCredentials): Promise<void> {
        await this.attemptLogin(credentials.email, credentials.password);

        await this.expectLoginAccepted();
    }

    async logout(): Promise<void> {
        await this.visit("");
        await this.profileMenu.click();
        await this.logoutLink.click();

        await expect(this.page).toHaveURL(/customer\/login|\/$/);

        await this.waitForBackgroundRequestsToSettle();
    }

    async typePassword(field: PasswordField, value: string): Promise<void> {
        await this.passwordField(field).fill(value);
    }

    async togglePasswordVisibility(field: PasswordField): Promise<void> {
        await this.passwordVisibilityToggle(field).click();
    }

    async expectLoginAccepted(): Promise<void> {
        await expect(this.page).not.toHaveURL(/customer\/login/);
    }

    async expectSignedIn(fullName: string): Promise<void> {
        await this.visit("customer/account/profile");

        await expect(this.page).toHaveURL(/customer\/account\/profile/);

        for (const part of fullName.split(" ")) {
            await expect(this.page.getByText(part, { exact: true })).toBeVisible();
        }
    }

    async expectSignedOut(): Promise<void> {
        await this.visit("customer/account/profile");

        await expect(this.page).toHaveURL(/customer\/login/);
    }

    async expectGuestMenu(): Promise<void> {
        await this.visit("");
        await this.profileMenu.click();

        await expect(this.welcomeGuestText).toBeVisible();
    }

    async expectLoginRefused(): Promise<void> {
        await expect(this.page).toHaveURL(/customer\/login/);
        await expect(
            this.page.getByText("Please check your credentials and try again.").first(),
        ).toBeVisible();
    }

    async expectRegistrationRefused(message: string): Promise<void> {
        await expect(this.page).toHaveURL(/customer\/register/);
        await expect(this.page.getByText(message).first()).toBeVisible();
    }

    async expectPasswordMasked(field: PasswordField): Promise<void> {
        await expect(this.passwordVisibilityToggle(field)).toHaveAttribute("aria-pressed", "false");
        await expect(this.passwordField(field)).toHaveAttribute("type", "password");
    }

    async expectPasswordRevealed(field: PasswordField, value: string): Promise<void> {
        await expect(this.passwordVisibilityToggle(field)).toHaveAttribute("aria-pressed", "true");
        await expect(this.passwordField(field)).toHaveAttribute("type", "text");
        await expect(this.passwordField(field)).toHaveValue(value);
    }
}
