import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import {
    CustomersPage,
    type CustomerData,
} from "../pages/admin/customers/CustomersPage";
import { CustomerDetailsPage } from "../pages/admin/customers/CustomerDetailsPage";
import { InvoicesPage } from "../pages/admin/sales/InvoicesPage";
import { OrderCreatePage } from "../pages/admin/sales/OrderCreatePage";
import { OrderViewPage } from "../pages/admin/sales/OrderViewPage";
import { RmaManagePage } from "../pages/admin/sales/RmaManagePage";
import {
    RmaCustomFieldsPage,
    type RmaCustomFieldData,
    RmaReasonsPage,
    RmaRulesPage,
    RmaStatusesPage,
} from "../pages/admin/sales/RmaSettingsPage";
import { RmaShopPage } from "../pages/shop/RmaShopPage";
import {
    generateDescription,
    generateFirstName,
    generateLastName,
    generateName,
    generatePhoneNumber,
    generateSKU,
    uniqueStamp,
} from "../utils/faker";

function buildCustomer(): CustomerData {
    const stamp = uniqueStamp();

    return {
        firstName: generateFirstName(),
        lastName: `${generateLastName()}${stamp}`,
        email: `order-${stamp}@example.com`,
        phone: generatePhoneNumber(),
        gender: "Other",
    };
}

async function createSimpleProduct(
    productCreatePage: ProductCreatePage,
    allowRma = false,
): Promise<string> {
    const name = `Simple ${uniqueStamp()}`;

    await productCreatePage.createSimpleProduct({
        name,
        productNumber: generateSKU(),
        shortDescription: generateDescription(),
        description: generateDescription(),
        price: "199",
        weight: "1",
        inventory: "100",
        allowRma,
    });

    return name;
}

test.describe("order management", () => {
    test.setTimeout(240000);

    let orderViewPage: OrderViewPage;
    let orderCreatePage: OrderCreatePage;
    let invoicesPage: InvoicesPage;
    let productName: string;
    let orderId: string;

    test.beforeEach(async ({ adminPage }) => {
        orderViewPage = new OrderViewPage(adminPage);
        orderCreatePage = new OrderCreatePage(adminPage);
        invoicesPage = new InvoicesPage(adminPage);

        const customer = buildCustomer();
        const customersPage = new CustomersPage(adminPage);

        productName = await createSimpleProduct(new ProductCreatePage(adminPage));

        await customersPage.createCustomer(customer);
        await customersPage.openCustomer(customer.email);
        await new CustomerDetailsPage(adminPage).addAddress({
            firstName: customer.firstName,
            lastName: customer.lastName,
            email: customer.email,
            street: "Sector 62",
            city: "Noida",
            postcode: "201301",
            phone: customer.phone,
        });

        orderId = await orderCreatePage.placeOrderForCustomer(
            customer.email,
            productName,
        );
    });

    test.afterEach(async ({ adminPage }) => {
        await new ProductListPage(adminPage).deleteProductsIfPresent([productName]);
    });

    test("should place an order from the admin and list it as pending", async () => {
        await orderViewPage.expectStatus("Pending");
        await orderViewPage.expectItemListed(productName);
        await orderViewPage.expectListedWithStatus(orderId, "Pending");
    });

    test("should keep a comment added to an order", async () => {
        const comment = `${generateName()} ${uniqueStamp()}`;

        await orderViewPage.open(orderId);
        await orderViewPage.addComment(comment);

        await orderViewPage.reload();
        await orderViewPage.expectCommentListed(comment);
    });

    test("should move the order to processing once it is invoiced", async () => {
        await orderViewPage.open(orderId);
        await orderViewPage.createInvoice();

        await orderViewPage.expectStatus("Processing");
        await invoicesPage.expectInvoiceForOrder(orderId, "Paid");
    });

    test("should complete the order once the invoiced items are shipped", async () => {
        await orderViewPage.open(orderId);
        await orderViewPage.createInvoice();
        await orderViewPage.createShipment(generateName(), `${uniqueStamp()}`);

        await orderViewPage.expectStatus("Completed");
    });

    test("should close the order once the invoice is refunded", async () => {
        await orderViewPage.open(orderId);
        await orderViewPage.createInvoice();
        await orderViewPage.refundAllItems();

        await orderViewPage.expectStatus("Closed");
    });

    test("should cancel a pending order and stop offering cancellation", async () => {
        await orderViewPage.open(orderId);
        await orderViewPage.cancelOrder();

        await orderViewPage.expectStatus("Canceled");
        await orderViewPage.expectCancelNotOffered();
        await orderViewPage.expectListedWithStatus(orderId, "Canceled");
    });

    test("should reorder an order into a new pending order", async () => {
        await orderViewPage.open(orderId);
        await orderViewPage.startReorder();

        const reorderId = await orderCreatePage.completeReorder();

        test.expect(reorderId).not.toBe(orderId);
        await orderViewPage.expectStatus("Pending");
        await orderViewPage.expectItemListed(productName);
    });

    test("should mark an invoice as overdue through the mass action", async () => {
        await orderViewPage.open(orderId);
        await orderViewPage.createInvoice();
        await invoicesPage.markInvoiceForOrder(orderId, "Overdue");

        await invoicesPage.expectInvoiceForOrder(orderId, "Overdue");
    });

    test("should print and resend an invoice", async () => {
        await orderViewPage.open(orderId);
        await orderViewPage.createInvoice();
        await invoicesPage.openInvoiceForOrder(orderId);

        test.expect(await invoicesPage.printInvoice()).toMatch(/\.pdf$/);

        await invoicesPage.sendDuplicateInvoice();
    });
});

