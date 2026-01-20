// import { test, expect } from "../../setup";
// import {
//     generateName,
//     generateDescription,
//     generateSlug,
//     generateFullName,
//     generateEmail,
//     generatePhoneNumber,
// } from "../../utils/faker";

// test.describe("inventory source management", () => {
//     test("should create a inventory source", async ({ adminPage }) => {
//         /**
//          * Navigate to the create inventory source page.
//          */
//         await adminPage.goto(
//             `admin/settings/inventory-sources`
//         );
//         await adminPage
//             .getByRole("link", { name: "Create Inventory Source" })
//             .click();

//         /**
//          * Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/inventory-sources/create"]'
//         );

//         /**
//          * General Section.
//          */
//         await adminPage
//             .getByRole("textbox", { name: "Code", exact: true })
//             .fill(generateSlug("_"));
//         await adminPage.locator("#name").fill(generateName());
//         await adminPage
//             .getByRole("textbox", { name: "Description" })
//             .fill(generateDescription());

//         /**
//          * Contact Information Section.
//          */
//         await adminPage.locator("#contact_name").fill(generateFullName());
//         await adminPage
//             .getByRole("textbox", { name: "Email" })
//             .fill(generateEmail());
//         await adminPage
//             .getByRole("textbox", { name: "Contact Number" })
//             .fill(generatePhoneNumber());
//         await adminPage
//             .getByRole("textbox", { name: "Fax" })
//             .fill(generatePhoneNumber());

//         /**
//          * Source Address Section.
//          */
//         await adminPage.locator("#country").selectOption("IN");
//         await adminPage.locator("#state").selectOption("DL");
//         await adminPage
//             .getByRole("textbox", { name: "City" })
//             .fill("New Delhi");
//         await adminPage.getByRole("textbox", { name: "Street" }).fill("Dwarka");
//         await adminPage
//             .getByRole("textbox", { name: "Postcode" })
//             .fill("110045");

//         /**
//          * Settings Section.
//          */
//         // Clicking the status and verify the toggle state.
//         await adminPage.click('label[for="status"]');
//         const toggleInput = await adminPage.getByPlaceholder("Status");
//         await expect(toggleInput).toBeChecked();

//         /**
//          * Save Inventory Source.
//          */
//         await adminPage
//             .getByRole("button", { name: "Save Inventory Sources" })
//             .click();

//         await expect(
//             adminPage.getByText("Inventory Source Created Successfully")
//         ).toBeVisible();
//     });

//     test("should edit a inventory source", async ({ adminPage }) => {
//         /**
//          * Navigate to the inventory source listing page.
//          */
//         await adminPage.goto(
//             `admin/settings/inventory-sources`
//         );
//         await adminPage
//             .getByRole("link", { name: "Create Inventory Source" })
//             .waitFor({ state: "visible" });

//         /**
//          * Edit the first inventory source.
//          */
//         await adminPage.waitForSelector("span.cursor-pointer.icon-edit", {
//             state: "visible",
//         });
//         const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
//         await iconEdit[0].click();

//         /**
//          * Waiting for the main form to be visible.
//          */
//         await adminPage.waitForSelector(
//             'form[action*="/settings/inventory-sources/edit"]'
//         );

//         // Content will be added here. Currently just checking the general save button.

//         /**
//          * Save Inventory Source.
//          */
//         await adminPage.click('button:has-text("Save Inventory Sources")');

//         await expect(
//             adminPage.getByText("Inventory Sources Updated Successfully")
//         ).toBeVisible();
//     });

//     test("should delete a inventory source", async ({ adminPage }) => {
//         /**
//          * Navigate to the inventory source listing page.
//          */
//         await adminPage.goto(
//             `admin/settings/inventory-sources`
//         );
//         await adminPage
//             .getByRole("link", { name: "Create Inventory Source" })
//             .waitFor({ state: "visible" });

//         /**
//          * Delete the first inventory source.
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
//             adminPage.getByText("Inventory Sources Deleted Successfully")
//         ).toBeVisible();
//     });
// });
