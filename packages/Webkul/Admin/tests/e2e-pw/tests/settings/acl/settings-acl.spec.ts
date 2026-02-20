import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";

test.describe("settings acl", () => {
    test("should create custom role with settings permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings"]);
    });

    test("should create custom role with settings (locale) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.themes",
            "settings.roles",
            "settings.users",
            "settings.channels",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.currencies",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->locales"]);
    });

    test("should create custom role with settings (locale-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.locales.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->locales"]);
        await aclManagement.localeCreateVerify();
    });

    test("should create custom role with settings (locale-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.locales.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->locales"]);
        await aclManagement.localeEditVerify();
    });

    test("should create custom role with settings (locale-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.locales.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->locales"]);
        await aclManagement.localeDeleteVerify();
    });

    test("should create custom role with settings (currencies) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.themes",
            "settings.roles",
            "settings.users",
            "settings.channels",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->currencies"]);
    });

    test("should create custom role with settings (currencies->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.currencies.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->currencies"]);
        await aclManagement.currencyCreateVerify();
    });

    test("should create custom role with settings (currencies->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.currencies.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->currencies"]);
        await aclManagement.currencyEditVerify();
    });

    test("should create custom role with settings (currencies->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.currencies.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->currencies"]);
        await aclManagement.currencyDeleteVerify();
    });

    test("should create custom role with settings (exchange_rates) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.themes",
            "settings.roles",
            "settings.users",
            "settings.channels",
            "settings.inventory_sources",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->exchange_rates"]);
    });

    test("should create custom role with settings (exchange_rates-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.exchange_rates.create",
            "settings.currencies.create",
            "settings.channels.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->exchange_rates"]);
        await aclManagement.exchangeRateCreateVerify();
    });

    test("should create custom role with settings (exchange_rates-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.exchange_rates.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->exchange_rates"]);
        await aclManagement.exchangeRateEditVerify();
    });

    test("should create custom role with settings (exchange_rates-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.exchange_rates.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->exchange_rates"]);
        await aclManagement.exchangeRateDeleteVerify();
    });

    test("should create custom role with settings (inventory_sources) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.themes",
            "settings.roles",
            "settings.users",
            "settings.channels",
            "settings.exchange_rates",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->inventory_sources"]);
    });

    test("should create custom role with settings (inventory_sources-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.inventory_sources.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->inventory_sources"]);
        await aclManagement.inventorySourceCreateVerify();
    });

    test("should create custom role with settings (inventory_sources-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.inventory_sources.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->inventory_sources"]);
        await aclManagement.inventorySourceEditVerify();
    });

    test("should create custom role with settings (inventory_sources-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.inventory_sources.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->inventory_sources"]);
        await aclManagement.inventorySourceDeleteVerify();
    });

    test("should create custom role with settings (channels) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.themes",
            "settings.roles",
            "settings.users",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->channels"]);
    });

    test("should create custom role with settings (channels-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.channels.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->channels"]);
        await aclManagement.channelCreateVerify();
    });

    test("should create custom role with settings (channels-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.channels.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->channels"]);
        await aclManagement.channelEditVerify();
    });

    test("should create custom role with settings (channels-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.channels.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->channels"]);
        await adminPage.goto("admin/settings/channels");
        await aclManagement.channelDeleteVerify();
    });

    test("should create custom role with settings (users) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.themes",
            "settings.roles",
            "settings.channels",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->users"]);
    });

    test("should create custom role with settings (users-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.users.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->users"]);
        await aclManagement.createUserVerify();
    });

    test("should create custom role with settings (users-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.users.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->users"]);
        await aclManagement.editUserVerify();
    });

    test("should create custom role with settings (users-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.users.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->users"]);
        await aclManagement.deleteUserVerify();
    });

    test("should create custom role with settings (roles) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.themes",
            "settings.users",
            "settings.channels",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->roles"]);
    });

    test("should create custom role with settings (roles-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.roles.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->roles"]);
        await aclManagement.roleCreateVerify();
    });

    test("should create custom role with settings (roles-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.roles.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->roles"]);
        await aclManagement.roleEditVerify();
    });

    test("should create custom role with settings (roles-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.roles.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->roles"]);
        await aclManagement.roleDeleteVerify();
    });

    test("should create custom role with settings (themes) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.taxes",
            "settings.roles",
            "settings.users",
            "settings.channels",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->themes"]);
    });

    test("should create custom role with settings (themes->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.themes.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->themes"]);
        await aclManagement.themeCreateVerify();
    });

    test("should create custom role with settings (themes->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.themes.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->themes"]);
        await aclManagement.themeEditVerify();
    });

    test("should create custom role with settings (themes->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings.themes.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->themes"]);
        await aclManagement.themeDeleteVerify();
    });

    test("should create custom role with settings (taxes) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.data_transfer",
            "settings.themes",
            "settings.roles",
            "settings.users",
            "settings.channels",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->taxes"]);
    });

    test("should create custom role with settings (taxe rate -> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.taxes.tax_rates.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->taxes_rates"]);
        await aclManagement.taxrateCreateVerify();
    });

    test("should create custom role with settings (taxe rate -> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.taxes.tax_rates.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->taxes_rates"]);
        await aclManagement.taxrateEditVerify();
    });

    test("should create custom role with settings (taxe category -> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.taxes.tax_categories.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->taxes_categories"]);
        await aclManagement.taxcategoryCreateVerify();
    });

    test("should create custom role with settings (taxe category -> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.taxes.tax_categories.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->taxes_categories"]);
        await aclManagement.taxcategoryEditVerify();
    });

    test("should create custom role with settings (taxe category -> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "settings.taxes.tax_categories.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->taxes_categories"]);
        await adminPage.goto("admin/settings/taxes/categories");
        await aclManagement.taxcategoryDeleteVerify();
    });

    test("should create custom role with settings (data_transfer) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["settings"]);
        await aclManagement.editRolePermission([
            "settings.taxes",
            "settings.themes",
            "settings.roles",
            "settings.users",
            "settings.channels",
            "settings.inventory_sources",
            "settings.exchange_rates",
            "settings.currencies",
            "settings.locales",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["settings->data_transfer"]);
    });
});
