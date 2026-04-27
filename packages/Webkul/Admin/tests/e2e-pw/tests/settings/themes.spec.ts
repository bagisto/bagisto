import { test, expect } from "../../setup";
import {
    generateDescription,
    generateHostname,
    generateName,
    getImageFile,
} from "../../utils/faker";

test.describe("theme management", () => {
    test("should create a product carousel theme", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/themes");
        await adminPage.getByRole("button", { name: "Create Theme" }).click();
        await adminPage.locator('input[name="name"]').fill(generateName());
        await adminPage.locator('input[name="sort_order"]').fill("1");
        await adminPage
            .locator('select[name="type"]')
            .selectOption("product_carousel");
        await adminPage.locator('select[name="channel_id"]').selectOption("1");
        await adminPage
            .locator('select[name="theme_code"]')
            .selectOption("default");
        await adminPage.getByRole("button", { name: "Save Theme" }).click();
        await adminPage.waitForSelector(
            'form[action*="/settings/themes/edit"]',
        );
        await adminPage
            .locator('input[name="en[options][title]"]')
            .fill(generateName());
        await adminPage
            .locator('select[name="en[options][filters][sort]"]')
            .selectOption("name-asc");
        await adminPage
            .locator('select[name="en[options][filters][limit]"]')
            .selectOption("12");
        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');

        await expect(toggleInput).toBeChecked();

        await adminPage.getByRole("button", { name: "Save" }).click();

        await expect(
            adminPage.getByText("Theme updated successfully"),
        ).toBeVisible();
    });

    test("should create a category carousel theme", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/themes");
        await adminPage.getByRole("button", { name: "Create Theme" }).click();
        await adminPage.locator('input[name="name"]').fill(generateName());
        await adminPage.locator('input[name="sort_order"]').fill("1");
        await adminPage
            .locator('select[name="type"]')
            .selectOption("category_carousel");
        await adminPage.locator('select[name="channel_id"]').selectOption("1");
        await adminPage
            .locator('select[name="theme_code"]')
            .selectOption("default");
        await adminPage.getByRole("button", { name: "Save Theme" }).click();
        await adminPage.waitForSelector(
            'form[action*="/settings/themes/edit"]',
        );
        await adminPage
            .locator('select[name="en[options][filters][sort]"]')
            .selectOption("asc");
        await adminPage
            .locator('input[name="en[options][filters][limit]"]')
            .fill("10");
        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');

        await expect(toggleInput).toBeChecked();

        await adminPage.getByRole("button", { name: "Save" }).click();

        await expect(
            adminPage.getByText("Theme updated successfully"),
        ).toBeVisible();
    });

    test("should create a static content theme", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/themes");
        await adminPage.getByRole("button", { name: "Create Theme" }).click();
        await adminPage.locator('input[name="name"]').fill(generateName());
        await adminPage.locator('input[name="sort_order"]').fill("1");
        await adminPage
            .locator('select[name="type"]')
            .selectOption("static_content");
        await adminPage.locator('select[name="channel_id"]').selectOption("1");
        await adminPage
            .locator('select[name="theme_code"]')
            .selectOption("default");
        await adminPage.getByRole("button", { name: "Save Theme" }).click();
        await adminPage.waitForSelector(
            'form[action*="/settings/themes/edit"]',
        );
        const content = generateDescription();
        const codeMirrorTextarea = await adminPage.locator(
            ".CodeMirror textarea",
        );
        await codeMirrorTextarea.focus();
        await adminPage.keyboard.type(content);
        const codeMirrorContent = await adminPage
            .locator(".CodeMirror-code")
            .innerText();

        expect(codeMirrorContent).toContain(content);

        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');

        await expect(toggleInput).toBeChecked();

        await adminPage.getByRole("button", { name: "Save" }).click();

        await expect(
            adminPage.getByText("Theme updated successfully"),
        ).toBeVisible();
    });

    test("should create a image carousel theme", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/themes");
        await adminPage.getByRole("button", { name: "Create Theme" }).click();
        await adminPage.locator('input[name="name"]').fill(generateName());
        await adminPage.locator('input[name="sort_order"]').fill("1");
        await adminPage
            .locator('select[name="type"]')
            .selectOption("image_carousel");
        await adminPage.locator('select[name="channel_id"]').selectOption("1");
        await adminPage
            .locator('select[name="theme_code"]')
            .selectOption("default");
        await adminPage.getByRole("button", { name: "Save Theme" }).click();

        await adminPage.waitForSelector(
            'form[action*="/settings/themes/edit"]',
        );

        await adminPage
            .locator('div.secondary-button:has-text("Add Slider")')
            .click();
        await adminPage.locator('input[name="en[title]"]').fill(generateName());
        await adminPage
            .locator('input[name="en[link]"]')
            .fill(generateHostname());

        const [fileChooser] = await Promise.all([
            adminPage.waitForEvent("filechooser"),
            adminPage
                .locator("label")
                .filter({ hasText: "Add Image png, jpeg, jpg" })
                .last()
                .click(),
        ]);
        await fileChooser.setFiles(getImageFile());

        await adminPage.getByRole("button", { name: "Save" }).nth(1).click();

        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');
        await expect(toggleInput).toBeChecked();

        await adminPage.getByRole("button", { name: "Save" }).click();

        await expect(
            adminPage.getByText("Theme updated successfully"),
        ).toBeVisible();
    });

    test("should create a footer link theme", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/themes");
        await adminPage.getByRole("button", { name: "Create Theme" }).click();
        await adminPage.locator('input[name="name"]').fill(generateName());
        await adminPage.locator('input[name="sort_order"]').fill("1");
        await adminPage
            .locator('select[name="type"]')
            .selectOption("footer_links");
        await adminPage.locator('select[name="channel_id"]').selectOption("1");
        await adminPage
            .locator('select[name="theme_code"]')
            .selectOption("default");
        await adminPage.getByRole("button", { name: "Save Theme" }).click();

        await adminPage.waitForSelector(
            'form[action*="/settings/themes/edit"]',
        );

        await adminPage
            .locator('div.secondary-button:has-text("Add Link")')
            .click();
        await adminPage.locator('select[name="column"]').selectOption("1");
        await adminPage.locator('input[name="title"]').fill(generateName());
        await adminPage.locator('input[name="url"]').fill(generateHostname());
        await adminPage
            .getByRole("textbox", { name: "Sort Order" })
            .first()
            .fill("1");

        await adminPage.getByRole("button", { name: "Save" }).nth(1).click();

        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');
        await expect(toggleInput).toBeChecked();

        await adminPage.getByRole("button", { name: "Save" }).click();

        await expect(
            adminPage.getByText("Theme updated successfully"),
        ).toBeVisible();
    });

    test("should create a services content theme", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/themes");
        await adminPage.getByRole("button", { name: "Create Theme" }).click();
        await adminPage.locator('input[name="name"]').fill(generateName());
        await adminPage.locator('input[name="sort_order"]').fill("1");
        await adminPage
            .locator('select[name="type"]')
            .selectOption("services_content");
        await adminPage.locator('select[name="channel_id"]').selectOption("1");
        await adminPage
            .locator('select[name="theme_code"]')
            .selectOption("default");
        await adminPage.getByRole("button", { name: "Save Theme" }).click();

        await adminPage.waitForSelector(
            'form[action*="/settings/themes/edit"]',
        );

        await adminPage
            .locator('div.secondary-button:has-text("Add Services")')
            .click();
        await adminPage
            .getByRole("textbox", { name: "Title" })
            .fill(generateName());
        await adminPage
            .getByRole("textbox", { name: "Description" })
            .fill(generateDescription());
        await adminPage
            .getByRole("textbox", { name: "Service Icon Class" })
            .fill("icon-truck");

        await adminPage.getByRole("button", { name: "Save" }).nth(1).click();

        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');
        await expect(toggleInput).toBeChecked();

        await adminPage.getByRole("button", { name: "Save" }).click();

        await expect(
            adminPage.getByText("Theme updated successfully"),
        ).toBeVisible();
    });

    test("should delete a theme", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/themes");
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete",
        );
        await iconDelete[0].click();

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
            adminPage.getByText("Theme deleted successfully"),
        ).toBeVisible();
    });
});
