import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class AdminOrderPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.locator('input[name="search"]');
    }

    private get gridRows() {
        return this.page.locator(
            "div.row:not(.datagrid-head):not(:has(.shimmer))",
        );
    }

    private orderRow(incrementId: string) {
        return this.gridRows.filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*#${incrementId}\\s*$`),
            }),
        });
    }

    private get orderHeading() {
        return this.page.locator("p").filter({ hasText: /^\s*Order #\s*\d+\s*$/ });
    }

    private get invoiceAction() {
        return this.page.getByText("Invoice", { exact: true });
    }

    private get createInvoiceButton() {
        return this.page.getByRole("button", { name: "Create Invoice" });
    }

    private get statusBadge() {
        return this.orderHeading.locator("xpath=following-sibling::span[1]");
    }

    async open(incrementId: string): Promise<void> {
        await this.visit("admin/sales/orders");

        await expect
            .poll(async () => (await this.gridRows.count()) > 0)
            .toBe(true);

        await this.searchInput.fill(incrementId);

        await Promise.all([
            this.page.waitForResponse((response) =>
                decodeURIComponent(response.url()).includes(incrementId),
            ),
            this.searchInput.press("Enter"),
        ]);

        await expect(this.orderRow(incrementId)).toHaveCount(1);

        await this.orderRow(incrementId)
            .locator('a[href*="/sales/orders/view/"]')
            .click();

        await expect(this.orderHeading).toContainText(`#${incrementId}`);
    }

    async createInvoice(incrementId: string): Promise<void> {
        await this.open(incrementId);
        await this.invoiceAction.click();
        await this.createInvoiceButton.click();

        await expect(
            this.page.getByText("Invoice created successfully"),
        ).toBeVisible();
    }

    async expectStatus(incrementId: string, status: string): Promise<void> {
        await this.open(incrementId);

        await expect(this.statusBadge).toHaveText(status);
    }
}
