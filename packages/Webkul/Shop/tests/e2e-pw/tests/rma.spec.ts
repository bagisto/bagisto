import { notAnImage, validImage } from "@shared/media-files";
import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { AdminOrderPage } from "../pages/admin/sales/AdminOrderPage";
import { RmaCreatePage } from "../pages/shop/RmaCreatePage";
import { RmaRequestPage } from "../pages/shop/RmaRequestPage";
import { SimpleProductCheckout } from "../pages/shop/checkout/product-types/SimpleProductCheckout";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { uniqueStamp } from "../utils/faker";
import path from "path";
import { fileURLToPath } from "url";

const photoPath = path.resolve(
    path.dirname(fileURLToPath(import.meta.url)),
    "../data/images/images.jpeg",
);

const secondPhotoPath = path.resolve(
    path.dirname(fileURLToPath(import.meta.url)),
    "../data/images/1.webp",
);

test.describe("return requests", () => {
    let productListPage: ProductListPage;
    let productName: string;

    async function createProduct(
        adminPage: import("@playwright/test").Page,
        allowRma: boolean,
    ): Promise<void> {
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
            allowRma,
        });
    }

    async function placeInvoicedOrder(
        adminPage: import("@playwright/test").Page,
        shopPage: import("@playwright/test").Page,
    ): Promise<string> {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new SimpleProductCheckout(shopPage).checkout(productName);

        await new AdminOrderPage(adminPage).createInvoice(orderId);

        return orderId;
    }

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should let a customer request a return for an invoiced order item that allows rma", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const rmaPage = new RmaCreatePage(shopPage);

        await rmaPage.requestReturn(orderId);

        await rmaPage.expectRequestListedFor(productName);
    });

    test("should refuse a return quantity above the ordered quantity", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const rmaPage = new RmaCreatePage(shopPage);

        await rmaPage.attemptReturnWithExcessQuantity(orderId);

        await rmaPage.expectQuantityRejected();
        await rmaPage.expectNoRequestForOrder(orderId);
    });

    test("should refuse a return request until the terms are accepted", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const rmaPage = new RmaCreatePage(shopPage);

        await rmaPage.attemptReturnWithoutAcceptingTerms(orderId);

        await rmaPage.expectTermsRejected();
        await rmaPage.expectNoRequestForOrder(orderId);
    });

    test("should show the request with its items and conversation on the detail page", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const message = `Please expedite ${uniqueStamp()}`;
        const requestPage = new RmaRequestPage(shopPage);

        await new RmaCreatePage(shopPage).requestReturn(orderId);

        await requestPage.expectRequestListed(orderId, "Pending Review");
        await requestPage.expectDetailPageServesTheRequest(
            orderId,
            productName,
            "Pending Review",
        );

        await requestPage.sendMessage(orderId, message);

        await requestPage.expectMessageInConversation(message);
    });

    test("should carry the photos a customer attaches through to the request", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const createPage = new RmaCreatePage(shopPage);
        const requestPage = new RmaRequestPage(shopPage);

        await createPage.requestReturnWithPhotos(orderId, [photoPath, secondPhotoPath]);

        await createPage.expectRequestCreatedOnDetailPage();

        await requestPage.expectPhotosServedOnDetailPage(2);
    });

    test("should keep the one photo a customer attaches", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const createPage = new RmaCreatePage(shopPage);
        const requestPage = new RmaRequestPage(shopPage);

        await createPage.requestReturnWithPhotos(orderId, [validImage()]);

        await createPage.expectRequestCreatedOnDetailPage();

        await requestPage.expectPhotosServedOnDetailPage(1);
    });

    test("should say why a photo was refused and create no request", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const createPage = new RmaCreatePage(shopPage);

        await createPage.requestReturnWithPhotos(orderId, [notAnImage()]);

        await createPage.expectPhotosRefused("The Images field must be a file of type");
        await createPage.expectNoRequestForOrder(orderId);
    });

    test("should say why a message attachment was refused and send a valid one", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const createPage = new RmaCreatePage(shopPage);
        const requestPage = new RmaRequestPage(shopPage);
        const message = `Message ${uniqueStamp()}`;
        const attachment = validImage(`attachment-${uniqueStamp()}.png`);

        await createPage.requestReturn(orderId);

        await requestPage.sendMessage(orderId, message, notAnImage());

        await requestPage.expectMessageRefused("The file field must be a file of type");
        await requestPage.expectMessageCanBeSentAgain();

        await requestPage.sendMessage(orderId, message, attachment);

        await requestPage.expectMessageInConversation(message);
        await requestPage.expectAttachmentInConversation(attachment.name);
    });

    test("should let a customer cancel a return request", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const requestPage = new RmaRequestPage(shopPage);

        await new RmaCreatePage(shopPage).requestReturn(orderId);

        await requestPage.cancelRequest(orderId);

        await requestPage.expectRequestListed(orderId, "Request Canceled");
        await requestPage.expectCancelNoLongerOffered(orderId);
    });

    test("should not offer an order whose product does not allow rma", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, false);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);

        await new RmaCreatePage(shopPage).expectOrderNotOffered(orderId);
    });
});
