// import { test, expect } from "../../setup";
// import {
//     generateDescription,
//     generateHostname,
//     generateName,
//     getImageFile,
// } from "../../utils/faker";

// test.describe("theme management", () => {
//     test("should create a product carousel theme", async ({ adminPage }) => {
//         /**
//          * Reaching to the theme listing page.
//          */
//         await adminPage.goto("admin/settings/themes");

//         /**
//          * Opening create theme form in modal.
//          */
//         await adminPage.getByRole("button", { name: "Create Theme" }).click();
//         await adminPage.locator('input[name="name"]').fill(generateName());
//         await adminPage.locator('input[name="sort_order"]').fill("1");
//         await adminPage
//             .locator('select[name="type"]')
//             .selectOption("product_carousel");
//         await adminPage.locator('select[name="channel_id"]').selectOption("1");
//         await adminPage
//             .locator('select[name="theme_code"]')
//             .selectOption("default");
//         await adminPage.getByRole("button", { name: "Save Theme" }).click();

//         /**
//          * After creating the product, the page is redirected to the edit theme page, where
//          * all the details need to be filled in. Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/themes/edit"]'
//         );

//         /**
//          * Product Carousel Section.
//          */
//         await adminPage
//             .locator('input[name="en[options][title]"]')
//             .fill(generateName());
//         await adminPage
//             .locator('select[name="en[options][filters][sort]"]')
//             .selectOption("name-asc");
//         await adminPage
//             .locator('select[name="en[options][filters][limit]"]')
//             .selectOption("12");

//         /**
//          * General Section.
//          */
//         // Clicking the status and verify the toggle state.
//         await adminPage.click('label[for="status"]');
//         const toggleInput = await adminPage.locator('input[name="status"]');
//         await expect(toggleInput).toBeChecked();

//         /**
//          * Save theme.
//          */
//         await adminPage.getByRole("button", { name: "Save" }).click();

//         await expect(
//             adminPage.getByText("Theme updated successfully")
//         ).toBeVisible();
//     });

//     test("should create a category carousel theme", async ({ adminPage }) => {
//         /**
//          * Reaching to the theme listing page.
//          */
//         await adminPage.goto("admin/settings/themes");

//         /**
//          * Opening create theme form in modal.
//          */
//         await adminPage.getByRole("button", { name: "Create Theme" }).click();
//         await adminPage.locator('input[name="name"]').fill(generateName());
//         await adminPage.locator('input[name="sort_order"]').fill("1");
//         await adminPage
//             .locator('select[name="type"]')
//             .selectOption("category_carousel");
//         await adminPage.locator('select[name="channel_id"]').selectOption("1");
//         await adminPage
//             .locator('select[name="theme_code"]')
//             .selectOption("default");
//         await adminPage.getByRole("button", { name: "Save Theme" }).click();

//         /**
//          * After creating the product, the page is redirected to the edit theme page, where
//          * all the details need to be filled in. Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/themes/edit"]'
//         );

//         /**
//          * Category Carousel Section.
//          */
//         await adminPage
//             .locator('select[name="en[options][filters][sort]"]')
//             .selectOption("asc");
//         await adminPage
//             .locator('input[name="en[options][filters][limit]"]')
//             .fill("10");

//         /**
//          * General Section.
//          */
//         // Clicking the status and verify the toggle state.
//         await adminPage.click('label[for="status"]');
//         const toggleInput = await adminPage.locator('input[name="status"]');
//         await expect(toggleInput).toBeChecked();

//         /**
//          * Save theme.
//          */
//         await adminPage.getByRole("button", { name: "Save" }).click();

//         await expect(
//             adminPage.getByText("Theme updated successfully")
//         ).toBeVisible();
//     });

//     test("should create a static content theme", async ({ adminPage }) => {
//         /**
//          * Reaching to the theme listing page.
//          */
//         await adminPage.goto("admin/settings/themes");

//         /**
//          * Opening create theme form in modal.
//          */
//         await adminPage.getByRole("button", { name: "Create Theme" }).click();
//         await adminPage.locator('input[name="name"]').fill(generateName());
//         await adminPage.locator('input[name="sort_order"]').fill("1");
//         await adminPage
//             .locator('select[name="type"]')
//             .selectOption("static_content");
//         await adminPage.locator('select[name="channel_id"]').selectOption("1");
//         await adminPage
//             .locator('select[name="theme_code"]')
//             .selectOption("default");
//         await adminPage.getByRole("button", { name: "Save Theme" }).click();

