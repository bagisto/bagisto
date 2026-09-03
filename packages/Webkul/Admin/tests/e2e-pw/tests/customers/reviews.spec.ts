import { test, expect } from "../../setup";
import { loginAsCustomer, addReview } from "../../utils/customer";
import { generateDescription, generateSKU } from "../../utils/faker";
import { CustomerReviewsPage } from "../../pages/admin/customers/CustomerReviewsPage";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";

test.describe("review management", () => {
    let productName: string;
    let reviewTitle: string;

    test.beforeEach(async ({ adminPage, shopPage }) => {
        productName = `simple-${Date.now()}`;

        const productCreation = new ProductCreatePage(adminPage);

        await productCreation.createSimpleProduct({
            name: productName,
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
            inventory: "5000",
        });

        await loginAsCustomer(shopPage);

        const review = await addReview(shopPage, productName);

        reviewTitle = review.title;
    });

    test("should approve the review", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);

        await reviewsPage.updateReviewStatus(reviewTitle, "approved");

        await expect(
            adminPage.locator("#app p", {
                hasText: "Review Update Successfully",
            }),
        ).toBeVisible();

        await reviewsPage.expectReviewStatus(reviewTitle, "Approved");
    });

    test("should disapprove the review", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);

        await reviewsPage.updateReviewStatus(reviewTitle, "disapproved");

        await expect(
            adminPage.locator("#app p", {
                hasText: "Review Update Successfully",
            }),
        ).toBeVisible();

        await reviewsPage.expectReviewStatus(reviewTitle, "Disapproved");
    });

    test("should approve the review via mass update", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);

        await reviewsPage.selectReviewForMassActions(reviewTitle);
        await reviewsPage.openSelectActionMenu();
        await reviewsPage.applyMassUpdateStatus("Approved");
        await reviewsPage.confirmAgreeDialog();

        await expect(
            adminPage.locator("#app p", {
                hasText: "Selected Review Updated Successfully",
            }),
        ).toBeVisible();

        await reviewsPage.expectReviewStatus(reviewTitle, "Approved");
    });

    test("should disapprove the review via mass update", async ({
        adminPage,
    }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);

        await reviewsPage.selectReviewForMassActions(reviewTitle);
        await reviewsPage.openSelectActionMenu();
        await reviewsPage.applyMassUpdateStatus("Disapproved");
        await reviewsPage.confirmAgreeDialog();

        await expect(
            adminPage.locator("#app p", {
                hasText: "Selected Review Updated Successfully",
            }),
        ).toBeVisible();

        await reviewsPage.expectReviewStatus(reviewTitle, "Disapproved");
    });

    test("should delete a review", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);

        await reviewsPage.deleteReview(reviewTitle);

        await expect(
            adminPage.locator("#app p", {
                hasText: "Review Deleted Successfully",
            }),
        ).toBeVisible();

        await reviewsPage.expectReviewNotListed(reviewTitle);
    });

    test("should mass delete reviews", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);

        await reviewsPage.selectReviewForMassActions(reviewTitle);
        await reviewsPage.openSelectActionMenu();
        await reviewsPage.applyMassDelete();
        await reviewsPage.confirmAgreeDialog();

        await expect(
            adminPage.getByText("Selected Review Deleted Successfully"),
        ).toBeVisible();

        await reviewsPage.expectReviewNotListed(reviewTitle);
    });
});
