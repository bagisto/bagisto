import { test } from "../../../setup";
import {
    GeneralConfigurationPage,
    type GeneralSettings,
} from "../../../pages/admin/configuration/general/GeneralConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("general configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: GeneralConfigurationPage;
    let original: GeneralSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new GeneralConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the weight unit after reload", async () => {
        const changed = { weightUnit: other(original.weightUnit, "kgs", "lbs") };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should show storefront breadcrumbs only while they are enabled", async () => {
        await configPage.applySettings({ breadcrumbs: true });

        await configPage.expectSettings({ breadcrumbs: true });
        await configPage.expectBreadcrumbsOnStorefront(true);

        await configPage.applySettings({ breadcrumbs: false });

        await configPage.expectSettings({ breadcrumbs: false });
        await configPage.expectBreadcrumbsOnStorefront(false);
    });
});