//         /**
//          * After creating the product, the page is redirected to the edit theme page, where
//          * all the details need to be filled in. Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/themes/edit"]'
//         );

//         /**
//          * Static Content Section.
//          */
//         // CodeMirror editor.
//         const content = generateDescription();
//         const codeMirrorTextarea = await adminPage.locator(
//             ".CodeMirror textarea"
//         );
//         await codeMirrorTextarea.focus();
//         await adminPage.keyboard.type(content);
//         const codeMirrorContent = await adminPage
//             .locator(".CodeMirror-code")
//             .innerText();
//         expect(codeMirrorContent).toContain(content);

//         /**
//          * General Section.
//          */
//         // Clicking the status and verify the toggle state.
//         await adminPage.click('label[for="status"]');
//         const toggleInput = await adminPage.locator('input[name="status"]');
//         await expect(toggleInput).toBeChecked();

//         /**
//          * Save theme.
//          */
//         await adminPage.getByRole("button", { name: "Save" }).click();

//         await expect(
//             adminPage.getByText("Theme updated successfully")
//         ).toBeVisible();
//     });

//     test("should create a image carousel theme", async ({ adminPage }) => {
//         /**
//          * Reaching to the theme listing page.
//          */
//         await adminPage.goto("admin/settings/themes");

//         /**
//          * Opening create theme form in modal.
//          */
//         await adminPage.getByRole("button", { name: "Create Theme" }).click();
//         await adminPage.locator('input[name="name"]').fill(generateName());
//         await adminPage.locator('input[name="sort_order"]').fill("1");
//         await adminPage
//             .locator('select[name="type"]')
//             .selectOption("image_carousel");
//         await adminPage.locator('select[name="channel_id"]').selectOption("1");
//         await adminPage
//             .locator('select[name="theme_code"]')
//             .selectOption("default");
//         await adminPage.getByRole("button", { name: "Save Theme" }).click();

//         /**
//          * After creating the product, the page is redirected to the edit theme page, where
//          * all the details need to be filled in. Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/themes/edit"]'
//         );

//         /**
//          * Slider Section.
//          */
//         // Clicking the add slider button and opening the modal.
//         await adminPage
//             .locator('div.secondary-button:has-text("Add Slider")')
//             .click();
//         await adminPage.locator('input[name="en[title]"]').fill(generateName());
//         await adminPage
//             .locator('input[name="en[link]"]')
//             .fill(generateHostname());

//         // Upload image.
//         const [fileChooser] = await Promise.all([
//             adminPage.waitForEvent("filechooser"),
//             adminPage
//                 .locator("label")
//                 .filter({ hasText: "Add Image png, jpeg, jpg" })
//                 .click(),
//         ]);
//         await fileChooser.setFiles(getImageFile());

//         // Saving and closing the add slider modal.
//         await adminPage.getByRole("button", { name: "Save" }).nth(1).click();

//         /**
//          * General Section.
//          */
//         // Clicking the status and verify the toggle state.
//         await adminPage.click('label[for="status"]');
//         const toggleInput = await adminPage.locator('input[name="status"]');
//         await expect(toggleInput).toBeChecked();

//         /**
//          * Save theme.
//          */
//         await adminPage.getByRole("button", { name: "Save" }).click();

//         await expect(
//             adminPage.getByText("Theme updated successfully")
//         ).toBeVisible();
//     });

//     test("should create a footer link theme", async ({ adminPage }) => {
//         /**
//          * Reaching to the theme listing page.
//          */
//         await adminPage.goto("admin/settings/themes");

//         /**
//          * Opening create theme form in modal.
//          */
//         await adminPage.getByRole("button", { name: "Create Theme" }).click();
//         await adminPage.locator('input[name="name"]').fill(generateName());
//         await adminPage.locator('input[name="sort_order"]').fill("1");
//         await adminPage
//             .locator('select[name="type"]')
//             .selectOption("footer_links");
//         await adminPage.locator('select[name="channel_id"]').selectOption("1");
//         await adminPage
//             .locator('select[name="theme_code"]')
//             .selectOption("default");
//         await adminPage.getByRole("button", { name: "Save Theme" }).click();

