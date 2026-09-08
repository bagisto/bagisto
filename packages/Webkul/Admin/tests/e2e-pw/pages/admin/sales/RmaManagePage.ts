import { expect, type Locator, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";
import { escapeRegExp } from "@shared/regex";

export type RmaStatus =
    | "Pending Review"
    | "Approved"
    | "Refunded"
    | "Request Declined";

export class RmaManagePage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/requests";
    }

    private get statusSelect() {
        return this.page.locator('select[name="rma_status_id"]');
    }

    private get saveStatusButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
    }

    private get refundItemButton() {
        return this.page.getByRole("button", { name: "Refund Item" });
    }

    private get refundItemModal() {
        return this.modalPanel("Refund Item");
    }

    private get statusBadge() {
        return this.page.locator("span.label-active");
    }

    private modalPanel(title: string): Locator {
        return this.page.locator(`div:has(> div > p:text-is("${title}"))`);
    }

    private requestRow(orderIncrementId: string) {
        return this.rowWithCell(`#${orderIncrementId}`);
    }

    private detailText(text: string): Locator {
        return this.page.locator("p", {
            hasText: new RegExp(`^\\s*${escapeRegExp(text)}\\s*$`),
        });
    }

    async openRequestForOrder(orderIncrementId: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(orderIncrementId);
        await this.requestRow(orderIncrementId).locator("span.icon-view").click();
        await this.waitForVueMount();

        await expect(this.page).toHaveURL(/sales\/rma\/requests\/view\/\d+/);
    }

    async updateStatus(status: RmaStatus): Promise<void> {
        await this.statusSelect.selectOption({ label: status });
        await this.saveStatusButton.click();
        await this.agreeButton.click();

        await expect(
            this.flashMessage("RMA Status updated successfully."),
        ).toBeVisible();
    }

    async refundItems(): Promise<void> {
        await this.refundItemButton.click();
        await this.refundItemModal
            .getByRole("button", { name: "Refund Item" })
            .click();

        await expect(this.statusBadge.filter({ hasText: "Refunded" })).toBeVisible();
    }

    async expectStatus(status: RmaStatus): Promise<void> {
        await expect(
            this.statusBadge.filter({
                hasText: new RegExp(`^\\s*${status}\\s*$`),
            }),
        ).toBeVisible();
    }

    async expectAdditionalField(label: string, value: string): Promise<void> {
        await expect(this.detailText(`${label} :`)).toBeVisible();
        await expect(this.detailText(value)).toBeVisible();
    }

    async expectRequestListed(
        orderIncrementId: string,
        status: RmaStatus,
    ): Promise<void> {
        await this.openGrid();
        await this.searchFor(orderIncrementId);

        await expect(this.requestRow(orderIncrementId)).toHaveCount(1);
        await expect(this.requestRow(orderIncrementId)).toContainText(status);
    }
}
