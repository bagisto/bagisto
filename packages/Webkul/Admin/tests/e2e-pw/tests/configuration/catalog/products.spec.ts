import { test } from "../../../setup";
import {
    ProductConfigurationPage,
    type ImageSize,
    type ProductSettings,
} from "../../../pages/admin/configuration/catalog/ProductConfigurationPage";
import { getImageFile, uniqueStamp } from "../../../utils/faker";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("product configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: ProductConfigurationPage;
    let original: ProductSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new ProductConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the compare and image search settings after reload", async () => {
        const changed = {
            compare: !original.compare,
            imageSearch: !original.imageSearch,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the product and cart view counts after reload", async () => {
        const changed = {
            relatedProducts: other(original.relatedProducts, "6", "7"),
            upSells: other(original.upSells, "6", "7"),
            crossSells: other(original.crossSells, "6", "7"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the storefront listing settings after reload", async () => {
        const changed = {
            storefrontMode: other(original.storefrontMode, "grid", "list"),
            productsPerPage: other(original.productsPerPage, "12", "24"),
            sortBy: other(original.sortBy, "name-asc", "price-desc"),
            buyNowButton: !original.buyNowButton,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the cache image sizes after reload", async () => {
        const changed = {
            imageSizes: {
                small: {
                    width: other(original.imageSizes.small.width, "150", "160"),
                    height: other(original.imageSizes.small.height, "150", "160"),
                },
                medium: {
                    width: other(original.imageSizes.medium.width, "300", "320"),
                    height: other(original.imageSizes.medium.height, "300", "320"),
                },
                large: {
                    width: other(original.imageSizes.large.width, "600", "640"),
                    height: other(original.imageSizes.large.height, "600", "640"),
                },
            },
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    for (const size of ["small", "medium", "large"] as ImageSize[]) {
        test(`should keep an uploaded ${size} image placeholder until it is removed`, async () => {
            await configPage.uploadImagePlaceholder(size, getImageFile());

            await configPage.expectImagePlaceholderShown(size);

            await configPage.removeImagePlaceholder(size);

            await configPage.expectImagePlaceholderAbsent(size);
        });
    }

    test("should persist the review settings after reload", async () => {
        const changed = {
            guestReview: !original.guestReview,
            customerReview: !original.customerReview,
            reviewSummary: other(original.reviewSummary, "star_counts", "average_rating"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the attribute upload size limits after reload", async () => {
        const changed = {
            imageUploadSize: other(original.imageUploadSize, "2048", "4096"),
            fileUploadSize: other(original.fileUploadSize, "2048", "4096"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the social share settings after reload", async () => {
        const changed = {
            socialShare: !original.socialShare,
            shareMessage: `Share ${uniqueStamp()}`,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
