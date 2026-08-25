import { test } from "../../setup";
import { ThemeGalleryPage } from "../../pages/admin/appearance/ThemeGalleryPage";

test.describe("theme gallery", () => {
    test("should group themes by whether they are installed", async ({
        adminPage,
    }) => {
        const themeGalleryPage = new ThemeGalleryPage(adminPage);

        await themeGalleryPage.expectThemesGroupedByInstallState();
    });
});
