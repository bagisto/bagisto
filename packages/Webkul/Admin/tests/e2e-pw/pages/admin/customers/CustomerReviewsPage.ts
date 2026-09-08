import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export type ReviewStatus = "approved" | "disapproved" | "pending";

export const REVIEW_STATUS_LABELS: Record<ReviewStatus, string> = {
    approved: "Approved",
    disapproved: "Disapproved",
    pending: "Pending",
};

export class CustomerReviewsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/customers/reviews";
    }

    private get editModal() {
        return this.page.locator("form").filter({
            has: this.page.locator('select[name="status"]'),
        });
    }

    private get statusSelect() {
        return this.editModal.locator('select[name="status"]');
    }

    private get saveButton() {
        return this.editModal.getByRole("button", { name: "Save" });
    }

    private editIconFor(title: string) {
        return this.row(title).locator("span.icon-sort-right");
    }

    private statusCell(title: string, status: ReviewStatus) {
        return this.row(title).locator('p[class^="label-"]', {
            hasText: new RegExp(`^\\s*${REVIEW_STATUS_LABELS[status]}\\s*$`),
        });
    }

    async setStatus(title: string, status: ReviewStatus): Promise<void> {
        await this.openGrid();
        await this.searchFor(title);
        await this.editIconFor(title).click();

        await expect(this.statusSelect).toBeVisible();

        await this.statusSelect.selectOption(status);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Review Update Successfully"),
        ).toBeVisible();
    }

    async massUpdateStatus(
        titles: string[],
        status: Exclude<ReviewStatus, "pending">,
    ): Promise<void> {
        await this.openGrid();
        await this.selectRows(titles);
        await this.applyMassAction("Update Status", REVIEW_STATUS_LABELS[status]);

        await expect(
            this.flashMessage("Selected Review Updated Successfully"),
        ).toBeVisible();
    }

    async deleteReview(title: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(title);
        await this.deleteRow(title, "Review Deleted Successfully");
    }

    async deleteReviewsIfPresent(titles: string[]): Promise<void> {
        await this.deleteRowsIfPresent(titles, "Review Deleted Successfully");
    }

    async massDeleteReviews(titles: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(titles);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected Review Deleted Successfully"),
        ).toBeVisible();
    }

    async expectReviewStatus(title: string, status: ReviewStatus): Promise<void> {
        await this.expectSearchedRowCount(title, 1);

        await expect(this.statusCell(title, status)).toHaveCount(1);
    }

    async expectReviewAbsent(title: string): Promise<void> {
        await this.expectSearchedRowCount(title, 0);
    }
}
