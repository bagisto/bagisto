import { test, expect } from "../../setup";
import { generateName, generateDescription } from "../../utils/faker";
import * as forms from "../../utils/form";

async function createTemplate(adminPage) {
    /**
     * Reaching the create template page.
     */
    await adminPage.goto(`admin/marketing/communications/email-templates`);
    await adminPage.click(
        'div.primary-button:visible:has-text("Create Template")'
    );

    const name = generateName();
    const description = generateDescription();

    /**
     * General Section.
     */
    await adminPage.fill('input[name="name"]', name);
    await adminPage.selectOption('select[name="status"]', "active");

    /**
     * Content Section.
     */
    await adminPage.fillInTinymce("#content_ifr", description);

    await adminPage.click(
        'button[type="submit"][class="primary-button"]:visible:has-text("Save Template")'
    );

    await expect(adminPage.locator('#app')).toContainText('Email template created successfully.');
}

test.describe("communication management", () => {
    test("should create a template", async ({ adminPage }) => {
        await createTemplate(adminPage);
    });

    test("should edit a template", async ({ adminPage }) => {
        /**
         * Creating a template to edit.
         */
        await createTemplate(adminPage);

        await adminPage.goto(`admin/marketing/communications/email-templates`);

        await adminPage.waitForSelector("span.cursor-pointer.icon-edit", {
            state: "visible",
        });
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();
        await adminPage.fill('input[name="name"]', generateName());

        /**
         * Save the edit template.
         */
        await adminPage.click(
            'button[type="submit"][class="primary-button"]:visible'
        );

        await expect(adminPage.locator('#app')).toContainText('Updated successfully');
    });

    test("should delete a template", async ({ adminPage }) => {
        /**
         * Creating a template to delete.
         */
        await createTemplate(adminPage);

        await adminPage.goto(`admin/marketing/communications/email-templates`);

        await adminPage.waitForSelector("span.cursor-pointer.icon-delete", {
            state: "visible",
        });
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );

        await expect(
            adminPage.getByText("Template Deleted successfully")
        ).toBeVisible();
    });

    test("create event", async ({ adminPage }) => {
        await adminPage.goto(`admin/marketing/communications/events`);

        await adminPage.click("div.primary-button:visible");

        adminPage.hover('input[name="name"]');

        const inputs = await adminPage.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
        );

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const time = forms.generateRandomDateTimeRange();

        await adminPage.fill('input[name="date"]', time.from);

        await adminPage.click('button[class="primary-button"]:visible');

        await expect(
            adminPage.getByText("Events Created Successfully")
        ).toBeVisible();
    });

    test("edit event", async ({ adminPage }) => {
        await adminPage.goto(`admin/marketing/communications/events`);

        await adminPage.waitForSelector(
            'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
        );

        const iconEdit = await adminPage.$$(
            'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
        );

        await iconEdit[0].click();

        const iconExists = await adminPage
            .waitForSelector(
                ".flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl",
                { timeout: 3000 }
            )
            .catch(() => null);

        if (iconExists) {
            const messages = await adminPage.$$(
                ".flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl"
            );
            const icons = await adminPage.$$(
                ".flex.items-center.break-all.text-sm + .cursor-pointer.underline"
            );

            const message = await messages[0].evaluate(
                (el) => el.parentNode.innerText
            );
            await icons[0].click();

            throw new Error(message);
        }

        adminPage.hover('input[name="name"]');

        const inputs = await adminPage.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
        );

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const time = forms.generateRandomDateTimeRange();

        await adminPage.fill('input[name="date"]', time.from);

        await adminPage.click('button[class="primary-button"]:visible');

        await expect(
            adminPage.getByText("Events Updated Successfully")
        ).toBeVisible();
    });

    test("delete event", async ({ adminPage }) => {
        await adminPage.goto(`admin/marketing/communications/events`);

        await adminPage.waitForSelector(
            'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
        );

        const iconDelete = await adminPage.$$(
            'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
        );

        await iconDelete[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );

        await expect(
            adminPage.getByText("Events Deleted Successfully")
        ).toBeVisible();
    });

    test("create campaign", async ({ adminPage }) => {
        /**
         * Creating a template to use in the campaign.
         */
        await createTemplate(adminPage);

        await adminPage.goto(`admin/marketing/communications/events`);

        await adminPage.click("div.primary-button:visible");

        adminPage.hover('input[name="name"]');

        const inputs = await adminPage.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
        );

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const time = forms.generateRandomDateTimeRange();

        await adminPage.fill('input[name="date"]', time.from);

        await adminPage.click('button[class="primary-button"]:visible');

        await expect(
            adminPage.getByText("Events Created Successfully")
        ).toBeVisible();

        await adminPage.goto(`admin/marketing/communications/campaigns`);

        await adminPage.click("div.primary-button:visible");

        await adminPage.click('input[type="checkbox"] + label.peer');

        const newInputs = await adminPage.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
        );

        for (let input of newInputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const selects = await adminPage.$$("select.custom-select");

        for (let select of selects) {
            const options = await select.$$eval("option", (options) => {
                return options.map((option) => option.value);
            });

            if (options.length > 1) {
                const randomIndex =
                    Math.floor(Math.random() * (options.length - 1)) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await adminPage.click('input[type="checkbox"] + label.peer');
        }

        await adminPage.click('button[class="primary-button"]:visible');

        await expect(
            adminPage.getByText("Campaign created successfully.")
        ).toBeVisible();
    });

    test("edit campaign", async ({ adminPage }) => {
        await adminPage.goto(`admin/marketing/communications/campaigns`);

        await adminPage.waitForSelector(
            'span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]'
        );

        const iconEdit = await adminPage.$$(
            'span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]'
        );

        await iconEdit[0].click();

        await adminPage.click('input[type="checkbox"] + label.peer');

        const inputs = await adminPage.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
        );

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const selects = await adminPage.$$("select.custom-select");

        for (let select of selects) {
            const options = await select.$$eval("option", (options) => {
                return options.map((option) => option.value);
            });

            if (options.length > 1) {
                const randomIndex =
                    Math.floor(Math.random() * (options.length - 1)) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await adminPage.click('input[type="checkbox"] + label.peer');
        }

        await adminPage.click('button[class="primary-button"]:visible');

        await expect(
            adminPage.getByText("Campaign updated successfully.")
        ).toBeVisible();
    });

    test("delete campaign", async ({ adminPage }) => {
        await adminPage.goto(`admin/marketing/communications/campaigns`);

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
            adminPage.getByText("Campaign deleted successfully")
        ).toBeVisible();
    });

    // test("edit newsletter subscriber", async ({ adminPage }) => {
    //     await adminPage.goto(
    //         `admin/marketing/communications/subscribers`
    //     );

    //     const iconEdit = await adminPage.$$(
    //         'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
    //     );

    //     await iconEdit[
    //         Math.floor(Math.random() * (iconEdit.length - 1 - 0 + 1)) + 0
    //     ].click();

    //     await adminPage.waitForSelector('select[name="is_subscribed"]');

    //     const select = await adminPage.$('select[name="is_subscribed"]');

    //     const option = Math.random() > 0.5 ? "1" : "0";

    //     await select.selectOption({ value: option });

    //     let i = Math.floor(Math.random() * 10) + 1;

    //     await adminPage.click('button[class="primary-button"]:visible');

    //     await expect(
    //         adminPage.getByText("Newsletter Subscription Updated Successfully")
    //     ).toBeVisible();
    // });

    // test("delete newsletter subscriber", async ({ adminPage }) => {
    //     await adminPage.goto(
    //         `admin/marketing/communications/subscribers`
    //     );

    //     await adminPage.waitForSelector(
    //         'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
    //     );

    //     const iconDelete = await adminPage.$$(
    //         'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
    //     );

    //     await iconDelete[0].click();

    //     await adminPage.click(
    //         "button.transparent-button + button.primary-button:visible"
    //     );

    //     await expect(
    //         adminPage.getByText("Subscriber Deleted Successfully")
    //     ).toBeVisible();
    // });
});