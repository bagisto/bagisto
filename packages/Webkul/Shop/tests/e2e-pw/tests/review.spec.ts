import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { ReviewPage } from "../pages/shop/ReviewPage";
import { loginAsCustomer } from "../utils/customer";
import { generateDescription, generateName, uniqueStamp } from "../utils/faker";

test.describe("product reviews", () => {
    let productName: string;
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
        productName = `Reviewed-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "simple",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should accept a review from a signed in customer and hold it for approval", async ({
        shopPage,
    }) => {
        const reviewPage = new ReviewPage(shopPage);
        const title = `${generateName()} ${uniqueStamp()}`;

        await loginAsCustomer(shopPage);
        await reviewPage.openProduct(productName);
        await reviewPage.submitReview({
            title,
            comment: generateDescription(),
            rating: 5,
        });

        await reviewPage.openProduct(productName);
        await reviewPage.expectReviewHidden(title);
    });

    test("should reject a review without a title, comment and rating", async ({
        shopPage,
    }) => {
        const reviewPage = new ReviewPage(shopPage);

        await loginAsCustomer(shopPage);
        await reviewPage.openProduct(productName);
        await reviewPage.submitEmptyReview();

        await reviewPage.expectValidationError("The Title field is required");
        await reviewPage.expectValidationError("The Comment field is required");
    });
});
