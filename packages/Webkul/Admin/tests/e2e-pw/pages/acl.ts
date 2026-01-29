import { Page, expect } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { generateEmail } from "../utils/faker";

const ACL_Routes: Record<
    string,
    {
        allowed: string;
        sidebar: string;
        notAllowed?: string[];
    }
> = {
    "sales->orders": {
        allowed: "admin/sales/orders",
        sidebar: "/admin/sales/orders",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
        ],
    },

    "sales->transactions": {
        allowed: "admin/sales/transactions",
        sidebar: "/admin/sales/transactions",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
        ],
    },

    "sales->shipments": {
        allowed: "admin/sales/shipments",
        sidebar: "/admin/sales/shipments",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "sales->invoices": {
        allowed: "admin/sales/invoices",
        sidebar: "/admin/sales/invoices",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "sales->refunds": {
        allowed: "admin/sales/refunds",
        sidebar: "/admin/sales/refunds",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "catalog->products": {
        allowed: "admin/catalog/products",
        sidebar: "/admin/catalog/products",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "catalog->categories": {
        allowed: "admin/catalog/categories",
        sidebar: "/admin/catalog/categories",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "catalog->attributes": {
        allowed: "admin/catalog/attributes",
        sidebar: "/admin/catalog/attributes",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/catalog/categories",
        ],
    },

    "catalog->families": {
        allowed: "admin/catalog/families",
        sidebar: "/admin/catalog/families",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "customers->customers": {
        allowed: "admin/customers",
        sidebar: "/admin/customers",
        notAllowed: [
            "admin/dashboard",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/customers/groups",
            "admin/customers/gdpr",
        ],
    },

    "customers->groups": {
        allowed: "admin/customers/groups",
        sidebar: "/admin/customers/groups",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "customers->reviews": {
        allowed: "admin/customers/reviews",
        sidebar: "/admin/customers/reviews",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "customers->gdpr": {
        allowed: "admin/customers/gdpr",
        sidebar: "/admin/customers/gdpr",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "marketing->promotions": {
        allowed: "admin/marketing/promotions/catalog-rules",
        sidebar: "/admin/marketing/promotions/catalog-rules",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "marketing->communications": {
        allowed: "admin/marketing/communications/email-templates",
        sidebar: "/admin/marketing/communications/email-templates",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "marketing->search_seo": {
        allowed: "admin/marketing/search-seo/url-rewrites",
        sidebar: "/admin/marketing/search-seo/url-rewrites",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/communications/email-templates",
        ],
    },

    "settings->locales": {
        allowed: "admin/settings/locales",
        sidebar: "/admin/settings/locales",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->currencies": {
        allowed: "admin/settings/currencies",
        sidebar: "/admin/settings/currencies",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/settings/locales",
        ],
    },

    "settings->exchange_rates": {
        allowed: "admin/settings/exchange-rates",
        sidebar: "/admin/settings/exchange-rates",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/settings/locales",
        ],
    },

    "settings->users": {
        allowed: "admin/settings/users",
        sidebar: "/admin/settings/users",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->roles": {
        allowed: "admin/settings/roles",
        sidebar: "/admin/settings/roles",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->themes": {
        allowed: "admin/settings/themes",
        sidebar: "/admin/settings/themes",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->taxes": {
        allowed: "admin/settings/taxes/categories",
        sidebar: "/admin/settings/taxes/categories",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

        "settings->data_transfer": {
        allowed: "admin/settings/data-transfer/imports",
        sidebar: "/admin/settings/data-transfer/imports",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    configuration: {
        allowed: "admin/configuration",
        sidebar: "/admin/configuration",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },
};

export class ACLManagement {
    readonly page: Page;
    readonly locators: WebLocators;
    readonly roleName: string;
    readonly userName: string;
    readonly userEmail: string;

    constructor(page: Page) {
        this.page = page;
        this.locators = new WebLocators(page);
        this.roleName = `role-${Date.now()}`;
        this.userName = `user-${Date.now()}`;
        this.userEmail = `user_${Date.now()}_${Math.floor(Math.random() * 10000)}${generateEmail()}`;
    }

    private async togglePermissionCheckbox(
        permissionValue: string,
        shouldCheck: boolean,
    ): Promise<void> {
        const input = this.page.locator(
            `input[name="permissions[]"][value="${permissionValue}"]`,
        );
        await expect(input).toBeAttached({ timeout: 10000 });

        const isChecked = await input.isChecked();
        if (shouldCheck && !isChecked) {
            await input.locator("..").locator("span").click();
        } else if (!shouldCheck && isChecked) {
            await input.locator("..").locator("span").click();
        }
    }

    async rolePermission(permissionValues: string[]): Promise<void> {
        await this.page.waitForLoadState("networkidle");
        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, true);
        }
    }

    async editRolePermission(permissionValues: string[]): Promise<void> {
        await this.page.goto("admin/settings/roles");
        await this.locators.iconEdit.click();
        await this.page.waitForLoadState("networkidle");
        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, false);
        }
        await this.locators.saveRole.click();
        await expect(this.locators.successEditRole).toBeVisible();
    }

    async createRole(
        permissionType: string,
        permissions?: string[],
    ): Promise<void> {
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

    async createUser(): Promise<void> {
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

    async expectUnauthorizedFor(routes: string[]) {
        for (const route of routes) {
            await this.page.goto(route);
            await expect(this.locators.unauthorized).toBeVisible();
        }
    }

    async expectAuthorizedFor(route: string) {
        await this.page.goto(route);
        await expect(this.locators.unauthorized).not.toBeVisible();
    }

    async verfiyAssignedRole(permissions: string[] = []) {
        await this.page.goto("admin/dashboard");
        await this.page.waitForLoadState("networkidle");
        await this.locators.profile.click();
        await this.locators.logout.click();
        await this.page.goto("admin/login");
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
            for (const permission of permissions) {
                const config = ACL_Routes[permission];
                if (!config) continue;

                if (config.notAllowed?.length) {
                    await this.expectUnauthorizedFor(config.notAllowed);
                }

                await this.expectAuthorizedFor(config.allowed);

                sidebarLinks.push(config.sidebar);
            }
        }
        for (const href of sidebarLinks) {
            await expect(
                sidebar.locator(`a[href*="${href}"]`).first(),
            ).toBeVisible();
        }
    }
}
