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
        return this.page.getByRole("button", { name: " Sign In " });
    }

    private get logoutButton() {
        return this.page.locator("div.flex.select-none >> button");
    }

    private get logoutLink() {
        return this.page.getByRole("link", { name: "Logout" });
    }

    private get megaSearchPlaceholder() {
        return this.page.getByPlaceholder("Mega Search").first();
    }

    async visit() {
        await super.visit("admin/login");
    }

    async login(email: string, password: string) {
        await this.visit();
        await this.emailInput.fill(email);
        await this.passwordInput.fill(password);
        await this.loginButton.click();
        await expect(this.megaSearchPlaceholder).toBeVisible();
    }

    async logout() {
        await this.logoutButton.click();
        await this.logoutLink.click();
        await expect(this.passwordInput).toBeVisible();
    }
}