//         /**
//          * After creating the product, the page is redirected to the edit theme page, where
//          * all the details need to be filled in. Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/themes/edit"]'
//         );

//         /**
//          * Footer Links Section.
//          */
//         // Clicking the add link button and opening the modal.
//         await adminPage
//             .locator('div.secondary-button:has-text("Add Link")')
//             .click();
//         await adminPage.locator('select[name="column"]').selectOption("1");
//         await adminPage.locator('input[name="title"]').fill(generateName());
//         await adminPage.locator('input[name="url"]').fill(generateHostname());
//         await adminPage
//             .getByRole("textbox", { name: "Sort Order" })
//             .first()
//             .fill("1");

//         // Saving and closing the add link modal.
//         await adminPage.getByRole("button", { name: "Save" }).nth(1).click();

//         /**
//          * General Section.
//          */
//         // Clicking the status and verify the toggle state.
//         await adminPage.click('label[for="status"]');
//         const toggleInput = await adminPage.locator('input[name="status"]');
//         await expect(toggleInput).toBeChecked();

//         /**
//          * Save theme.
//          */
//         await adminPage.getByRole("button", { name: "Save" }).click();

//         await expect(
//             adminPage.getByText("Theme updated successfully")
//         ).toBeVisible();
//     });

//     test("should create a services content theme", async ({ adminPage }) => {
//         /**
//          * Reaching to the theme listing page.
//          */
//         await adminPage.goto("admin/settings/themes");

//         /**
//          * Opening create theme form in modal.
//          */
//         await adminPage.getByRole("button", { name: "Create Theme" }).click();
//         await adminPage.locator('input[name="name"]').fill(generateName());
//         await adminPage.locator('input[name="sort_order"]').fill("1");
//         await adminPage
//             .locator('select[name="type"]')
//             .selectOption("services_content");
//         await adminPage.locator('select[name="channel_id"]').selectOption("1");
//         await adminPage
//             .locator('select[name="theme_code"]')
//             .selectOption("default");
//         await adminPage.getByRole("button", { name: "Save Theme" }).click();

//         /**
//          * After creating the product, the page is redirected to the edit theme page, where
//          * all the details need to be filled in. Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/themes/edit"]'
//         );

//         /**
//          * Footer Links Section.
//          */
//         // Clicking the add services button and opening the modal.
//         await adminPage
//             .locator('div.secondary-button:has-text("Add Services")')
//             .click();
//         await adminPage
//             .getByRole("textbox", { name: "Title" })
//             .fill(generateName());
//         await adminPage
//             .getByRole("textbox", { name: "Description" })
//             .fill(generateDescription());
//         await adminPage
//             .getByRole("textbox", { name: "Service Icon Class" })
//             .fill("icon-truck");

//         // Saving and closing the add services button.
//         await adminPage.getByRole("button", { name: "Save" }).nth(1).click();

//         /**
//          * General Section.
//          */
//         // Clicking the status and verify the toggle state.
//         await adminPage.click('label[for="status"]');
//         const toggleInput = await adminPage.locator('input[name="status"]');
//         await expect(toggleInput).toBeChecked();

//         /**
//          * Save theme.
//          */
//         await adminPage.getByRole("button", { name: "Save" }).click();

//         await expect(
//             adminPage.getByText("Theme updated successfully")
//         ).toBeVisible();
//     });

//     test("should delete a theme", async ({ adminPage }) => {
//         /**
//          * Reaching to the theme listing page.
//          */
//         await adminPage.goto("admin/settings/themes");

//         /**
//          * Delete the first theme.
//          */
//         await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
//         const iconDelete = await adminPage.$$(
//             "span.cursor-pointer.icon-delete"
//         );
//         await iconDelete[0].click();

//         await adminPage.waitForSelector("text=Are you sure");
//         const agreeButton = await adminPage.locator(
//             'button.primary-button:has-text("Agree")'
//         );

//         if (await agreeButton.isVisible()) {
//             await agreeButton.click();
//         } else {
//             console.error("Agree button not found or not visible.");
//         }

//         await expect(
//             adminPage.getByText("Theme deleted successfully")
//         ).toBeVisible();
//     });
// });
