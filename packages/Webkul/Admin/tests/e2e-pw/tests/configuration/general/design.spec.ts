import { test } from "../../../setup";
import { getImageFile } from "../../../utils/faker";
import { DesignConfigurationPage } from "../../../pages/admin/configuration/general/DesignConfigurationPage";

test.describe("design configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new DesignConfigurationPage(adminPage).open();
    });

    test("should update and delete the logo", async ({ adminPage }) => {
        const page = new DesignConfigurationPage(adminPage);
        await page.uploadLogo(getImageFile());
        await page.saveAndVerify();
        await page.deleteLogo();
        await page.saveAndVerify();
    });

    test("should update and delete favicon", async ({ adminPage }) => {
        const page = new DesignConfigurationPage(adminPage);
        await page.uploadFavicon(getImageFile());
        await page.saveAndVerify();
        await page.deleteFavicon();
        await page.saveAndVerify();
    });

    test("should set sidebar menu category view", async ({ adminPage }) => {
        const page = new DesignConfigurationPage(adminPage);

        await page.selectCategoryView("sidebar");
        await page.previewSidebarMenu();
        await page.saveAndVerify();

        await page.expectSidebarMenuOnStorefront();
    });

    test("should set default menu category view", async ({ adminPage }) => {
        const page = new DesignConfigurationPage(adminPage);

        await page.selectCategoryView("default");
        await page.previewDefaultMenu();
        await page.saveAndVerify();

        await page.expectDefaultMenuOnStorefront("Men", "Formal Wear");
    });
});
