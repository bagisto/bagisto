import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class LoginPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get emailInput() {
        return this.page.getByPlaceholder("Email");
    }

    private get passwordInput() {
        return this.page.getByPlaceholder("Password");
    }

    private get loginButton() {
        return this.page.getByRole("button", { name: "Sign In" });
    }

    private get accountDropdownToggle() {
        return this.page.locator("header div.flex.select-none > button").last();
    }

    private get logoutLink() {
        return this.page.getByRole("link", { name: "Logout" });
    }

    async visit() {
        await super.visit("admin/login");
    }

    async login(email: string, password: string) {
        await this.visit();
        await this.emailInput.fill(email);
        await this.passwordInput.fill(password);
        await this.loginButton.click();
        await this.page.waitForURL("**/admin/dashboard");
    }

    async logout() {
        await this.accountDropdownToggle.click();
        await this.logoutLink.click();
        await this.page.waitForURL("**/admin/login");
        await expect(this.passwordInput).toBeVisible();
    }
}
