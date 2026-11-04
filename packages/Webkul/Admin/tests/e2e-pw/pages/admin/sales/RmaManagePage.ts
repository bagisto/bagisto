import { expect, type Locator, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";
import type { MediaFile } from "@shared/media-files";
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

    private get messageInput() {
        return this.page.locator('textarea[name="message"]');
    }

    private get messageAttachmentInput() {
        return this.page.locator('input#file[name="file"]');
    }

    private get sendMessageButton() {
        return this.page.getByRole("button", { name: "Send Message" });
    }

    private get newRequestItemCheckbox() {
        return this.page.locator('input[name^="isChecked["]');
    }

    private get newRequestResolutionSelect() {
        return this.page.locator('select[name="resolution_type"]');
    }

    private get newRequestReasonSelect() {
        return this.page.locator('select[name="rma_reason_id"]');
    }

    private get newRequestQuantityInput() {
        return this.page.locator('input[name="rma_qty"]').filter({ visible: true });
    }

    private get newRequestPackageSelect() {
        return this.page.locator('select[name="package_condition"]');
    }

    private get newRequestImagesInput() {
        return this.page.locator('input[type="file"][name="images[]"]');
    }

    private get newRequestSaveButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
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

    async startNewRequestForOrder(
        orderIncrementId: string,
        reason: string,
    ): Promise<void> {
        await this.visit("admin/sales/rma/requests/create");
        await this.waitForVueMount();
        await this.waitForGrid();
        await this.searchFor(orderIncrementId);
        await this.row(`#${orderIncrementId}`).locator(".icon-edit").click();

        await expect(this.newRequestItemCheckbox).toBeAttached();

        await this.newRequestItemCheckbox.check({ force: true });
        await this.newRequestResolutionSelect.selectOption("return");
        await this.newRequestResolutionSelect.dispatchEvent("change");

        await expect(this.newRequestReasonSelect).toBeVisible();

        await this.newRequestReasonSelect.selectOption({ label: reason });
        await this.newRequestQuantityInput.fill("1");
        await this.newRequestPackageSelect.selectOption("open");
    }

    async attemptNewRequestWithImages(images: MediaFile[]): Promise<void> {
        await this.newRequestImagesInput.setInputFiles(images);
        await this.newRequestSaveButton.click();
    }

    async createNewRequestWithImages(images: MediaFile[]): Promise<void> {
        await this.attemptNewRequestWithImages(images);

        await expect(this.page).not.toHaveURL(/sales\/rma\/requests\/create/);
    }

    async sendMessageWithAttachment(
        message: string,
        attachment: MediaFile,
    ): Promise<void> {
        await this.messageInput.fill(message);
        await this.messageAttachmentInput.setInputFiles(attachment);
        await this.sendMessageButton.click();
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

    async expectRefusal(message: string): Promise<void> {
        await expect(this.flashMessage(message)).toBeVisible();
    }

    async expectNoRequestForOrder(orderIncrementId: string): Promise<void> {
        await this.expectSearchedRowCount(`#${orderIncrementId}`, 0);
    }

    async expectMessageCanBeSentAgain(): Promise<void> {
        await expect(this.sendMessageButton).toBeEnabled();
    }

    async expectMessageWithAttachment(
        message: string,
        attachmentName: string,
    ): Promise<void> {
        await expect(this.page.getByText(message, { exact: true })).toBeVisible();
        await expect(this.page.getByText(attachmentName)).toBeVisible();
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
