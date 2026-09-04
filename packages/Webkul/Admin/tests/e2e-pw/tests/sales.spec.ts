import { test } from "../setup";
import { RmaManagePage } from "../pages/admin/sales/RmaManagePage";
import { RmaStorefrontPage } from "../pages/shop/RMAStorefrontPage";
import { SalesCreatePage } from "../pages/admin/sales/SalesCreatePage";
import { SalesManagePage } from "../pages/admin/sales/SalesManagePage";

test.describe.serial("rma management", () => {
    test.setTimeout(240000);

    test("should allow customer checkout for rma creation so the admin can accept it", async ({
        adminPage,
        shopPage,
    }) => {
        await new SalesCreatePage(adminPage).createRmaEnabledSimpleProduct();
        await new RmaManagePage(shopPage).customerCheckoutForRMA();
    });

    test("should allow admin to create invoice before rma creation so admin can accept it", async ({
        shopPage,
    }) => {
        await new RmaManagePage(shopPage).adminInvoiceCreateRMA();
    });

    test("should allow customer to create rma so admin can accept it", async ({
        shopPage,
    }) => {
        await new RmaManagePage(shopPage).customerCreateRMA();
    });

    test("should allow admin to accept rma", async ({ adminPage }) => {
        await new RmaManagePage(adminPage).adminAcceptRma();
    });

    test("should allow customer checkout for rma so the admin can decline it", async ({
        adminPage,
        shopPage,
    }) => {
        await new SalesCreatePage(adminPage).createRmaEnabledSimpleProduct();
        await new RmaManagePage(shopPage).customerCheckoutForRMA();
    });

    test("should allow admin to create invoice for rma so the admin can decline it", async ({
        shopPage,
    }) => {
        await new RmaManagePage(shopPage).adminInvoiceCreateRMA();
    });

    test("should allow customer to create rma so admin can decline it", async ({
        shopPage,
    }) => {
        await new RmaManagePage(shopPage).customerCreateRMA();
    });

    test("should allow admin to declined rma", async ({ adminPage }) => {
        await new RmaManagePage(adminPage).adminDeclineRma();
    });
});

test.describe(" rma management ", () => {
    test("should allow admin to create reason for rma", async ({
        adminPage,
    }) => {
        await new RmaManagePage(adminPage).adminCreateRmaReason();
    });

    test("should allow admin to create rule for rma", async ({ adminPage }) => {
        await new RmaManagePage(adminPage).adminCreateRmaRule();
    });

    test("should allow admin to create status for rma", async ({
        adminPage,
    }) => {
        await new RmaManagePage(adminPage).adminCreateRmaStatus();
    });
});

test.describe("rma storefront", () => {
    test.setTimeout(240000);

    test("should place an order the customer can return", async ({
        adminPage,
        shopPage,
    }) => {
        await new SalesCreatePage(adminPage).createRmaEnabledSimpleProduct();

        await new RmaStorefrontPage(shopPage).createReturnableOrder();
    });

    test("should invoice that order so the item becomes returnable", async ({
        shopPage,
    }) => {
        await new RmaManagePage(shopPage).adminInvoiceCreateRMA();
    });

    test("should refuse a return quantity above the quantity ordered", async ({
        shopPage,
    }) => {
        const storefront = new RmaStorefrontPage(shopPage);

        await storefront.signIn();
        await storefront.expectQuantityAboveOrderedIsRejected();
    });

    test("should refuse a request until the terms are accepted", async ({
        shopPage,
    }) => {
        const storefront = new RmaStorefrontPage(shopPage);

        await storefront.signIn();
        await storefront.expectTermsAreRequired();
    });

    test("should let the customer raise a return", async ({ shopPage }) => {
        const storefront = new RmaStorefrontPage(shopPage);
        await storefront.signIn();
        await storefront.openNewRequestForm();
        await storefront.fillRequest();
        await storefront.submitRequest();
    });

    test("should list the request on the customer's rma page", async ({
        shopPage,
    }) => {
        const storefront = new RmaStorefrontPage(shopPage);

        await storefront.signIn();
        await storefront.expectRequestListed();
    });

    test("should open the request detail page without a server error", async ({
        shopPage,
    }) => {
        const storefront = new RmaStorefrontPage(shopPage);

        await storefront.signIn();
        await storefront.expectRequestDetailPageOpens();
    });

    test("should show the request details and its conversation", async ({
        shopPage,
    }) => {
        const storefront = new RmaStorefrontPage(shopPage);

        await storefront.signIn();
        await storefront.expectRequestDetails();
    });

    test("should let the customer cancel the request", async ({ shopPage }) => {
        const storefront = new RmaStorefrontPage(shopPage);

        await storefront.signIn();
        await storefront.cancelRequest();
    });
});

