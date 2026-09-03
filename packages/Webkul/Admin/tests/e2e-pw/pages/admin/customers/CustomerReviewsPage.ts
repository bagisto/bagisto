import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CustomerReviewsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get statusSelect() {
        return this.page.locator('select[name="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get selectActionButton() {
        return this.page.locator('button:has-text("Select Action")');
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    private reviewRow(title: string) {
        return this.page.locator(".row").filter({ hasText: title });
    }

    async open(): Promise<void> {
        await this.visit("admin/customers/reviews");
    }

    async expectReviewListed(title: string): Promise<void> {
        await this.open();
        await expect(this.reviewRow(title)).toBeVisible({ timeout: 30000 });
    }

    async openReviewDetails(title: string): Promise<void> {
        await this.expectReviewListed(title);
        await this.reviewRow(title)
            .locator("span.cursor-pointer.icon-sort-right")
            .click();
    }

    async updateReviewStatus(
        title: string,
        status: "approved" | "disapproved",
    ): Promise<void> {
        await this.openReviewDetails(title);
        await this.statusSelect.selectOption(status);
        await this.saveButton.click();
    }

    async expectReviewStatus(title: string, status: string): Promise<void> {
        await this.open();
        await expect(this.reviewRow(title)).toContainText(status);
    }

    async selectReviewForMassActions(title: string): Promise<void> {
        await this.expectReviewListed(title);
        await this.reviewRow(title).locator(".icon-uncheckbox").click();
    }

    async deleteReview(title: string): Promise<void> {
        await this.expectReviewListed(title);
        await this.reviewRow(title)
            .locator("span.cursor-pointer.icon-delete")
            .click();
        await this.confirmAgreeDialog();
    }

    async expectReviewNotListed(title: string): Promise<void> {
        await this.open();
        await expect(this.reviewRow(title)).toHaveCount(0);
    }

    async openSelectActionMenu(): Promise<void> {
        await expect(this.selectActionButton).toBeVisible({ timeout: 10000 });
        await this.selectActionButton.click();
    }

    async applyMassUpdateStatus(
        status: "Approved" | "Pending" | "Disapproved",
    ): Promise<void> {
        await this.page.hover('a:has-text("Update Status")');

        const statusOption = this.page.getByRole("link", {
            name: status,
            exact: true,
        });

        await statusOption.waitFor({ state: "visible" });
        await statusOption.click();
    }

    async applyMassDelete(): Promise<void> {
        await this.page.click('a:has-text("Delete")', { timeout: 10000 });
    }

    async confirmAgreeDialog(): Promise<void> {
        await expect(this.agreeButton).toBeVisible({ timeout: 10000 });
        await this.agreeButton.click();
    }
}
