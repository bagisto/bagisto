import { Page, expect } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { generateEmail } from "../utils/faker";

const roleName = `role-${Date.now()}`.toString();
const userName = `user-${Date.now()}`.toString();
const userEmail =
    `user_${Date.now()}_${Math.floor(Math.random() * 10000)}` + generateEmail();
export class ACLManagement {
    readonly page: Page;
    readonly locators: WebLocators;
    readonly roleName: string;
    readonly userName: string;
    readonly userEmail: string;

    constructor(page: Page) {
        this.page = page;
        this.locators = new WebLocators(page);
        this.roleName = `role-${Date.now()}`.toString();
        this.userName = `user-${Date.now()}`.toString();
        this.userEmail =
            `user_${Date.now()}_${Math.floor(Math.random() * 10000)}` +
            generateEmail();
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

    async editRolePermission(permissionValues: string[]) {
        await this.page.goto("admin/settings/roles");
        await this.locators.iconEdit.click();
        await this.page.waitForLoadState("networkidle");
        for (const value of permissionValues) {
            const input = this.page.locator(
                `input[name="permissions[]"][value="${value}"]`,
            );
            await expect(input).toBeAttached({ timeout: 10000 });
            const label = input.locator("..");
            if (await input.isChecked()) {
                await label.locator("span").click();
            }
        }
        await this.locators.saveRole.click();
        await expect(this.locators.successEditRole).toBeVisible();
    }

    async createRole(permissionType: string, permissions?: string[]) {
        await this.page.goto("admin/settings/roles");
        await this.locators.createRole.click();
        await this.locators.name.fill(this.roleName);
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
        await this.locators.name.fill(this.userName);
        await this.locators.selectRole.selectOption({ label: this.roleName });
        await this.locators.userEmail.fill(this.userEmail);
        await this.locators.userPassword.fill("user123");
        await this.locators.confirmPassword.fill("user123");
        await this.locators.statusToggle.click();
        const toggleInput = await this.locators.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.locators.saveUser.click();
        await expect(this.locators.successUser.first()).toBeVisible();
    }

    async verfiyAssignedRole(permissions: string[] = []) {
        await this.page.goto("admin/dashboard");
        await this.page.waitForLoadState("networkidle");
        await this.locators.profile.click();
        await this.locators.logout.click();
        await this.page.waitForURL("**/admin/login");
        await this.page.waitForLoadState("networkidle");
        await this.locators.userEmail.fill(this.userEmail);
        await this.locators.userPassword.fill("user123");
        await this.locators.userPassword.press("Enter");
        await this.page.waitForLoadState("networkidle");

        const sidebar = this.page.locator("div.fixed.top-14 nav");
        let sidebarLinks: string[] = [];

        if (permissions.includes("all")) {
            sidebarLinks = [
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
        } else {
            if (permissions.includes("dashboard")) {
                sidebarLinks.push("/admin/dashboard");
            }

            if (permissions.includes("sales")) {
                sidebarLinks.push("/admin/sales/orders");
            }

            if (permissions.includes("sales->order")) {
                await this.page.goto("admin/dashboard");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/catalog/products");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/customers");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/cms");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto(
                    "admin/marketing/promotions/catalog-rules",
                );
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/reporting/sales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/settings/locales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/configuration");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/transactions");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/orders");
                await expect(this.locators.unauthorized).not.toBeVisible();

                sidebarLinks.push("/admin/sales/orders");
            }

            if (permissions.includes("sales->transactions")) {
                await this.page.goto("admin/dashboard");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/catalog/products");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/customers");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/cms");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto(
                    "admin/marketing/promotions/catalog-rules",
                );
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/reporting/sales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/settings/locales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/configuration");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/orders");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/transactions");

                sidebarLinks.push("/admin/sales/transactions");
            }

            if (permissions.includes("sales->shipments")) {
                await this.page.goto("admin/dashboard");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/catalog/products");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/customers");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/cms");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto(
                    "admin/marketing/promotions/catalog-rules",
                );
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/reporting/sales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/settings/locales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/configuration");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/orders");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/shipments");
                await expect(this.locators.unauthorized).not.toBeVisible();

                sidebarLinks.push("/admin/sales/shipments");
            }

            if (permissions.includes("sales->invoices")) {
                await this.page.goto("admin/dashboard");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/catalog/products");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/customers");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/cms");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto(
                    "admin/marketing/promotions/catalog-rules",
                );
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/reporting/sales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/settings/locales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/configuration");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/orders");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/invoices");
                await expect(this.locators.unauthorized).not.toBeVisible();

                sidebarLinks.push("/admin/sales/invoices");
            }

            if (permissions.includes("sales->refunds")) {
                await this.page.goto("admin/dashboard");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/catalog/products");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/customers");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/cms");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto(
                    "admin/marketing/promotions/catalog-rules",
                );
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/reporting/sales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/settings/locales");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/configuration");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/orders");
                await expect(this.locators.unauthorized).toBeVisible();
                await this.page.goto("admin/sales/refunds");
                await expect(this.locators.unauthorized).not.toBeVisible();

                sidebarLinks.push("/admin/sales/refunds");
            }
            if (permissions.includes("catalog")) {
                sidebarLinks.push("/admin/catalog/categories");
            }

            if (permissions.includes("customers")) {
                sidebarLinks.push("/admin/customers");
            }

            if (permissions.includes("cms")) {
                sidebarLinks.push("/admin/cms");
            }

            if (permissions.includes("marketing")) {
                sidebarLinks.push("/admin/marketing/promotions/catalog-rules");
            }

            if (permissions.includes("reporting")) {
                sidebarLinks.push("/admin/reporting/sales");
            }

            if (permissions.includes("settings")) {
                sidebarLinks.push("/admin/settings/locales");
            }

            if (permissions.includes("configuration")) {
                sidebarLinks.push("/admin/configuration");
            }
        }

        for (const href of sidebarLinks) {
            await expect(
                sidebar.locator(`a[href*="${href}"]`).first(),
            ).toBeVisible();
        }
    }
}
