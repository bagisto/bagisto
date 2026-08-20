import { test } from "../../setup";
import { SectionsPage } from "../../pages/admin/appearance/SectionsPage";

test.describe("section management", () => {
    test("should create a product carousel section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createAndPublishSection("Product Carousel");
    });

    test("should create a category carousel section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createAndPublishSection("Category Carousel");
    });

    test("should create a static content section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createAndPublishSection("Static Content");
    });

    test("should create a image carousel section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createAndPublishSection("Image Carousel");
    });

    test("should create a services content section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createAndPublishSection("Services Content");
    });

    test("should not offer a second footer links section", async ({
        adminPage,
    }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.expectFooterLinksNotOffered();
    });

    test("should delete a section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.deleteSection("Static Content");
    });
});
