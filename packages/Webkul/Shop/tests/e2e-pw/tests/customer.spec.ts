import { test, expect } from "../setup";
import { loginAsCustomer, addAddress, addWishlist } from "../utils/customer";
import { generatePhoneNumber, generateEmail } from "../utils/faker";

test("should edit a profile", async ({ page }) => {
    const credentials = await loginAsCustomer(page);

    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Profile" }).click();
    await page.getByRole("link", { name: "Edit" }).click();
    await page.getByPlaceholder("First Name").click();
    await page.getByPlaceholder("First Name").fill(credentials.firstName);
    await page.getByPlaceholder("Last Name").click();
    await page.getByPlaceholder("Last Name").fill(credentials.lastName);
    await page.getByPlaceholder("Email", { exact: true }).click();
    await page
        .getByPlaceholder("Email", { exact: true })
        .fill(credentials.email);
    await page.getByPlaceholder("Phone").click();
    await page.getByPlaceholder("Phone").fill(generatePhoneNumber());
    await page.getByLabel("shop::app.customers.account.").selectOption("Male");
    await page.getByPlaceholder("Date of Birth").click();
    const date = new Date();
    date.setFullYear(date.getFullYear() - 1);
    const formattedDate = date.toISOString().split("T")[0];
    await page.getByPlaceholder("Date of Birth").fill(formattedDate);
    await page.getByRole("button", { name: "Save" }).click();

    await expect(
        page.getByText("Profile updated successfully").first()
    ).toBeVisible();
});

test("should add an address", async ({ page }) => {
    await loginAsCustomer(page);

    await addAddress(page);
});

test("should edit an address", async ({ page }) => {
    await loginAsCustomer(page);

    await addAddress(page);

    await page.getByLabel("More Options").first().click();
    await page.getByRole("link", { name: "Edit" }).click();
    await page.getByPlaceholder("Company Name").click();
    await page.getByPlaceholder("Company Name").fill("webkul1");
    await page.getByPlaceholder("First Name").click();
    await page.getByPlaceholder("First Name").click();
    await page.getByPlaceholder("First Name").fill("User1");
    await page.getByPlaceholder("Last Name").click();
    await page.getByPlaceholder("Last Name").fill("Demo1");
    await page.getByPlaceholder("Email", { exact: true }).click();
    await page.getByPlaceholder("Email", { exact: true }).fill(generateEmail());
    await page.getByPlaceholder("Vat ID").click();
    await page.getByPlaceholder("Street Address").click();
    await page.getByPlaceholder("Street Address").fill("123ghds1");
    await page.getByLabel("Country").selectOption("IN");
    await page.locator("#state").selectOption("TR");
    await page.getByPlaceholder("City").click();
    await page.getByPlaceholder("City").fill("noida");
    await page.getByPlaceholder("Post Code").click();
    await page.getByPlaceholder("Post Code").fill("201301");
    await page.getByPlaceholder("Phone").click();
    await page.getByPlaceholder("Phone").fill("9876543219");
    await page.getByRole("button", { name: "Update" }).click();

    await expect(
        page.getByText("Address updated successfully.").first()
    ).toBeVisible();
});

test("should set the default address", async ({ page }) => {
    await loginAsCustomer(page);

    await addAddress(page);

    await page.getByLabel("More Options").first().click();
    await page.getByRole("button", { name: "Set as Default" }).click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(page.getByText("Default Address").first()).toBeVisible();
});

test("should delete the address", async ({ page }) => {
    await loginAsCustomer(page);

    await addAddress(page);

    await page.getByLabel("More Options").first().click();
    await page.getByRole("link", { name: "Delete" }).click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page.getByText("Address successfully deleted").first()
    ).toBeVisible();
});

// for these testing we need order helper.. first we create order then we will test the reorder,
// cancel order, print invoice, downloadable orders, etc and also these test needs improvements...
// test("Reorder", async ({ page }) => {
//     await loginAsCustomer(page);

//     await page.getByLabel("Profile").click();
//     await page.getByRole("link", { name: "Orders", exact: true }).click();
//     await page.locator("div").locator("span.icon-eye").first().click();
//     await page.getByRole("link", { name: "Reorder" }).click();

//     await page.getByRole("button", { name: "Update Cart" }).click();

