import { test } from "../setup";
import { InstallerPage } from "../pages/InstallerPage";

test.describe("guided installation", () => {
    test("should install bagisto through the guided web installer in english @en", async ({
        page,
    }) => {
        const installer = new InstallerPage(page);

        await installer.install("en");

        await installer.expectLayoutDirection("ltr");
        await installer.expectAdminLoginOffered();
    });

    test("should install bagisto through the guided web installer in arabic @ar", async ({
        page,
    }) => {
        const installer = new InstallerPage(page);

        await installer.install("ar");

        await installer.expectLayoutDirection("rtl");
        await installer.expectAdminLoginOffered();
    });
});
