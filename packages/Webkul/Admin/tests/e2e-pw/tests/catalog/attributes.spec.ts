import { test, expect } from "../../setup";
import { generateName, generateSlug } from "../../utils/faker";

test.describe("attribute management", () => {
    test("should create a new attribute successfully", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")'
        );

        const attributeName = generateName();

        // Label Section
        await adminPage.fill('input[name="admin_name"]', attributeName);
        await adminPage.fill('input[name="en[name]"]', attributeName);

        // General Section
        await adminPage.fill("#code", generateSlug("_"));
        await adminPage.selectOption('select[name="type"]', "text");

        // Validations Section
        await adminPage.click('label[for="is_required"]');

        // Configuration Section
        await adminPage.click('label[for="value_per_locale"]');
        await adminPage.click('label[for="value_per_channel"]');
        await adminPage.click('label[for="is_visible_on_front"]');
        await adminPage.click('label[for="is_comparable"]');

        // Verify Checkbox States Using The Hidden Inputs
        await expect(adminPage.locator("input#is_required")).toBeChecked();
        await expect(adminPage.locator("input#value_per_locale")).toBeChecked();
        await expect(
            adminPage.locator("input#value_per_channel")
        ).toBeChecked();
        await expect(
            adminPage.locator("input#is_visible_on_front")
        ).toBeChecked();
        await expect(adminPage.locator("input#is_comparable")).toBeChecked();

        // Submit
        await adminPage.click('button[type="submit"]');

        await expect(
            adminPage.getByText("Attribute Created Successfully")
        ).toBeVisible();
    });

    test("should validate required fields", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" }
        );

        await adminPage.click(
            'div.primary-button:has-text("Create Attribute")'
        );

        await adminPage.click('button[type="submit"]');

        await expect(
            adminPage.locator("text=The Admin field is required")
        ).toBeVisible();
        await expect(
            adminPage.locator("text=The Attribute Code field is required")
        ).toBeVisible();
    });

    test("should edit an existing attribute successfully", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" }
        );

        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        // Content will be added here. Currently just checking the general save button.

        await adminPage.click('button[type="submit"]');

        await expect(
            adminPage.getByText("Attribute Updated Successfully")
        ).toBeVisible();
    });

    test("should delete an existing attribute", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" }
        );

        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );

        await expect(
            adminPage.getByText("Attribute Deleted Successfully")
        ).toBeVisible();
    });

    test("should mass delete and existing attributes", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/attributes");
        await adminPage.waitForSelector(
            'div.primary-button:has-text("Create Attribute")',
            { state: "visible" }
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible");
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")'
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")');

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
            adminPage.getByText(
                /Attribute Deleted Successfully|Attribute Deleted Failed/
            )
        ).toBeVisible();
    });
});
