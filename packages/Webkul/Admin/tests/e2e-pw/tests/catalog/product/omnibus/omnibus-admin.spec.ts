import { test, expect } from "../../../../../setup";
import { OmnibusAdminPage as OmnibusAdmin } from "../../../../../pages/admin/omnibus/OmnibusAdminPage";

test.describe("omnibus price disclosure", () => {
    test("should enable omnibus price disclosure in admin configuration", async ({
        adminPage,
    }) => {
        const omnibusAdmin = new OmnibusAdmin(adminPage);

        await omnibusAdmin.enableOmnibus();
        await omnibusAdmin.saveConfig();

        await adminPage.reload();
        await adminPage.waitForLoadState("networkidle");

        expect(await omnibusAdmin.isOmnibusEnabled()).toBeTruthy();
    });

    test("should disable omnibus price disclosure in admin configuration", async ({
        adminPage,
    }) => {
        const omnibusAdmin = new OmnibusAdmin(adminPage);

        await omnibusAdmin.enableOmnibus();
        await omnibusAdmin.saveConfig();

        await omnibusAdmin.disableOmnibus();
        await omnibusAdmin.saveConfig();

        await adminPage.reload();
        await adminPage.waitForLoadState("networkidle");

        expect(await omnibusAdmin.isOmnibusEnabled()).toBeFalsy();
    });
});
