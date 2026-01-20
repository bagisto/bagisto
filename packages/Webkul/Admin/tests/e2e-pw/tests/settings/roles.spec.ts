// import { test, expect } from "../../setup";
// import { generateName, generateDescription } from "../../utils/faker";

// test.describe("role management", () => {
//     test("create role", async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/roles');
//         await adminPage.click("a.primary-button:visible");

//         await adminPage.selectOption("#permission_type", "all");
//         await adminPage.fill('input[name="name"]', generateName());
//         await adminPage.fill(
//             'textarea[name="description"]',
//             generateDescription()
//         );

//         await adminPage.click(
//             'button.primary-button:visible:has-text("Save Role")'
//         );

//         await expect(
//             adminPage.getByText("Roles Created Successfully")
//         ).toBeVisible();
//     });

//     test("edit role", async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/roles');
//         await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
//         const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
//         await iconEdit[0].click();

//         // Content will be added here. Currently just checking the general save button.

//         await adminPage.click(
//             'button.primary-button:visible:has-text("Save Role")'
//         );

//         await expect(
//             adminPage.getByText("Roles is updated successfully")
//         ).toBeVisible();
//     });

//     test("delete role", async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/roles');

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
//             adminPage.getByText("Roles is deleted successfully")
//         ).toBeVisible();
//     });
// });
