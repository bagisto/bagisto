import { test, expect, config } from "../setup";
import {
    generateName,
    generateSlug,
    generateShortDescription,
} from "../utils/faker";
import * as forms from "../utils/form";

test.describe("cms management", () => {
    test("should create a new page", async ({ adminPage }) => {
        /**
         * Reaching the create page.
         */
        await adminPage.goto(`${config.baseUrl}/admin/cms`);
        await adminPage.waitForSelector(
            'a.primary-button:has-text("Create Page")',
            { state: "visible" }
        );
        await adminPage.click('a.primary-button:has-text("Create Page")');

        const name = generateName();
        const slug = generateSlug();
        const shortDescription = generateShortDescription();

        /**
         * Description Section.
         */
        await adminPage.waitForSelector("iframe.tox-edit-area__iframe");
        const iframe = await adminPage.frameLocator(
            "iframe.tox-edit-area__iframe"
        );
        const editorBody = iframe.locator("body");
        await editorBody.click();
        await editorBody.pressSequentially(shortDescription);
        await expect(editorBody).toHaveText(shortDescription);

        /**
         * General Section.
         */
        await adminPage.fill("#page_title", name);

        await adminPage.click('label[for="channels_1"]');
        await expect(adminPage.locator("input#channels_1")).toBeChecked();

        /**
         * SEO Section.
         */
        await adminPage.fill("#meta_title", name);
        await adminPage.fill("#url_key", slug);
        await adminPage.fill("#meta_keywords", name);
        await adminPage.fill("#meta_description", shortDescription);

        /**
         * Now submit the form.
         */
        await adminPage.click('button.primary-button:has-text("Save Page")');
        await expect(
            adminPage.getByText("CMS created successfully.")
        ).toBeVisible();
    });

    test("should edit an existing page", async ({ adminPage }) => {
        /**
         * Reaching the edit channel page.
         */
        await adminPage.goto(`${config.baseUrl}/admin/cms`);
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        // Content will be added here. Currently just checking the general save button.

        /**
         * Now Submit The Form.
         */
        await adminPage.click('button.primary-button:has-text("Save Page")');
        await adminPage.screenshot({ path: 'screenshot.png', fullPage: true });
        await expect(
            adminPage.getByText("CMS updated successfully.")
        ).toBeVisible();
    });

    test("delete page", async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/cms`);

        await adminPage.waitForSelector(
            'span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]'
        );

        const iconDelete = await adminPage.$$(
            'span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]'
        );

        await iconDelete[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );

        await expect(
            adminPage.getByText("CMS deleted successfully.")
        ).toBeVisible();
    });

    test("mass delete pages", async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/cms`);

        await adminPage.waitForSelector(".icon-uncheckbox");

        const checkboxs = await adminPage.$$(".icon-uncheckbox");

        await checkboxs[1].click();

        await adminPage
            .waitForSelector(
                'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible',
                { timeout: 1000 }
            )
            .catch(() => null);

        await adminPage.click(
            'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible'
        );
        await adminPage.click(
            'a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible'
        );

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );

        await expect(
            adminPage.getByText("Selected Data Deleted Successfully")
        ).toBeVisible();
    });
});
