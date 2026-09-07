import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class RestrictedAdminPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get emailInput() {
        return this.page.getByPlaceholder("Email");
    }

    private get passwordInput() {
        return this.page.getByPlaceholder("Password");
    }

    private get signInButton() {
        return this.page.getByRole("button", { name: "Sign In" });
    }

    private get errorCode() {
        return this.page.locator("div.text-\\[38px\\]");
    }

    private get sidebar() {
        return this.page.locator("div.fixed.top-14 nav");
    }

    private sidebarLink(href: string) {
        return this.sidebar.locator(`a.flex[href$="${href}"]`);
    }

    async login(email: string, password: string): Promise<void> {
        await this.visit("admin/login");
        await this.emailInput.fill(email);
        await this.passwordInput.fill(password);
        await this.signInButton.click();

        await expect(this.page).not.toHaveURL(/admin\/login/);
    }

    async expectRouteAllowed(path: string): Promise<void> {
        await this.visit(path);

        await expect(this.page).toHaveURL(new RegExp(`/${path}(\\?.*)?$`));
        await expect(this.errorCode).toHaveCount(0);
    }

    async expectRouteDenied(path: string): Promise<void> {
        await this.visit(path);

        await expect(this.errorCode).toHaveText("401");
    }

    async expectSidebarLinkVisible(href: string): Promise<void> {
        await expect(this.sidebarLink(href)).toBeVisible();
    }

    async expectSidebarLinkAbsent(href: string): Promise<void> {
        await expect(this.sidebarLink(href)).toHaveCount(0);
    }
}
