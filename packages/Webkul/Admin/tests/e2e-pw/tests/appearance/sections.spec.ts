import { test } from "../../setup";
import { SectionsPage } from "../../pages/admin/appearance/SectionsPage";
import { generateName, uniqueStamp } from "../../utils/faker";

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
        { type: "Services Content", fields: ["Services"] },
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

    test("should not offer a second footer links section", async () => {
        await sectionsPage.expectTypeNotOffered("Footer Links");
    });

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
});
