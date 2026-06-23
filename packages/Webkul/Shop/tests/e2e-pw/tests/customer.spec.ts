import { test, expect } from "../setup";
import { loginAsCustomer } from "../utils/customer";
import {
    generateFirstName,
    generateLastName,
    generatePhoneNumber,
    generateEmail,
} from "../utils/faker";
import { ProductCreation } from "../pages/admin/catalog/products/ProductCreatePage";
import { CustomerPage } from "../pages/shop/CustomerPage";
import { AddressPage } from "../pages/shop/AddressPage";
import { OrderPage } from "../pages/shop/OrderPage";
import { AuthPage } from "../pages/shop/AuthPage";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const imagePath = path.resolve(__dirname, "../data/images/images.jpeg");

function generateRandomDate() {
    const today = new Date();
    const endDate = new Date(
        today.getFullYear() - 1,
        today.getMonth(),
        today.getDate(),
    );
    const startDate = new Date(1925, 0, 1);

    const randomDate = new Date(
        startDate.getTime() +
            Math.random() * (endDate.getTime() - startDate.getTime()),
    );

    const year = randomDate.getFullYear();
    const month = String(randomDate.getMonth() + 1).padStart(2, "0");
    const day = String(randomDate.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
}

test("should display correct message when email verfication is off", async ({
    shopPage,
}) => {
    const authPage = new AuthPage(shopPage);
    const credentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await authPage.register(credentials);
});

test("should display correct message when email verfication is on", async ({
    shopPage,
    adminPage,
}) => {
    await adminPage.goto("admin/configuration/customer/settings");
    const toggle = adminPage.locator(
        "div:nth-child(10) > div > .mb-4 > .relative > .peer.h-5",
    );

    if (!(await toggle.isChecked())) {
        await toggle.click();
    }
    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
    await expect(
        adminPage.locator("#app").getByText("Configuration saved successfully"),
    ).toBeVisible();

    // Register new user
    const authPage = new AuthPage(shopPage);
    const credentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await authPage.register(credentials);
    await expect(
        shopPage
            .getByText(
                "Account created successfully, an e-mail has been sent for verification.",
            )
            .first(),
    ).toBeVisible();

    // Disable email verification
    await adminPage.goto("admin/configuration/customer/settings");
    await adminPage.waitForLoadState("networkidle");
    await adminPage
        .locator("div:nth-child(10) > div > .mb-4 > .relative > .peer.h-5")
        .click();
    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
    await expect(
        adminPage.locator("#app").getByText("Configuration saved successfully"),
    ).toBeVisible();
});

test("should edit a profile", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    const credentials = await loginAsCustomer(shopPage);

    await customerPage.gotoProfilePage();
    await customerPage.editProfile({
        firstName: credentials.firstName,
        lastName: credentials.lastName,
        email: credentials.email,
        phone: generatePhoneNumber(),
        gender: "Male",
        dob: generateRandomDate(),
    });
});

test("should upload profile image", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    await loginAsCustomer(shopPage);

    await customerPage.gotoProfilePage();
    await customerPage.uploadProfileImage(imagePath);
    await customerPage.verifyImageUploaded();
});

test("should add an address", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    const addressPage = new AddressPage(shopPage);
    await loginAsCustomer(shopPage);

    await customerPage.gotoHome();
    await customerPage.openProfile();
    await customerPage.seeProfile();
    await customerPage.clickProfileLink("Address");

    await addressPage.addAddress({
        companyName: "Webkul",
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        streetAddress: "123 Main St",
        country: "US",
        state: "AL",
        city: "New York",
        postCode: "10001",
        phone: generatePhoneNumber(),
    });
});

test("should edit an address", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    const addressPage = new AddressPage(shopPage);
    await loginAsCustomer(shopPage);

    await customerPage.gotoHome();
    await customerPage.openProfile();
    await customerPage.seeProfile();
    await customerPage.clickProfileLink("Address");

    await addressPage.addAddress({
        companyName: "Webkul",
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        streetAddress: "123 Main St",
        country: "US",
        state: "AL",
        city: "New York",
        postCode: "10001",
        phone: generatePhoneNumber(),
    });

    await addressPage.editAddress({
        companyName: "webkul1",
        firstName: "User1",
        lastName: "Demo1",
        email: generateEmail(),
        streetAddress: "123ghds1",
        country: "IN",
        state: "TR",
        city: "noida",
        postCode: "201301",
        phone: "9876543219",
    });
});

