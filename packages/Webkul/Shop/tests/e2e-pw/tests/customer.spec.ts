import path from "path";
import { fileURLToPath } from "url";
import { expect, test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { AdminOrderPage } from "../pages/admin/sales/AdminOrderPage";
import { AddressPage, type AddressData } from "../pages/shop/AddressPage";
import { AuthPage } from "../pages/shop/AuthPage";
import { CustomerPage } from "../pages/shop/CustomerPage";
import { OrderPage } from "../pages/shop/OrderPage";
import { WishlistPage } from "../pages/shop/WishlistPage";
import { SimpleProductCheckout } from "../pages/shop/checkout/product-types/SimpleProductCheckout";
import { setConfigSwitch } from "../utils/admin";
import {
    buildCustomerCredentials,
    login,
    loginAsCustomer,
    type CustomerCredentials,
} from "../utils/customer";
import {
    generateEmail,
    generateFirstName,
    generateLastName,
    generatePhoneNumber,
    uniqueStamp,
} from "../utils/faker";

const imagePath = path.resolve(
    path.dirname(fileURLToPath(import.meta.url)),
    "../data/images/images.jpeg",
);

function buildAddress(): AddressData {
    return {
        companyName: "Webkul",
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        streetAddress: `${uniqueStamp()} Main St`,
        country: "US",
        state: "AL",
        city: "New York",
        postCode: "10001",
        phone: generatePhoneNumber(),
    };
}

test.describe("customer account", () => {
    test.describe("registration messages", () => {
        const CUSTOMER_SETTINGS_PATH = "admin/configuration/customer/settings";
        const VERIFICATION_FIELD = "customer[settings][email][verification]";

        test("should confirm the account directly while email verification is off", async ({
            adminPage,
            shopPage,
        }) => {
            const original = await setConfigSwitch(
                adminPage,
                CUSTOMER_SETTINGS_PATH,
                VERIFICATION_FIELD,
                false,
            );

            try {
                await new AuthPage(shopPage).register(
                    buildCustomerCredentials(),
                    "Account created successfully.",
                );
            } finally {
                await setConfigSwitch(adminPage, CUSTOMER_SETTINGS_PATH, VERIFICATION_FIELD, original);
            }
        });

        test("should ask for email verification while it is on", async ({
            adminPage,
            shopPage,
        }) => {
            const original = await setConfigSwitch(
                adminPage,
                CUSTOMER_SETTINGS_PATH,
                VERIFICATION_FIELD,
                true,
            );

            try {
                await new AuthPage(shopPage).register(
                    buildCustomerCredentials(),
                    "Account created successfully, an e-mail has been sent for verification.",
                );
            } finally {
                await setConfigSwitch(adminPage, CUSTOMER_SETTINGS_PATH, VERIFICATION_FIELD, original);
            }
        });
    });

    test.describe("profile", () => {
        let customerPage: CustomerPage;
        let credentials: CustomerCredentials;

        test.beforeEach(async ({ shopPage }) => {
            customerPage = new CustomerPage(shopPage);
            credentials = await loginAsCustomer(shopPage);
        });

        test("should update the profile and show the new details", async () => {
            const changes = {
                firstName: generateFirstName(),
                lastName: generateLastName(),
                phone: generatePhoneNumber(),
                gender: "Male" as const,
                dateOfBirth: "1990-05-17",
            };

            await customerPage.updateProfile(changes);

            await customerPage.expectProfileShows([
                changes.firstName,
                changes.lastName,
                "Male",
            ]);
            await customerPage.expectEditFormValues(changes);
        });

        test("should keep an uploaded profile image", async () => {
            await customerPage.uploadProfileImage(imagePath, {
                phone: generatePhoneNumber(),
                gender: "Male",
            });

            await customerPage.expectProfileImageShown();
        });

        test("should sign in with the new password after changing it", async ({
            shopPage,
        }) => {
            const newPassword = "testUser@1234";
            const authPage = new AuthPage(shopPage);

            await customerPage.changePassword(credentials.password, newPassword, {
                phone: generatePhoneNumber(),
                gender: "Male",
            });
            await authPage.logout();
            await authPage.attemptLogin(credentials.email, credentials.password);

            await authPage.expectLoginRefused();

            await login(shopPage, { ...credentials, password: newPassword });

            await authPage.expectSignedIn(`${credentials.firstName} ${credentials.lastName}`);
        });

        test("should delete the profile and refuse a later sign in", async ({ shopPage }) => {
            const authPage = new AuthPage(shopPage);

            await customerPage.deleteProfile(credentials.password);
            await authPage.attemptLogin(credentials.email, credentials.password);

            await authPage.expectLoginRefused();
        });
    });

    test.describe("addresses", () => {
        let addressPage: AddressPage;

        test.beforeEach(async ({ shopPage }) => {
            addressPage = new AddressPage(shopPage);

            await loginAsCustomer(shopPage);
        });

        test("should add an address and list it", async () => {
            const address = buildAddress();

            await addressPage.addAddress(address);

            await addressPage.expectAddressListed(address);
        });

        test("should reject an address without its required fields", async () => {
            await addressPage.submitEmptyAddress();

            await addressPage.expectValidationError("The First Name field is required");
            await addressPage.expectValidationError("The Street Address field is required");
        });

        test("should edit an address and show the new details", async () => {
            const address = buildAddress();
            const changes = {
                firstName: generateFirstName(),
                lastName: generateLastName(),
                streetAddress: `${uniqueStamp()} Sector 62`,
                country: "IN",
                state: "UP",
                city: "Noida",
                postCode: "201301",
            };

            await addressPage.addAddress(address);
            await addressPage.editAddress(address.streetAddress, changes);

            await addressPage.expectAddressListed({ ...address, ...changes });
            await addressPage.expectAddressAbsent(address.streetAddress);
        });

        test("should mark an address as the default one", async () => {
            const first = buildAddress();
            const second = buildAddress();

            await addressPage.addAddress(first);
            await addressPage.addAddress(second);
            await addressPage.setDefaultAddress(second.streetAddress);

            await addressPage.expectDefaultAddress(second.streetAddress);
            await addressPage.expectNotDefaultAddress(first.streetAddress);
        });

        test("should delete an address and keep the others", async () => {
            const address = buildAddress();
            const untouched = buildAddress();

            await addressPage.addAddress(address);
            await addressPage.addAddress(untouched);
            await addressPage.deleteAddress(address.streetAddress);

            await addressPage.expectAddressAbsent(address.streetAddress);
            await addressPage.expectAddressListed(untouched);
        });
    });

    test.describe("orders and wishlist", () => {
        let productName: string;
        let productListPage: ProductListPage;

        test.beforeEach(async ({ adminPage }) => {
            productListPage = new ProductListPage(adminPage);
            productName = `Simple-${uniqueStamp()}`;

            await new ProductCreatePage(adminPage).createProduct({
                type: "simple",
                sku: `SKU-${uniqueStamp()}`,
                name: productName,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
        });

        test.afterEach(async () => {
            await productListPage.deleteProductsIfPresent([productName]);
        });

        async function placeOrder(shopPage: import("@playwright/test").Page): Promise<string> {
            await loginAsCustomer(shopPage);
            await new AddressPage(shopPage).addAddress(buildAddress());

            return new SimpleProductCheckout(shopPage).checkout(productName, {
                payment: "cashondelivery",
            });
        }

        test("should reorder a placed order into the cart", async ({ shopPage }) => {
            const orderId = await placeOrder(shopPage);
            const orderPage = new OrderPage(shopPage);

            await orderPage.reorder(orderId);

            await orderPage.expectCartContains(productName);
        });

        test("should cancel a pending order", async ({ shopPage }) => {
            const orderId = await placeOrder(shopPage);
            const orderPage = new OrderPage(shopPage);

            await orderPage.cancelOrder(orderId);

            await orderPage.expectOrderStatus(orderId, "Canceled");
            await orderPage.expectAllItemsCanceled(orderId);
            await orderPage.expectCancelNotOffered(orderId);
        });

        test("should download the invoice once the admin has invoiced the order", async ({
            adminPage,
            shopPage,
        }) => {
            const orderId = await placeOrder(shopPage);

            await new AdminOrderPage(adminPage).createInvoice(orderId);

            const fileName = await new OrderPage(shopPage).printInvoice(orderId);

            expect(fileName).toMatch(/\.pdf$/);
        });

        test("should move a wishlist item to the cart", async ({ shopPage }) => {
            const wishlistPage = new WishlistPage(shopPage);

            await loginAsCustomer(shopPage);
            await wishlistPage.addToWishlistFromListing(productName);
            await wishlistPage.open();
            await wishlistPage.moveToCart(productName);

            await wishlistPage.expectItemAbsent(productName);
            await new OrderPage(shopPage).expectCartContains(productName);
        });
    });
});