test.describe("rma management", () => {
    test.setTimeout(300000);

    let reasonsPage: RmaReasonsPage;
    let reasonTitle: string;

    test.beforeEach(async ({ adminPage }) => {
        reasonsPage = new RmaReasonsPage(adminPage);
        reasonTitle = `Reason ${uniqueStamp()}`;

        await reasonsPage.createReason(reasonTitle);
    });

    test.afterEach(async () => {
        await reasonsPage.deleteReasonsIfPresent([reasonTitle]);
    });

    for (const { title, status } of [
        { title: "approve a return request and refund the item", status: "Approved" },
        { title: "decline a return request", status: "Request Declined" },
    ] as const) {
        test(`should let the admin ${title}`, async ({ adminPage, shopPage }) => {
            const productName = await createSimpleProduct(
                new ProductCreatePage(adminPage),
                true,
            );
            const shop = new RmaShopPage(shopPage);
            const rmaPage = new RmaManagePage(adminPage);
            const orderViewPage = new OrderViewPage(adminPage);

            try {
                await shop.registerAndAddAddress();
                const orderId = await shop.placeOrder(productName);

                await orderViewPage.open(orderId);
                await orderViewPage.createInvoice();

                await shop.requestReturn(orderId, reasonTitle);

                await rmaPage.openRequestForOrder(orderId);
                await rmaPage.expectStatus("Pending Review");
                await rmaPage.updateStatus(status);
                await rmaPage.expectStatus(status);

                if (status === "Approved") {
                    await rmaPage.refundItems();
                    await rmaPage.expectRequestListed(orderId, "Refunded");
                } else {
                    await rmaPage.expectRequestListed(orderId, "Request Declined");
                }
            } finally {
                await new ProductListPage(adminPage).deleteProductsIfPresent([productName]);
            }
        });
    }

    test("should list a newly created rma reason", async () => {
        await reasonsPage.expectReasonListed(reasonTitle);
    });

    test("should list a newly created rma rule", async ({ adminPage }) => {
        const rulesPage = new RmaRulesPage(adminPage);
        const name = `Rule ${uniqueStamp()}`;

        try {
            await rulesPage.createRule(name, "15");

            await rulesPage.expectRuleListed(name, "15");
        } finally {
            await rulesPage.deleteRulesIfPresent([name]);
        }
    });

    test("should list a newly created rma custom field", async ({ adminPage }) => {
        const customFieldsPage = new RmaCustomFieldsPage(adminPage);
        const field: RmaCustomFieldData = {
            label: `Field ${uniqueStamp()}`,
            code: `field_${uniqueStamp()}`,
            type: "text",
        };

        try {
            await customFieldsPage.createCustomField(field);

            await customFieldsPage.expectCustomFieldListed(field);
        } finally {
            await customFieldsPage.deleteCustomFieldsIfPresent([field.label]);
        }
    });

    test("should keep the chosen options of a multiselect custom field on the request", async ({
        adminPage,
        shopPage,
    }) => {
        const customFieldsPage = new RmaCustomFieldsPage(adminPage);
        const chosen = [`damaged${uniqueStamp()}`, `late${uniqueStamp()}`];
        const field: RmaCustomFieldData = {
            label: `Field ${uniqueStamp()}`,
            code: `field_${uniqueStamp()}`,
            type: "multiselect",
            options: chosen,
        };
        const productName = await createSimpleProduct(
            new ProductCreatePage(adminPage),
            true,
        );
        const shop = new RmaShopPage(shopPage);
        const rmaPage = new RmaManagePage(adminPage);

        try {
            await customFieldsPage.createCustomField(field);

            await shop.registerAndAddAddress();

            const orderId = await shop.placeOrder(productName);

            await new OrderViewPage(adminPage).open(orderId);
            await new OrderViewPage(adminPage).createInvoice();

            await shop.requestReturn(orderId, reasonTitle, chosen);

            await rmaPage.openRequestForOrder(orderId);
            await rmaPage.expectAdditionalField(field.label, chosen.join(","));
        } finally {
            await customFieldsPage.deleteCustomFieldsIfPresent([field.label]);
            await new ProductListPage(adminPage).deleteProductsIfPresent([
                productName,
            ]);
        }
    });

    test("should list a newly created rma status", async ({ adminPage }) => {
        const statusesPage = new RmaStatusesPage(adminPage);
        const title = `Status ${uniqueStamp()}`;

        try {
            await statusesPage.createStatus(title);

            await statusesPage.expectStatusListed(title);
        } finally {
            await statusesPage.deleteStatusesIfPresent([title]);
        }
    });
});
