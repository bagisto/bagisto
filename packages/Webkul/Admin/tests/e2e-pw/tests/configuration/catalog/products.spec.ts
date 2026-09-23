import { test } from "../../../setup";
import {
    ProductConfigurationPage,
    type ProductSettings,
} from "../../../pages/admin/configuration/catalog/ProductConfigurationPage";
import { uniqueStamp } from "../../../utils/faker";

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
