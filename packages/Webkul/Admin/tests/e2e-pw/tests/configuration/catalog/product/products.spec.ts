import { test } from "../../../../setup";
import {
    generateDescription,
    generateRandomNumericString,
    getImageFile,
} from "../../../../utils/faker";
import { ProductConfigurationPage } from "../../../../pages/admin/configuration/catalog/ProductConfigurationPage";

test.describe("product configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new ProductConfigurationPage(adminPage).open();
    });

    test("should update the compare and image search", async ({
        adminPage,
    }) => {
        await new ProductConfigurationPage(
            adminPage,
        ).toggleCompareAndImageSearch();
    });

    test("should update the product view page configuration", async ({
        adminPage,
    }) => {
        await new ProductConfigurationPage(adminPage).updateProductView({
            relatedProducts: generateRandomNumericString(2),
            upSellsProducts: generateRandomNumericString(2),
        });
    });

    test("should update the cart view page configuration", async ({
        adminPage,
    }) => {
        await new ProductConfigurationPage(adminPage).updateCartView(
            generateRandomNumericString(2),
        );
    });

    test("should update the store front configuration", async ({
        adminPage,
    }) => {
        await new ProductConfigurationPage(adminPage).updateStorefront({
            mode: "grid",
            productsPerPage: generateRandomNumericString(2),
            sortBy: "name-asc",
            buyNowDisplay: true,
        });
    });

    test("should update the small image size and placeholder", async ({
        adminPage,
    }) => {
        const page = new ProductConfigurationPage(adminPage);

        await page.updateImageSize(
            "small",
            generateRandomNumericString(3),
            generateRandomNumericString(3),
        );
        await page.uploadImagePlaceholder("Small", getImageFile());
        await page.saveAndVerify();

        await page.removeImagePlaceholder("small");
        await page.saveAndVerify();
    });

    test("should update the medium image size and placeholder", async ({
        adminPage,
    }) => {
        const page = new ProductConfigurationPage(adminPage);

        await page.updateImageSize(
            "medium",
            generateRandomNumericString(3),
            generateRandomNumericString(3),
        );
        await page.uploadImagePlaceholder("Medium", getImageFile());
        await page.saveAndVerify();

        await page.removeImagePlaceholder("medium");
        await page.saveAndVerify();
    });

    test("should update the large image size and placeholder", async ({
        adminPage,
    }) => {
        const page = new ProductConfigurationPage(adminPage);

        await page.updateImageSize(
            "large",
            generateRandomNumericString(3),
            generateRandomNumericString(3),
        );
        await page.uploadImagePlaceholder("Large", getImageFile());
        await page.saveAndVerify();

        await page.removeImagePlaceholder("large");
        await page.saveAndVerify();
    });

    test("should update the review configuration", async ({ adminPage }) => {
        await new ProductConfigurationPage(adminPage).updateReviewConfig(
            "star_counts",
        );
    });

    test("should update the allowed image and file upload size", async ({
        adminPage,
    }) => {
        await new ProductConfigurationPage(adminPage).updateUploadSizes(
            generateRandomNumericString(3),
            generateRandomNumericString(3),
        );
    });

    test("should update social share configuration", async ({ adminPage }) => {
        await new ProductConfigurationPage(adminPage).updateSocialShare(
            generateDescription(),
        );
    });
});
