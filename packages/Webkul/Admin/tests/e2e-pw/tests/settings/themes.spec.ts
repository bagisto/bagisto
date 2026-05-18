import { test, expect } from "../../setup";
import { ThemesPage } from "../../pages/admin/settings/ThemesPage";

test.describe("theme management", () => {
    test("should create a product carousel theme", async ({ adminPage }) => {
        const themesPage = new ThemesPage(adminPage);

        await themesPage.createProductCarouselTheme();
    });

    test("should create a category carousel theme", async ({ adminPage }) => {
        const themesPage = new ThemesPage(adminPage);

        await themesPage.createCategoryCarouselTheme();
    });

    test("should create a static content theme", async ({ adminPage }) => {
        const themesPage = new ThemesPage(adminPage);

        await themesPage.createStaticContentTheme();
    });

    test("should create a image carousel theme", async ({ adminPage }) => {
        const themesPage = new ThemesPage(adminPage);

        await themesPage.createImageCarouselTheme();
    });

    test("should create a footer link theme", async ({ adminPage }) => {
        const themesPage = new ThemesPage(adminPage);

        await themesPage.createFooterLinkTheme();
    });

    test("should create a services content theme", async ({ adminPage }) => {
        const themesPage = new ThemesPage(adminPage);

        await themesPage.createServicesContentTheme();
    });

    test("should delete a theme", async ({ adminPage }) => {
        const themesPage = new ThemesPage(adminPage);

        await themesPage.deleteFirstTheme();
    });
});
