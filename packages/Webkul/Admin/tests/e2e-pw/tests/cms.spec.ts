import { test, expect } from "../setup";
import {
    generateName,
    generateSlug,
    generateDescription,
} from "../utils/faker";

async function createPage(adminPage) {
    const cms = {
        name: generateName(),
        slug: generateSlug(),
        shortDescription: generateDescription(),
    };

    /**
     * Reaching the create page.
     */
    await adminPage.goto("admin/cms");
    await adminPage.waitForSelector(
        'a.primary-button:has-text("Create Page")',
        { state: "visible" }
    );
    await adminPage.click('a.primary-button:has-text("Create Page")');

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce("#content_ifr", cms.shortDescription);

    /**
     * General Section.
     */
    await adminPage.fill("#page_title", cms.name);

    await adminPage.click('label[for="channels_1"]');
    await expect(adminPage.locator("input#channels_1")).toBeChecked();

    /**
     * SEO Section.
     */
    await adminPage.fill("#meta_title", cms.name);
    await adminPage.fill("#url_key", cms.slug);
    await adminPage.fill("#meta_keywords", cms.name);
    await adminPage.fill("#meta_description", cms.shortDescription);

    /**
     * Now submit the form.
     */
    await adminPage.click('button.primary-button:has-text("Save Page")');
    await expect(
        adminPage.locator("#app p", { hasText: "CMS created successfully." })
    ).toBeVisible();
    await expect(adminPage.getByText(cms.name)).toBeVisible();
    await expect(adminPage.getByText(cms.slug)).toBeVisible();

    return cms;
}

test.describe("cms management", () => {
    test("should create a page", async ({ adminPage }) => {
        await createPage(adminPage);
    });

    test("should edit a page", async ({ adminPage }) => {
        const cms = {
            name: generateName(),
            slug: generateSlug(),
            shortDescription: generateDescription(),
        };

        /**
         * Reaching the edit CMS page.
         */
        await adminPage.goto("admin/cms");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        /**
         * Edit General Section.
         */
        await adminPage.getByRole("textbox", { name: "Page Title" }).fill("");
        await adminPage
            .getByRole("textbox", { name: "Page Title" })
            .fill(cms.name);

        /**
         * Edit Description Section.
         */
        const editorFrame = adminPage.frameLocator(
            "iframe.tox-edit-area__iframe"
        );
        await editorFrame.locator("body").fill(cms.shortDescription);

        /**
         * Edit SEO Section.
         */
        await adminPage.fill("#meta_title", "");
        await adminPage.fill("#meta_title", cms.name);
        await adminPage.fill("#url_key", "");
        await adminPage.fill("#url_key", cms.slug);
        await adminPage.fill("#meta_keywords", "");
        await adminPage.fill("#meta_keywords", cms.name);
        await adminPage.fill("#meta_description", "");
        await adminPage.fill("#meta_description", cms.shortDescription);

        /**
         * Now Submit The Form.
         */
        await adminPage.click('button.primary-button:has-text("Save Page")');
        await expect(
            adminPage.locator("#app p", {
                hasText: "CMS updated successfully.",
            })
        ).toBeVisible();
        await expect(adminPage.getByText(cms.name)).toBeVisible();
        await expect(adminPage.getByText(cms.slug)).toBeVisible();
    });

    test("should delete a page", async ({ adminPage }) => {
        /**
         * Creating a page to delete.
         */
        const cms = await createPage(adminPage);

        /**
         * Deleting a CMS page.
         */
        await adminPage.goto("admin/cms");
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete", {
            state: "visible",
        });
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        /**
         * Confirmation modal for deleting a CMS page.
         */
        await adminPage.waitForSelector("text=Are you sure");
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );
        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Alert message for deleting a CMS page should appear.
         */ ("CMS deleted successfully.");
        await expect(
            adminPage.locator("#app p", {
                hasText: "CMS deleted successfully.",
            })
        ).toBeVisible();
        await expect(adminPage.getByText(cms.name)).not.toBeVisible();
    });

    test("should mass delete the selected pages", async ({ adminPage }) => {
        /**
         * Creating CMS pages for multiselect delete.
         */
        await createPage(adminPage);

        /**
         * Deleting a CMS page.
         */
        await adminPage.goto("admin/cms");
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete", {
            state: "visible",
        });
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        /**
         * Confirmation modal for deleting a CMS page.
         */
        await adminPage.waitForSelector("text=Are you sure");

        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Alert message for deleting a CMS page should appear.
         */
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

        await adminPage.goto("admin/cms");

        /**
         * Selecting a multiselector checkbox.
         */
        await adminPage.locator(".icon-uncheckbox").first().click();
        await adminPage
            .getByRole("button", { name: "Select Action " })
            .click();
        await adminPage.getByRole("link", { name: "Delete" }).click();

        /**
         * Confirmation modal for deleting CMS pages.
         */
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        /**
         * Confirmation modal for deleting CMS pages.
         */
        await adminPage.getByText("Selected Data Deleted").click();
        await expect(adminPage.getByText(cms.name)).not.toBeVisible();
    });
});