test("should set the default address", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    const addressPage = new AddressPage(shopPage);
    await loginAsCustomer(shopPage);

    await customerPage.gotoHome();
    await customerPage.openProfile();
    await customerPage.seeProfile();
    await customerPage.clickProfileLink("Address");

    await addressPage.addAddress({
        companyName: "Webkul",
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        streetAddress: "123 Main St",
        country: "US",
        state: "AL",
        city: "New York",
        postCode: "10001",
        phone: generatePhoneNumber(),
    });

    await addressPage.setDefaultAddress();
});

test("should delete the address", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    const addressPage = new AddressPage(shopPage);
    await loginAsCustomer(shopPage);

    await customerPage.gotoHome();
    await customerPage.openProfile();
    await customerPage.seeProfile();
    await customerPage.clickProfileLink("Address");

    await addressPage.addAddress({
        companyName: "Webkul",
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        streetAddress: "123 Main St",
        country: "US",
        state: "AL",
        city: "New York",
        postCode: "10001",
        phone: generatePhoneNumber(),
    });

    await addressPage.deleteAddress();
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

    test("should be able to reorder", async ({ shopPage }) => {
        const customerPage = new CustomerPage(shopPage);
        const orderPage = new OrderPage(shopPage);
        const addressPage = new AddressPage(shopPage);
        await loginAsCustomer(shopPage);

        // Add address
        await customerPage.gotoHome();
        await customerPage.openProfile();
        await customerPage.seeProfile();
        await customerPage.clickProfileLink("Address");

        await addressPage.addAddress({
            companyName: "Webkul",
            firstName: generateFirstName(),
            lastName: generateLastName(),
            email: generateEmail(),
            streetAddress: "123 Main St",
            country: "US",
            state: "AL",
            city: "New York",
            postCode: "10001",
            phone: generatePhoneNumber(),
        });

        // Create an order
        await customerPage.gotoHome();
        await customerPage.searchProduct("simple");
        await customerPage.addFirstProductToCart();

        await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
        await shopPage
            .getByRole("link", { name: "Continue to Checkout" })
            .click();

        await shopPage
            .locator(
                'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]',
            )
            .nth(0)
            .click();
        await shopPage.getByRole("button", { name: "Proceed" }).click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Free Shipping");
        await shopPage.getByText("Free Shipping").first().click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Cash On Delivery");
        await shopPage.getByText("Cash On Delivery").first().click();
        await shopPage.waitForTimeout(2000);
        await shopPage.getByRole("button", { name: "Place Order" }).click();
        await shopPage.waitForTimeout(2000);

        // Now navigate to orders and reorder
        await orderPage.gotoOrdersPage();
        await orderPage.viewFirstOrder();
        await orderPage.reorderFirstOrder();
    });

    test("should be able to cancel order", async ({ shopPage }) => {
        const customerPage = new CustomerPage(shopPage);
        const orderPage = new OrderPage(shopPage);
        const addressPage = new AddressPage(shopPage);
        await loginAsCustomer(shopPage);

        await customerPage.gotoHome();
        await customerPage.openProfile();
        await customerPage.seeProfile();
        await customerPage.clickProfileLink("Address");

        await addressPage.addAddress({
            companyName: "Webkul",
            firstName: generateFirstName(),
            lastName: generateLastName(),
            email: generateEmail(),
            streetAddress: "123 Main St",
            country: "US",
            state: "AL",
            city: "New York",
            postCode: "10001",
            phone: generatePhoneNumber(),
        });

        // Create an order
        await customerPage.gotoHome();
        await customerPage.searchProduct("simple");
        await customerPage.addFirstProductToCart();

        await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
        await shopPage
            .getByRole("link", { name: "Continue to Checkout" })
            .click();

        await shopPage
            .locator(
                'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]',
            )
            .nth(0)
            .click();
        await shopPage.getByRole("button", { name: "Proceed" }).click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Free Shipping");
        await shopPage.getByText("Free Shipping").first().click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Cash On Delivery");
        await shopPage.getByText("Cash On Delivery").first().click();
        await shopPage.waitForTimeout(2000);
        await shopPage.getByRole("button", { name: "Place Order" }).click();
        await shopPage.waitForTimeout(2000);

        // Cancel the order
        await orderPage.gotoOrdersPage();
        await orderPage.viewFirstOrder();
        await orderPage.cancelFirstOrder();
    });

    test("should be able to print invoice", async ({ shopPage, adminPage }) => {
        const customerPage = new CustomerPage(shopPage);
        const orderPage = new OrderPage(shopPage);
        const addressPage = new AddressPage(shopPage);
        await loginAsCustomer(shopPage);

        await customerPage.gotoHome();
        await customerPage.openProfile();
        await customerPage.seeProfile();
        await customerPage.clickProfileLink("Address");

        await addressPage.addAddress({
            companyName: "Webkul",
            firstName: generateFirstName(),
            lastName: generateLastName(),
            email: generateEmail(),
            streetAddress: "123 Main St",
            country: "US",
            state: "AL",
            city: "New York",
            postCode: "10001",
            phone: generatePhoneNumber(),
        });

        // Create an order
        await customerPage.gotoHome();
        await customerPage.searchProduct("simple");
        await customerPage.addFirstProductToCart();

        await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
        await shopPage
            .getByRole("link", { name: "Continue to Checkout" })
            .click();

        await shopPage
            .locator(
                'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]',
            )
            .nth(0)
            .click();
        await shopPage.getByRole("button", { name: "Proceed" }).click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Free Shipping");
        await shopPage.getByText("Free Shipping").first().click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Cash On Delivery");
        await shopPage.getByText("Cash On Delivery").first().click();
        await shopPage.waitForTimeout(2000);
        await shopPage.getByRole("button", { name: "Place Order" }).click();
        await shopPage.waitForTimeout(2000);

        // Create invoice from admin
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };
        await adminPage.goto("admin/sales/orders");
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage.getByText("Invoice", { exact: true }).click();
        await adminPage.locator("#can_create_transaction").nth(1).click();
        await adminPage.getByRole("button", { name: "Create Invoice" }).click();

        await expect(
            adminPage.getByText("Invoice created successfully Close"),
        ).toBeVisible();

        // Go back to shop and download invoice
        await orderPage.gotoOrdersPage();
        await orderPage.viewFirstOrder();
        await orderPage.printInvoice();
    });

    test("should add wishlist to cart", async ({ shopPage }) => {
        const customerPage = new CustomerPage(shopPage);
        await loginAsCustomer(shopPage);

        await customerPage.gotoHome();
        await customerPage.searchProduct("simple");
        await customerPage.addFirstProductToWishlist();

        await shopPage.goto("customer/account/wishlist");
        await customerPage.moveFirstWishlistItemToCart();
        await expect(
            shopPage.getByText("Item Successfully Moved To Cart").first(),
        ).toBeVisible();
    });
});

test("should remove product from wishlist", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    await loginAsCustomer(shopPage);

    await customerPage.gotoHome();
    await customerPage.searchProduct("simple");
    await customerPage.addFirstProductToWishlist();

    await shopPage.goto("");
    await customerPage.openProfile();
    await customerPage.clickProfileLink("Wishlist");

    await customerPage.removeFirstWishlistItem();
});

test("should change password", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    const credentials = await loginAsCustomer(shopPage);

    await customerPage.gotoProfilePage();
    await customerPage.changePassword({
        currentPassword: credentials.password,
        newPassword: "testUser@1234",
        confirmPassword: "testUser@1234",
    });
});

test("should delete a profile", async ({ shopPage }) => {
    const customerPage = new CustomerPage(shopPage);
    const credentials = await loginAsCustomer(shopPage);

    await customerPage.gotoProfilePage();
    await customerPage.deleteProfile(credentials.password);
});
