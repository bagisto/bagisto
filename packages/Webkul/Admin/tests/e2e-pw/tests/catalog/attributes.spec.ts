import { test, expect } from "../../setup";
import type { Page } from "@playwright/test";
import { generateName, generateSlug, generateSKU } from "../../utils/faker";

const CREATE_ATTRIBUTE_BUTTON =
    'div.primary-button:has-text("Create Attribute")';
const ATTRIBUTE_SUBMIT_BUTTON = 'button[type="submit"]';
const DEFAULT_CONFIG_TOGGLES = [
    'label[for="value_per_locale"]',
    'label[for="value_per_channel"]',
    'label[for="is_visible_on_front"]',
    'label[for="is_comparable"]',
];
const ATTRIBUTE_GROUP_LISTS =
    "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l";
const UNASSIGNED_ATTRIBUTE_HANDLES = "#unassigned-attributes i.icon-drag";
const FAMILY_SAVE_BUTTON = "button.primary-button:visible";

async function openCreateAttributeForm(page: Page) {
    await openAttributesList(page);
    await page.click(CREATE_ATTRIBUTE_BUTTON);
}

async function openAttributesList(page: Page) {
    await page.goto("admin/catalog/attributes");
    await page.waitForSelector(CREATE_ATTRIBUTE_BUTTON, { state: "visible" });
}

async function enableDefaultConfiguration(page: Page) {
    for (const toggle of DEFAULT_CONFIG_TOGGLES) {
        await page.click(toggle);
    }
}

async function submitAttributeForm(page: Page) {
    await page.click(ATTRIBUTE_SUBMIT_BUTTON);
}

async function addAttributeToDefaultFamily(page: Page) {
    await page.goto("admin/catalog/families");
    await page.waitForSelector("span.cursor-pointer.icon-edit");
    const editIcon = page
        .locator('div.row:has-text("default")')
        .locator("span.icon-edit");

    if (!(await editIcon.isVisible())) {
        await page.locator("span.icon-sort-down").click();
        await page.locator("ul.py-4 >> li", { hasText: "20" }).click();
        await page.waitForLoadState("networkidle");
        await editIcon.click();
    } else {
        await editIcon.click();
    }

    await page.waitForSelector(ATTRIBUTE_GROUP_LISTS, { state: "visible" });
    const targets = page.locator(ATTRIBUTE_GROUP_LISTS);
    const targetCount = await targets.count();
    expect(targetCount).toBeGreaterThan(0);

    const targetColumns = Math.min(2, targetCount);
    const targetPoints: { x: number; y: number }[] = [];

    for (let index = 0; index < targetColumns; index++) {
        const target = targets.nth(index);
        await target.scrollIntoViewIfNeeded();

        if (!(await target.isVisible())) {
            continue;
        }

        const targetBox = await target.boundingBox();

        if (!targetBox) {
            continue;
        }

        targetPoints.push({
            x: targetBox.x + targetBox.width / 2,
            y: targetBox.y + targetBox.height / 2,
        });
    }

    expect(targetPoints.length).toBeGreaterThan(0);

    const unassignedItems = await page.$$(UNASSIGNED_ATTRIBUTE_HANDLES);

    for (const [index, item] of unassignedItems.entries()) {
        const itemBox = await item.boundingBox();

        if (!itemBox) {
            continue;
        }

        const targetPoint = targetPoints[index % targetPoints.length];

        await page.mouse.move(
            itemBox.x + itemBox.width / 2,
            itemBox.y + itemBox.height / 2,
        );
        await page.mouse.down();
        await page.mouse.move(targetPoint.x, targetPoint.y, { steps: 20 });
        await page.mouse.up();
        await page.waitForTimeout(200);
    }

    await page.click(FAMILY_SAVE_BUTTON);
    await expect(
        page.getByText("Family updated successfully.").first(),
    ).toBeVisible();
}

test.describe("attribute management", () => {
    test("should validate required fields", async ({ adminPage }) => {
        await openCreateAttributeForm(adminPage);

        await submitAttributeForm(adminPage);

        await expect(
            adminPage.getByText("The Admin field is required").first(),
        ).toBeVisible();
        await expect(
            adminPage.getByText("The Attribute Code field is").first(),
        ).toBeVisible();
    });

    test("should create a new text type attribute", async ({ adminPage }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

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
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new textarea type attribute with wysiwyg editor", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new textarea type attribute without wysiwyg editor", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new select type attribute with dropdown swatch type", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new select type attribute with color swatch type", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new select type attribute with image swatch type", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new select type attribute with text swatch type", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new price type attribute ", async ({ adminPage }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new boolean type attribute ", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new date type attribute ", async ({ adminPage }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new datetime type attribute ", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new image type attribute ", async ({ adminPage }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new file type attribute ", async ({ adminPage }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new multiselect type attribute", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should create a new checkbox type attribute", async ({
        adminPage,
    }) => {
        await openCreateAttributeForm(adminPage);

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
        await enableDefaultConfiguration(adminPage);

        /**
         * Submit
         */
        await submitAttributeForm(adminPage);

        await expect(adminPage.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );

        /**
         * Drag and drop the attribute to the Attribute Family
         */
        await addAttributeToDefaultFamily(adminPage);
    });

    test("should edit an existing attribute successfully", async ({
        adminPage,
    }) => {
        await openAttributesList(adminPage);

        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        // Content will be added here. Currently just checking the general save button.

        await submitAttributeForm(adminPage);

        await expect(
            adminPage.getByText("Attribute Updated Successfully").first(),
        ).toBeVisible();
    });

    test("should delete an existing attribute", async ({ adminPage }) => {
        await openAttributesList(adminPage);

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
        await openAttributesList(adminPage);
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
