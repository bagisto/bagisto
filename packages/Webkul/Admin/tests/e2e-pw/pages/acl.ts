import { Page, expect } from "@playwright/test";
import { ACLAdminLocators } from "../locators/admin/acl-admin";
import { loginAsCustomer, addAddress } from "../utils/customer";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    randomElement,
    generateName,
    generateSlug,
    generateSKU,
    generateDescription,
    generateRandomDate,
    generateHostname,
    generatePhoneNumber,
} from "../utils/faker";
import { fillInTinymce } from "../utils/tinymce";

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

    "sales->rma": {
        allowed: "admin/sales/rma/requests",
        sidebar: "/admin/sales/rma/requests",
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
            "admin/sales/refunds",
        ],
    },

    "sales->rma->reason": {
        allowed: "admin/sales/rma/reasons",
        sidebar: "/admin/sales/rma/reasons",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/sales/rma/rules",
            "admin/sales/rma/requests",
            "admin/sales/rma/rma-status",
            "admin/sales/rma/custom-fields",
        ],
    },

    "sales->rma->rma_rules": {
        allowed: "admin/sales/rma/rules",
        sidebar: "/admin/sales/rma/rules",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/sales/rma/reasons",
            "admin/sales/rma/requests",
            "admin/sales/rma/rma-status",
            "admin/sales/rma/custom-fields",
        ],
    },

    "sales->rma->rma_status": {
        allowed: "admin/sales/rma/rma-status",
        sidebar: "/admin/sales/rma/rma-status",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/rma/reasons",
            "admin/sales/rma/rules",
            "admin/sales/rma/requests",
            "admin/sales/rma/custom-fields",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    catalog: {
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

    "marketing->promotions->catalog_rules": {
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
            "admin/marketing/promotions/cart-rules",
        ],
    },

    "marketing->promotions->cart_rules": {
        allowed: "admin/marketing/promotions/cart-rules",
        sidebar: "/admin/marketing/promotions/cart-rules",
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
            "admin/marketing/promotions/catalog-rules",
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

    "marketing->communications->email": {
        allowed: "admin/marketing/communications/email-templates",
        sidebar: "/admin/marketing/communications/email-templates",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/marketing/promotions/cart-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/communications/events",
        ],
    },

    "marketing->communications->event": {
        allowed: "admin/marketing/communications/events",
        sidebar: "/admin/marketing/communications/events",
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

    "marketing->communications->campaign": {
        allowed: "admin/marketing/communications/campaigns",
        sidebar: "/admin/marketing/communications/campaigns",
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
            "admin/marketing/communications/events",
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

    "marketing->search_seo->url_rewrites": {
        allowed: "admin/marketing/search-seo/url-rewrites",
        sidebar: "/admin/marketing/search-seo/url-rewrites",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/marketing/search-seo/sitemaps",
        ],
    },

    "marketing->search_seo->search_terms": {
        allowed: "admin/marketing/search-seo/search-terms",
        sidebar: "/admin/marketing/search-seo/search-terms",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-synonyms",
            "admin/marketing/search-seo/sitemaps",
        ],
    },

    "marketing->search_seo->search_synonyms": {
        allowed: "admin/marketing/search-seo/search-synonyms",
        sidebar: "/admin/marketing/search-seo/search-synonyms",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/sitemaps",
        ],
    },

    "marketing->search_seo->sitemaps": {
        allowed: "admin/marketing/search-seo/sitemaps",
        sidebar: "/admin/marketing/search-seo/sitemaps",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
        ],
    },

    reporting: {
        allowed: "admin/reporting/sales",
        sidebar: "/admin/reporting/sales",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
        ],
    },

    "reporting->sales": {
        allowed: "admin/reporting/sales",
        sidebar: "/admin/reporting/sales",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/reporting/customers",
            "admin/reporting/products",
        ],
    },

    "reporting->customers": {
        allowed: "admin/reporting/customers",
        sidebar: "/admin/reporting/customers",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/reporting/sales",
            "admin/reporting/products",
        ],
    },

    "reporting->products": {
        allowed: "admin/reporting/products",
        sidebar: "/admin/reporting/products",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/reporting/sales",
            "admin/reporting/customers",
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

    "settings->taxes_rates": {
        allowed: "admin/settings/taxes/rates",
        sidebar: "/admin/settings/taxes/rates",
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
            "admin/settings/taxes/categories",
            "admin/sales/transactions",
        ],
    },

    "settings->taxes_categories": {
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
            "admin/settings/taxes/rates",
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
    readonly aclAdminLocators: ACLAdminLocators;
    readonly roleName: string;
    readonly userName: string;
    readonly userEmail: string;

    constructor(page: Page) {
        this.page = page;
        this.aclAdminLocators = new ACLAdminLocators(page);
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
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, false);
        }
        await this.aclAdminLocators.saveRole.click();
        await expect(this.aclAdminLocators.successEditRole).toBeVisible();
    }

    async createRole(
        permissionType: string,
        permissions?: string[],
    ): Promise<void> {
        await this.page.goto("admin/settings/roles");
        await this.aclAdminLocators.createRole.click();
        await this.aclAdminLocators.name.fill(this.roleName);
        await this.aclAdminLocators.selectRoleType.selectOption(permissionType);
        if (
            permissionType === "custom" &&
            permissions &&
            permissions.length > 0
        ) {
            await this.rolePermission(permissions);
        }
        await this.aclAdminLocators.roleDescription.fill("test description");
        await this.aclAdminLocators.saveRole.click();
        await expect(this.aclAdminLocators.successRole.first()).toBeVisible();
    }

    async createUser(): Promise<void> {
        await this.page.goto("admin/settings/users");
        await this.aclAdminLocators.createUser.click();
        await this.aclAdminLocators.name.fill(this.userName);
        await this.aclAdminLocators.selectRole.selectOption({ label: this.roleName });
        await this.aclAdminLocators.userEmail.fill(this.userEmail);
        await this.aclAdminLocators.userPassword.fill("user123");
        await this.aclAdminLocators.confirmPassword.fill("user123");
        await this.aclAdminLocators.statusToggle.click();
        const toggleInput = await this.aclAdminLocators.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.aclAdminLocators.saveUser.click();
        await expect(this.aclAdminLocators.successUser.first()).toBeVisible();
    }

    async expectUnauthorizedFor(routes: string[]) {
        for (const route of routes) {
            await this.page.goto(route);
            await expect(this.aclAdminLocators.unauthorized).toBeVisible();
        }
    }

    async expectAuthorizedFor(route: string) {
        await this.page.goto(route);
        await expect(this.aclAdminLocators.unauthorized).not.toBeVisible();
    }

    async verfiyAssignedRole(permissions: string[] = []) {
        await this.page.goto("admin/dashboard");
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.profile.first().click();
        await this.aclAdminLocators.logout.click();
        await this.page.goto("admin/login");
        await this.page.waitForURL("**/admin/login");
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.userEmail.fill(this.userEmail);
        await this.aclAdminLocators.userPassword.fill("user123");
        await this.aclAdminLocators.userPassword.press("Enter");
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

    async orderCreateVerify() {
        await expect(this.aclAdminLocators.createBtn).toBeVisible();
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.saveBtn).toBeVisible();
    }

    async productEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.editBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.successMSG.first()).toBeVisible();
    }

    async productCopyVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.viewBtn.nth(1)).not.toBeVisible();
        await this.aclAdminLocators.copyBtn.nth(1).click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.copySuccess.first()).toBeVisible();
    }

    async productDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.viewBtn.nth(1)).not.toBeVisible();
        await this.aclAdminLocators.selectRowBtn.nth(2).click();
        await this.aclAdminLocators.selectAction.click();
        await this.aclAdminLocators.selectDelete.click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.productDeleteSuccess.first()).toBeVisible();
    }

    async categoryEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.saveCategoryBtn.click();
        await expect(this.aclAdminLocators.categorySuccess.first()).toBeVisible();
    }

    async categoryDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.selectRowBtn.nth(1).click();
        await this.aclAdminLocators.selectAction.click();
        await this.aclAdminLocators.deleteBtn.click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.categoryDeleteSuccess.first()).toBeVisible();
    }

    async attributeCreateVerify() {
        await expect(this.aclAdminLocators.createBtn).toBeVisible();
        await expect(this.aclAdminLocators.editBtn.first()).not.toBeVisible();
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.fillname.fill("Test Attribute");
        await this.aclAdminLocators.fillCode.fill("testattribute");
        await this.aclAdminLocators.selectTypeAttribute.selectOption("text");
        await this.aclAdminLocators.saveAttributeBtn.click();
        await expect(this.aclAdminLocators.attributeSuccess.first()).toBeVisible();
    }

    async attributeEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.saveAttributeBtn.click();
        await expect(
            this.aclAdminLocators.attributeUpdateSuccess.first(),
        ).toBeVisible();
    }

    async attributeDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.attributeDeleteSuccess.first(),
        ).toBeVisible();
    }

    async familyCreateVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminLocators.editBtn.first()).not.toBeVisible();
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.familyName.fill("Test Family");
        await this.aclAdminLocators.fillCode.fill("family");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.familySuccess.first()).toBeVisible();
    }

    async familyEditVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.familyUpdateSuccess.first()).toBeVisible();
    }

    async familyDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.editBtn.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.familyDeleteSuccess.first()).toBeVisible();
    }

    async customerCreateVerify() {
        await this.page.goto("admin/customers");
        await this.aclAdminLocators.createBtn.click();
        await this.aclAdminLocators.customerfirstname.fill(generateFirstName());
        await this.aclAdminLocators.customerlastname.fill(generateLastName());
        await this.aclAdminLocators.customeremail.fill(generateEmail());
        await this.aclAdminLocators.customergender.selectOption(
            randomElement(["Male", "Female", "Other"]),
        );
        await this.aclAdminLocators.customerNumber.fill("1234567890");
        await this.aclAdminLocators.customerNumber.press("Enter");
        await expect(
            this.aclAdminLocators.customercreatedsuccess.first(),
        ).toBeVisible();
    }

    async customerEditVerify() {
        await this.page.goto("admin/customers");
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.viewIcon.first()).toBeVisible();
    }

    async customerDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.selectRowBtn.nth(1).click();
        await this.aclAdminLocators.selectAction.click();
        await this.aclAdminLocators.deleteBtn.click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.customerDeleteSuccess.first()).toBeVisible();
    }

    async groupCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.aclAdminLocators.name.fill(generateName());
        await this.aclAdminLocators.fillCode.fill("code");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.successGroupMSG.first()).toBeVisible();
    }

    async groupEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.successUpdateMSG.first()).toBeVisible();
    }

    async groupDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successGroupDeleteMSG.first()).toBeVisible();
    }

    async cmsCreateVerify() {
        await this.aclAdminLocators.pagetitle.fill(generateName());
        await this.aclAdminLocators.urlKey.fill(generateSlug());
        await this.aclAdminLocators.metaTitle.fill(generateName());
        await this.aclAdminLocators.metaKeywords.fill("keywords");
        await this.aclAdminLocators.metaDescription.fill(generateDescription());
        await this.aclAdminLocators.statusBTN.first().click();
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.successPageCreate).toBeVisible();
    }

    async cmsEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.successPageUpdate).toBeVisible();
    }

    async cmsDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successPageDelete).toBeVisible();
    }

    async cartRuleCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.ruleName.fill(generateName());
        await this.aclAdminLocators.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.aclAdminLocators.addConditionBtn.click();
        await this.aclAdminLocators.selectCondition.selectOption("product|name");
        await this.aclAdminLocators.conditionName.fill(generateName());
        await this.aclAdminLocators.discountAmmount.fill("10");
        await this.aclAdminLocators.sortOrder.fill("1");
        await this.aclAdminLocators.channelSelect.first().click();
        await expect(this.aclAdminLocators.channelSelect.first()).toBeChecked();
        await this.aclAdminLocators.customerGroupSelect.first().click();
        await expect(this.aclAdminLocators.customerGroupSelect.first()).toBeChecked();
        await this.aclAdminLocators.statusToggle.click();
        await expect(this.aclAdminLocators.toggleInput).toBeChecked();
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.cartRuleSuccess.first()).toBeVisible();
    }

    async cartRuleCopyVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.copyBtn.first().click();
        // await expect(this.aclAdminLocators.cartRuleCopySuccess.first()).toBeVisible();
        await expect(this.aclAdminLocators.saveCartRuleBTN).toBeVisible();
    }

    async cartRuleEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.copyBtn.first()).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.cartRuleEditSuccess.first()).toBeVisible();
    }

    async cartRuleDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.copyBtn.first()).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.cartRuleDeleteSuccess.first()).toBeVisible();
    }

    async catalogRuleCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.ruleName.fill(generateName());
        await this.aclAdminLocators.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.aclAdminLocators.addConditionBtn.click();
        await this.aclAdminLocators.selectCondition.selectOption("product|name");
        await this.aclAdminLocators.conditionName.fill(generateName());
        await this.aclAdminLocators.discountAmmount.fill("10");
        await this.aclAdminLocators.sortOrder.fill("1");
        await this.aclAdminLocators.channelSelect.first().click();
        await expect(this.aclAdminLocators.channelSelect.first()).toBeChecked();
        await this.aclAdminLocators.customerGroupSelect.first().click();
        await expect(this.aclAdminLocators.customerGroupSelect.first()).toBeChecked();
        await this.aclAdminLocators.statusToggle.click();
        await expect(this.aclAdminLocators.toggleInput).toBeChecked();
        await this.aclAdminLocators.createBtn.click();
        await expect(
            this.aclAdminLocators.catalogRuleCreateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(
            this.aclAdminLocators.catalogRuleUpdateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.catalogRuleDeleteSuccess.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        const description = generateDescription();
        await this.aclAdminLocators.name.fill("template");
        await fillInTinymce(this.page, "#content_ifr", description);
        await this.aclAdminLocators.emailStatusSeletct.selectOption("active");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.emailSuccessMSG.first()).toBeVisible();
    }

    async communicationEmailTemplateEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.emailUpdateSuccessMSG.first()).toBeVisible();
    }

    async communicationEmailTemplateDeleteVerify() {
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.emailDeleteSuccessMSG.first()).toBeVisible();
    }

    async eventCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.hover('input[name="name"]');
        const inputs = await this.page.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible',
        );

        for (let input of inputs) {
            await input.fill(generateName());
        }
        await this.aclAdminLocators.date.fill(generateRandomDate());
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.eventCreateSuccess.first()).toBeVisible();
    }

    async eventEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.eventUpdateSuccess.first()).toBeVisible();
    }

    async eventDeleteVerify() {
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await expect(this.aclAdminLocators.createBtn.nth(1)).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.eventDeleteSuccess.first()).toBeVisible();
    }

    async campaignCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.name.fill(generateName());
        await this.aclAdminLocators.subject.fill(generateName());
        await this.aclAdminLocators.event.selectOption({ label: "Birthday" });
        await this.aclAdminLocators.emailTemplate.selectOption({ label: "template" });
        await this.aclAdminLocators.selectChannel.selectOption("1");
        await this.aclAdminLocators.customerGroup.selectOption("1");
        await this.aclAdminLocators.campaignStatus.click();
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.campaignCreateSuccess.first()).toBeVisible();
    }

    async campaignEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.campaignUpdateSuccess.first()).toBeVisible();
    }

    async campaignDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.campaignDeleteSuccess.first()).toBeVisible();
    }

    async urlRewriteCreateVerify() {
        const seo = {
            url: generateHostname(),
            product: "product",
        };
        await this.aclAdminLocators.createBtn.click();
        await this.aclAdminLocators.entityType.selectOption(seo.product);
        await this.aclAdminLocators.requestPath.fill(seo.url);
        await this.aclAdminLocators.targetPath.fill(seo.url);
        await this.aclAdminLocators.redirectPath.selectOption("301");
        await this.aclAdminLocators.locale.selectOption("en");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.saveRedirectSuccess).toBeVisible();
    }

    async urlRewriteEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.saveRedirectUpdatedSuccess).toBeVisible();
    }

    async urlRewriteDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.deleteRedirectSuccess).toBeVisible();
    }

    async searchTermsCreateVerify() {
        await expect(this.aclAdminLocators.deleteIcon.first()).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.createBtn.click();
        await this.aclAdminLocators.searchQuery.fill(generateName());
        await this.aclAdminLocators.redirectURL.fill("https://example.com");
        await this.aclAdminLocators.selectChannel.selectOption("1");
        await this.aclAdminLocators.locale.selectOption("en");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(
            this.aclAdminLocators.searchTermCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(
            this.aclAdminLocators.searchTermUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.searchTermDeleteSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.name.fill(generateName());
        await this.aclAdminLocators.terms.fill("test, synonym");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(
            this.aclAdminLocators.searchSynonymCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(
            this.aclAdminLocators.searchSynonymUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.searchSynonymDeleteSuccess.first(),
        ).toBeVisible();
    }

    async sitemapCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await expect(this.aclAdminLocators.deleteIcon.first()).not.toBeVisible();
        await this.aclAdminLocators.fileName.fill("sitemap.xml");
        await this.aclAdminLocators.path.fill("/sitemapxml/test/example/");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.sitemapCreateSuccess.first()).toBeVisible();
    }

    async sitemapEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.deleteIcon.first()).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.sitemapUpdateSuccess.first()).toBeVisible();
    }

    async sitemapDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.sitemapDeleteSuccess.first()).toBeVisible();
    }

    async localeCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.fillCode.fill("test");
        await this.aclAdminLocators.name.fill("TestLocale");
        await this.aclAdminLocators.direction.selectOption("ltr");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.successLocaleCreate.first()).toBeVisible();
    }

    async localeEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.successLocaleUpdate.first()).toBeVisible();
    }

    async localeDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successLocaleDelete.first()).toBeVisible();
    }

    async currencyCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.fillCode.fill("TST");
        await this.aclAdminLocators.name.fill("Test Currency");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.successCurrencyCreate.first()).toBeVisible();
    }

    async currencyEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.successCurrencyUpdate.first()).toBeVisible();
    }

    async currencyDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successCurrencyDelete.first()).toBeVisible();
    }

    async exchangeRateCreateVerify() {
        await this.page.goto("admin/settings/currencies");
        await this.currencyCreateVerify();
        await this.page.goto("admin/settings/channels");
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.getByText("Test Currency").first().click();
        await this.aclAdminLocators.createBtn.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.goto("admin/settings/exchange-rates");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.targetCurrency.selectOption({
            label: "Test Currency",
        });
        await this.aclAdminLocators.rate.fill("100");
        await this.aclAdminLocators.createBtn.nth(2).click();
        await expect(
            this.aclAdminLocators.successExchangeRateCreate.first(),
        ).toBeVisible();
    }

    async exchangeRateEditVerify() {
        await expect(this.aclAdminLocators.createBtn.nth(1)).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(
            this.aclAdminLocators.successExchangeRateUpdate.first(),
        ).toBeVisible();
    }

    async exchangeRateDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn.nth(1)).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.successExchangeRateDelete.first(),
        ).toBeVisible();
    }

    async inventorySourceCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.fillCode.fill("testsource");
        await this.aclAdminLocators.name.fill(generateName());
        await this.aclAdminLocators.description.fill(generateDescription());
        await this.aclAdminLocators.contactName.fill(generateName());
        await this.aclAdminLocators.enterEmail.fill(generateEmail());
        await this.aclAdminLocators.contactNumber.fill(generatePhoneNumber());
        await this.aclAdminLocators.fax.fill(generatePhoneNumber());
        await this.aclAdminLocators.country.selectOption("IN");
        await this.aclAdminLocators.state.selectOption("DL");
        await this.aclAdminLocators.city.fill("New Delhi");
        await this.aclAdminLocators.street.fill("Some street address");
        await this.aclAdminLocators.postcode.fill("110001");
        await this.aclAdminLocators.statusToggle.click();
        await this.aclAdminLocators.createBtn.click();
        await expect(
            this.aclAdminLocators.successInventorySourceCreate.first(),
        ).toBeVisible();
    }

    async inventorySourceEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(
            this.aclAdminLocators.successInventorySourceUpdate.first(),
        ).toBeVisible();
    }

    async inventorySourceDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.successInventorySourceDelete.first(),
        ).toBeVisible();
    }

    async channelCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.name.fill(generateName());
        const code = generateSlug("_");
        const name = generateName();
        const description = generateDescription();
        await this.aclAdminLocators.fillCode.fill(code);
        await this.aclAdminLocators.promotionRuleDescription.fill(description);
        await this.aclAdminLocators.inventoryToggle.first().click();
        await this.aclAdminLocators.categoryID.selectOption("1");
        await this.aclAdminLocators.hostname.fill(generateHostname());
        await this.aclAdminLocators.selectLocale.first().click();
        await this.aclAdminLocators.selectCurrency.first().click();
        await this.aclAdminLocators.baseCurrencyID.selectOption("1");
        await this.aclAdminLocators.defaultLocaleID.selectOption("1");
        await this.aclAdminLocators.metaTitleChannel.fill(name);
        await this.aclAdminLocators.seoKeywords.fill("keywords");
        await this.aclAdminLocators.metaDescription.fill(description);
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.channleCreateSuccess.first()).toBeVisible();
    }

    async channelEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.channlUpdateSuccess.first()).toBeVisible();
    }

    async channelDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.channelDeleteSuccess.first()).toBeVisible();
    }

    async createUserVerify() {
        await this.aclAdminLocators.createUser.click();
        await this.aclAdminLocators.name.fill(this.userName);
        await this.aclAdminLocators.selectRole.selectOption({ label: this.roleName });
        await this.aclAdminLocators.userEmail.fill(generateEmail());
        await this.aclAdminLocators.userPassword.fill("user123");
        await this.aclAdminLocators.confirmPassword.fill("user123");
        await this.aclAdminLocators.statusToggle.click();
        const toggleInput = await this.aclAdminLocators.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.aclAdminLocators.saveUser.click();
        await expect(this.aclAdminLocators.successUser.first()).toBeVisible();
    }

    async editUserVerify() {
        await expect(this.aclAdminLocators.createUser).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.saveUser.click();
        await expect(this.aclAdminLocators.successUserUpdate.first()).toBeVisible();
    }

    async deleteUserVerify() {
        await expect(this.aclAdminLocators.createUser).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.nth(2).click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successUserDelete.first()).toBeVisible();
    }

    async roleCreateVerify() {
        await this.aclAdminLocators.createRole.click();
        await this.aclAdminLocators.name.fill(this.roleName);
        await this.aclAdminLocators.selectRoleType.selectOption("all");
        await this.aclAdminLocators.roleDescription.fill("test description");
        await this.aclAdminLocators.saveRole.click();
        await expect(this.aclAdminLocators.successRole.first()).toBeVisible();
    }

    async roleEditVerify() {
        await expect(this.aclAdminLocators.createRole).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.saveRole.click();
        await expect(this.aclAdminLocators.successUpdateRole.first()).toBeVisible();
    }

    async roleDeleteVerify() {
        await expect(this.aclAdminLocators.createRole).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.nth(2).click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successDeleteRole.first()).toBeVisible();
    }

    async themeCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.name.fill(generateName());
        await this.aclAdminLocators.sortOrder.fill("1");
        await this.aclAdminLocators.selectTypeAttribute.selectOption(
            "product_carousel",
        );
        await this.aclAdminLocators.selectChannel.selectOption("1");
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(this.aclAdminLocators.athorization.first()).toBeVisible();
    }

    async themeEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.iconEdit.nth(3).click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.click();
        await expect(this.aclAdminLocators.successEditTheme.first()).toBeVisible();
    }

    async themeDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.nth(3)).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successDeleteTheme.first()).toBeVisible();
    }

    async taxrateCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.identifier.fill("test-tax-rate");
        await this.aclAdminLocators.selectCountry.selectOption("IN");
        await this.aclAdminLocators.taxRate.fill("10");
        await this.aclAdminLocators.createBtn.first().click();
        await expect(this.aclAdminLocators.successCreateTaxRate.first()).toBeVisible();
    }

    async taxrateEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.first().click();
        await expect(this.aclAdminLocators.successUpdateTaxRate.first()).toBeVisible();
    }

    async taxrateDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.successDeleteTaxRate.first()).toBeVisible();
    }

    async taxcategoryCreateVerify() {
        await this.aclAdminLocators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.fillCode.fill("test-tax-category");
        await this.aclAdminLocators.name.fill("Test Tax Category");
        await this.aclAdminLocators.description.fill("This is a test tax category");
        await this.aclAdminLocators.selectTaxRate.selectOption({
            label: "test-tax-rate",
        });
        await this.aclAdminLocators.createBtn.nth(1).click();
        await expect(
            this.aclAdminLocators.successCreateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryEditVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.createBtn.first().click();
        await expect(
            this.aclAdminLocators.successUpdateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryDeleteVerify() {
        await expect(this.aclAdminLocators.createBtn).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.successDeleteTaxCategory.first(),
        ).toBeVisible();
    }

    async createOrder() {
        await loginAsCustomer(this.page);
        await this.page.waitForLoadState("networkidle");
        const acceptButton = this.page.getByRole("button", { name: "Accept" });

        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }
        await addAddress(this.page);
        await this.page.goto("");
        await this.aclAdminLocators.searchInput.fill("simple");
        await this.aclAdminLocators.searchInput.press("Enter");
        await this.aclAdminLocators.addToCartButton.first().click();
        await expect(this.aclAdminLocators.addCartSuccess.first()).toBeVisible();
        await this.aclAdminLocators.ShoppingCartIcon.click();
        await this.aclAdminLocators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.aclAdminLocators.clickProcessButton.click();
        await this.aclAdminLocators.chooseShippingMethod.click();
        await this.aclAdminLocators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.aclAdminLocators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    async createSimpleProduct(adminPage) {
        /**
         * Main product data which we will use to create the product.
         */
        const product = {
            name: `simple-${Date.now()}`,
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
        };

        /**
         * Reaching to the create product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        await adminPage.getByRole("button", { name: "Create Product" }).click();

        /**
         * Opening create product form in modal.
         */
        await adminPage.locator('select[name="type"]').selectOption("simple");
        await adminPage
            .locator('select[name="attribute_family_id"]')
            .selectOption("1");
        await adminPage.locator('input[name="sku"]').fill(generateSKU());
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * After creating the product, the page is redirected to the edit product page, where
         * all the details need to be filled in.
         */
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Save Product")',
        );

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        /**
         * General Section.
         */
        await adminPage.locator("#product_number").fill(product.productNumber);
        await adminPage.locator("#name").fill(product.name);
        const name = await adminPage.locator('input[name="name"]').inputValue();

        /**
         * Description Section.
         */
        await adminPage.fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await adminPage.fillInTinymce("#description_ifr", product.description);

        /**
         * Meta Description Section.
         */
        await adminPage.locator("#meta_title").fill(product.name);
        await adminPage.locator("#meta_keywords").fill(product.name);
        await adminPage
            .locator("#meta_description")
            .fill(product.shortDescription);

        /**
         * Image Section.
         */
        // Will add images later.

        /**
         * Price Section.
         */
        await adminPage.locator("#price").fill(product.price);

        /**
         * Shipping Section.
         */
        await adminPage.locator("#weight").fill(product.weight);

        /**
         * Inventories Section.
         */
        await adminPage.locator('input[name="inventories\\[1\\]"]').click();
        await adminPage
            .locator('input[name="inventories\\[1\\]"]')
            .fill("5000");

        /**
         * RMA
         */

        await this.page.locator('label[for="allow_rma"]').click();
        await this.aclAdminLocators.rmaSelection.selectOption("1");

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator("#app")).toContainText(
            /product updated successfully/i,
        );

        /**
         * Checking the product in the list.
         */
        await adminPage.goto("admin/catalog/products");
        await expect(
            adminPage
                .locator("p.break-all.text-base")
                .filter({ hasText: product.name }),
        ).toBeVisible();
    }

    async rmaCreateVerify() {
        await this.createSimpleProduct(this.page);
        await this.createOrder();
        await this.page.goto("admin/sales/rma/requests");
        await this.aclAdminLocators.createBtn.click();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.aclAdminLocators.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.resolution.selectOption("cancel_items");
        await this.aclAdminLocators.resolution.selectOption("cancel_items");
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.reason.selectOption("1");
        await this.aclAdminLocators.rmaQTY.fill("1");
        await this.aclAdminLocators.info.fill("Changed My Mind.");
        await this.aclAdminLocators.createBtn.first().click();
        await expect(this.aclAdminLocators.successAdminRMA).toBeVisible();
    }

    async rmaReasonCreateVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await this.aclAdminLocators.createRMAReason.click();
        await this.aclAdminLocators.reasonTitle.fill("Broken Product");
        await this.aclAdminLocators.reasonStatus.check();
        await this.aclAdminLocators.position.fill("1");
        await this.aclAdminLocators.reasonType.selectOption("return");
        await this.aclAdminLocators.saveReason.click();
        await expect(this.aclAdminLocators.saveReasonSuccess).toBeVisible();
    }

    async rmaReasonEditVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await expect(this.aclAdminLocators.createRMAReason).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.aclAdminLocators.position.fill("5");
        await this.aclAdminLocators.saveReason.click();
        await expect(
            this.aclAdminLocators.saveReasonUpdateSuccess.first(),
        ).toBeVisible();
    }

    async rmaReasonDeleteVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await expect(this.aclAdminLocators.createRMAReason).not.toBeVisible();
        await expect(this.aclAdminLocators.editIcon.first()).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(
            this.aclAdminLocators.saveReasonDeleteSuccess.first(),
        ).toBeVisible();
    }

    async rmaRulesCreateVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await this.aclAdminLocators.rmaRulesCreate.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.ruleTitle.fill("Test Rule1");
        await this.aclAdminLocators.reasonStatus.check();
        await this.aclAdminLocators.ruleDescription.fill("Test Rule Description");
        await this.aclAdminLocators.returnPeriod.fill("15");
        await this.aclAdminLocators.saveRule.click();
        await expect(this.aclAdminLocators.ruleSuccessMSG).toBeVisible();
    }

    async rmaRulesEditVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await expect(this.aclAdminLocators.rmaRulesCreate).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.ruleTitle.fill("Test Rule1");
        await this.aclAdminLocators.reasonStatus.check();
        await this.aclAdminLocators.ruleDescription.fill("Test Rule Description");
        await this.aclAdminLocators.returnPeriod.fill("15");
        await this.aclAdminLocators.saveRule.click();
        await expect(this.aclAdminLocators.ruleSuccessUpdatedMSG).toBeVisible();
    }

    async rmaRulesDeleteVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await expect(this.aclAdminLocators.rmaRulesCreate).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit).not.toBeVisible();
        await this.aclAdminLocators.deleteIcon.first().click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.ruleDeleteSuccessMSG).toBeVisible();
    }

    async rmaStatusCreateVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await this.aclAdminLocators.createRMAStatus.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.rmaStatusTitle.fill("RMA Status");
        await this.aclAdminLocators.reasonStatus.click();
        await this.aclAdminLocators.saveStatus.click();
        await expect(this.aclAdminLocators.statusSuccess).toBeVisible();
    }

    async rmaStatusEditVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await expect(this.aclAdminLocators.createRMAStatus).not.toBeVisible();
        await this.aclAdminLocators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminLocators.rmaStatusTitle.fill("RMA Status edited");
        await this.aclAdminLocators.saveStatus.click();
        await expect(this.aclAdminLocators.statusUpdateSuccess).toBeVisible();
    }

    async rmaStatusDeleteVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await expect(this.aclAdminLocators.createRMAStatus).not.toBeVisible();
        await expect(this.aclAdminLocators.iconEdit.first()).not.toBeVisible();
        await expect(this.aclAdminLocators.deleteIcon.first()).not.toBeVisible();
        await this.aclAdminLocators.selectRowBtn.first().click();
        await this.aclAdminLocators.selectAction.click();
        await this.aclAdminLocators.deleteBtn.click();
        await this.aclAdminLocators.agreeBtn.click();
        await expect(this.aclAdminLocators.statusDeleteSuccess).toBeVisible();
    }
}
