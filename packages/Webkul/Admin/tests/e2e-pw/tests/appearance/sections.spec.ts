import { test } from "../../setup";
import { SectionsPage } from "../../pages/admin/appearance/SectionsPage";

test.describe("section management", () => {
    test("should create a product carousel section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createSectionOfType("Product Carousel", [
            "Title",
            "Filters",
        ]);
    });

    test("should create a category carousel section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createSectionOfType("Category Carousel", [
            "Filters",
        ]);
    });

    test("should create a static content section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createSectionOfType("Static Content", [
            "HTML",
            "CSS",
        ]);
    });

    test("should create a image carousel section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createSectionOfType("Image Carousel", ["Slider"]);
    });

    test("should create a services content section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.createSectionOfType("Services Content", [
            "Services",
        ]);
    });

    test("should not offer a second footer links section", async ({
        adminPage,
    }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.expectFooterLinksNotOffered();
    });

    test("should hold typed content as a draft until published", async ({
        adminPage,
    }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.editContentAndPublish();
    });

    test("should stage a reorder until it is published", async ({
        adminPage,
    }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.reorderIsStagedUntilPublished();
    });

    test("should stage a status change until it is published", async ({
        adminPage,
    }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.statusChangeIsStaged();
    });

    test("should put a staged status back on discard", async ({
        adminPage,
    }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.discardRevertsStagedStatus();
    });

    test("should delete a section", async ({ adminPage }) => {
        const sectionsPage = new SectionsPage(adminPage);

        await sectionsPage.deleteSection("Static Content");
    });
});
