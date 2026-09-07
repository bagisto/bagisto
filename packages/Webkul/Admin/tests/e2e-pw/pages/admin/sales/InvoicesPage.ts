import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export class InvoicesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/invoices";
    }

    private get sendDuplicateButton() {
        return this.page.getByRole("button", { name: "Send Duplicate Invoice" });
    }

    private get sendButton() {
        return this.page.getByRole("button", { name: "Send", exact: true });
    }

    private get printLink() {
        return this.page.getByRole("link", { name: "Print" });
    }

    private invoiceRow(orderIncrementId: string) {
        return this.rowWithCell(orderIncrementId);
    }

    async openInvoiceForOrder(orderIncrementId: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(orderIncrementId);
        await this.invoiceRow(orderIncrementId).locator("span.icon-view").click();
        await this.waitForVueMount();

        await expect(this.page).toHaveURL(/sales\/invoices\/view\/\d+/);
    }

    async markInvoiceForOrder(
        orderIncrementId: string,
        status: "Paid" | "Overdue",
    ): Promise<void> {
        await this.openGrid();
        await this.searchFor(orderIncrementId);
        await this.selectRowsWithCell([orderIncrementId]);
        await this.applyMassAction("Update Status", status);

        await expect(
            this.flashMessage("Selected invoice updated successfully."),
        ).toBeVisible();
    }

    async sendDuplicateInvoice(): Promise<void> {
        await this.sendDuplicateButton.click();
        await this.sendButton.click();

        await expect(
            this.flashMessage("Invoice sent successfully"),
        ).toBeVisible();
    }

    async printInvoice(): Promise<string> {
        const download = this.page.waitForEvent("download");

        await this.printLink.click();

        return (await download).suggestedFilename();
    }

    async expectInvoiceForOrder(
        orderIncrementId: string,
        status: "Pending" | "Paid" | "Overdue",
    ): Promise<void> {
        await this.openGrid();
        await this.searchFor(orderIncrementId);

        await expect(this.invoiceRow(orderIncrementId)).toHaveCount(1);
        await expect(this.invoiceRow(orderIncrementId)).toContainText(status);
    }
}