test.describe("sales management", () => {
    test("should be able to create orders", async ({ adminPage }) => {
        const salesCreatePage = new SalesCreatePage(adminPage);
        await salesCreatePage.createSimpleProduct();
        await salesCreatePage.generateSimpleOrder();
    });

    test("should be comment on order", async ({ adminPage }) => {
        await new SalesManagePage(adminPage).addOrderComment();
    });

    test("should be able to reorder", async ({ adminPage }) => {
        await new SalesManagePage(adminPage).reorderOrder();
    });

    test("should be able to create invoice", async ({ adminPage }) => {
        await new SalesManagePage(adminPage).createInvoice();
    });

    test("should be create shipment", async ({ adminPage }) => {
        await new SalesManagePage(adminPage).createShipment();
    });

    test("should be able to create refund", async ({ adminPage }) => {
        await new SalesManagePage(adminPage).createRefund();
    });

    test("should be create mail invoice", async ({ adminPage }) => {
        await new SalesManagePage(adminPage).sendDuplicateInvoice();
    });

    test("should be able to print invoice", async ({ adminPage }) => {
        await new SalesManagePage(adminPage).printInvoice();
    });

    test.describe("should able to cancel product", () => {
        test("should be able to cancel simple order", async ({ adminPage }) => {
            const salesCreatePage = new SalesCreatePage(adminPage);
            await salesCreatePage.createSimpleProduct();
            await salesCreatePage.generateSimpleOrder();
            await new SalesManagePage(adminPage).cancelLatestOrder();
        });

        test("should be able to cancel configurable order", async ({
            adminPage,
        }) => {
            const salesCreatePage = new SalesCreatePage(adminPage);
            await salesCreatePage.createConfigurableProduct();
            await salesCreatePage.generateConfigurableOrder();
            await new SalesManagePage(adminPage).cancelLatestOrder();
        });

        test("should be able to cancel group order", async ({ adminPage }) => {
            const salesCreatePage = new SalesCreatePage(adminPage);
            await salesCreatePage.createSimpleProduct();
            await salesCreatePage.createGroupedProduct();
            await salesCreatePage.generateGroupOrder();
            await new SalesManagePage(adminPage).cancelLatestOrder();
        });

        test("should be able to cancel virtual order", async ({
            adminPage,
        }) => {
            const salesCreatePage = new SalesCreatePage(adminPage);
            await salesCreatePage.createVirtualProduct();
            await salesCreatePage.generateVirtualOrder();
            await new SalesManagePage(adminPage).cancelLatestOrder();
        });

        test("should be able to cancel downloadable order", async ({
            adminPage,
        }) => {
            const salesCreatePage = new SalesCreatePage(adminPage);
            await salesCreatePage.createDownloadableProduct();
            await salesCreatePage.generateDownloadableOrder();
            await new SalesManagePage(adminPage).cancelLatestOrder();
        });
    });

    test("support mass status change to overdue for invoices", async ({
        adminPage,
    }) => {
        await new SalesManagePage(adminPage).massUpdateInvoiceStatus("Overdue");
    });
});
