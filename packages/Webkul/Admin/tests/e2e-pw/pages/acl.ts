import { Page, expect } from "@playwright/test";
import { ACLAdminPage } from "../locators/admin/acl";
import { ACLShopPage } from "../locators/shop/acl";
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
    readonly aclAdminPage: ACLAdminPage;
    readonly aclShopPage: ACLShopPage;
    readonly roleName: string;
    readonly userName: string;
    readonly userEmail: string;

    constructor(page: Page) {
        this.page = page;
        this.aclAdminPage = new ACLAdminPage(page);
        this.aclShopPage = new ACLShopPage(page);
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
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, false);
        }
        await this.aclAdminPage.saveRole.click();
        await expect(this.aclAdminPage.successEditRole).toBeVisible();
    }

    async createRole(
        permissionType: string,
        permissions?: string[],
    ): Promise<void> {
        await this.page.goto("admin/settings/roles");
        await this.aclAdminPage.createRole.click();
        await this.aclAdminPage.name.fill(this.roleName);
        await this.aclAdminPage.selectRoleType.selectOption(permissionType);
        if (
            permissionType === "custom" &&
            permissions &&
            permissions.length > 0
        ) {
            await this.rolePermission(permissions);
        }
        await this.aclAdminPage.roleDescription.fill("test description");
        await this.aclAdminPage.saveRole.click();
        await expect(this.aclAdminPage.successRole.first()).toBeVisible();
    }

    async createUser(): Promise<void> {
        await this.page.goto("admin/settings/users");
        await this.aclAdminPage.createUser.click();
        await this.aclAdminPage.name.fill(this.userName);
        await this.aclAdminPage.selectRole.selectOption({ label: this.roleName });
        await this.aclAdminPage.userEmail.fill(this.userEmail);
        await this.aclAdminPage.userPassword.fill("user123");
        await this.aclAdminPage.confirmPassword.fill("user123");
        await this.aclAdminPage.statusToggle.click();
        const toggleInput = await this.aclAdminPage.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.aclAdminPage.saveUser.click();
        await expect(this.aclAdminPage.successUser.first()).toBeVisible();
    }

    async expectUnauthorizedFor(routes: string[]) {
        for (const route of routes) {
            await this.page.goto(route);
            await expect(this.aclAdminPage.unauthorized).toBeVisible();
        }
    }

    async expectAuthorizedFor(route: string) {
        await this.page.goto(route);
        await expect(this.aclAdminPage.unauthorized).not.toBeVisible();
    }

    async verfiyAssignedRole(permissions: string[] = []) {
        await this.page.goto("admin/dashboard");
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.profile.first().click();
        await this.aclAdminPage.logout.click();
        await this.page.goto("admin/login");
        await this.page.waitForURL("**/admin/login");
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.userEmail.fill(this.userEmail);
        await this.aclAdminPage.userPassword.fill("user123");
        await this.aclAdminPage.userPassword.press("Enter");
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
        await expect(this.aclAdminPage.createBtn).toBeVisible();
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.saveBtn).toBeVisible();
    }

    async productEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.editBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.successMSG.first()).toBeVisible();
    }

    async productCopyVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.viewBtn.nth(1)).not.toBeVisible();
        await this.aclAdminPage.copyBtn.nth(1).click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.copySuccess.first()).toBeVisible();
    }

    async productDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.viewBtn.nth(1)).not.toBeVisible();
        await this.aclAdminPage.selectRowBtn.nth(2).click();
        await this.aclAdminPage.selectAction.click();
        await this.aclAdminPage.selectDelete.click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.productDeleteSuccess.first()).toBeVisible();
    }

    async categoryEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.saveCategoryBtn.click();
        await expect(this.aclAdminPage.categorySuccess.first()).toBeVisible();
    }

    async categoryDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.selectRowBtn.nth(1).click();
        await this.aclAdminPage.selectAction.click();
        await this.aclAdminPage.deleteBtn.click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.categoryDeleteSuccess.first()).toBeVisible();
    }

    async attributeCreateVerify() {
        await expect(this.aclAdminPage.createBtn).toBeVisible();
        await expect(this.aclAdminPage.editBtn.first()).not.toBeVisible();
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.fillname.fill("Test Attribute");
        await this.aclAdminPage.fillCode.fill("testattribute");
        await this.aclAdminPage.selectTypeAttribute.selectOption("text");
        await this.aclAdminPage.saveAttributeBtn.click();
        await expect(this.aclAdminPage.attributeSuccess.first()).toBeVisible();
    }

    async attributeEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.saveAttributeBtn.click();
        await expect(
            this.aclAdminPage.attributeUpdateSuccess.first(),
        ).toBeVisible();
    }

    async attributeDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.attributeDeleteSuccess.first(),
        ).toBeVisible();
    }

    async familyCreateVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminPage.editBtn.first()).not.toBeVisible();
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.familyName.fill("Test Family");
        await this.aclAdminPage.fillCode.fill("family");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.familySuccess.first()).toBeVisible();
    }

    async familyEditVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.familyUpdateSuccess.first()).toBeVisible();
    }

    async familyDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.editBtn.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.familyDeleteSuccess.first()).toBeVisible();
    }

    async customerCreateVerify() {
        await this.page.goto("admin/customers");
        await this.aclAdminPage.createBtn.click();
        await this.aclAdminPage.customerfirstname.fill(generateFirstName());
        await this.aclAdminPage.customerlastname.fill(generateLastName());
        await this.aclAdminPage.customeremail.fill(generateEmail());
        await this.aclAdminPage.customergender.selectOption(
            randomElement(["Male", "Female", "Other"]),
        );
        await this.aclAdminPage.customerNumber.fill("1234567890");
        await this.aclAdminPage.customerNumber.press("Enter");
        await expect(
            this.aclAdminPage.customercreatedsuccess.first(),
        ).toBeVisible();
    }

    async customerEditVerify() {
        await this.page.goto("admin/customers");
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.viewIcon.first()).toBeVisible();
    }

    async customerDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.selectRowBtn.nth(1).click();
        await this.aclAdminPage.selectAction.click();
        await this.aclAdminPage.deleteBtn.click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.customerDeleteSuccess.first()).toBeVisible();
    }

    async groupCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.aclAdminPage.name.fill(generateName());
        await this.aclAdminPage.fillCode.fill("code");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.successGroupMSG.first()).toBeVisible();
    }

    async groupEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.successUpdateMSG.first()).toBeVisible();
    }

    async groupDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successGroupDeleteMSG.first()).toBeVisible();
    }

    async cmsCreateVerify() {
        await this.aclAdminPage.pagetitle.fill(generateName());
        await this.aclAdminPage.urlKey.fill(generateSlug());
        await this.aclAdminPage.metaTitle.fill(generateName());
        await this.aclAdminPage.metaKeywords.fill("keywords");
        await this.aclAdminPage.metaDescription.fill(generateDescription());
        await this.aclAdminPage.statusBTN.first().click();
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.successPageCreate).toBeVisible();
    }

    async cmsEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.successPageUpdate).toBeVisible();
    }

    async cmsDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successPageDelete).toBeVisible();
    }

    async cartRuleCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.ruleName.fill(generateName());
        await this.aclAdminPage.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.aclAdminPage.addConditionBtn.click();
        await this.aclAdminPage.selectCondition.selectOption("product|name");
        await this.aclAdminPage.conditionName.fill(generateName());
        await this.aclAdminPage.discountAmmount.fill("10");
        await this.aclAdminPage.sortOrder.fill("1");
        await this.aclAdminPage.channelSelect.first().click();
        await expect(this.aclAdminPage.channelSelect.first()).toBeChecked();
        await this.aclAdminPage.customerGroupSelect.first().click();
        await expect(this.aclAdminPage.customerGroupSelect.first()).toBeChecked();
        await this.aclAdminPage.statusToggle.click();
        await expect(this.aclAdminPage.toggleInput).toBeChecked();
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.cartRuleSuccess.first()).toBeVisible();
    }

    async cartRuleCopyVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.copyBtn.first().click();
        // await expect(this.aclAdminPage.cartRuleCopySuccess.first()).toBeVisible();
        await expect(this.aclAdminPage.saveCartRuleBTN).toBeVisible();
    }

    async cartRuleEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.copyBtn.first()).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.cartRuleEditSuccess.first()).toBeVisible();
    }

    async cartRuleDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.copyBtn.first()).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.cartRuleDeleteSuccess.first()).toBeVisible();
    }

    async catalogRuleCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.ruleName.fill(generateName());
        await this.aclAdminPage.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.aclAdminPage.addConditionBtn.click();
        await this.aclAdminPage.selectCondition.selectOption("product|name");
        await this.aclAdminPage.conditionName.fill(generateName());
        await this.aclAdminPage.discountAmmount.fill("10");
        await this.aclAdminPage.sortOrder.fill("1");
        await this.aclAdminPage.channelSelect.first().click();
        await expect(this.aclAdminPage.channelSelect.first()).toBeChecked();
        await this.aclAdminPage.customerGroupSelect.first().click();
        await expect(this.aclAdminPage.customerGroupSelect.first()).toBeChecked();
        await this.aclAdminPage.statusToggle.click();
        await expect(this.aclAdminPage.toggleInput).toBeChecked();
        await this.aclAdminPage.createBtn.click();
        await expect(
            this.aclAdminPage.catalogRuleCreateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(
            this.aclAdminPage.catalogRuleUpdateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.catalogRuleDeleteSuccess.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        const description = generateDescription();
        await this.aclAdminPage.name.fill("template");
        await fillInTinymce(this.page, "#content_ifr", description);
        await this.aclAdminPage.emailStatusSeletct.selectOption("active");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.emailSuccessMSG.first()).toBeVisible();
    }

    async communicationEmailTemplateEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.emailUpdateSuccessMSG.first()).toBeVisible();
    }

    async communicationEmailTemplateDeleteVerify() {
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.emailDeleteSuccessMSG.first()).toBeVisible();
    }

    async eventCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.hover('input[name="name"]');
        const inputs = await this.page.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible',
        );

        for (let input of inputs) {
            await input.fill(generateName());
        }
        await this.aclAdminPage.date.fill(generateRandomDate());
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.eventCreateSuccess.first()).toBeVisible();
    }

    async eventEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.eventUpdateSuccess.first()).toBeVisible();
    }

    async eventDeleteVerify() {
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await expect(this.aclAdminPage.createBtn.nth(1)).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.eventDeleteSuccess.first()).toBeVisible();
    }

    async campaignCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.name.fill(generateName());
        await this.aclAdminPage.subject.fill(generateName());
        await this.aclAdminPage.event.selectOption({ label: "Birthday" });
        await this.aclAdminPage.emailTemplate.selectOption({ label: "template" });
        await this.aclAdminPage.selectChannel.selectOption("1");
        await this.aclAdminPage.customerGroup.selectOption("1");
        await this.aclAdminPage.campaignStatus.click();
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.campaignCreateSuccess.first()).toBeVisible();
    }

    async campaignEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.campaignUpdateSuccess.first()).toBeVisible();
    }

    async campaignDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.campaignDeleteSuccess.first()).toBeVisible();
    }

    async urlRewriteCreateVerify() {
        const seo = {
            url: generateHostname(),
            product: "product",
        };
        await this.aclAdminPage.createBtn.click();
        await this.aclAdminPage.entityType.selectOption(seo.product);
        await this.aclAdminPage.requestPath.fill(seo.url);
        await this.aclAdminPage.targetPath.fill(seo.url);
        await this.aclAdminPage.redirectPath.selectOption("301");
        await this.aclAdminPage.locale.selectOption("en");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.saveRedirectSuccess).toBeVisible();
    }

    async urlRewriteEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.saveRedirectUpdatedSuccess).toBeVisible();
    }

    async urlRewriteDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.deleteRedirectSuccess).toBeVisible();
    }

    async searchTermsCreateVerify() {
        await expect(this.aclAdminPage.deleteIcon.first()).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.createBtn.click();
        await this.aclAdminPage.searchQuery.fill(generateName());
        await this.aclAdminPage.redirectURL.fill("https://example.com");
        await this.aclAdminPage.selectChannel.selectOption("1");
        await this.aclAdminPage.locale.selectOption("en");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(
            this.aclAdminPage.searchTermCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(
            this.aclAdminPage.searchTermUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.searchTermDeleteSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.name.fill(generateName());
        await this.aclAdminPage.terms.fill("test, synonym");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(
            this.aclAdminPage.searchSynonymCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(
            this.aclAdminPage.searchSynonymUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.searchSynonymDeleteSuccess.first(),
        ).toBeVisible();
    }

    async sitemapCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await expect(this.aclAdminPage.deleteIcon.first()).not.toBeVisible();
        await this.aclAdminPage.fileName.fill("sitemap.xml");
        await this.aclAdminPage.path.fill("/sitemapxml/test/example/");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.sitemapCreateSuccess.first()).toBeVisible();
    }

    async sitemapEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.deleteIcon.first()).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.sitemapUpdateSuccess.first()).toBeVisible();
    }

    async sitemapDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.sitemapDeleteSuccess.first()).toBeVisible();
    }

    async localeCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.fillCode.fill("test");
        await this.aclAdminPage.name.fill("TestLocale");
        await this.aclAdminPage.direction.selectOption("ltr");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.successLocaleCreate.first()).toBeVisible();
    }

    async localeEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.successLocaleUpdate.first()).toBeVisible();
    }

    async localeDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successLocaleDelete.first()).toBeVisible();
    }

    async currencyCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.fillCode.fill("TST");
        await this.aclAdminPage.name.fill("Test Currency");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.successCurrencyCreate.first()).toBeVisible();
    }

    async currencyEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.successCurrencyUpdate.first()).toBeVisible();
    }

    async currencyDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successCurrencyDelete.first()).toBeVisible();
    }

    async exchangeRateCreateVerify() {
        await this.page.goto("admin/settings/currencies");
        await this.currencyCreateVerify();
        await this.page.goto("admin/settings/channels");
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.getByText("Test Currency").first().click();
        await this.aclAdminPage.createBtn.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.goto("admin/settings/exchange-rates");
        await this.aclAdminPage.createBtn.nth(1).click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.targetCurrency.selectOption({
            label: "Test Currency",
        });
        await this.aclAdminPage.rate.fill("100");
        await this.aclAdminPage.createBtn.nth(2).click();
        await expect(
            this.aclAdminPage.successExchangeRateCreate.first(),
        ).toBeVisible();
    }

    async exchangeRateEditVerify() {
        await expect(this.aclAdminPage.createBtn.nth(1)).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(
            this.aclAdminPage.successExchangeRateUpdate.first(),
        ).toBeVisible();
    }

    async exchangeRateDeleteVerify() {
        await expect(this.aclAdminPage.createBtn.nth(1)).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.successExchangeRateDelete.first(),
        ).toBeVisible();
    }

    async inventorySourceCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.fillCode.fill("testsource");
        await this.aclAdminPage.name.fill(generateName());
        await this.aclAdminPage.description.fill(generateDescription());
        await this.aclAdminPage.contactName.fill(generateName());
        await this.aclAdminPage.enterEmail.fill(generateEmail());
        await this.aclAdminPage.contactNumber.fill(generatePhoneNumber());
        await this.aclAdminPage.fax.fill(generatePhoneNumber());
        await this.aclAdminPage.country.selectOption("IN");
        await this.aclAdminPage.state.selectOption("DL");
        await this.aclAdminPage.city.fill("New Delhi");
        await this.aclAdminPage.street.fill("Some street address");
        await this.aclAdminPage.postcode.fill("110001");
        await this.aclAdminPage.statusToggle.click();
        await this.aclAdminPage.createBtn.click();
        await expect(
            this.aclAdminPage.successInventorySourceCreate.first(),
        ).toBeVisible();
    }

    async inventorySourceEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(
            this.aclAdminPage.successInventorySourceUpdate.first(),
        ).toBeVisible();
    }

    async inventorySourceDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.successInventorySourceDelete.first(),
        ).toBeVisible();
    }

    async channelCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.name.fill(generateName());
        const code = generateSlug("_");
        const name = generateName();
        const description = generateDescription();
        await this.aclAdminPage.fillCode.fill(code);
        await this.aclAdminPage.promotionRuleDescription.fill(description);
        await this.aclAdminPage.inventoryToggle.first().click();
        await this.aclAdminPage.categoryID.selectOption("1");
        await this.aclAdminPage.hostname.fill(generateHostname());
        await this.aclAdminPage.selectLocale.first().click();
        await this.aclAdminPage.selectCurrency.first().click();
        await this.aclAdminPage.baseCurrencyID.selectOption("1");
        await this.aclAdminPage.defaultLocaleID.selectOption("1");
        await this.aclAdminPage.metaTitleChannel.fill(name);
        await this.aclAdminPage.seoKeywords.fill("keywords");
        await this.aclAdminPage.metaDescription.fill(description);
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.channleCreateSuccess.first()).toBeVisible();
    }

    async channelEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.channlUpdateSuccess.first()).toBeVisible();
    }

    async channelDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.channelDeleteSuccess.first()).toBeVisible();
    }

    async createUserVerify() {
        await this.aclAdminPage.createUser.click();
        await this.aclAdminPage.name.fill(this.userName);
        await this.aclAdminPage.selectRole.selectOption({ label: this.roleName });
        await this.aclAdminPage.userEmail.fill(generateEmail());
        await this.aclAdminPage.userPassword.fill("user123");
        await this.aclAdminPage.confirmPassword.fill("user123");
        await this.aclAdminPage.statusToggle.click();
        const toggleInput = await this.aclAdminPage.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.aclAdminPage.saveUser.click();
        await expect(this.aclAdminPage.successUser.first()).toBeVisible();
    }

    async editUserVerify() {
        await expect(this.aclAdminPage.createUser).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.saveUser.click();
        await expect(this.aclAdminPage.successUserUpdate.first()).toBeVisible();
    }

    async deleteUserVerify() {
        await expect(this.aclAdminPage.createUser).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.nth(2).click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successUserDelete.first()).toBeVisible();
    }

    async roleCreateVerify() {
        await this.aclAdminPage.createRole.click();
        await this.aclAdminPage.name.fill(this.roleName);
        await this.aclAdminPage.selectRoleType.selectOption("all");
        await this.aclAdminPage.roleDescription.fill("test description");
        await this.aclAdminPage.saveRole.click();
        await expect(this.aclAdminPage.successRole.first()).toBeVisible();
    }

    async roleEditVerify() {
        await expect(this.aclAdminPage.createRole).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.saveRole.click();
        await expect(this.aclAdminPage.successUpdateRole.first()).toBeVisible();
    }

    async roleDeleteVerify() {
        await expect(this.aclAdminPage.createRole).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.nth(2).click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successDeleteRole.first()).toBeVisible();
    }

    async themeCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.name.fill(generateName());
        await this.aclAdminPage.sortOrder.fill("1");
        await this.aclAdminPage.selectTypeAttribute.selectOption(
            "product_carousel",
        );
        await this.aclAdminPage.selectChannel.selectOption("1");
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(this.aclAdminPage.athorization.first()).toBeVisible();
    }

    async themeEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.iconEdit.nth(3).click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.click();
        await expect(this.aclAdminPage.successEditTheme.first()).toBeVisible();
    }

    async themeDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.nth(3)).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successDeleteTheme.first()).toBeVisible();
    }

    async taxrateCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.identifier.fill("test-tax-rate");
        await this.aclAdminPage.selectCountry.selectOption("IN");
        await this.aclAdminPage.taxRate.fill("10");
        await this.aclAdminPage.createBtn.first().click();
        await expect(this.aclAdminPage.successCreateTaxRate.first()).toBeVisible();
    }

    async taxrateEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.first().click();
        await expect(this.aclAdminPage.successUpdateTaxRate.first()).toBeVisible();
    }

    async taxrateDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.successDeleteTaxRate.first()).toBeVisible();
    }

    async taxcategoryCreateVerify() {
        await this.aclAdminPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.fillCode.fill("test-tax-category");
        await this.aclAdminPage.name.fill("Test Tax Category");
        await this.aclAdminPage.description.fill("This is a test tax category");
        await this.aclAdminPage.selectTaxRate.selectOption({
            label: "test-tax-rate",
        });
        await this.aclAdminPage.createBtn.nth(1).click();
        await expect(
            this.aclAdminPage.successCreateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryEditVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.createBtn.first().click();
        await expect(
            this.aclAdminPage.successUpdateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryDeleteVerify() {
        await expect(this.aclAdminPage.createBtn).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.successDeleteTaxCategory.first(),
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
        await this.aclShopPage.searchInput.fill("simple");
        await this.aclShopPage.searchInput.press("Enter");
        await this.aclShopPage.addToCartButton.first().click();
        await expect(this.aclShopPage.addCartSuccess.first()).toBeVisible();
        await this.aclShopPage.shoppingCartIcon.click();
        await this.aclShopPage.continueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.aclShopPage.clickProcessButton.click();
        await this.aclShopPage.chooseShippingMethod.click();
        await this.aclShopPage.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.aclShopPage.clickPlaceOrderButton.click();
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
        await this.aclAdminPage.rmaSelection.selectOption("1");

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
        await this.aclAdminPage.createBtn.click();
        await this.aclAdminPage.iconEdit.first().click();
        await this.aclAdminPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.resolution.selectOption("cancel_items");
        await this.aclAdminPage.resolution.selectOption("cancel_items");
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.reason.selectOption("1");
        await this.aclAdminPage.rmaQTY.fill("1");
        await this.aclAdminPage.info.fill("Changed My Mind.");
        await this.aclAdminPage.createBtn.first().click();
        await expect(this.aclAdminPage.successAdminRMA).toBeVisible();
    }

    async rmaReasonCreateVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await this.aclAdminPage.createRMAReason.click();
        await this.aclAdminPage.reasonTitle.fill("Broken Product");
        await this.aclAdminPage.reasonStatus.check();
        await this.aclAdminPage.position.fill("1");
        await this.aclAdminPage.reasonType.selectOption("return");
        await this.aclAdminPage.saveReason.click();
        await expect(this.aclAdminPage.saveReasonSuccess).toBeVisible();
    }

    async rmaReasonEditVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await expect(this.aclAdminPage.createRMAReason).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.aclAdminPage.position.fill("5");
        await this.aclAdminPage.saveReason.click();
        await expect(
            this.aclAdminPage.saveReasonUpdateSuccess.first(),
        ).toBeVisible();
    }

    async rmaReasonDeleteVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await expect(this.aclAdminPage.createRMAReason).not.toBeVisible();
        await expect(this.aclAdminPage.editIcon.first()).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(
            this.aclAdminPage.saveReasonDeleteSuccess.first(),
        ).toBeVisible();
    }

    async rmaRulesCreateVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await this.aclAdminPage.rmaRulesCreate.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.ruleTitle.fill("Test Rule1");
        await this.aclAdminPage.reasonStatus.check();
        await this.aclAdminPage.ruleDescription.fill("Test Rule Description");
        await this.aclAdminPage.returnPeriod.fill("15");
        await this.aclAdminPage.saveRule.click();
        await expect(this.aclAdminPage.ruleSuccessMSG).toBeVisible();
    }

    async rmaRulesEditVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await expect(this.aclAdminPage.rmaRulesCreate).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.ruleTitle.fill("Test Rule1");
        await this.aclAdminPage.reasonStatus.check();
        await this.aclAdminPage.ruleDescription.fill("Test Rule Description");
        await this.aclAdminPage.returnPeriod.fill("15");
        await this.aclAdminPage.saveRule.click();
        await expect(this.aclAdminPage.ruleSuccessUpdatedMSG).toBeVisible();
    }

    async rmaRulesDeleteVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await expect(this.aclAdminPage.rmaRulesCreate).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit).not.toBeVisible();
        await this.aclAdminPage.deleteIcon.first().click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.ruleDeleteSuccessMSG).toBeVisible();
    }

    async rmaStatusCreateVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await this.aclAdminPage.createRMAStatus.click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.rmaStatusTitle.fill("RMA Status");
        await this.aclAdminPage.reasonStatus.click();
        await this.aclAdminPage.saveStatus.click();
        await expect(this.aclAdminPage.statusSuccess).toBeVisible();
    }

    async rmaStatusEditVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await expect(this.aclAdminPage.createRMAStatus).not.toBeVisible();
        await this.aclAdminPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.aclAdminPage.rmaStatusTitle.fill("RMA Status edited");
        await this.aclAdminPage.saveStatus.click();
        await expect(this.aclAdminPage.statusUpdateSuccess).toBeVisible();
    }

    async rmaStatusDeleteVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await expect(this.aclAdminPage.createRMAStatus).not.toBeVisible();
        await expect(this.aclAdminPage.iconEdit.first()).not.toBeVisible();
        await expect(this.aclAdminPage.deleteIcon.first()).not.toBeVisible();
        await this.aclAdminPage.selectRowBtn.first().click();
        await this.aclAdminPage.selectAction.click();
        await this.aclAdminPage.deleteBtn.click();
        await this.aclAdminPage.agreeBtn.click();
        await expect(this.aclAdminPage.statusDeleteSuccess).toBeVisible();
    }
}
