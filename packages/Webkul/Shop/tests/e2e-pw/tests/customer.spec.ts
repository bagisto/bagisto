import { test, expect } from "../setup";
import { loginAsCustomer, addAddress, addWishlist } from "../utils/customer";
import {loginAsAdmin} from "../utils/admin";
import {
    generateFirstName,
    generateLastName,
    generatePhoneNumber,
    generateEmail,
} from "../utils/faker";
import { downloadableOrder } from "../utils/order";
import { ProductCreation } from "../pages/product";
import path from "path";
import { fileURLToPath } from "url";
import { generateName, generateDescription, generateSKU } from "../utils/faker";

async function createSimpleProduct(adminPage) {
    /**
     * Main product data which we will use to create the product.
     */
    const product = {
        name: `simple-${Date.now()}`,
        sku: generateSKU(),
        productNumber: generateSKU(),
        shortDescription: generateDescription(),
        description: generateDescription(),
        price: "199",
        weight: "25",
    };

    /**
     * Reaching to the create product page.
     */
    await loginAsAdmin(adminPage);
    await adminPage.goto("admin/catalog/products");
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Create Product")',
    );
    await adminPage.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await adminPage.locator('select[name="type"]').selectOption("simple");
    await adminPage
        .locator('select[name="attribute_family_id"]')
        .selectOption("1");
    await adminPage.locator('input[name="sku"]').fill(generateSKU());
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Save Product")',
    );

    /**
     * Waiting for the main form to be visible.
     */
    await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

    /**
     * General Section.
     */
    await adminPage.locator("#product_number").fill(product.productNumber);
    await adminPage.locator("#name").fill(product.name);
    const name = await adminPage.locator('input[name="name"]').inputValue();

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription,
    );
    await adminPage.fillInTinymce("#description_ifr", product.description);

    /**
     * Meta Description Section.
     */
    await adminPage.locator("#meta_title").fill(product.name);
    await adminPage.locator("#meta_keywords").fill(product.name);
    await adminPage.locator("#meta_description").fill(product.shortDescription);

    /**
     * Image Section.
     */
    // Will add images later.

    /**
     * Price Section.
     */
    await adminPage.locator("#price").fill(product.price);

    /**
     * Shipping Section.
     */
    await adminPage.locator("#weight").fill(product.weight);

    /**
     * Inventories Section.
     */
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("5000");

    /**
     * Saving the product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator("#app")).toContainText(
        /product updated successfully/i,
    );

    /**
     * Checking the product in the list.
     */
    await adminPage.goto("admin/catalog/products");
    await expect(
        adminPage
            .locator("p.break-all.text-base")
            .filter({ hasText: product.name }),
    ).toBeVisible();
}

 async function generateOrder(page) {
    /**
     * Customer login.
     */
    await loginAsCustomer(page);

    /**
     * Fill customer default address.
     */
    await addAddress(page);

    /**
     * Go to the shop to buy a product.
     */
    await page.goto("");
    await page.waitForLoadState("networkidle");
        await page.getByPlaceholder("Search products here").fill("simple");
    await page.getByPlaceholder("Search products here").press("Enter");
    await page.getByRole("button", { name: "Add To Cart" }).first().click();
    await expect(page.locator("#app")).toContainText("Item Added Successfully");
    await page.waitForTimeout(2000);
    await page.getByRole("button", { name: "Shopping Cart" }).click();
    await page.getByRole("link", { name: "Continue to Checkout" }).click();
    await page
        .locator(
            'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]'
        )
        .nth(0)
        .click();
    await page.getByRole("button", { name: "Proceed" }).click();
    await page.waitForTimeout(2000);

    /**
     * Choose shipping method.
     */
    await page.waitForSelector("text=Free Shipping");
    await page.getByText("Free Shipping").first().click();
    await page.waitForTimeout(2000);

    /**
     * Choose payment option.
     */
    await page.waitForSelector("text=Cash On Delivery");
    await page.getByText("Cash On Delivery").first().click();
    await page.waitForTimeout(2000);

    /**
     * Place order.
     */
    await page.getByRole("button", { name: "Place Order" }).click();
    await page.waitForTimeout(2000);
    await page.waitForSelector("text=Thank you for your order!");
    await expect(page.locator("text=Thank you for your order!")).toBeVisible();
}

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const imagePath = path.resolve(__dirname, "../data/images/images.jpeg");

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

test("should display correct message when email verfication is off", async ({
    page,
}) => {
    /**
     * Customer registration
     */
    const credentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Sign Up" }).click();
    await page.waitForLoadState("networkidle");
    await page.getByPlaceholder("First Name").fill(credentials.firstName);
    await page.getByPlaceholder("Last Name").fill(credentials.lastName);
    await page.getByPlaceholder("email@example.com").fill(credentials.email);
    await page
        .getByPlaceholder("Password", { exact: true })
        .fill(credentials.password);
    await page.getByPlaceholder("Confirm Password").fill(credentials.password);

    const agreementLocator = page.locator("#agreement").nth(1);

    const isVisible = await agreementLocator.isVisible();

    if (isVisible) {
        await page.getByText("I agree with this statement.").click();
    }

    await page
        .locator("#main form div")
        .filter({ hasText: "Subscribe to newsletter" })
        .locator("label")
        .first()
        .click();
    await page.waitForTimeout(1000);
    await page.getByRole("button", { name: "Register" }).click();

    await expect(
        page.getByText("Account created successfully.").first()
    ).toBeVisible();
});

