import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import type { CustomerCredentials } from "../../../utils/customer";

export class BookingsAdminPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createInvoiceAction() {
        return this.page.locator("div.transparent-button:has(.icon-sales)");
    }

    private get canCreateTransactionToggle() {
        return this.page.locator(
            'div.mb-4:has(label[for="can_create_transaction"])',
        );
    }

    private get createInvoiceButton() {
        return this.page.getByRole("button", { name: "Create Invoice" });
    }

    private get invoiceCreatedMessage() {
        return this.page.getByText("Invoice created successfully");
    }

    private get slotGraphEvents() {
        return this.page.locator("div.vuecal__event:has(div.slot)");
    }

    private get bookingDialogOrderId() {
        return this.page.locator(
            "div:has(> div.text-lg.font-semibold) > div.text-xs.text-gray-500",
        );
    }

    private get bookingCustomerName() {
        return this.page.locator("span.font-medium");
    }

    private get bookingDialogCloseButton() {
        return this.page.locator("span.icon-close:visible");
    }

    private get bookingListToggleButton() {
        return this.page.locator("button.icon-list");
    }

    private get cancelOrderAction() {
        return this.page.locator("div.transparent-button:has(span.icon-cancel)");
    }

    private get refundButton() {
        return this.page.getByRole("button", { name: "Refund", exact: true });
    }

    private get refundCreatedMessage() {
        return this.page.getByText("Refund created successfully");
    }

    private get calendarNextButton() {
        return this.page.locator("span.icon-sort-right");
    }

    private get calendarLeavingViews() {
        return this.page.locator('[class*="leave-active"]');
    }

    private slotGraphTime(slotGraph: Locator) {
        return slotGraph.locator("span.truncate");
    }

    private slotGraphEventsAt(time: string) {
        return this.slotGraphEvents.filter({
            has: this.page.locator("span.truncate", { hasText: time }),
        });
    }

    private bookingDetailValue(label: string | RegExp) {
        return this.page
            .locator("div.text-gray-500", { hasText: label })
            .locator("xpath=following-sibling::div[1]");
    }

    private bookingRowByOrderId(orderId: string) {
        return this.page.locator("div.row.py-4").filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*#?${orderId}\\s*$`),
            }),
        });
    }

    private bookingRowText(row: Locator, index: number) {
        return row.locator("p").nth(index);
    }

    private slotGraphEventsOf(customerName: string) {
        return this.slotGraphEvents.filter({ hasText: customerName });
    }

    private async waitForCalendarSettled(
        action: () => Promise<void>,
    ): Promise<void> {
        const bookingsLoaded = this.page.waitForResponse((response) =>
            response.url().includes("sales/bookings/get"),
        );

        await action();
        await bookingsLoaded;
        await this.page.waitForLoadState("networkidle");

        await expect(this.calendarLeavingViews).toHaveCount(0);
    }

    private async openBookingsCalendar(): Promise<void> {
        await this.waitForCalendarSettled(() => this.visit("admin/sales/bookings"));
    }

    private async goToNextCalendarPage(): Promise<void> {
        await this.waitForCalendarSettled(() => this.calendarNextButton.click());
    }

    private async openSlotDialog(slotGraph: Locator): Promise<void> {
        await slotGraph.dispatchEvent("mousedown");
        await slotGraph.dispatchEvent("mouseup");
        await slotGraph.dispatchEvent("click");

        await expect(this.bookingDialogOrderId.first()).toBeVisible();
    }

    private async closeSlotDialog(): Promise<void> {
        await this.bookingDialogCloseButton.first().click();

        await expect(this.bookingDialogCloseButton).toHaveCount(0);
    }

    private async openDialogForOrder(
        candidates: Locator,
        orderId: string,
    ): Promise<Locator | null> {
        const total = await candidates.count();

        for (let index = 0; index < total; index++) {
            const slotGraph = candidates.nth(index);

            await this.openSlotDialog(slotGraph);

            const dialogOrderId = (
                await this.bookingDialogOrderId.first().innerText()
            ).trim();

            if (dialogOrderId === `#${orderId}`) {
                return slotGraph;
            }

            await this.closeSlotDialog();
        }

        return null;
    }

    private async findBookingAcrossWeeks(
        candidates: () => Locator,
        orderId: string,
    ): Promise<Locator> {
        for (let week = 0; week < 7; week++) {
            const slotGraph = await this.openDialogForOrder(candidates(), orderId);

            if (slotGraph) {
                return slotGraph;
            }

            await this.goToNextCalendarPage();
        }

        throw new Error(`No booking found for order #${orderId}`);
    }

    private async expectListedBooking(
        orderId: string,
        from: string,
        till: string,
    ): Promise<void> {
        await this.bookingListToggleButton.click();

        const row = this.bookingRowByOrderId(orderId);

        await expect(this.bookingRowText(row, 3)).toContainText(from);
        await expect(this.bookingRowText(row, 4)).toContainText(till);
    }

    async invoiceOrder(orderId: string): Promise<void> {
        await this.visit(`admin/sales/orders/view/${orderId}`);
        await this.createInvoiceAction.click();
        await this.canCreateTransactionToggle.click();
        await this.createInvoiceButton.click();

        await expect(this.invoiceCreatedMessage).toBeVisible();
    }

    async refundOrder(orderId: string): Promise<void> {
        await this.visit(`admin/sales/orders/view/${orderId}`);
        await this.cancelOrderAction.click();
        await this.refundButton.click();

        await expect(this.refundCreatedMessage).toBeVisible();
    }

    async expectSlotBooking(
        customer: CustomerCredentials,
        orderId: string,
    ): Promise<void> {
        await this.invoiceOrder(orderId);
        await this.openBookingsCalendar();

        const customerName = `${customer.firstName} ${customer.lastName}`;
        const slotTime = "10:35 AM - 11:20 AM";
        const slotGraph = await this.findBookingAcrossWeeks(
            () => this.slotGraphEventsAt(slotTime),
            orderId,
        );

        await expect(this.slotGraphTime(slotGraph)).toHaveText(slotTime);
        await expect(this.bookingDetailValue("Booking From")).toContainText("10:35 AM");
        await expect(this.bookingDetailValue("Booking Till")).toContainText("11:20 AM");
        await expect(this.bookingCustomerName).toContainText(customerName);

        await this.closeSlotDialog();
        await this.expectListedBooking(orderId, "10:35AM", "11:20AM");
        await this.refundOrder(orderId);
    }

    async expectDayBooking(
        customer: CustomerCredentials,
        orderId: string,
    ): Promise<void> {
        await this.openBookingsCalendar();

        const customerName = `${customer.firstName} ${customer.lastName}`;

        await this.findBookingAcrossWeeks(
            () => this.slotGraphEventsOf(customerName),
            orderId,
        );

        await expect(this.bookingDetailValue(/^(Booking|Event) From$/)).toContainText(
            "12:00 PM",
        );
        await expect(this.bookingDetailValue(/^(Booking|Event) Till$/)).toContainText(
            "12:00 PM",
        );
        await expect(this.bookingCustomerName).toContainText(customerName);

        await this.closeSlotDialog();
        await this.expectListedBooking(orderId, "12:00PM", "12:00PM");
    }
}
