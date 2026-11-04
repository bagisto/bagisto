import { notAnImage, validImage, validVideo } from "@shared/media-files";
import { test } from "../../setup";
import { SectionsPage } from "../../pages/admin/appearance/SectionsPage";
import { generateName, uniqueStamp } from "../../utils/faker";

const UPLOAD_REFUSED =
    "The file field must be a file of type: bmp, jpeg, jpg, png, webp, mp4, webm, ogg.";

function sectionName(): string {
    return `${generateName()} ${uniqueStamp()}`;
}

test.describe("section management", () => {
    let sectionsPage: SectionsPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        sectionsPage = new SectionsPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await sectionsPage.deleteSectionsIfPresent(created);
    });

    const sectionTypes = [
        { type: "Product Carousel", fields: ["Title", "Filters"] },
        { type: "Category Carousel", fields: ["Filters"] },
        { type: "Static Content", fields: ["HTML", "CSS"] },
        { type: "Image Carousel", fields: ["Slider"] },
    ];

    for (const { type, fields } of sectionTypes) {
        test(`should create a ${type.toLowerCase()} section with its editor fields and publish it`, async () => {
            const name = sectionName();
            created.push(name);

            await sectionsPage.createSection(type, name);

            await sectionsPage.expectSectionListed(name, type);
            await sectionsPage.expectEditorFields(fields);
            await sectionsPage.expectUnpublishedChanges(name);

            await sectionsPage.closeSection();
            await sectionsPage.publishAll();

            await sectionsPage.expectPublished(name);
            await sectionsPage.open();
            await sectionsPage.expectSectionListed(name, type);
            await sectionsPage.expectPublished(name);
        });
    }

    for (const type of ["Footer Links", "Services Content"]) {
        test(`should not offer a second ${type.toLowerCase()} section`, async () => {
            await sectionsPage.expectTypeNotOffered(type);
        });
    }

    test("should hold typed content as a draft until it is published", async () => {
        const name = sectionName();
        const title = `Title ${uniqueStamp()}`;
        created.push(name);

        await sectionsPage.createSection("Product Carousel", name);
        await sectionsPage.fillField("Title", title);

        await sectionsPage.expectUnpublishedChanges(name);

        await sectionsPage.closeSection();
        await sectionsPage.publishAll();

        await sectionsPage.expectPublished(name);

        await sectionsPage.openSection(name);

        await sectionsPage.expectFieldValue("Title", title);
    });

    test("should stage a reorder until it is published", async () => {
        const first = sectionName();
        const second = sectionName();
        created.push(first, second);

        await sectionsPage.createSection("Static Content", first);
        await sectionsPage.closeSection();
        await sectionsPage.createSection("Static Content", second);
        await sectionsPage.closeSection();
        await sectionsPage.publishAll();
        await sectionsPage.expectOrderedBefore(first, second);

        await sectionsPage.dragSectionOnto(second, first);

        await sectionsPage.expectOrderedBefore(second, first);
        await sectionsPage.expectUnpublishedChanges(first);
        await sectionsPage.expectUnpublishedChanges(second);

        await sectionsPage.publishAll();
        await sectionsPage.open();

        await sectionsPage.expectOrderedBefore(second, first);
        await sectionsPage.expectPublished(first);
        await sectionsPage.expectPublished(second);
    });

    test("should stage a status change until it is published", async () => {
        const name = sectionName();
        created.push(name);

        await sectionsPage.createSection("Static Content", name);
        await sectionsPage.closeSection();
        await sectionsPage.publishAll();
        await sectionsPage.expectPublished(name);
        await sectionsPage.expectSwitchedOn(name);

        await sectionsPage.toggleStatus(name);

        await sectionsPage.expectUnpublishedChanges(name);
        await sectionsPage.expectSwitchedOff(name);

        await sectionsPage.publishAll();
        await sectionsPage.open();

        await sectionsPage.expectPublished(name);
        await sectionsPage.expectSwitchedOff(name);
    });

    test("should put a staged status back on discard", async () => {
        const name = sectionName();
        created.push(name);

        await sectionsPage.createSection("Static Content", name);
        await sectionsPage.closeSection();
        await sectionsPage.publishAll();
        await sectionsPage.expectSwitchedOn(name);

        await sectionsPage.toggleStatus(name);

        await sectionsPage.expectUnpublishedChanges(name);
        await sectionsPage.expectSwitchedOff(name);

        await sectionsPage.discardAll();

        await sectionsPage.expectPublished(name);
        await sectionsPage.expectSwitchedOn(name);

        await sectionsPage.open();

        await sectionsPage.expectSwitchedOn(name);
    });

    test("should delete a section and keep the others", async () => {
        const name = sectionName();
        const untouched = sectionName();
        created.push(name, untouched);

        await sectionsPage.createSection("Static Content", name);
        await sectionsPage.closeSection();
        await sectionsPage.createSection("Static Content", untouched);
        await sectionsPage.closeSection();
        await sectionsPage.deleteSection(name);

        await sectionsPage.open();
        await sectionsPage.expectSectionListed(untouched, "Static Content");
    });

    test("should keep an image uploaded to a slide after the section is reopened", async () => {
        const name = sectionName();
        created.push(name);

        await sectionsPage.createSection("Image Carousel", name);
        await sectionsPage.addSlide();
        await sectionsPage.uploadSlideImage(validImage());
        await sectionsPage.closeSection();

        await sectionsPage.openSection(name);

        await sectionsPage.expectSlideImageShown();
    });

    test("should say why a slide image was refused and keep none", async () => {
        const name = sectionName();
        created.push(name);

        await sectionsPage.createSection("Image Carousel", name);
        await sectionsPage.addSlide();
        await sectionsPage.attemptSlideImage(notAnImage());

        await sectionsPage.expectUploadRefused(UPLOAD_REFUSED);
        await sectionsPage.expectNoSlideImage();
    });

    for (const { kind, file, markup } of [
        { kind: "image", file: validImage, markup: "<img src=" },
        { kind: "video", file: validVideo, markup: "<video src=" },
    ]) {
        test(`should write an uploaded ${kind} into the html of a static content section`, async () => {
            const name = sectionName();
            created.push(name);

            await sectionsPage.createSection("Static Content", name);
            await sectionsPage.insertHtmlMedia(file());
            await sectionsPage.closeSection();

            await sectionsPage.openSection(name);

            await sectionsPage.expectHtmlContains(markup);
        });
    }

    test("should say why a file for the html was refused and leave the html alone", async () => {
        const name = sectionName();
        created.push(name);

        await sectionsPage.createSection("Static Content", name);
        await sectionsPage.attemptHtmlMedia(notAnImage());

        await sectionsPage.expectUploadRefused(UPLOAD_REFUSED);
        await sectionsPage.expectHtmlEmpty();
    });
});
