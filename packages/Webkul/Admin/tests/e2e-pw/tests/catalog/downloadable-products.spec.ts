import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductEditPage } from "../../pages/admin/catalog/products/ProductEditPage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { getImageFile, uniqueStamp } from "../../utils/faker";

test.describe("downloadable product authoring", () => {
    test.setTimeout(240000);

    let productName: string;
    let linkTitle: string;
    let linkFilePath: string;

    test.beforeEach(async () => {
        productName = `downloadable-${uniqueStamp()}`;
        linkTitle = `link-${uniqueStamp()}`;
        linkFilePath = getImageFile();
    });

    test.afterEach(async ({ adminPage }) => {
        await new ProductListPage(adminPage).deleteProductsIfPresent([
            productName,
        ]);
    });

    test("should create a downloadable product carrying a file link", async ({
        adminPage,
    }) => {
        const productCreatePage = new ProductCreatePage(adminPage);

        await productCreatePage.createDownloadableProduct(
            {
                name: productName,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
            },
            { title: linkTitle, filePath: linkFilePath, downloads: 2 },
        );

        const productEditPage = new ProductEditPage(adminPage);

        await productEditPage.openProduct(productName);
        await productEditPage.expectDownloadableLinkListed(linkTitle);
    });

    test("should accept an unlimited download allowance of zero", async ({
        adminPage,
    }) => {
        const productCreatePage = new ProductCreatePage(adminPage);

        await productCreatePage.createDownloadableProduct(
            {
                name: productName,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
            },
            { title: linkTitle, filePath: linkFilePath, downloads: 0 },
        );

        const productEditPage = new ProductEditPage(adminPage);

        await productEditPage.openProduct(productName);
        await productEditPage.expectDownloadableLinkListed(linkTitle);
    });

    test("should refuse a negative download allowance", async ({
        adminPage,
    }) => {
        const productCreatePage = new ProductCreatePage(adminPage);

        await productCreatePage.createDownloadableProduct(
            {
                name: productName,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
            },
            { title: linkTitle, filePath: linkFilePath, downloads: 2 },
        );

        const productEditPage = new ProductEditPage(adminPage);

        await productEditPage.openProduct(productName);
        const refusedTitle = `link-${uniqueStamp()}`;

        await productEditPage.attemptDownloadableLink(
            refusedTitle,
            linkFilePath,
            -1,
        );
        await productEditPage.expectDownloadableLinkRefused(refusedTitle);
    });
});
