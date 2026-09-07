import { expect, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export interface ReviewData {
    title: string;
    comment: string;
    rating: 1 | 2 | 3 | 4 | 5;
}

export class ProductReviewShopPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private productLink(productName: string) {
        return this.page
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) })
            .getByRole("link", { name: productName });
    }

    private get cookieConsentAccept() {
        return this.page
            .locator(".js-cookie-consent")
            .getByRole("button", { name: "Accept" });
    }

    private get reviewsTab() {
        return this.page.getByRole("tab", { name: "Reviews" });
    }

    private get reviewTabPanel() {
        return this.page.locator("#review-tab");
    }

    private get writeReviewButton() {
        return this.reviewTabPanel.getByText("Write a Review");
    }

    private ratingStar(rating: number) {
        return this.reviewTabPanel.getByRole("button", {
            name: `Rating ${rating} Stars`,
        });
    }

    private get titleInput() {
        return this.page.getByPlaceholder("Title");
    }

    private get commentInput() {
        return this.page.getByPlaceholder("Comment");
    }

    private get submitButton() {
        return this.page.getByRole("button", { name: "Submit Review" });
    }

    private async dismissCookieConsent(): Promise<void> {
        if (await this.cookieConsentAccept.count()) {
            await this.cookieConsentAccept.click();

            await expect(this.cookieConsentAccept).toBeHidden();
        }
    }

    async openProduct(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        await expect(this.productLink(productName)).toHaveCount(1);

        await this.productLink(productName).click();
        await this.dismissCookieConsent();

        await expect(this.reviewsTab).toBeVisible();
    }

    async openReviewsTab(): Promise<void> {
        await this.reviewsTab.click();

        await expect(this.reviewTabPanel).toBeVisible();
    }

    async submitReview(productName: string, review: ReviewData): Promise<void> {
        await this.openProduct(productName);
        await this.openReviewsTab();
        await this.writeReviewButton.click();
        await this.ratingStar(review.rating).click();

        await expect(this.ratingStar(review.rating)).toHaveAttribute(
            "aria-pressed",
            "true",
        );

        await this.titleInput.fill(review.title);
        await this.commentInput.fill(review.comment);
        await this.submitButton.click();

        await expect(
            this.page.getByText("Review submitted successfully.").first(),
        ).toBeVisible();
    }

    async expectReviewShown(productName: string, title: string): Promise<void> {
        await this.openProduct(productName);
        await this.openReviewsTab();

        await expect(
            this.reviewTabPanel
                .getByText(title, { exact: true })
                .filter({ visible: true }),
        ).toBeVisible();
    }

    async expectReviewHidden(productName: string, title: string): Promise<void> {
        await this.openProduct(productName);
        await this.openReviewsTab();

        await expect(this.reviewTabPanel.getByText(title, { exact: true })).toHaveCount(0);
    }
}
