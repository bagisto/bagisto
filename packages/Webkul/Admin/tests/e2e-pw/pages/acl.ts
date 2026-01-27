import { Page, expect } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { generateEmail } from "../utils/faker";

const roleName = `role-${Date.now()}`.toString();
const userName = `user-${Date.now()}`.toString();
const userEmail = generateEmail();
export class ACLManagement {
    readonly page: Page;
    readonly locators: WebLocators;

    constructor(page: Page) {
        this.page = page;

        this.locators = new WebLocators(page);
    }

    async rolePermission(permissionValues: string[]) {
        await this.page.waitForLoadState("networkidle");

        for (const value of permissionValues) {
            const input = this.page.locator(
                `input[name="permissions[]"][value="${value}"]`,
            );

            await expect(input).toBeAttached({ timeout: 10000 });

            const label = input.locator("..");

            if (!(await input.isChecked())) {
                await label.locator("span").click();
            }
        }
    }

    async createRole(permissionType: string, permissions?: string[]) {
        await this.page.goto("admin/settings/roles");
        await this.locators.createRole.click();
        await this.locators.name.fill(roleName);
        await this.locators.selectType.selectOption(permissionType);
        if (
            permissionType === "custom" &&
            permissions &&
            permissions.length > 0
        ) {
            await this.rolePermission(permissions);
        }
        await this.locators.roleDescription.fill("test description");
        await this.locators.saveRole.click();
        await expect(this.locators.successRole.first()).toBeVisible();
    }

    async createUser() {
        await this.page.goto("admin/settings/users");
        await this.locators.createUser.click();
        await this.locators.name.fill(userName);
        await this.locators.selectRole.selectOption({ label: roleName });
        await this.locators.userEmail.fill(userEmail);
        await this.locators.userPassword.fill("user123");
        await this.locators.confirmPassword.fill("user123");
        await this.locators.statusToggle.click();
        const toggleInput = await this.locators.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.locators.saveUser.click();
        await expect(this.locators.successUser.first()).toBeVisible();
    }

    async verfiyAssignedRole(permissions?: string[]) {
        await this.page.goto("admin/dashboard");
        await this.page.waitForLoadState("networkidle");
        await this.locators.profile.click();
        await this.locators.logout.click();
        await this.page.waitForURL("**/admin/login");
        await this.page.waitForLoadState("networkidle");
        await this.locators.userEmail.fill(userEmail);
        await this.locators.userPassword.fill("user123");
        await this.locators.userPassword.press("Enter");
        await this.page.waitForLoadState("networkidle");

        const sidebar = this.page.locator("div.fixed.top-14 nav");
        let sidebarLinks: string[] = [];

        if (permissions && permissions.includes("dashboard")) {
            const sidebarLinks = ["/admin/dashboard"];
        }

        if (permissions && permissions.includes("sales")) {
            const sidebarLinks = ["/admin/sales/orders"];
        }

        if (permissions && permissions.includes("catalog")) {
            const sidebarLinks = ["/admin/catalog/products"];
        }

        if (permissions && permissions.includes("customers")) {
            const sidebarLinks = ["/admin/customers"];
        }

        if (permissions && permissions.includes("cms")) {
            const sidebarLinks = ["/admin/cms"];
        }

        if (permissions && permissions.includes("marketing")) {
            const sidebarLinks = ["/admin/marketing/promotions/catalog-rules"];
        }

        if (permissions && permissions.includes("reporting")) {
            const sidebarLinks = ["/admin/reporting/sales"];
        }

        if (permissions && permissions.includes("settings")) {
            const sidebarLinks = ["/admin/settings/locales"];
        }

        if (permissions && permissions.includes("configuration")) {
            const sidebarLinks = ["/admin/configuration"];
        }

        if (permissions && permissions.includes("all")) {
            const sidebarLinks = [
                "/admin/dashboard",
                "/admin/sales/orders",
                "/admin/catalog/products",
                "/admin/customers",
                "/admin/cms",
                "/admin/marketing/promotions/catalog-rules",
                "/admin/reporting/sales",
                "/admin/settings/locales",
                "/admin/configuration",
            ];
        }

        for (const href of sidebarLinks) {
            await expect(
                sidebar.locator(`a[href*="${href}"]`).first(),
            ).toBeVisible();
        }
    }
}
