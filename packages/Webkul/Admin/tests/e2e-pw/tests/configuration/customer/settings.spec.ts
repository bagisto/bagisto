// import { test, expect } from "../../../setup";

// export async function clickIfNotEnabled(adminPage, selector: string) {
//     const element = adminPage.locator(selector);

//     // Wait for the element to be visible and attached to the DOM
//     await element.waitFor({ state: "visible" });

//     // Check if the button/element is enabled or disabled
//     const isChecked = await element.isChecked();

//     // If the element is disabled, click it to enable
//     if (!isChecked) {
//         await adminPage.locator(selector).click();
//     }
// }

// test.describe("settings configuration", () => {
//     test.beforeEach(async ({ adminPage }) => {
//         /**
//          * Navigate to the configuration page.
//          */
//         await adminPage.goto("admin/configuration/customer/settings");
//     });

//     test("should enable the wishlist feature", async ({ adminPage }) => {
//         await adminPage.click(
//             'label[for="customer[settings][wishlist][wishlist_option]"]'
//         );

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     test("should update the redirect page option after the login", async ({
//         adminPage,
//     }) => {
//         await adminPage.selectOption(
//             'select[name="customer[settings][login_options][redirected_to_page]"]',
//             "home"
//         );
//         const weightUnitSelect = adminPage.locator(
//             'select[name="customer[settings][login_options][redirected_to_page]"]'
//         );
//         await expect(weightUnitSelect).toHaveValue("home");

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     test("should update default customer group and enabling the newsletter subscription option during sign-up", async ({
//         adminPage,
//     }) => {
//         await adminPage.selectOption(
//             'select[name="customer[settings][create_new_account_options][default_group]"]',
//             "general"
//         );
//         const defaultGroup = adminPage.locator(
//             'select[name="customer[settings][create_new_account_options][default_group]"]'
//         );
//         await expect(defaultGroup).toHaveValue("general");

//         await adminPage.click(
//             'label[for="customer[settings][create_new_account_options][news_letter]"]'
//         );

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     test("should update the newsletter subscription option", async ({
//         adminPage,
//     }) => {
//         const isChecked = await adminPage
//             .locator(
//                 'input[type="checkbox"][name="customer[settings][newsletter][subscription]"]'
//             )
//             .isChecked();

//         if (!isChecked) {
//             await adminPage.click(
//                 'label[for="customer[settings][newsletter][subscription]"]'
//             );
//         }

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();

//         await expect(
//             await adminPage.locator(
//                 'input[type="checkbox"][name="customer[settings][newsletter][subscription]"]'
//             )
//         ).toBeChecked();
//     });

//     test.describe("Social login configuration", () => {
//         test("should enable the Github login ", async ({ adminPage }) => {
//             await clickIfNotEnabled(
//                 adminPage,
//                 'label[for="customer[settings][social_login][enable_github]"]'
//             );
//             await adminPage.click(
//                 'button[type="submit"].primary-button:visible'
//             );

//             /**
//              * Verify the configuration is saved.
//              */
//             await expect(adminPage.locator("#app")).toContainText(
//                 "Configuration saved successfully"
//             );

//             /**
//              * Verify the Github login is enabled.
//              */
//             await adminPage.goto("customer/login");
//             const rect = adminPage.locator(
//                 'rect[width="40"][height="40"][rx="20"][fill="black"]'
//             );
//             await expect(rect).toBeVisible();
//             await rect.click();
//         });

//         test("should enable the linkedin login ", async ({ adminPage }) => {
//             await clickIfNotEnabled(
//                 adminPage,
//                 'label[for="customer[settings][social_login][enable_linkedin-openid]"]'
//             );
//             await adminPage.click(
//                 'button[type="submit"].primary-button:visible'
//             );

//             /**
//              * Verify the configuration is saved.
//              */
//             await expect(
//                 adminPage.locator('p:has-text("Configuration saved successfully")')
//               ).toBeVisible();

//             /**
//              * Verify the Linkedin login is enabled.
//              */
//             await adminPage.goto("customer/login");
//             const rect = adminPage.locator(
//                 'rect[width="40"][height="40"][rx="20"][fill="#1D8DEE"]'
//             );
//             await expect(rect).toBeVisible();
//             await rect.click();
//         });

//         test("should enable the google login ", async ({ adminPage }) => {
//             await clickIfNotEnabled(
//                 adminPage,
//                 'label[for="customer[settings][social_login][enable_google]"]'
//             );
//             await adminPage.click(
//                 'button[type="submit"].primary-button:visible'
//             );

//             /**
//              * Verify the configuration is saved.
//              */
//             await expect(
//                 adminPage.locator('p:has-text("Configuration saved successfully")')
//               ).toBeVisible();
//             /**
//              * Verify the Google login is enabled.
//              */
//             await adminPage.goto("customer/login");
//             const rect = adminPage.locator(
//                 'rect[width="40"][height="40"][rx="20"][fill="white"]'
//             );
//             await expect(rect).toBeVisible();
//             await rect.click();
//         });

//         test("should enable the twitter login ", async ({ adminPage }) => {
//             await clickIfNotEnabled(
//                 adminPage,
//                 'label[for="customer[settings][social_login][enable_twitter]"]'
//             );
//             await adminPage.click(
//                 'button[type="submit"].primary-button:visible'
//             );

//             /**
//              * Verify the configuration is saved.
//              */
//             await expect(
//                 adminPage.locator('p:has-text("Configuration saved successfully")')
//               ).toBeVisible();

//             /**
//              * Verify the Twitter login is enabled.
//              */
//             await adminPage.goto("customer/login");
//             const rect = adminPage.locator(
//                 'rect[width="40"][height="40"][rx="20"][fill="#1A1A1A"]'
//             );
//             await expect(rect).toBeVisible();
//             await rect.click();
//         });

//         test("should enable the facebook login ", async ({ adminPage }) => {
//             await clickIfNotEnabled(
//                 adminPage,
//                 'label[for="customer[settings][social_login][enable_facebook]"]'
//             );
//             await adminPage.click(
//                 'button[type="submit"].primary-button:visible'
//             );

//             /**
//              * Verify the configuration is saved.
//              */
//             await expect(
//                 adminPage.locator('p:has-text("Configuration saved successfully")')
//               ).toBeVisible();

//             /**
//              * Verify the Facebook login is enabled.
//              */
//             await adminPage.goto("customer/login");
//             const rect = adminPage.locator(
//                 'rect[width="40"][height="40"][rx="20"][fill="#1877F2"]'
//             );
//             await expect(rect).toBeVisible();
//             await rect.click();
//         });
//     });
// });
