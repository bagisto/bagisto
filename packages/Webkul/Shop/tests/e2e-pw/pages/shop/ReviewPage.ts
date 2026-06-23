import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class ReviewPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async searchProduct(term: string): Promise<void> {
        await this.page.getByPlaceholder("Search products here").fill(term);
        await this.page.getByPlaceholder("Search products here").press("Enter");
    }

    async openFirstProduct(): Promise<void> {
        await this.page.locator(".group img").first().click();
    }

    async writeReview(title: string, comment: string): Promise<void> {
        await this.page.getByRole("button", { name: "Reviews" }).click();
        await this.page.waitForSelector("#review-tab");
        await this.page
            .locator("#review-tab")
            .getByText("Write a Review")
            .click();
        await this.page.locator("#review-tab span").nth(3).click();
        await this.page.locator("#review-tab span").nth(4).click();
        await this.page.getByPlaceholder("Title").click();
        await this.page.getByPlaceholder("Title").fill(title);
        await this.page.getByPlaceholder("Comment").click();
        await this.page.getByPlaceholder("Comment").fill(comment);
        await this.page.getByRole("button", { name: "Submit Review" }).click();
    }

    async expectReviewSuccess(): Promise<void> {
        await expect(
            this.page.getByText("Review submitted successfully.").first(),
        ).toBeVisible();
    }
}
