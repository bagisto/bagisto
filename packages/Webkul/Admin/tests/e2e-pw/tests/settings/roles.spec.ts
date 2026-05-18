import { test, expect } from "../../setup";
import { RolesPage } from "../../pages/admin/settings/RolesPage";

test.describe("role management", () => {
    test("create role", async ({ adminPage }) => {
        const rolesPage = new RolesPage(adminPage);
        await rolesPage.createRole();
    });

    test("edit role", async ({ adminPage }) => {
        const rolesPage = new RolesPage(adminPage);
        await rolesPage.editFirstRole();
    });

    test("delete role", async ({ adminPage }) => {
        const rolesPage = new RolesPage(adminPage);
        await rolesPage.deleteFirstRole();
    });
});
