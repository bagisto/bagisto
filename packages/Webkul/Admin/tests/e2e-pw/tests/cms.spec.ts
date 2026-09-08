import { test } from "../setup";
import { CmsPage, type CmsPageData } from "../pages/admin/cms/CmsPage";
import { generateDescription, generateName, uniqueStamp } from "../utils/faker";

function buildPage(): CmsPageData {
    const title = `${generateName()} ${uniqueStamp()}`;

    return {
        title,
        urlKey: title.toLowerCase().replace(/[^a-z0-9]+/g, "-"),
        content: generateDescription(),
    };
}

test.describe("cms management", () => {
    let cmsPage: CmsPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        cmsPage = new CmsPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await cmsPage.deletePagesIfPresent(created);
    });

    test("should create a page and publish it on the storefront", async () => {
        const page = buildPage();
        created.push(page.title);

        await cmsPage.createPage(page);

        await cmsPage.expectPageListed(page.title, page.urlKey);
        await cmsPage.expectPublishedOnStorefront(page.urlKey, page.content);
    });

    test("should reject a page without its required fields", async () => {
        await cmsPage.submitEmptyCreateForm();

        await cmsPage.expectValidationError("The Title field is required");
        await cmsPage.expectValidationError("The URL Key field is required");
        await cmsPage.expectValidationError("The Content field is required");
        await cmsPage.expectValidationError("The Channels field is required");
        await cmsPage.expectStillOnCreateForm();
    });

    test("should reject a page whose url key is already used", async () => {
        const existing = buildPage();
        const duplicate = { ...buildPage(), urlKey: existing.urlKey };
        created.push(existing.title, duplicate.title);

        await cmsPage.createPage(existing);
        await cmsPage.attemptCreatePage(duplicate);

        await cmsPage.expectValidationError(
            "The url key has already been taken.",
        );
        await cmsPage.expectPageAbsent(duplicate.title);
        await cmsPage.expectPublishedOnStorefront(
            existing.urlKey,
            existing.content,
        );
    });

    test("should update a page and serve it under its new url key", async () => {
        const page = buildPage();
        const changes = buildPage();
        created.push(page.title, changes.title);

        await cmsPage.createPage(page);
        await cmsPage.updatePage(page.title, changes);

        await cmsPage.expectPageListed(changes.title, changes.urlKey);
        await cmsPage.expectPageAbsent(page.title);
        await cmsPage.expectTitleInEditForm(changes.title);
        await cmsPage.expectPublishedOnStorefront(changes.urlKey, page.content);
        await cmsPage.expectNotOnStorefront(page.urlKey);
    });

    test("should delete a page and take it off the storefront", async () => {
        const page = buildPage();
        const untouched = buildPage();
        created.push(page.title, untouched.title);

        await cmsPage.createPage(page);
        await cmsPage.createPage(untouched);
        await cmsPage.deletePage(page.title);

        await cmsPage.expectPageAbsent(page.title);
        await cmsPage.expectNotOnStorefront(page.urlKey);
        await cmsPage.expectPageListed(untouched.title, untouched.urlKey);
    });

    test("should mass delete only the selected pages", async () => {
        const first = buildPage();
        const second = buildPage();
        const untouched = buildPage();
        created.push(first.title, second.title, untouched.title);

        await cmsPage.createPage(first);
        await cmsPage.createPage(second);
        await cmsPage.createPage(untouched);
        await cmsPage.massDeletePages([first.title, second.title]);

        await cmsPage.expectPageAbsent(first.title);
        await cmsPage.expectPageAbsent(second.title);
        await cmsPage.expectPageListed(untouched.title, untouched.urlKey);
    });
});
