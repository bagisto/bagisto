import { test } from "../../setup";
import {
    CustomersPage,
    type CustomerData,
} from "../../pages/admin/customers/CustomersPage";
import {
    CustomerDetailsPage,
    type CustomerAddressData,
} from "../../pages/admin/customers/CustomerDetailsPage";
import {
    generateDescription,
    generateFirstName,
    generateLastName,
    generatePhoneNumber,
    uniqueStamp,
} from "../../utils/faker";

function buildCustomer(overrides: Partial<CustomerData> = {}): CustomerData {
    const stamp = uniqueStamp();

    return {
        firstName: generateFirstName(),
        lastName: `${generateLastName()}${stamp}`,
        email: `customer-${stamp}@example.com`,
        phone: generatePhoneNumber(),
        gender: "Other",
        ...overrides,
    };
}

function buildAddress(): CustomerAddressData {
    const stamp = uniqueStamp();

    return {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: `address-${stamp}@example.com`,
        street: `${stamp} Sector 62`,
        city: "Noida",
        postcode: "201301",
        phone: generatePhoneNumber(),
    };
}

test.describe("customer management", () => {
    let customersPage: CustomersPage;
    let detailsPage: CustomerDetailsPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        customersPage = new CustomersPage(adminPage);
        detailsPage = new CustomerDetailsPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await customersPage.deleteCustomersIfPresent(created);
    });

    test("should create a customer and list it as active", async () => {
        const customer = buildCustomer();
        created.push(customer.email);

        await customersPage.createCustomer(customer);

        await customersPage.expectCustomerListed(
            customer.email,
            `${customer.firstName} ${customer.lastName}`,
        );
    });

    test("should reject a customer without its required fields", async () => {
        await customersPage.submitEmptyCreateForm();

        await customersPage.expectValidationError(
            "The First Name field is required",
        );
        await customersPage.expectValidationError(
            "The Last Name field is required",
        );
        await customersPage.expectValidationError("The Email field is required");
        await customersPage.expectValidationError("The Gender field is required");
    });

    test("should reject a customer whose email is already registered", async () => {
        const existing = buildCustomer();
        const duplicate = buildCustomer({ email: existing.email });
        created.push(existing.email);

        await customersPage.createCustomer(existing);
        await customersPage.attemptCreateCustomer(duplicate);

        await customersPage.expectValidationError(
            "The email has already been taken.",
        );
        await customersPage.expectCustomerListed(
            existing.email,
            `${existing.firstName} ${existing.lastName}`,
        );
    });

    test("should update a customer profile and show the new name in the grid", async () => {
        const customer = buildCustomer();
        const changes = {
            firstName: generateFirstName(),
            lastName: `${generateLastName()}${uniqueStamp()}`,
        };
        created.push(customer.email);

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await detailsPage.updateProfile(changes);

        await detailsPage.reload();
        await detailsPage.expectCustomerName(
            `${changes.firstName} ${changes.lastName}`,
        );
        await customersPage.expectCustomerListed(
            customer.email,
            `${changes.firstName} ${changes.lastName}`,
        );
    });

    test("should add an address to a customer", async () => {
        const customer = buildCustomer();
        const address = buildAddress();
        created.push(customer.email);

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await detailsPage.addAddress(address);

        await detailsPage.reload();
        await detailsPage.expectAddressShown(address);
    });

    test("should update a customer address", async () => {
        const customer = buildCustomer();
        const address = buildAddress();
        const newStreet = `${uniqueStamp()} Sector 18`;
        created.push(customer.email);

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await detailsPage.addAddress(address);
        await detailsPage.updateAddressStreet(address.street, newStreet);

        await detailsPage.reload();
        await detailsPage.expectAddressShown({ ...address, street: newStreet });
        await detailsPage.expectAddressAbsent(address.street);
    });

    test("should mark a customer address as the default one", async () => {
        const customer = buildCustomer();
        const first = buildAddress();
        const second = { ...buildAddress(), street: `${uniqueStamp()} Sector 15` };
        created.push(customer.email);

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await detailsPage.addAddress(first);
        await detailsPage.addAddress(second);
        await detailsPage.setDefaultAddress(second.street);

        await detailsPage.reload();
        await detailsPage.expectDefaultAddress(second.street);
        await detailsPage.expectNotDefaultAddress(first.street);
    });

    test("should delete a customer address", async () => {
        const customer = buildCustomer();
        const address = buildAddress();
        const untouched = { ...buildAddress(), street: `${uniqueStamp()} Sector 15` };
        created.push(customer.email);

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await detailsPage.addAddress(address);
        await detailsPage.addAddress(untouched);
        await detailsPage.deleteAddress(address.street);

        await detailsPage.reload();
        await detailsPage.expectAddressAbsent(address.street);
        await detailsPage.expectAddressShown(untouched);
    });

    test("should add a note to a customer", async () => {
        const customer = buildCustomer();
        const note = `${generateDescription(60)} ${uniqueStamp()}`;
        created.push(customer.email);

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await detailsPage.addNote(note);

        await detailsPage.reload();
        await detailsPage.expectNoteShown(note);
    });

    test("should delete a customer account", async () => {
        const customer = buildCustomer();
        const untouched = buildCustomer();
        created.push(customer.email, untouched.email);

        await customersPage.createCustomer(customer);
        await customersPage.createCustomer(untouched);
        await customersPage.openCustomer(customer.email);
        await detailsPage.deleteAccount();

        await customersPage.expectCustomerAbsent(customer.email);
        await customersPage.expectCustomerListed(
            untouched.email,
            `${untouched.firstName} ${untouched.lastName}`,
        );
    });

    test("should start an order for a customer from its details page", async () => {
        const customer = buildCustomer();
        created.push(customer.email);

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await detailsPage.createOrder();

        await detailsPage.expectOrderCreationStarted();
    });

    test("should mass delete only the selected customers", async () => {
        const first = buildCustomer();
        const second = buildCustomer();
        const untouched = buildCustomer();
        created.push(first.email, second.email, untouched.email);

        await customersPage.createCustomer(first);
        await customersPage.createCustomer(second);
        await customersPage.createCustomer(untouched);
        await customersPage.massDeleteCustomers([first.email, second.email]);

        await customersPage.expectCustomerAbsent(first.email);
        await customersPage.expectCustomerAbsent(second.email);
        await customersPage.expectCustomerListed(
            untouched.email,
            `${untouched.firstName} ${untouched.lastName}`,
        );
    });

    test("should deactivate selected customers through the mass action", async () => {
        const first = buildCustomer();
        const untouched = buildCustomer();
        created.push(first.email, untouched.email);

        await customersPage.createCustomer(first);
        await customersPage.createCustomer(untouched);
        await customersPage.massUpdateStatus([first.email], "Inactive");

        await customersPage.expectCustomerListed(
            first.email,
            `${first.firstName} ${first.lastName}`,
            "Inactive",
        );
        await customersPage.expectCustomerListed(
            untouched.email,
            `${untouched.firstName} ${untouched.lastName}`,
            "Active",
        );
    });
});
