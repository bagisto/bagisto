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
