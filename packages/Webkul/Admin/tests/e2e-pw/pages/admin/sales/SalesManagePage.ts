import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { generateDescription, generateName, generateRandomNumericString } from "../../../utils/faker";

export class SalesManagePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async addOrderComment() {
        await this.visit("admin/sales/orders");
        await this.page.locator(".row > div:nth-child(4) > a").first().click();
        const lorem100 = generateDescription(200);
        await this.page.fill('textarea[name="comment"]', lorem100);
        await this.page
            .locator('span.icon-uncheckbox.cursor-pointer[role="button"]')
            .click();
        await this.page.getByRole("button", { name: "Submit Comment" }).click();
        await expect(this.page.locator("#app")).toContainText(
            "Comment added successfully.",
        );
    }

    async reorderOrder() {
        await this.visit("admin/sales/orders");
        await this.page.waitForTimeout(3000);
        await this.page.locator(".row > div:nth-child(4) > a").first().click();
        await this.page.getByRole("link", { name: " Reorder" }).click();
        await expect(this.page.getByText("Cart Items")).toBeVisible();
        await this.page.locator("label.icon-radio-normal").first().click();
        await this.page.getByRole("button", { name: "Proceed" }).click();
        await this.page.getByText("Free Shipping$0.00Free").click();
        await this.page
            .locator("label")
            .filter({ hasText: "Cash On Delivery" })
            .click();
        await this.page.getByRole("button", { name: "Place Order" }).click();
        await expect(this.page.locator("#app")).toContainText("Pending");
    }

    async createInvoice() {
        await this.visit("admin/sales/orders");
        await this.page.locator(".row > div:nth-child(4) > a").first().click();
        await this.page.click(
            "div.transparent-button.px-1 > .icon-sales.text-2xl:visible",
        );
        await this.page.click('button[type="submit"].primary-button:visible');
        await expect(this.page.locator("#app")).toContainText(
            "Invoice created successfully",
        );
    }

    async createShipment() {
        await this.visit("admin/sales/orders");
        await this.page.locator(".row > div:nth-child(4) > a").first().click();
        await this.page.click(
            "div.transparent-button.px-1 > .icon-ship.text-2xl:visible",
        );
        await this.page.fill('input[name="shipment[carrier_title]"]', generateName());
        await this.page.fill(
            'input[name="shipment[track_number]"]',
            generateRandomNumericString(),
        );
        await this.page.locator('[id="shipment\\[source\\]"]').selectOption("1");
        await this.page.click('button[type="submit"].primary-button:visible');
        await expect(this.page.locator("#app")).toContainText(
            "Shipment created successfully",
        );
    }

    async createRefund() {
        await this.visit("admin/sales/orders");
        await this.page.locator(".row > div:nth-child(4) > a").first().click();
        await this.page
            .locator("div.transparent-button.px-1 > .icon-cancel.text-2xl")
            .click();

        const itemQty = await this.page.$$(
            'input[type="text"].w-full.rounded-md.border.px-3.text-sm.text-gray-600.transition-all:visible',
        );

        let i = 1;

        for (const element of itemQty) {
            await element.scrollIntoViewIfNeeded();

            if (i > itemQty.length - 2) {
                const rand = Math.floor(Math.random() * 2000);
                await element.fill(rand.toString());
            }

            if (i > itemQty.length - 3) {
                continue;
            }

            const currentValue = await element.inputValue();
            const maxQty = parseInt(currentValue, 10);
            const qty = Math.floor(Math.random() * (maxQty - 1)) + 1;

            await element.fill(qty.toString());

            i++;
        }

        await this.page.click('button[type="submit"].primary-button:visible');
        await expect(
            this.page.locator("p", { hasText: "Refund created successfully" }),
        ).toBeVisible();
    }

    async sendDuplicateInvoice() {
        await this.visit("admin/sales/invoices");
        await this.page
            .locator(".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view")
            .first()
            .click();
        await this.page
            .getByRole("button", { name: " Send Duplicate Invoice" })
            .click();
        await this.page.getByRole("button", { name: "Send", exact: true }).click();
        await expect(this.page.locator("#app")).toContainText(
            "Invoice sent successfully",
        );
    }

    async printInvoice() {
        await this.visit("admin/sales/invoices");
        await this.page
            .locator(".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view")
            .first()
            .click();
        const downloadPromise = this.page.waitForEvent("download");
        await this.page.getByRole("link", { name: " Print" }).click();
        await downloadPromise;
    }

    async cancelLatestOrder() {
        await this.page.getByRole("link", { name: "Sales" }).click();
        await this.visit("admin/sales/orders");
        await this.page
            .locator(".flex.items-center.justify-between > a")
            .first()
            .click();
        await this.page.getByRole("link", { name: "Cancel" }).click();
        await this.page.getByRole("button", { name: "Agree", exact: true }).click();
        await expect(this.page.locator("#app")).toContainText(
            "Order cancelled successfully",
        );
    }

    async createTransaction() {
        await this.visit("admin/sales/orders");
        await this.page.waitForTimeout(3000);
        await this.page.reload();
        await this.page.locator(".row > div:nth-child(4) > a").first().click();
        await this.page.locator(".transparent-button > .icon-sales").click();
        await this.page.locator("#can_create_transaction").nth(1).click();
        await this.page.getByRole("button", { name: "Create Invoice" }).click();
        await this.visit("admin/sales/transactions");
        await expect(this.page.getByText("Paid").first()).toBeVisible();
    }

    async massUpdateInvoiceStatus(status: "Paid" | "Overdue") {
        await this.visit("admin/sales/invoices");
        await this.page.locator(".icon-uncheckbox").first().click();
        await this.page
            .getByRole("button", { name: "Select Action " })
            .click();
        await this.page.getByRole("link", { name: "Update Status " }).hover();
        await this.page.getByRole("link", { name: status }).click();
        await this.page.getByRole("button", { name: "Agree", exact: true }).click();
        await expect(this.page.locator("#app")).toContainText(status);
        await expect(
            this.page.getByText("Selected invoice updated successfully"),
        ).toBeVisible();
    }
}
