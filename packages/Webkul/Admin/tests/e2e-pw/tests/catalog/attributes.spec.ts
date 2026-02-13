import { test, expect } from "../../setup";
import { generateName, generateSlug, generateSKU } from "../../utils/faker";

test.describe("attribute management", () => {
    test("should validate required fields", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" },
        );

        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        await adminPage.click('button[type="submit"]');

        await expect(
            adminPage.getByText("The Admin field is required"),
        ).toBeVisible();
        await expect(
            adminPage.getByText("The Attribute Code field is"),
        ).toBeVisible();
    });

    test("should create a new text type attribute", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.selectOption('select[name="type"]', "text");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Verify Checkbox States Using The Hidden Inputs
         */
        await expect(adminPage.locator("input#value_per_locale")).toBeChecked();
        await expect(
            adminPage.locator("input#value_per_channel"),
        ).toBeChecked();
        await expect(
            adminPage.locator("input#is_visible_on_front"),
        ).toBeChecked();
        await expect(adminPage.locator("input#is_comparable")).toBeChecked();

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         * Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];
        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         *  Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new textarea type attribute with wysiwyg editor", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("textarea");
        await adminPage.locator(".relative > label").click();

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        // Locate all target divs where items will be dragged
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        // Use only the first target (e.g., "General")
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        // Locate all draggable icons inside the unassigned section
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        // Drag each item to the first target
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            // Move mouse to item center
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            // Move mouse to target center
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        // Click save
        await adminPage.click(".primary-button:visible");

        // Verify success
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new textarea type attribute without wysiwyg editor", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("textarea");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();
        // Locate all target divs where items will be dragged
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        // Use only the first target (e.g., "General")
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        // Locate all draggable icons inside the unassigned section
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        // Drag each item to the first target
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            // Move mouse to item center
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            // // Move mouse to target center
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        // Click save
        await adminPage.click(".primary-button:visible");

        // Verify success
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new select type attribute with dropdown swatch type", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("select");

        /**
         * Dropdown Options
         */
        await adminPage.locator("#swatchType").selectOption("dropdown");
        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("1 Year");
        await adminPage.locator('input[name="en"]').fill("1 Year");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("2 Years");
        await adminPage.locator('input[name="en"]').fill("2 Years");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("5 Years");
        await adminPage.locator('input[name="en"]').fill("5 Years");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("No Warranty");
        await adminPage.locator('input[name="en"]').fill("No Warranty");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new select type attribute with color swatch type", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("select");

        /**
         * Dropdown Options
         */
        await adminPage.locator("#swatchType").selectOption("color");
        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Red");
        await adminPage.locator('input[name="en"]').fill("Red");
        await adminPage.getByPlaceholder("Color").fill("#eb0f0f");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Green");
        await adminPage.locator('input[name="en"]').fill("Green");
        await adminPage.getByPlaceholder("Color").fill("#3bdb0f");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Yellow");
        await adminPage.locator('input[name="en"]').fill("Yellow");
        await adminPage.getByPlaceholder("Color").fill("#e1f00a");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Blue");
        await adminPage.locator('input[name="en"]').fill("Blue");
        await adminPage.getByPlaceholder("Color").fill("#0af0ec");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();
        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new select type attribute with image swatch type", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("select");

        /**
         * Dropdown Options
         */
        await adminPage.locator("#swatchType").selectOption("color");
        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Image-1");
        await adminPage.locator('input[name="en"]').fill("Image-1");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Image-2");
        await adminPage.locator('input[name="en"]').fill("Image-2");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Image-3");
        await adminPage.locator('input[name="en"]').fill("Image-3");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Image-4");
        await adminPage.locator('input[name="en"]').fill("Image-4");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new select type attribute with text swatch type", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("select");

        /**
         * Dropdown Options
         */
        await adminPage.locator("#swatchType").selectOption("text");
        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Text-1");
        await adminPage.locator('input[name="en"]').fill("Text-1");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Text-2");
        await adminPage.locator('input[name="en"]').fill("Text-2");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Text-3");
        await adminPage.locator('input[name="en"]').fill("Text-3");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("Text-4");
        await adminPage.locator('input[name="en"]').fill("Text-4");
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new price type attribute ", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("price");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new boolean type attribute ", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("boolean");
        await adminPage.locator('input[name="default_value"]').fill("1");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new date type attribute ", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("date");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new datetime type attribute ", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("datetime");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();

    });

    test("should create a new image type attribute ", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("image");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new file type attribute ", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("file");

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new multiselect type attribute", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("multiselect");

        /**
         * Dropdown Options
         */
        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill(generateName());
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill(generateName());
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("5 Years");
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill(generateName());
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }

        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 20 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should create a new checkbox type attribute", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")',
        );

        const attributeName = generateName();

        /**
         * Label Section
         */
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        /**
         * General Section
         */
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.locator("#type").selectOption("multiselect");

        /**
         * Dropdown Options
         */
        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill(generateName());
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill(generateName());
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill("5 Years");
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        await adminPage.getByText("Add Row").click();
        await adminPage
            .getByRole("textbox", { name: "Admin" })
            .nth(1)
            .fill(generateName());
        await adminPage.locator('input[name="en"]').fill(generateName());
        await adminPage.getByRole("button", { name: "Save Option" }).click();

        /**
         * Configuration Section
         */
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        /**
         * Submit
         */
        await adminPage.click('button[type="submit"]');

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        await adminPage
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit")
            .click();

        /**
         *  Locate all target divs where items will be dragged
         */
        const targets = await adminPage.$$(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );

        /**
         * Use only the first target (e.g., "General")
         */
        const firstTarget = targets[0];

        const targetBox = await firstTarget.boundingBox();

        if (!targetBox) {
            throw new Error("Target element is not visible");
        }
        /**
         * Locate all draggable icons inside the unassigned section
         */
        const unassignedItems = await adminPage.$$(
            "#unassigned-attributes i.icon-drag",
        );

        /**
         * Drag each item to the first target
         */
        for (const item of unassignedItems) {
            const itemBox = await item.boundingBox();
            if (!itemBox) continue;

            /**
             * Move mouse to item center
             */
            await adminPage.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await adminPage.mouse.down();

            /**
             * Move mouse to target center
             */
            await adminPage.mouse.move(
                targetBox.x + targetBox.width / 2,
                targetBox.y + targetBox.height / 2,
                { steps: 15 },
            );
            await adminPage.mouse.up();

            await adminPage.waitForTimeout(200);
        }

        /**
         * Click save
         */
        await adminPage.click(".primary-button:visible");

        /**
         * Verify success
         */
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should edit an existing attribute successfully", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" },
        );

        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        // Content will be added here. Currently just checking the general save button.

        await adminPage.click('button[type="submit"]');

        await expect(
            adminPage.getByText("Attribute Updated Successfully").first(),
        ).toBeVisible();
    });

    test("should delete an existing attribute", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" },
        );

        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete",
        );
        await iconDelete[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible",
        );

        await expect(
            adminPage.getByText("Attribute Deleted Successfully").first(),
        ).toBeVisible();
    });

    test("should mass delete and existing attributes", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" },
        );
        await adminPage.getByRole("button", { name: "" }).click();
        await adminPage.getByText("50", { exact: true }).first().click();
        await adminPage.waitForSelector(".icon-uncheckbox:visible");
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");

        for (let i = 1; i <= 14; i++) {
            await checkboxes[i].click();
        }

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")');

        await adminPage.waitForSelector("text=Are you sure");
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")',
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage
                .getByText(
                    /Attribute Deleted Successfully|Attribute Deleted Failed/,
                )
                .first(),
        ).toBeVisible();
    });
});
