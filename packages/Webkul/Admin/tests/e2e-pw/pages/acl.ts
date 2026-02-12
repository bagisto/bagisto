import { Page, expect } from "@playwright/test";
import { WebLocators } from "../locators/locator";

import {
    generateFirstName,
    generateLastName,
    generateEmail,
    randomElement,
    generateName,
    generateSlug,
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
        await this.locators.iconEdit.first().click();
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
        await this.locators.selectRoleType.selectOption(permissionType);
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
        await this.locators.profile.first().click();
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

    async orderCreateVerify() {
        await expect(this.locators.createBtn).toBeVisible();
        await this.locators.createBtn.click();
        await expect(this.locators.saveBtn).toBeVisible();
    }

    async productEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.editBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.successMSG.first()).toBeVisible();
    }

    async productCopyVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.viewBtn.nth(1)).not.toBeVisible();
        await this.locators.copyBtn.nth(1).click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.copySuccess.first()).toBeVisible();
    }

    async productDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.viewBtn.nth(1)).not.toBeVisible();
        await this.locators.selectRowBtn.nth(2).click();
        await this.locators.selectAction.click();
        await this.locators.selectDelete.click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.productDeleteSuccess.first()).toBeVisible();
    }

    async categoryEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.saveCategoryBtn.click();
        await expect(this.locators.categorySuccess.first()).toBeVisible();
    }

    async categoryDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.selectRowBtn.nth(1).click();
        await this.locators.selectAction.click();
        await this.locators.deleteBtn.click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.categoryDeleteSuccess.first()).toBeVisible();
    }

    async attributeCreateVerify() {
        await expect(this.locators.createBtn).toBeVisible();
        await expect(this.locators.editBtn.first()).not.toBeVisible();
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.fillname.fill("Test Attribute");
        await this.locators.fillCode.fill("testattribute");
        await this.locators.selectTypeAttribute.selectOption("text");
        await this.locators.saveAttributeBtn.click();
        await expect(this.locators.attributeSuccess.first()).toBeVisible();
    }

    async attributeEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.saveAttributeBtn.click();
        await expect(
            this.locators.attributeUpdateSuccess.first(),
        ).toBeVisible();
    }

    async attributeDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(
            this.locators.attributeDeleteSuccess.first(),
        ).toBeVisible();
    }

    async familyCreateVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.locators.editBtn.first()).not.toBeVisible();
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.familyName.fill("Test Family");
        await this.locators.fillCode.fill("family");
        await this.locators.createBtn.click();
        await expect(this.locators.familySuccess.first()).toBeVisible();
    }

    async familyEditVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.familyUpdateSuccess.first()).toBeVisible();
    }

    async familyDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.editBtn.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.familyDeleteSuccess.first()).toBeVisible();
    }

    async customerCreateVerify() {
        await this.page.goto("admin/customers");
        await this.locators.createBtn.click();
        await this.locators.customerfirstname.fill(generateFirstName());
        await this.locators.customerlastname.fill(generateLastName());
        await this.locators.customeremail.fill(generateEmail());
        await this.locators.customergender.selectOption(
            randomElement(["Male", "Female", "Other"]),
        );
        await this.locators.customerNumber.fill("1234567890");
        await this.locators.customerNumber.press("Enter");
        await expect(
            this.locators.customercreatedsuccess.first(),
        ).toBeVisible();
    }

    async customerEditVerify() {
        await this.page.goto("admin/customers");
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.viewIcon.first()).toBeVisible();
    }

    async customerDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.selectRowBtn.nth(1).click();
        await this.locators.selectAction.click();
        await this.locators.deleteBtn.click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.customerDeleteSuccess.first()).toBeVisible();
    }

    async groupCreateVerify() {
        await this.locators.createBtn.click();
        await this.locators.name.fill(generateName());
        await this.locators.fillCode.fill("code");
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.successGroupMSG.first()).toBeVisible();
    }

    async groupEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.successUpdateMSG.first()).toBeVisible();
    }

    async groupDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successGroupDeleteMSG.first()).toBeVisible();
    }

    async cmsCreateVerify() {
        await this.locators.pagetitle.fill(generateName());
        await this.locators.urlKey.fill(generateSlug());
        await this.locators.metaTitle.fill(generateName());
        await this.locators.metaKeywords.fill("keywords");
        await this.locators.metaDescription.fill(generateDescription());
        await this.locators.statusBTN.first().click();
        await this.locators.createBtn.click();
        await expect(this.locators.successPageCreate).toBeVisible();
    }

    async cmsEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.successPageUpdate).toBeVisible();
    }

    async cmsDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successPageDelete).toBeVisible();
    }

    async cartRuleCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.ruleName.fill(generateName());
        await this.locators.promotionRuleDescription.fill(generateDescription());
        await this.locators.addConditionBtn.click();
        await this.locators.selectCondition.selectOption("product|name");
        await this.locators.conditionName.fill(generateName());
        await this.locators.discountAmmount.fill("10");
        await this.locators.sortOrder.fill("1");
        await this.locators.channelSelect.first().click();
        await expect(this.locators.channelSelect.first()).toBeChecked();
        await this.locators.customerGroupSelect.first().click();
        await expect(this.locators.customerGroupSelect.first()).toBeChecked();
        await this.locators.statusToggle.click();
        await expect(this.locators.toggleInput).toBeChecked();
        await this.locators.createBtn.click();
        await expect(this.locators.cartRuleSuccess.first()).toBeVisible();
    }

    async cartRuleCopyVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.copyBtn.first().click();
        // await expect(this.locators.cartRuleCopySuccess.first()).toBeVisible();
        await expect(this.locators.saveCartRuleBTN).toBeVisible();
    }

    async cartRuleEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.copyBtn.first()).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.cartRuleEditSuccess.first()).toBeVisible();
    }

    async cartRuleDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.copyBtn.first()).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.cartRuleDeleteSuccess.first()).toBeVisible();
    }

    async catalogRuleCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.ruleName.fill(generateName());
        await this.locators.promotionRuleDescription.fill(generateDescription());
        await this.locators.addConditionBtn.click();
        await this.locators.selectCondition.selectOption("product|name");
        await this.locators.conditionName.fill(generateName());
        await this.locators.discountAmmount.fill("10");
        await this.locators.sortOrder.fill("1");
        await this.locators.channelSelect.first().click();
        await expect(this.locators.channelSelect.first()).toBeChecked();
        await this.locators.customerGroupSelect.first().click();
        await expect(this.locators.customerGroupSelect.first()).toBeChecked();
        await this.locators.statusToggle.click();
        await expect(this.locators.toggleInput).toBeChecked();
        await this.locators.createBtn.click();
        await expect(
            this.locators.catalogRuleCreateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(
            this.locators.catalogRuleUpdateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(
            this.locators.catalogRuleDeleteSuccess.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateCreateVerify() {
        await this.locators.createBtn.click();
        const description = generateDescription();
        await this.locators.name.fill("template");
        await fillInTinymce(this.page, "#content_ifr", description);
        await this.locators.emailStatusSeletct.selectOption("active");
        await this.locators.createBtn.click();
        await expect(this.locators.emailSuccessMSG.first()).toBeVisible();
    }

    async communicationEmailTemplateEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.emailUpdateSuccessMSG.first()).toBeVisible();
    }

    async communicationEmailTemplateDeleteVerify() {
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.emailDeleteSuccessMSG.first()).toBeVisible();
    }

    async eventCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.hover('input[name="name"]');
        const inputs = await this.page.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible',
        );

        for (let input of inputs) {
            await input.fill(generateName());
        }
        await this.locators.date.fill(generateRandomDate());
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.eventCreateSuccess.first()).toBeVisible();
    }

    async eventEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.eventUpdateSuccess.first()).toBeVisible();
    }

    async eventDeleteVerify() {
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await expect(this.locators.createBtn.nth(1)).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.eventDeleteSuccess.first()).toBeVisible();
    }

    async campaignCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.name.fill(generateName());
        await this.locators.subject.fill(generateName());
        await this.locators.event.selectOption({ label: "Birthday" });
        await this.locators.emailTemplate.selectOption({label: "template"});
        await this.locators.selectChannel.selectOption("1");
        await this.locators.customerGroup.selectOption("1");
        await this.locators.campaignStatus.click();
        await this.locators.createBtn.click();
        await expect(this.locators.campaignCreateSuccess.first()).toBeVisible();
    }

    async campaignEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.campaignUpdateSuccess.first()).toBeVisible();
    }

    async campaignDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.campaignDeleteSuccess.first()).toBeVisible();
    }

    async urlRewriteCreateVerify() {
        const seo = {
            url: generateHostname(),
            product: "product",
        };
        await this.locators.createBtn.click();
        await this.locators.entityType.selectOption(seo.product);
        await this.locators.requestPath.fill(seo.url);
        await this.locators.targetPath.fill(seo.url);
        await this.locators.redirectPath.selectOption("301");
        await this.locators.locale.selectOption("en");
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.saveRedirectSuccess).toBeVisible();
    }

    async urlRewriteEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.saveRedirectUpdatedSuccess).toBeVisible();
    }

    async urlRewriteDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.deleteRedirectSuccess).toBeVisible();
    }

    async searchTermsCreateVerify() {
        await expect(this.locators.deleteIcon.first()).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.createBtn.click();
        await this.locators.searchQuery.fill(generateName());
        await this.locators.redirectURL.fill("https://example.com");
        await this.locators.selectChannel.selectOption("1");
        await this.locators.locale.selectOption("en");
        await this.locators.createBtn.nth(1).click();
        await expect(
            this.locators.searchTermCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(
            this.locators.searchTermUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(
            this.locators.searchTermDeleteSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsCreateVerify() {
        await this.locators.createBtn.click();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.name.fill(generateName());
        await this.locators.terms.fill("test, synonym");
        await this.locators.createBtn.nth(1).click();
        await expect(
            this.locators.searchSynonymCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(
            this.locators.searchSynonymUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(
            this.locators.searchSynonymDeleteSuccess.first(),
        ).toBeVisible();
    }

    async sitemapCreateVerify() {
        await this.locators.createBtn.click();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await expect(this.locators.deleteIcon.first()).not.toBeVisible();
        await this.locators.fileName.fill("sitemap.xml");
        await this.locators.path.fill("/sitemapxml/test/example/");
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.sitemapCreateSuccess.first()).toBeVisible();
    }

    async sitemapEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.deleteIcon.first()).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.sitemapUpdateSuccess.first()).toBeVisible();
    }

    async sitemapDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.sitemapDeleteSuccess.first()).toBeVisible();
    }

    async localeCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.fillCode.fill("test");
        await this.locators.name.fill("TestLocale");
        await this.locators.direction.selectOption("ltr");
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.successLocaleCreate.first()).toBeVisible();
    }

    async localeEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.successLocaleUpdate.first()).toBeVisible();
    }

    async localeDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successLocaleDelete.first()).toBeVisible();
    }

    async currencyCreateVerify() {
        await this.locators.createBtn.click();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.fillCode.fill("TST");
        await this.locators.name.fill("Test Currency");
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.successCurrencyCreate.first()).toBeVisible();
    }

    async currencyEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.successCurrencyUpdate.first()).toBeVisible();
    }

    async currencyDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successCurrencyDelete.first()).toBeVisible();
    }

    async exchangeRateCreateVerify() {
        await this.page.goto("admin/settings/currencies");
        await this.currencyCreateVerify();
        await this.page.goto("admin/settings/channels");
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.getByText("Test Currency").first().click();
        await this.locators.createBtn.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.goto("admin/settings/exchange-rates");
        await this.locators.createBtn.nth(1).click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.targetCurrency.selectOption({
            label: "Test Currency",
        });
        await this.locators.rate.fill("100");
        await this.locators.createBtn.nth(2).click();
        await expect(
            this.locators.successExchangeRateCreate.first(),
        ).toBeVisible();
    }

    async exchangeRateEditVerify() {
        await expect(this.locators.createBtn.nth(1)).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.nth(1).click();
        await expect(
            this.locators.successExchangeRateUpdate.first(),
        ).toBeVisible();
    }

    async exchangeRateDeleteVerify() {
        await expect(this.locators.createBtn.nth(1)).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(
            this.locators.successExchangeRateDelete.first(),
        ).toBeVisible();
    }

    async inventorySourceCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.fillCode.fill("testsource");
        await this.locators.name.fill(generateName());
        await this.locators.description.fill(generateDescription());
        await this.locators.contactName.fill(generateName());
        await this.locators.enterEmail.fill(generateEmail());
        await this.locators.contactNumber.fill(generatePhoneNumber());
        await this.locators.fax.fill(generatePhoneNumber());
        await this.locators.country.selectOption("IN");
        await this.locators.state.selectOption("DL");
        await this.locators.city.fill("New Delhi");
        await this.locators.street.fill("Some street address");
        await this.locators.postcode.fill("110001");
        await this.locators.statusToggle.click();
        await this.locators.createBtn.click();
        await expect(
            this.locators.successInventorySourceCreate.first(),
        ).toBeVisible();
    }

    async inventorySourceEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(
            this.locators.successInventorySourceUpdate.first(),
        ).toBeVisible();
    }

    async inventorySourceDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(
            this.locators.successInventorySourceDelete.first(),
        ).toBeVisible();
    }

    async channelCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.name.fill(generateName());
        const code = generateSlug("_");
        const name = generateName();
        const description = generateDescription();
        await this.locators.fillCode.fill(code);
        await this.locators.promotionRuleDescription.fill(description);
        await this.locators.inventoryToggle.first().click();
        await this.locators.categoryID.selectOption("1");
        await this.locators.hostname.fill(generateHostname());
        await this.locators.selectLocale.first().click();
        await this.locators.selectCurrency.first().click();
        await this.locators.baseCurrencyID.selectOption("1");
        await this.locators.defaultLocaleID.selectOption("1");
        await this.locators.metaTitleChannel.fill(name);
        await this.locators.seoKeywords.fill("keywords");
        await this.locators.metaDescription.fill(description);
        await this.locators.createBtn.click();
        await expect(this.locators.channleCreateSuccess.first()).toBeVisible();
    }

    async channelEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.channlUpdateSuccess.first()).toBeVisible();
    }

    async channelDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.channelDeleteSuccess.first()).toBeVisible();
    }

    async createUserVerify() {
        await this.locators.createUser.click();
        await this.locators.name.fill(this.userName);
        await this.locators.selectRole.selectOption({ label: this.roleName });
        await this.locators.userEmail.fill(generateEmail());
        await this.locators.userPassword.fill("user123");
        await this.locators.confirmPassword.fill("user123");
        await this.locators.statusToggle.click();
        const toggleInput = await this.locators.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.locators.saveUser.click();
        await expect(this.locators.successUser.first()).toBeVisible();
    }

    async editUserVerify() {
        await expect(this.locators.createUser).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.saveUser.click();
        await expect(this.locators.successUserUpdate.first()).toBeVisible();
    }

    async deleteUserVerify() {
        await expect(this.locators.createUser).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.nth(2).click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successUserDelete.first()).toBeVisible();
    }

    async roleCreateVerify() {
        await this.locators.createRole.click();
        await this.locators.name.fill(this.roleName);
        await this.locators.selectRoleType.selectOption("all");
        await this.locators.roleDescription.fill("test description");
        await this.locators.saveRole.click();
        await expect(this.locators.successRole.first()).toBeVisible();
    }

    async roleEditVerify() {
        await expect(this.locators.createRole).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.saveRole.click();
        await expect(this.locators.successUpdateRole.first()).toBeVisible();
    }

    async roleDeleteVerify() {
        await expect(this.locators.createRole).not.toBeVisible();
        await this.locators.deleteIcon.nth(2).click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successDeleteRole.first()).toBeVisible();
    }

    async themeCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.name.fill(generateName());
        await this.locators.sortOrder.fill("1");
        await this.locators.selectTypeAttribute.selectOption(
            "product_carousel",
        );
        await this.locators.selectChannel.selectOption("1");
        await this.locators.createBtn.nth(1).click();
        await expect(this.locators.athorization.first()).toBeVisible();
    }

    async themeEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.locators.iconEdit.nth(3).click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.click();
        await expect(this.locators.successEditTheme.first()).toBeVisible();
    }

    async themeDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.nth(3)).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successDeleteTheme.first()).toBeVisible();
    }

    async taxrateCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.identifier.fill("test-tax-rate");
        await this.locators.selectCountry.selectOption("IN");
        await this.locators.taxRate.fill("10");
        await this.locators.createBtn.first().click();
        await expect(this.locators.successCreateTaxRate.first()).toBeVisible();
    }

    async taxrateEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.first().click();
        await expect(this.locators.successUpdateTaxRate.first()).toBeVisible();
    }

    async taxrateDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(this.locators.successDeleteTaxRate.first()).toBeVisible();
    }

    async taxcategoryCreateVerify() {
        await this.locators.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.fillCode.fill("test-tax-category");
        await this.locators.name.fill("Test Tax Category");
        await this.locators.description.fill("This is a test tax category");
        await this.locators.selectTaxRate.selectOption({label:"test-tax-rate"});
        await this.locators.createBtn.nth(1).click();
        await expect(
            this.locators.successCreateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryEditVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await this.locators.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.createBtn.first().click();
        await expect(
            this.locators.successUpdateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryDeleteVerify() {
        await expect(this.locators.createBtn).not.toBeVisible();
        await expect(this.locators.iconEdit.first()).not.toBeVisible();
        await this.locators.deleteIcon.first().click();
        await this.locators.agreeBtn.click();
        await expect(
            this.locators.successDeleteTaxCategory.first(),
        ).toBeVisible();
    }
}
