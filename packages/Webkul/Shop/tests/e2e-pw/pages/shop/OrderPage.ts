import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class OrderPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private orderCard(orderId: string) {
        return this.page.locator("div.rounded-md").filter({
            hasText: new RegExp(`Order ID:\\s*#${orderId}\\b`),
        });
    }

    private get reorderLink() {
        return this.page.getByRole("link", { name: "Reorder" });
    }

    private get cancelLink() {
        return this.page.getByRole("link", { name: "Cancel" });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get invoicesTab() {
        return this.page.getByRole("tab", { name: "Invoices" });
    }

    private get printLink() {
        return this.page.getByRole("link", { name: "Print" });
    }

    private get itemStatusCells() {
        return this.page.locator('td[data-value="Item Status"]');
    }

    private statusLabel(status: string) {
        return this.page.locator('p[class^="label-"]', {
            hasText: new RegExp(`^\\s*${status}\\s*$`),
        });
    }

    private cartItemRow(productName: string) {
        return this.page
            .locator("div.flex")
            .filter({ has: this.page.getByRole("link", { name: productName, exact: true }) });
    }

    async openOrders(): Promise<void> {
        await this.visit("customer/account/orders");

        await expect(this.page).toHaveURL(/customer\/account\/orders/);
    }

    async openOrder(orderId: string): Promise<void> {
        await this.visit(`customer/account/orders/view/${orderId}`);

        await expect(this.page).toHaveURL(new RegExp(`orders/view/${orderId}$`));
    }

    async reorder(orderId: string): Promise<void> {
        await this.openOrder(orderId);
        await this.reorderLink.click();

        await expect(this.page).toHaveURL(/checkout\/cart/);
    }

    async cancelOrder(orderId: string): Promise<void> {
        await this.openOrder(orderId);
        await this.cancelLink.click();
        await this.agreeButton.click();

        await expect(this.page.getByText(/has been canceled/).first()).toBeVisible();
    }

    async printInvoice(orderId: string): Promise<string> {
        await this.openOrder(orderId);
        await this.invoicesTab.click();

        const [download] = await Promise.all([
            this.page.waitForEvent("download"),
            this.printLink.click(),
        ]);

        return download.suggestedFilename();
    }

    async expectOrderListed(
        orderId: string,
        status: string,
        grandTotal?: string,
    ): Promise<void> {
        await this.openOrders();

        const card = this.orderCard(orderId);

        await expect(card).toHaveCount(1);
        await expect(card).toContainText(status);

        if (grandTotal !== undefined) {
            await expect(card).toContainText(grandTotal);
        }
    }

    async expectOrderStatus(orderId: string, status: string): Promise<void> {
        await this.openOrder(orderId);

        await expect(this.statusLabel(status)).toHaveCount(1);
    }

    async expectAllItemsCanceled(orderId: string): Promise<void> {
        await this.openOrder(orderId);

        const count = await this.itemStatusCells.count();

        expect(count).toBeGreaterThan(0);

        for (let index = 0; index < count; index++) {
            await expect(this.itemStatusCells.nth(index)).toContainText("Canceled");
        }
    }

    async expectCancelNotOffered(orderId: string): Promise<void> {
        await this.openOrder(orderId);

        await expect(this.cancelLink).toHaveCount(0);
    }

    async expectCartContains(productName: string): Promise<void> {
        await this.visit("checkout/cart");

        await expect(this.cartItemRow(productName).first()).toBeVisible();
    }
}
