import { test, expect } from "../../setup";
import {
    generateName,
    generateSlug,
    generateDescription,
    generateHostname,
    getImageFile,
} from "../../utils/faker";

async function createChannel(adminPage) {
    /**
     * Reaching the create channel page.
     */
    await adminPage.goto('admin/settings/channels');
    await adminPage.waitForSelector(
        'a.primary-button:has-text("Create Channel")',
        { state: "visible" }
    );
    await adminPage.click('a.primary-button:has-text("Create Channel")');

    const code = generateSlug("_");
    const name = generateName();
    const description = generateDescription();

    await adminPage.waitForSelector(
        'form[action*="/settings/channels/create"]'
    );

    /**
     * General Section.
     */
    await adminPage.fill("#code", code);
    await adminPage.fill("#name", name);
    await adminPage.fill("#description", description);

    await adminPage.click('label[for="inventory_sources_1"]');
    await expect(adminPage.locator("input#inventory_sources_1")).toBeChecked();

    await adminPage.selectOption("#root_category_id", "1");
    await adminPage.fill("#hostname", generateHostname());

    /**
     * Currencies And Locales Section.
     */
    await adminPage.click('label[for="locales_1"]');
    await expect(adminPage.locator("input#locales_1")).toBeChecked();

    await adminPage.selectOption("#default_locale_id", "1");

    await adminPage.click('label[for="currencies_1"]');
    await expect(adminPage.locator("input#currencies_1")).toBeChecked();

    await adminPage.selectOption("#base_currency_id", "1");

    /**
     * Design Section.
     */
    // const [logoChooser] = await Promise.all([
    //     adminPage.waitForEvent("filechooser"),
    //     adminPage.click('label:has-text("Add Image") >> nth=0'),
    // ]);
    // await logoChooser.setFiles(getImageFile());

    // const [faviconChooser] = await Promise.all([
    //     adminPage.waitForEvent("filechooser"),
    //     adminPage.click('label:has-text("Add Image") >> nth=1'),
    // ]);
    // await faviconChooser.setFiles(getImageFile());

    /**
     * Home Page SEO Section.
     */
    await adminPage.fill("#meta_title", name);
    await adminPage.fill("#seo_keywords", name);
    await adminPage.fill("#meta_description", description);

    /**
     * Now Submit The Form.
     */
    await adminPage.click('button.primary-button:has-text("Save Channel")');
    await expect(
        adminPage.getByText("Channel created successfully.")
    ).toBeVisible();
}

test.describe("channel management", () => {
    test("should create a new channel", async ({ adminPage }) => {
        await createChannel(adminPage);
    });

    test("should edit an existing channel", async ({ adminPage }) => {
        /**
         * Creating a new channel.
         */
        await createChannel(adminPage);

        /**
         * Reaching the edit channel page.
         */
        await adminPage.goto('admin/settings/channels');
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        // Content will be added here. Currently just checking the general save button.

        /**
         * Now Submit The Form.
         */
        await adminPage.click('button.primary-button:has-text("Save Channel")');
        await expect(
            adminPage.getByText("Update Channel Successfully")
        ).toBeVisible();
    });

    test("should delete an existing channel", async ({ adminPage }) => {
        /**
         * Creating a new channel.
         */
        await createChannel(adminPage);

        await adminPage.goto('admin/settings/channels');
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
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
            adminPage.getByText("Channel deleted successfully.")
        ).toBeVisible();
    });
});
