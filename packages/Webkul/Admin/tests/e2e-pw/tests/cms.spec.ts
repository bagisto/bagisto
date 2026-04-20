import { test, expect } from "../setup";
import type { Page } from "@playwright/test";
import {
    generateName,
    generateSlug,
    generateDescription,
} from "../utils/faker";
import { CMSCreatePage, type CMSCreateData } from "../pages/admin/cms/CMSCreatePage";
import { CMSEditPage, type CMSEditData } from "../pages/admin/cms/CMSEditPage";
import { CMSDeletePage } from "../pages/admin/cms/CMSDeletePage";

async function createPage(adminPage: Page): Promise<CMSCreateData> {
    const cmsCreatePage = new CMSCreatePage(adminPage);
    const cms: CMSCreateData = {
        name: generateName(),
        slug: generateSlug(),
        shortDescription: generateDescription(),
    };

    await cmsCreatePage.createPage(cms);
    return cms;
}

test.describe("cms management", () => {
    test("should create a page", async ({ adminPage }) => {
        await createPage(adminPage);
    });

    test("should edit a page", async ({ adminPage }) => {
        const cmsEditPage = new CMSEditPage(adminPage);
        const cms: CMSEditData = {
            name: generateName(),
            slug: generateSlug(),
            shortDescription: generateDescription(),
        };

        await cmsEditPage.editPage(cms);
    });

    test("should delete a page", async ({ adminPage }) => {
        /**
         * Creating a page to delete.
         */
        const cms = await createPage(adminPage);

        const cmsDeletePage = new CMSDeletePage(adminPage);
        await cmsDeletePage.deleteFirstPage();
        await expect(adminPage.getByText(cms.name)).not.toBeVisible();
    });

    test("should mass delete the selected pages", async ({ adminPage }) => {
        /**
         * Creating CMS pages for multiselect delete.
         */
        await createPage(adminPage);

        const cmsDeletePage = new CMSDeletePage(adminPage);
        await cmsDeletePage.deleteFirstPage();
        await expect(
            adminPage.getByText("CMS deleted successfully.")
        ).toBeVisible();
    });

    test("should mass delete all the rows of current pages", async ({
        adminPage,
    }) => {
        /**
         * Creating a page to delete.
         */
        const cms = await createPage(adminPage);

        const cmsDeletePage = new CMSDeletePage(adminPage);
        await cmsDeletePage.massDeletePages();
        await expect(adminPage.getByText(cms.name)).not.toBeVisible();
    });
});
