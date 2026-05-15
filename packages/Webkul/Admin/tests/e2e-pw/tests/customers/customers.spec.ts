import { test, expect } from "../../setup";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    randomElement,
    generatePhoneNumber,
    generateFullName,
    generateDescription,
} from "../../utils/faker";
import { CustomersPage } from "../../pages/admin/customers/CustomersPage";
import { CustomerDetailsPage } from "../../pages/admin/customers/CustomerDetailsPage";

test.describe("customer management", () => {
    test("should create customer", async ({ adminPage }) => {
        const customersPage = new CustomersPage(adminPage);

        await customersPage.createCustomer(
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            randomElement(["Male", "Female", "Other"]),
            generatePhoneNumber(),
        );
    });

    test("should edit customer", async ({ adminPage }) => {
        const customersPage = new CustomersPage(adminPage);
        const detailsPage = new CustomerDetailsPage(adminPage);

        await customersPage.createCustomer(
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            randomElement(["Male", "Female", "Other"]),
            generatePhoneNumber(),
        );

        await detailsPage.openFirstCustomerDetails();

        await detailsPage.editCustomerProfile(
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            generatePhoneNumber(),
            "Other",
        );
    });

    test("should add address", async ({ adminPage }) => {
        const detailsPage = new CustomerDetailsPage(adminPage);

        await detailsPage.openFirstCustomerDetails();
        await detailsPage.addAddress(
            generateFullName(),
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            generateFirstName(),
            "IN",
            "UP",
            generateLastName(),
            "201301",
            generatePhoneNumber(),
        );
    });

    test("should edit address", async ({ adminPage }) => {
        const detailsPage = new CustomerDetailsPage(adminPage);

        await detailsPage.openFirstCustomerDetails();
        await detailsPage.editAddress(
            generateLastName(),
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            generateFirstName(),
            "IN",
            "UP",
            generateLastName(),
            "201301",
            generatePhoneNumber(),
        );
    });

    test("should set default address", async ({ adminPage }) => {
        const detailsPage = new CustomerDetailsPage(adminPage);

        await detailsPage.openFirstCustomerDetails();
        await detailsPage.setDefaultAddress();
    });

    test("should delete address", async ({ adminPage }) => {
        const detailsPage = new CustomerDetailsPage(adminPage);

        await detailsPage.openFirstCustomerDetails();
        await detailsPage.deleteAddress();
    });

    test("should add note in customer", async ({ adminPage }) => {
        const detailsPage = new CustomerDetailsPage(adminPage);
        const description = generateDescription();

        await detailsPage.openFirstCustomerDetails();
        await detailsPage.addNote(description);
    });

    test("should delete account", async ({ adminPage }) => {
        const customersPage = new CustomersPage(adminPage);
        const detailsPage = new CustomerDetailsPage(adminPage);

        await customersPage.createCustomer(
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            randomElement(["Male", "Female", "Other"]),
            generatePhoneNumber(),
        );

        await detailsPage.openFirstCustomerDetails();
        await detailsPage.deleteAccount();
    });

    test("should create order", async ({ adminPage }) => {
        const detailsPage = new CustomerDetailsPage(adminPage);

        await detailsPage.openFirstCustomerDetails();
        await detailsPage.createOrder();
    });

    test("should mass delete customers", async ({ adminPage }) => {
        const customersPage = new CustomersPage(adminPage);

        await customersPage.createCustomer(
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            randomElement(["Male", "Female", "Other"]),
            generatePhoneNumber(),
        );

        await customersPage.openMassActionMenu();
        await customersPage.deleteSelectedCustomers();
        await customersPage.confirmAgreeDialog();

        await expect(
            adminPage.getByText("Selected data successfully deleted")
        ).toBeVisible();
    });

    test("should mass update customers", async ({ adminPage }) => {
        const customersPage = new CustomersPage(adminPage);

        await customersPage.createCustomer(
            generateFirstName(),
            generateLastName(),
            generateEmail(),
            randomElement(["Male", "Female", "Other"]),
            generatePhoneNumber(),
        );

        await customersPage.openMassActionMenu();
        await customersPage.updateSelectedCustomersStatusTo("Active");
        await customersPage.confirmAgreeDialog();

        await expect(
            adminPage.getByText("Selected Customers successfully updated")
        ).toBeVisible();
    });
});
