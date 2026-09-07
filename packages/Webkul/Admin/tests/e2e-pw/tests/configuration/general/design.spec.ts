import { test } from "../../../setup";
import { CategoryPage, type CategoryData } from "../../../pages/admin/catalog/categories/CategoryPage";
import {
    DesignConfigurationPage,
    type DesignSettings,
} from "../../../pages/admin/configuration/general/DesignConfigurationPage";
import { StorefrontMenuPage } from "../../../pages/shop/StorefrontMenuPage";
import { generateName, generateSlug, getImageFile, uniqueStamp } from "../../../utils/faker";

function buildCategory(): CategoryData {
    return {
        name: `${generateName()} ${uniqueStamp()}`,
        slug: generateSlug(),
    };
}

test.describe("design configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let designConfig: DesignConfigurationPage;
    let original: DesignSettings;

    test.beforeEach(async ({ adminPage }) => {
        designConfig = new DesignConfigurationPage(adminPage);
        original = await designConfig.readSettings();
    });

    test.afterEach(async () => {
        await designConfig.applySettings(original);
    });

    for (const field of ["logo_image", "favicon"] as const) {
        test(`should store an uploaded ${field.replace("_", " ")} and remove it again`, async () => {
            test.skip(
                await designConfig.hasMedia(field),
                `A ${field} is already configured and would be lost by this test`,
            );

            await designConfig.uploadMedia(field, getImageFile());

            await designConfig.expectMediaStored(field);

            await designConfig.deleteMedia(field);

            await designConfig.expectMediaAbsent(field);
        });
    }

    test.describe("category menu view", () => {
        let categoryPage: CategoryPage;
        let category: CategoryData;

        test.beforeEach(async ({ adminPage }) => {
            categoryPage = new CategoryPage(adminPage);
            category = buildCategory();

            await categoryPage.createCategory(category);
        });

        test.afterEach(async () => {
            await categoryPage.deleteCategoriesIfPresent([category.name]);
        });

        test("should show categories in a sidebar drawer when the sidebar view is saved", async ({
            adminPage,
        }) => {
            await designConfig.previewCategoryView("sidebar");
            await designConfig.applySettings({ categoryView: "sidebar" });

            await designConfig.expectSettings({ categoryView: "sidebar" });
            await new StorefrontMenuPage(adminPage).expectSidebarMenuLists(category.name);
        });

        test("should show categories in the header when the default view is saved", async ({
            adminPage,
        }) => {
            await designConfig.previewCategoryView("default");
            await designConfig.applySettings({ categoryView: "default" });

            await designConfig.expectSettings({ categoryView: "default" });
            await new StorefrontMenuPage(adminPage).expectDefaultMenuLists(category.name);
        });
    });
});
