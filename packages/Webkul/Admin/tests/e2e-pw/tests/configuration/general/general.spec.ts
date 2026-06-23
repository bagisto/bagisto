import { test } from "../../../setup";
import { GeneralConfigurationPage } from "../../../pages/admin/configuration/general/GeneralConfigurationPage";

test.describe("general configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new GeneralConfigurationPage(adminPage).open();
    });

    test("should update weight unit", async ({ adminPage }) => {
        const page = new GeneralConfigurationPage(adminPage);

        await page.updateWeightUnit("lbs");
        await page.saveAndVerify();
    });

    test("should update breadcrumbs status", async ({ adminPage }) => {
        const page = new GeneralConfigurationPage(adminPage);

        await page.toggleBreadcrumbs();
        await page.saveAndVerify();
    });
});