test("should display correct message when email verfication is on", async ({
    page,
}) => {
    await page.goto("admin/configuration/customer/settings");
    await page
        .getByRole("textbox", { name: "Email Address" })
        .fill("admin@example.com");
    await page.getByRole("textbox", { name: "Password" }).fill("admin123");
    await page.waitForTimeout(1000);
    await page.getByRole("button", { name: "Sign In" }).click();
    await page.waitForLoadState("networkidle");
    const toggle = page.locator(
        "div:nth-child(10) > div > .mb-4 > .relative > .peer.h-5"
    );

    if (!(await toggle.isChecked())) {
        await toggle.click();
    }
    await page.getByRole("button", { name: "Save Configuration" }).click();
    await expect(
        page.locator("#app").getByText("Configuration saved successfully")
    ).toBeVisible();

    /**
     * Customer registration
     */
    const credentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Sign Up" }).click();
    await page.waitForLoadState("networkidle");
    await page.getByPlaceholder("First Name").fill(credentials.firstName);
    await page.getByPlaceholder("Last Name").fill(credentials.lastName);
    await page.getByPlaceholder("email@example.com").fill(credentials.email);
    await page
        .getByPlaceholder("Password", { exact: true })
        .fill(credentials.password);
    await page.getByPlaceholder("Confirm Password").fill(credentials.password);

    const agreementLocator = page.locator("#agreement").nth(1);

    const isVisible = await agreementLocator.isVisible();

    if (isVisible) {
        await page.getByText("I agree with this statement.").click();
    }

    await page
        .locator("#main form div")
        .filter({ hasText: "Subscribe to newsletter" })
        .locator("label")
        .first()
        .click();
    await page.waitForTimeout(2000);
    await page.getByRole("button", { name: "Register" }).click();

    await expect(
        page
            .getByText(
                "Account created successfully, an e-mail has been sent for verification."
            )
            .first()
    ).toBeVisible();

    /**
     * turn off again email verfication for further tests
     */
    await page.goto("admin/configuration/customer/settings");
    await page.waitForLoadState("networkidle");
    await page
        .locator("div:nth-child(10) > div > .mb-4 > .relative > .peer.h-5")
        .click();
    await page.getByRole("button", { name: "Save Configuration" }).click();
    await expect(
        page.locator("#app").getByText("Configuration saved successfully")
    ).toBeVisible();
});

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
    /**
     * Upload profile image
     */
    const profileImageInput = page.getByLabel("Add Image/Video");
    await profileImageInput.setInputFiles(imagePath);
    await page.getByRole("button", { name: "Save" }).click();

    await expect(
        page.getByText("Profile updated successfully").first()
    ).toBeVisible();
});

test("Should display profile photo after saving profile again without any changes", async ({
    page,
}) => {
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
    /**
     * Upload profile image
     */
    const profileImageInput = page.getByLabel("Add Image/Video");
    await profileImageInput.setInputFiles(imagePath);

    /**
     * Save profile
     */
    await page.getByRole("button", { name: "Save" }).click();
    await expect(
        page.getByText("Profile updated successfully").first()
    ).toBeVisible();

    /**
     * Again save profile without any changes
     */
    await page.getByRole("link", { name: "Edit" }).click();
    await page.getByRole("button", { name: "Save" }).click();
    await expect(
        page.getByText("Profile updated successfully").first()
    ).toBeVisible();

    /**
     * Verify profile photo should be visible
     */
    await page.getByRole("link", { name: "Edit" }).click();
    const uploadedImage = page.locator('img[alt="Uploaded Image"]');

    await expect(uploadedImage).toBeVisible();
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

test.describe("customer actions", () => {
        test("should create simple product", async ({ adminPage }) => {
            const productCreation = new ProductCreation(adminPage);
    
            await productCreation.createProduct({
                type: "simple",
                sku: `SKU-${Date.now()}`,
                name: `Simple-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
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

    await page.waitForTimeout(5000);
    await expect(page.locator('td[data-value="Item Status"]')).toContainText(
        "Canceled"
    );
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
    test.setTimeout(210_000);
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
    const popupPromise = shopPage.waitForEvent("popup").catch(() => null);
    const downloadPromise = shopPage.waitForEvent("download").catch(() => null);
    await shopPage.getByRole("link", { name: productName }).click();
    const result = await Promise.race([popupPromise, downloadPromise]);
});

test("should add wishlist to cart", async ({ page }) => {
    await loginAsCustomer(page);
    await page.getByPlaceholder("Search products here").fill("simple");
    await page.getByPlaceholder("Search products here").press("Enter");
    await page.getByRole('button', { name: 'Add To Wishlist' }).first().click();
    await page.waitForTimeout(2000);
    await page.goto("customer/account/wishlist");
    await page.getByRole("button", { name: "Move To Cart" }).first().click();

    await expect(
        page
            .getByRole("paragraph")
            .filter({ hasText: "Item Successfully Moved to Cart" })
    ).toBeVisible();
});

test("should remove product from wishlist", async ({ page }) => {
    await loginAsCustomer(page);
    await addWishlist(page);
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
});