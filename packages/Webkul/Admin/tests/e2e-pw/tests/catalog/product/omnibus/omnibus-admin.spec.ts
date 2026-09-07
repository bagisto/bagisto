import { test } from "../../../../setup";
import { OmnibusAdminPage } from "../../../../pages/admin/omnibus/OmnibusAdminPage";

test.describe("omnibus price disclosure", () => {
    let omnibusAdmin: OmnibusAdminPage;
    let original: boolean;

    test.beforeEach(async ({ adminPage }) => {
        omnibusAdmin = new OmnibusAdminPage(adminPage);
        original = await omnibusAdmin.readEnabled();
    });

    test.afterEach(async () => {
        await omnibusAdmin.setEnabled(original);
    });

    test("should persist the omnibus setting through enable and disable", async () => {
        await omnibusAdmin.setEnabled(!original);

        await omnibusAdmin.expectEnabled(!original);

        await omnibusAdmin.setEnabled(original);

        await omnibusAdmin.expectEnabled(original);
    });
});
