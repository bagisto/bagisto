import { test, expect } from "../setup";
import { loginAsCustomer, addAddress, addWishlist } from "../utils/customer";
import { generatePhoneNumber, generateEmail } from "../utils/faker";
import { downloadableOrder, generateOrder } from "../utils/order";

function generateRandomDate() {
    const today = new Date();
    const endDate = new Date(
        today.getFullYear() - 1,
        today.getMonth(),
        today.getDate()
    );
    const startDate = new Date(1925, 0, 1);

    const randomDate = new Date(
        startDate.getTime() +
            Math.random() * (endDate.getTime() - startDate.getTime())
    );

    const year = randomDate.getFullYear();
    const month = String(randomDate.getMonth() + 1).padStart(2, "0");
    const day = String(randomDate.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
}

test("should edit a profile", async ({ page }) => {
    const credentials = await loginAsCustomer(page);

    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Profile" }).click();
    await page.getByRole("link", { name: "Edit" }).click();
    await page.getByRole("textbox", { name: "First Name" }).click();
    await page
        .getByRole("textbox", { name: "First Name" })
        .fill(credentials.firstName);
    await page.getByRole("textbox", { name: "Last Name" }).click();
    await page
        .getByRole("textbox", { name: "Last Name" })
        .fill(credentials.lastName);
    await page.getByPlaceholder("Email", { exact: true }).click();
    await page
        .getByPlaceholder("Email", { exact: true })
        .fill(credentials.email);
    await page.getByPlaceholder("Phone").click();
    await page.getByPlaceholder("Phone").fill(generatePhoneNumber());
    await page.getByLabel("shop::app.customers.account.").selectOption("Male");
    await page.getByRole("textbox", { name: "Date of Birth" }).click();
    await page
        .getByRole("textbox", { name: "Date of Birth" })
        .fill(generateRandomDate());
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

test("should be able to reorder", async ({ page }) => {
    await generateOrder(page);

    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Orders", exact: true }).click();
    await page.locator("div").locator("span.icon-eye").first().click();
    await page.getByRole("link", { name: "Reorder" }).click();

    await page.getByRole("button", { name: "Update Cart" }).click();

    await expect(
        page.getByText("Quantity updated successfully").first()
    ).toBeVisible();
});

test("should be able to cancel order", async ({ page }) => {
    await generateOrder(page);

    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Orders", exact: true }).click();
    await page.locator("div").locator("span.icon-eye").first().click();
    await page.getByRole("link", { name: "Cancel" }).click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();
    
    await page.waitForTimeout(4000);
    await expect(
        page
            .getByRole("paragraph")
            .filter({ hasText: "Your order has been canceled" })
    ).toBeVisible();
});

test("should be able to print invoice", async ({ page }) => {
    await generateOrder(page);

    /**
     * Login to admin panel.
     */
    const adminCredentials = {
        email: "admin@example.com",
        password: "admin123",
    };
    await page.goto("admin/login");
    await page.getByPlaceholder("Email Address").click();
    await page.getByPlaceholder("Email Address").fill(adminCredentials.email);
    await page.getByPlaceholder("Password").click();
    await page.getByPlaceholder("Password").fill(adminCredentials.password);
    await page.getByRole("button", { name: "Sign In" }).click();

    /**
     * Create invoice
     */
    await page.goto("admin/sales/orders");
    await page.locator(".row > div:nth-child(4) > a").first().click();
    await page.getByText("Invoice", { exact: true }).click();
    await page.locator("#can_create_transaction").nth(1).click();
    await page.getByRole("button", { name: "Create Invoice" }).click();
    await expect(
        page.getByText("Invoice created successfully Close")
    ).toBeVisible();
    await expect(
        page.locator("span").filter({ hasText: "Processing" })
    ).toBeVisible();

    /**
     * check invoice to customer side.
     */
    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Orders", exact: true }).click();
    await page.locator("div").locator("span.icon-eye").first().click();
    await page.getByRole("button", { name: "Invoices" }).click();
    const downloadPromise = page.waitForEvent("download");
    await page.getByRole("link", { name: " Print" }).click();
    await downloadPromise;
});

test("should able to download downloadable orders", async ({ shopPage }) => {
    /**
     * Login to admin panel.
     */
    const adminCredentials = {
        email: "admin@example.com",
        password: "admin123",
    };

    await shopPage.goto("admin/login");
    await shopPage.getByPlaceholder("Email Address").click();
    await shopPage
        .getByPlaceholder("Email Address")
        .fill(adminCredentials.email);
    await shopPage.getByPlaceholder("Password").click();
    await shopPage.getByPlaceholder("Password").fill(adminCredentials.password);
    await shopPage.getByRole("button", { name: "Sign In" }).click();

    /**
     * Create downloadable product.
     */
    const productName = await downloadableOrder(shopPage);

    /**
     * Go to shop for download a product.
     */
    await shopPage.goto("");
    await shopPage.getByLabel("Profile").click();
    await shopPage.getByRole("link", { name: "Profile", exact: true }).click();
    await shopPage
        .getByRole("link", { name: " Downloadable Products " })
        .click();
    const popupPromise = shopPage.waitForEvent('popup').catch(() => null);
    const downloadPromise = shopPage.waitForEvent('download').catch(() => null);
    await shopPage.getByRole("link", { name: productName }).click();
    const result = await Promise.race([popupPromise, downloadPromise]);
});

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
    await page.getByRole("button", { name: "Move To Cart" }).nth(1).click();

    await expect(
        page
            .getByRole("paragraph")
            .filter({ hasText: "Item Successfully Moved to Cart" })
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
