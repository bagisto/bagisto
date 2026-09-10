import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export type CustomerCredentials = {
    firstName: string;
    lastName: string;
    email: string;
    password: string;
};

export type PasswordField = "password" | "confirmPassword";

export class AuthPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get profileMenu() {
        return this.page.getByLabel("Profile");
    }

    private get signUpLink() {
        return this.page.getByRole("link", { name: "Sign Up" });
    }

    private get signInLink() {
        return this.page.getByRole("link", { name: "Sign In" });
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

    private get agreementCheckbox() {
        return this.page.locator("#agreement").nth(1);
    }

    private get agreementText() {
        return this.page.getByText("I agree with this statement.");
    }

    private get newsletterOptIn() {
        return this.page
            .locator("#main form div")
            .filter({ hasText: "Subscribe to newsletter" })
            .locator("label")
            .first();
    }

    private get registerButton() {
        return this.page.getByRole("button", { name: "Register" });
    }

    private get loginButton() {
        return this.page.getByRole("button", { name: "Sign In" });
    }

    private get logoutLink() {
        return this.page.getByRole("link", { name: "Logout" });
    }

    private get accountCreatedMessage() {
        return this.page.getByText("Account created successfully");
    }

    private get welcomeGuestText() {
        return this.page.getByText("Welcome Guest").first();
    }

    private passwordField(field: PasswordField) {
        return field === "password"
            ? this.passwordInput
            : this.confirmPasswordInput;
    }

    private passwordVisibilityToggle(field: PasswordField) {
        return this.passwordField(field)
            .locator("..")
            .getByRole("button", { name: "Show Password" });
    }

    async visit() {
        await super.visit("");
    }

    async openSignUpForm() {
        await this.visit();
        await this.profileMenu.click();
        await this.signUpLink.click();
        await this.page.waitForLoadState("networkidle");
    }

    async openSignInForm() {
        await this.visit();
        await this.profileMenu.click();
        await this.signInLink.click();
        await this.page.waitForLoadState("networkidle");
    }

    async register(credentials: CustomerCredentials) {
        await this.openSignUpForm();

        await this.firstNameInput.fill(credentials.firstName);
        await this.lastNameInput.fill(credentials.lastName);
        await this.emailInput.fill(credentials.email);
        await this.passwordInput.fill(credentials.password);
        await this.confirmPasswordInput.fill(credentials.password);

        if (await this.agreementCheckbox.isVisible()) {
            await this.agreementText.first().click();
        }

        await this.newsletterOptIn.click();
        await this.registerButton.click();

        await expect(this.accountCreatedMessage.first()).toBeVisible();

        return credentials;
    }

    async fillSignInForm(credentials: CustomerCredentials) {
        await this.emailInput.fill(credentials.email);
        await this.passwordInput.fill(credentials.password);
    }

    async submitSignInForm() {
        await this.loginButton.click();
        await this.profileMenu.click();

        await expect(this.logoutLink.first()).toBeVisible();
    }

    async login(credentials: CustomerCredentials) {
        await this.openSignInForm();
        await this.fillSignInForm(credentials);
        await this.submitSignInForm();
    }

    async logout() {
        await this.logoutLink.click();
        await this.profileMenu.waitFor({ state: "visible" });
        await this.profileMenu.click();
        await expect(this.welcomeGuestText).toBeVisible();
    }

    async typePassword(field: PasswordField, value: string) {
        await this.passwordField(field).fill(value);
    }

    async togglePasswordVisibility(field: PasswordField) {
        await this.passwordVisibilityToggle(field).click();
    }

    async expectPasswordMasked(field: PasswordField) {
        await expect(this.passwordVisibilityToggle(field)).toHaveAttribute(
            "aria-pressed",
            "false",
        );
        await expect(this.passwordField(field)).toHaveAttribute(
            "type",
            "password",
        );
    }

    async expectPasswordRevealed(field: PasswordField, value: string) {
        await expect(this.passwordVisibilityToggle(field)).toHaveAttribute(
            "aria-pressed",
            "true",
        );
        await expect(this.passwordField(field)).toHaveAttribute("type", "text");
        await expect(this.passwordField(field)).toHaveValue(value);
    }
}
