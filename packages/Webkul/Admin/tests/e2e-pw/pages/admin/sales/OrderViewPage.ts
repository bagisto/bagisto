import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export type OrderStatus =
    | "Pending"
    | "Processing"
    | "Completed"
    | "Closed"
    | "Canceled";

export class OrderViewPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/orders";
    }

    private get orderHeading() {
        return this.page.locator("p").filter({ hasText: /^\s*Order #\s*\d+\s*$/ });
    }

    private get statusBadge() {
        return this.orderHeading.locator("xpath=following-sibling::span[1]");
    }

    private get commentInput() {
        return this.page.locator('textarea[name="comment"]');
    }

    private get submitCommentButton() {
        return this.page.getByRole("button", { name: "Submit Comment" });
    }

    private actionTrigger(label: string) {
        return this.page.locator("div.transparent-button").filter({
            hasText: new RegExp(`^\\s*${label}\\s*$`),
        });
    }

    private get invoiceAction() {
        return this.actionTrigger("Invoice");
    }

    private get createInvoiceButton() {
        return this.page.getByRole("button", { name: "Create Invoice" });
    }

    private get shipAction() {
        return this.actionTrigger("Ship");
    }

    private get carrierTitleInput() {
        return this.page.locator('input[name="shipment[carrier_title]"]');
    }

    private get trackingNumberInput() {
        return this.page.locator('input[name="shipment[track_number]"]');
    }

    private get shipmentSourceSelect() {
        return this.page.locator('select[name="shipment[source]"]');
    }

    private get createShipmentButton() {
        return this.page.getByRole("button", { name: "Create Shipment" });
    }

    private get refundAction() {
        return this.actionTrigger("Refund");
    }

    private get refundButton() {
        return this.page.getByRole("button", { name: "Refund", exact: true });
    }

    private get cancelLink() {
        return this.page.getByRole("link", { name: "Cancel", exact: true });
    }

    private get reorderLink() {
        return this.page.getByRole("link", { name: "Reorder" });
    }

    private orderViewLink(incrementId: string) {
        return this.rowWithCell(`#${incrementId}`).locator(
            'a[href*="/sales/orders/view/"]',
        );
    }

    async open(incrementId: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(incrementId);
        await this.orderViewLink(incrementId).click();
        await this.waitForVueMount();

        await expect(this.orderHeading).toContainText(`#${incrementId}`);
    }

    async reload(): Promise<void> {
        await this.page.reload();
        await this.waitForVueMount();
    }

    async addComment(comment: string): Promise<void> {
        await this.commentInput.fill(comment);
        await this.submitCommentButton.click();

        await expect(
            this.flashMessage("Comment added successfully."),
        ).toBeVisible();
    }

    async createInvoice(): Promise<void> {
        await this.invoiceAction.click();
        await this.createInvoiceButton.click();

        await expect(
            this.flashMessage("Invoice created successfully"),
        ).toBeVisible();
    }

    async createShipment(carrier: string, trackingNumber: string): Promise<void> {
        await this.shipAction.click();
        await this.carrierTitleInput.fill(carrier);
        await this.trackingNumberInput.fill(trackingNumber);
        await this.shipmentSourceSelect.selectOption({ label: "Default" });
        await this.createShipmentButton.click();

        await expect(
            this.flashMessage("Shipment created successfully"),
        ).toBeVisible();
    }

    async refundAllItems(): Promise<void> {
        await this.refundAction.click();
        await expect(this.refundButton).toBeVisible();
        await this.refundButton.click();

        await expect(
            this.flashMessage("Refund created successfully"),
        ).toBeVisible();
    }

    async cancelOrder(): Promise<void> {
        await this.cancelLink.click();
        await this.agreeButton.click();

        await expect(
            this.flashMessage("Order cancelled successfully"),
        ).toBeVisible();
    }

    async startReorder(): Promise<void> {
        await this.reorderLink.click();

        await expect(this.page).toHaveURL(/sales\/orders\/create\/\d+/);
    }

    async expectStatus(status: OrderStatus): Promise<void> {
        await expect(this.statusBadge).toHaveText(status);
    }

    async expectItemListed(productName: string): Promise<void> {
        await expect(this.page.getByText(productName).first()).toBeVisible();
    }

    async expectCommentListed(comment: string): Promise<void> {
        await expect(this.page.getByText(comment)).toBeVisible();
    }

    async expectCancelNotOffered(): Promise<void> {
        await expect(this.cancelLink).toHaveCount(0);
    }

    async expectListedWithStatus(
        incrementId: string,
        status: OrderStatus,
    ): Promise<void> {
        await this.openGrid();
        await this.searchFor(incrementId);

        await expect(this.rowWithCell(`#${incrementId}`)).toHaveCount(1);
        await expect(this.rowWithCell(`#${incrementId}`)).toContainText(status);
    }
}
