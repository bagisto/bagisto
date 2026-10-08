import path from "path";
import { fileURLToPath } from "url";
import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { AdminOrderPage } from "../pages/admin/sales/AdminOrderPage";
import { DownloadableProductsPage } from "../pages/shop/DownloadableProductsPage";
import { DownloadableProductCheckout } from "../pages/shop/checkout/product-types/DownloadableProductCheckout";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { uniqueStamp } from "../utils/faker";
import type { Page } from "@playwright/test";

const PRICE = 199;

const DOWNLOADS_ALLOWED = 2;

const linkFilePath = path.resolve(
    path.dirname(fileURLToPath(import.meta.url)),
    "../data/images/images.jpeg",
);

test.describe("downloadable products account management", () => {
    test.setTimeout(240000);

    let productName: string;
    let productListPage: ProductListPage;

    const createProductWithFileLinks = async (
        adminPage: Page,
        extraLink = false,
        downloadsAllowed = DOWNLOADS_ALLOWED,
    ): Promise<void> => {
        await new ProductCreatePage(adminPage).createProduct({
            type: "downloadable",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: PRICE,
            inventory: 100,
            downloadableFile: linkFilePath,
            downloadsAllowed,
            ...(extraLink ? { downloadableExtraFile: linkFilePath } : {}),
        });
    };

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
        productName = `downloadable-${uniqueStamp()}`;
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should withhold the download until the order is invoiced", async ({
        adminPage,
        shopPage,
    }) => {
        await createProductWithFileLinks(adminPage);

        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new DownloadableProductCheckout(shopPage).checkout(
            productName,
        );

        const downloadablesPage = new DownloadableProductsPage(shopPage);

        await downloadablesPage.expectRowCount(productName, 1);
        await downloadablesPage.expectStatus(productName, "Pending");
        await downloadablesPage.expectDownloadNotOffered(productName);

        await new AdminOrderPage(adminPage).createInvoice(orderId);

        await downloadablesPage.expectStatus(productName, "Available");
        await downloadablesPage.expectDownloadOffered(productName);
    });

    test("should count each download against the entitlement and expire the link", async ({
        adminPage,
        shopPage,
    }) => {
        await createProductWithFileLinks(adminPage);

        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new DownloadableProductCheckout(shopPage).checkout(
            productName,
        );

        await new AdminOrderPage(adminPage).createInvoice(orderId);

        const downloadablesPage = new DownloadableProductsPage(shopPage);

        await downloadablesPage.expectRemainingDownloads(
            productName,
            DOWNLOADS_ALLOWED,
        );

        await downloadablesPage.download(productName);

        await downloadablesPage.expectRemainingDownloads(productName, 1);
        await downloadablesPage.expectStatus(productName, "Available");

        await downloadablesPage.download(productName);

        await downloadablesPage.expectRemainingDownloads(productName, 0);
        await downloadablesPage.expectStatus(productName, "Expired");
        await downloadablesPage.expectDownloadNotOffered(productName);
    });

    test("should withdraw the download once the order is refunded", async ({
        adminPage,
        shopPage,
    }) => {
        await createProductWithFileLinks(adminPage);

        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new DownloadableProductCheckout(shopPage).checkout(
            productName,
        );

        const adminOrderPage = new AdminOrderPage(adminPage);

        await adminOrderPage.createInvoice(orderId);

        const downloadablesPage = new DownloadableProductsPage(shopPage);

        await downloadablesPage.expectDownloadOffered(productName);

        await adminOrderPage.refundAllItems(orderId);

        await downloadablesPage.expectStatus(productName, "Expired");
        await downloadablesPage.expectRemainingDownloads(productName, 0);
        await downloadablesPage.expectDownloadNotOffered(productName);
    });

    test("should keep serving a link sold with no download limit", async ({
        adminPage,
        shopPage,
    }) => {
        await createProductWithFileLinks(adminPage, false, 0);

        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new DownloadableProductCheckout(shopPage).checkout(
            productName,
        );

        await new AdminOrderPage(adminPage).createInvoice(orderId);

        const downloadablesPage = new DownloadableProductsPage(shopPage);

        await downloadablesPage.expectUnlimitedDownloads(productName);

        await downloadablesPage.download(productName);
        await downloadablesPage.download(productName);
        await downloadablesPage.download(productName);

        await downloadablesPage.expectUnlimitedDownloads(productName);
        await downloadablesPage.expectStatus(productName, "Available");
        await downloadablesPage.expectDownloadOffered(productName);
    });

    test("should grant only the link the customer bought when a product offers several", async ({
        adminPage,
        shopPage,
    }) => {
        await createProductWithFileLinks(adminPage, true);

        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new DownloadableProductCheckout(
            shopPage,
        ).checkoutWithLink(productName, `${productName} extra`);

        await new AdminOrderPage(adminPage).createInvoice(orderId);

        const downloadablesPage = new DownloadableProductsPage(shopPage);

        await downloadablesPage.expectRowCount(productName, 1);
        await downloadablesPage.expectDownloadOffered(productName);
    });
});