//     await expect(
//         page.getByText("Quantity updated successfully").first()
//     ).toBeVisible();
// });

// test("Cancel Order", async ({ page }) => {
//     await loginAsCustomer(page);

//     await page.getByLabel("Profile").click();
//     await page.getByRole("link", { name: "Orders", exact: true }).click();
//     await page.locator("div").locator("span.icon-eye").first().click();
//     await page.getByRole("link", { name: "Cancel" }).click();
//     await page.getByRole("button", { name: "Agree", exact: true }).click();

//     await expect(
//         page.getByText("Your order has been canceled").first()
//     ).toBeVisible();
// });

// test("Print Invoice", async ({ page }) => {
//     await loginAsCustomer(page);

//     await page.getByLabel("Profile").click();
//     await page.getByRole("link", { name: "Orders", exact: true }).click();
//     await page.locator("div").locator("span.icon-eye").first().click();
//     await page.getByRole("button", { name: "Invoices" }).click();
//     const downloadPromise = page.waitForEvent("download");
//     await page.getByRole("link", { name: " Print" }).click();
//     const download = await downloadPromise;
// });

// test("Downloadable Orders", async ({ page }) => {
//     await loginAsCustomer(page);

//     await page.getByLabel("Profile").click();
//     await page.getByRole("link", { name: "Profile", exact: true }).click();
//     await page.getByRole("link", { name: " Downloadable Products " }).click();
//     const page2Promise = page.waitForEvent("popup");
//     const download1Promise = page.waitForEvent("download");
//     await page.getByRole("link", { name: "file", exact: true }).click();
//     const page2 = await page2Promise;
//     const download1 = await download1Promise;
// });

// need wishlist helper first...
test("should add wishlist to cart", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);

    await page.locator(".action-items > span").first().click();
    await page
        .locator(
            "div:nth-child(9) > div:nth-child(2) > div:nth-child(2) > .-mt-9 > .action-items > span"
        )
        .first()
        .click();
    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Wishlist", exact: true }).click();
    await page.getByRole("button", { name: "Move To Cart" }).first().click();

    await expect(
        page.getByText("Item Successfully Moved to Cart").first()
    ).toBeVisible();
});

test("should remove product from wishlist", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);

    await page
        .locator(
            "div:nth-child(9) > div:nth-child(2) > div:nth-child(3) > .-mt-9 > .action-items > span"
        )
        .first()
        .click();
    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Wishlist", exact: true }).click();
    await page.locator(".max-md\\:hidden > .flex").first().click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page.getByText("Item Successfully Removed From Wishlist").first()
    ).toBeVisible();
});

test("should clear all wishlist", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);

    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Wishlist", exact: true }).click();
    await page.getByText("Delete All", { exact: true }).click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page.getByText("Item Successfully Removed From Wishlist").first()
    ).toBeVisible();
});

test("should change password", async ({ page }) => {
    const credentials = await loginAsCustomer(page);

    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Profile" }).click();
    await page.getByRole("link", { name: "Edit" }).click();
    await page.getByPlaceholder("Phone").click();
    await page.getByPlaceholder("Phone").fill(generatePhoneNumber());
    await page.getByLabel("shop::app.customers.account.").selectOption("Male");
    await page.getByPlaceholder("Current Password").click();
    await page.getByPlaceholder("Current Password").fill(credentials.password);
    await page.getByPlaceholder("New Password").click();
    await page.getByPlaceholder("New Password").fill("testUser@1234");
    await page.getByPlaceholder("Confirm Password").click();
    await page.getByPlaceholder("Confirm Password").fill("testUser@1234");
    await page.getByRole("button", { name: "Save" }).click();

    await expect(
        page.getByText("Profile updated successfully").first()
    ).toBeVisible();
});

test("should delete a profile", async ({ page }) => {
    const credentials = await loginAsCustomer(page);

    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Profile" }).click();
    await page.getByText("Delete Profile").first().click();
    await page.getByPlaceholder("Enter your password").click();
    await page
        .getByPlaceholder("Enter your password")
        .fill(credentials.password);
    await page.getByRole("button", { name: "Delete" }).click();

    await expect(
        page.getByText("Customer deleted successfully").first()
    ).toBeVisible();
});
