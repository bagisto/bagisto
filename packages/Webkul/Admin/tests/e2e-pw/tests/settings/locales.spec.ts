import { test, expect } from "../../setup";
import { LocalesPage } from "../../pages/admin/settings/LocalesPage";

test.describe("locale management", () => {
    test("create locale", async ({ adminPage }) => {
        const localesPage = new LocalesPage(adminPage);
        await localesPage.createLocale();
    });

    test("edit locale", async ({ adminPage }) => {
        const localesPage = new LocalesPage(adminPage);
        await localesPage.editFirstLocale();
    });

    test("delete locale", async ({ adminPage }) => {
        const localesPage = new LocalesPage(adminPage);
        await localesPage.deleteFirstLocale();
    });
});
