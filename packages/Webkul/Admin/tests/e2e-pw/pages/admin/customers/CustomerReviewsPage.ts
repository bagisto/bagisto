import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CustomerReviewsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get reviewDetailIcons() {
        return this.page.locator("span.cursor-pointer.icon-sort-right");
    }

    private get statusSelect() {
        return this.page.locator('select[name="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get checkboxes() {
        return this.page.locator(".icon-uncheckbox:visible");
    }

    private get selectActionButton() {
        return this.page.locator('button:has-text("Select Action")');
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    private get deleteActionLink() {
        return this.page.locator('a:has-text("Delete")');
    }

    async open(): Promise<void> {
        await this.visit("admin/customers/reviews");
    }

    async openFirstReviewDetails(): Promise<void> {
        await this.open();
        await expect(this.reviewDetailIcons.first()).toBeVisible({
            timeout: 10000,
        });
        await this.reviewDetailIcons.first().click();
    }

    async updateFirstReviewStatus(
        status: "approved" | "disapproved",
    ): Promise<void> {
        await this.open();
        await this.reviewDetailIcons.first().click();
        await this.statusSelect.selectOption(status);
        await this.saveButton.click();
    }

    async selectFirstReviewForMassActions(): Promise<void> {
        await this.open();
        const count = await this.checkboxes.count();
        expect(count).toBeGreaterThan(1);
        await this.checkboxes.nth(1).click();
    }

    async openSelectActionMenu(): Promise<void> {
        await expect(this.selectActionButton).toBeVisible({ timeout: 10000 });
        await this.selectActionButton.click();
    }

    async applyMassUpdateStatus(
        status: "Approved" | "Disapproved",
    ): Promise<void> {
        await this.page.hover('a:has-text("Update Status")');
        await this.page.waitForSelector(
            'a:has-text("Pending"), a:has-text("Approved"), a:has-text("Disapproved")',
            { state: "visible", timeout: 10000 },
        );
        await this.page.click(`a:has-text("${status}")`);
    }

    async applyMassDelete(): Promise<void> {
        await this.page.click('a:has-text("Delete")', { timeout: 10000 });
    }

    async confirmAgreeDialog(): Promise<void> {
        await expect(this.agreeButton).toBeVisible({ timeout: 10000 });
        await this.agreeButton.click();
    }
}
