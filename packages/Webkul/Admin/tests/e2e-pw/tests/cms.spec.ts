import { test, expect } from "../setup";
import {
    generateName,
    generateSlug,
    generateDescription,
} from "../utils/faker";

async function createPage(adminPage) {
    /**
     * Reaching the create page.
     */
    await adminPage.goto('admin/cms');
    await adminPage.waitForSelector(
        'a.primary-button:has-text("Create Page")',
        { state: "visible" }
    );
    await adminPage.click('a.primary-button:has-text("Create Page")');

    const name = generateName();
    const slug = generateSlug();
    const shortDescription = generateDescription();

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
}

test.describe("cms management", () => {
    test("should create a page", async ({ adminPage }) => {
        await createPage(adminPage);
    });

    test("should edit a page", async ({ adminPage }) => {
        /**
         * Creating a page to edit.
         */
        await createPage(adminPage);

        /**
         * Reaching the edit channel page.
         */
        await adminPage.goto('admin/cms');
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        // Content will be added here. Currently just checking the general save button.

        /**
         * Now Submit The Form.
         */
        await adminPage.click('button.primary-button:has-text("Save Page")');
        await expect(
            adminPage.getByText("CMS updated successfully.")
        ).toBeVisible();
    });

    test("should delete a page", async ({ adminPage }) => {
        /**
         * Creating a page to delete.
         */
        await createPage(adminPage);

        await adminPage.goto('admin/cms');

        await adminPage.waitForSelector("span.cursor-pointer.icon-delete", {
            state: "visible",
        });
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        await adminPage.waitForSelector("text=Are you sure");
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("CMS deleted successfully.")
        ).toBeVisible();
    });

    test("should mass delete the pages", async ({ adminPage }) => {
        /**
         * Creating a page to delete.
         */
        await createPage(adminPage);

        await adminPage.goto('admin/cms');

        await adminPage.waitForSelector('.icon-uncheckbox:visible', { state: 'visible' });
        const checkboxes = await adminPage.$$('.icon-uncheckbox:visible');
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector('button:has-text("Select Action")', { timeout: 1000 });
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        await adminPage.waitForSelector('text=Are you sure', { state: 'visible', timeout: 1000 });

        const agreeButton = await adminPage.locator('button.primary-button:has-text("Agree")');

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("Selected Data Deleted Successfully")
        ).toBeVisible();
    });
});
