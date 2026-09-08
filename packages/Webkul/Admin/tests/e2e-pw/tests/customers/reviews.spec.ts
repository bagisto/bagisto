import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { CustomerReviewsPage } from "../../pages/admin/customers/CustomerReviewsPage";
import { CustomersPage } from "../../pages/admin/customers/CustomersPage";
import {
    ProductReviewShopPage,
    type ReviewData,
} from "../../pages/shop/ProductReviewShopPage";
import { loginAsCustomer } from "../../utils/customer";
import {
    generateDescription,
    generateName,
    generateSKU,
    uniqueStamp,
} from "../../utils/faker";

test.describe("review management", () => {
    test.setTimeout(180000);

    let reviewsPage: CustomerReviewsPage;
    let productListPage: ProductListPage;
    let customersPage: CustomersPage;
    let reviewShop: ProductReviewShopPage;
    let productName: string;
    let customerEmail: string;
    let review: ReviewData;

    test.beforeEach(async ({ adminPage, shopPage }) => {
        reviewsPage = new CustomerReviewsPage(adminPage);
        productListPage = new ProductListPage(adminPage);
        customersPage = new CustomersPage(adminPage);
        reviewShop = new ProductReviewShopPage(shopPage);
        productName = `Reviewed ${uniqueStamp()}`;
        review = {
            title: `${generateName()} ${uniqueStamp()}`,
            comment: generateDescription(),
            rating: 5,
        };

        await new ProductCreatePage(adminPage).createSimpleProduct({
            name: productName,
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
            inventory: "5000",
        });

        customerEmail = (await loginAsCustomer(shopPage)).email;

        await reviewShop.submitReview(productName, review);

        await reviewsPage.expectReviewStatus(review.title, "pending");
    });

    test.afterEach(async () => {
        try {
            await reviewsPage.deleteReviewsIfPresent([review.title]);
        } finally {
            try {
                await productListPage.deleteProductsIfPresent([productName]);
            } finally {
                await customersPage.deleteCustomersIfPresent([customerEmail]);
            }
        }
    });

    test("should publish a review on approval and hide it again on disapproval", async () => {
        await reviewShop.expectReviewHidden(productName, review.title);

        await reviewsPage.setStatus(review.title, "approved");

        await reviewsPage.expectReviewStatus(review.title, "approved");
        await reviewShop.expectReviewShown(productName, review.title);

        await reviewsPage.setStatus(review.title, "disapproved");

        await reviewsPage.expectReviewStatus(review.title, "disapproved");
        await reviewShop.expectReviewHidden(productName, review.title);
    });

    test("should update the status of selected reviews through the mass action", async () => {
        await reviewsPage.massUpdateStatus([review.title], "approved");

        await reviewsPage.expectReviewStatus(review.title, "approved");
        await reviewShop.expectReviewShown(productName, review.title);

        await reviewsPage.massUpdateStatus([review.title], "disapproved");

        await reviewsPage.expectReviewStatus(review.title, "disapproved");
        await reviewShop.expectReviewHidden(productName, review.title);
    });

    test("should delete a review and remove it from the grid", async () => {
        await reviewsPage.deleteReview(review.title);

        await reviewsPage.expectReviewAbsent(review.title);
    });

    test("should delete selected reviews through the mass action", async () => {
        await reviewsPage.massDeleteReviews([review.title]);

        await reviewsPage.expectReviewAbsent(review.title);
    });
});
