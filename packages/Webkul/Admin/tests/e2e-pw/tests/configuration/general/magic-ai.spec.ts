// import { test, expect } from "../../../setup";
// import { generateName, generateDescription, generateHostname } from "../../../utils/faker";

// test.describe("magic ai configuration", () => {
//     test.beforeEach(async ({ adminPage }) => {
//         await adminPage.goto("admin/configuration/general/magic_ai");
//     });

//     test("should update the openai credential", async ({ adminPage }) => {
//         const isChecked = await adminPage
//             .locator(
//                 'input[type="checkbox"][name="general[magic_ai][settings][enabled]"]'
//             )
//             .isChecked();

//         if (!isChecked) {
//             await adminPage.click(
//                 'label[for="general[magic_ai][settings][enabled]"]'
//             );
//         }

//         /**
//          * Fill the Open AI credentials.
//          */
//         await adminPage
//             .locator('input[name="general[magic_ai][settings][api_key]"]')
//             .fill(generateDescription(20));
//         await adminPage
//             .locator('input[name="general[magic_ai][settings][organization]"]')
//             .fill(generateName());
//         await adminPage
//             .locator('input[name="general[magic_ai][settings][api_domain]"]')
//             .fill(generateHostname());

//         /**
//          * Save the configuration.
//          */
//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();

//         await expect(
//             await adminPage.locator(
//                 'input[type="checkbox"][name="general[magic_ai][settings][enabled]"]'
//             )
//         ).toBeChecked();
//     });

//     test("should manage content using ai", async ({ adminPage }) => {
//         const isChecked = await adminPage
//             .locator(
//                 'input[type="checkbox"][name="general[magic_ai][content_generation][enabled]"]'
//             )
//             .isChecked();

//         if (!isChecked) {
//             await adminPage.click(
//                 'label[for="general[magic_ai][content_generation][enabled]"]'
//             );
//         }

//         await adminPage
//             .locator(
//                 'textarea[name="general[magic_ai][content_generation][product_short_description_prompt]"]'
//             )
//             .fill(generateDescription(100));
//         await adminPage
//             .locator(
//                 'textarea[name="general[magic_ai][content_generation][product_description_prompt]"]'
//             )
//             .fill(generateDescription(100));
//         await adminPage
//             .locator(
//                 'textarea[name="general[magic_ai][content_generation][category_description_prompt]"]'
//             )
//             .fill(generateDescription(100));
//         await adminPage
//             .locator(
//                 'textarea[name="general[magic_ai][content_generation][cms_page_content_prompt]"]'
//             )
//             .fill(generateDescription(100));

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     test("should enable image generation using ai", async ({ adminPage }) => {
//         const isChecked = await adminPage
//             .locator(
//                 'input[type="checkbox"][name="general[magic_ai][image_generation][enabled]"]'
//             )
//             .isChecked();

//         if (!isChecked) {
//             await adminPage.click(
//                 'label[for="general[magic_ai][image_generation][enabled]"]'
//             );
//         }

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     test("should enable review translation using ai", async ({ adminPage }) => {
//         const isChecked = await adminPage
//             .locator(
//                 'input[type="checkbox"][name="general[magic_ai][review_translation][enabled]"]'
//             )
//             .isChecked();

//         if (!isChecked) {
//             await adminPage.click(
//                 'label[for="general[magic_ai][review_translation][enabled]"]'
//             );
//         }

//         /**
//          * Selecting the model.
//          */
//         await adminPage.selectOption(
//             'select[name="general[magic_ai][review_translation][model]"]',
//             "gemini-2.0-flash"
//         );

//         /**
//          * Saving configuration.
//          */
//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();

//         await expect(
//             await adminPage.locator(
//                 'input[type="checkbox"][name="general[magic_ai][review_translation][enabled]"]'
//             )
//         ).toBeChecked();
//     });

//     test("should craft a personalized checkout message for customers using AI", async ({
//         adminPage,
//     }) => {
//         const isChecked = await adminPage
//             .locator(
//                 'input[type="checkbox"][name="general[magic_ai][checkout_message][enabled]"]'
//             )
//             .isChecked();

//         if (!isChecked) {
//             await adminPage.click(
//                 'label[for="general[magic_ai][checkout_message][enabled]"]'
//             );
//         }

//         /**
//          * Fill the form.
//          */
//         await adminPage.selectOption(
//             'select[name="general[magic_ai][checkout_message][model]"]',
//             "gemini-2.0-flash"
//         );

//         await adminPage
//             .locator(
//                 'textarea[name="general[magic_ai][checkout_message][prompt]"]'
//             )
//             .fill(generateDescription(100));

//         /**
//          * Save the configuration.
//          */
//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();

//         await expect(
//             adminPage.locator(
//                 'select[name="general[magic_ai][checkout_message][model]"]'
//             )
//         ).toHaveValue("gemini-2.0-flash");
//     });
// });
