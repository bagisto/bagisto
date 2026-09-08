import { Locator, Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";

export class BookingProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    /**
     * Picks the earliest bookable date after today.
     *
     * Today is skipped deliberately. A slot is only offered while its start
     * time is still in the future, and the server re-validates that when the
     * checkout page is requested, so a same-day slot whose start falls between
     * adding it to the cart and placing the order is rejected as expired.
     */
    private async selectFirstAvailableDate() {
        await this.bookingDateInput.click();
        await this.flatpickrOpenCalendar.waitFor({ state: "visible" });

        for (let month = 0; month < 24; month++) {
            if (await this.flatpickrOpenEnabledDatesAfterToday.count()) {
                await this.flatpickrOpenEnabledDatesAfterToday.first().click();

                return;
            }

            await this.goToNextFlatpickrMonth();
        }

        throw new Error("No bookable date after today found in the next two years");
    }

    private async rentalDateSelect(count: number, dateInput: Locator) {
        await dateInput.click();
        await this.flatpickrOpenCalendar.waitFor({ state: "visible" });

        for (let month = 0; month < 12; month++) {
            const total = await this.flatpickrOpenEnabledDates.count();

            if (count <= total) {
                await this.flatpickrOpenEnabledDates.nth(count - 1).click();

                return;
            }

            await this.goToNextFlatpickrMonth();
            count -= total;
        }

        throw new Error("Date not found");
    }

    private async closeCalendar() {
        await this.pageBody.click({ position: { x: 0, y: 0 } });

        await expect(this.flatpickrOpenCalendar).toHaveCount(0);
    }

    private cartDetailLabel(label: string) {
        return this.miniCartDrawer.locator("p", {
            hasText: new RegExp(`^\\s*${label}:\\s*$`),
        });
    }

    private cartDetailValue(label: string) {
        return this.cartDetailLabel(label).locator("xpath=following-sibling::p[1]");
    }

    private async expandCartSummaries() {
        await expect(this.miniCartDrawer).toBeVisible();
        await expect(this.cartSummaryToggles).not.toHaveCount(0);

        const toggleCount = await this.cartSummaryToggles.count();

        for (let index = 0; index < toggleCount; index++) {
            await this.cartSummaryToggles.nth(index).click();
        }
    }

    private async expectCartSummaryTable(table: boolean) {
        await this.shoppingCartButton.click();
        await this.expandCartSummaries();

        if (table) {
            await expect(this.cartDetailValue("Charged Per")).toHaveText("Per Table");
            await expect(this.cartDetailValue("Guest Limit Per Table")).toHaveText("2");
        } else {
            await expect(this.cartDetailValue("Charged Per")).toHaveText("Per Guest");
            await expect(this.cartDetailLabel("Guest Limit Per Table")).toHaveCount(0);
        }

        await this.cartDismissButton.click();
    }

    private async expectCartSummaryHour(hour: string) {
        await this.shoppingCartButton.click();
        await this.expandCartSummaries();

        await expect(this.miniCartDrawer).toContainText(
            new RegExp(`${hour}:\\d{2} [AP]M`),
        );

        await this.cartDismissButton.click();
    }

    private async selectSlotRange() {
        await this.bookingSlotStartSelect.waitFor({ state: "visible" });
        await this.bookingSlotStartSelect.selectOption({ index: 1 });
        await this.bookingSlotEndSelect.waitFor({ state: "visible" });
        await this.bookingSlotEndSelect.selectOption({ index: 1 });
    }

    private async selectFirstSlot() {
        await this.bookingSlotSelect.waitFor({ state: "visible" });

        await expect
            .poll(async () => this.bookingSlotSelect.locator("option").count())
            .toBeGreaterThan(1);

        await expect(async () => {
            await this.bookingSlotSelect.selectOption({ index: 1 });
            await this.bookingSlotSelect.dispatchEvent("change");

            await expect(this.bookingSlotSelect).not.toHaveValue("", { timeout: 2000 });
        }).toPass({ timeout: 30000 });
    }

    private async finishCheckout(hour?: string): Promise<string> {
        if (hour) {
            await this.expectCartSummaryHour(hour);
        }

        await this.proceedWithSavedAddress();
        await this.choosePayment("moneytransfer");

        return this.placeOrder();
    }

    async expectCancellationNotAllowedOnProduct(): Promise<void> {
        await expect(this.cancellationNotAllowedText).toBeVisible();
    }

    async expectCancellationNotAllowedOnOrder(orderId: string): Promise<void> {
        await this.visit(`customer/account/orders/view/${orderId}`);

        await expect(this.bookingItemsWillNotBeCanceledText).toBeVisible();
    }

    async checkout(
        productName: string,
        options: { hour?: string; tickets?: number; allowCancellation?: boolean } = {},
    ): Promise<string> {
        await this.openProduct(productName);

        if (options.tickets !== undefined) {
            if (options.tickets === 1) {
                await this.eventTicket.nth(0).click();
            }
        } else {
            await this.selectFirstAvailableDate();
            await this.selectFirstSlot();
        }

        if (options.allowCancellation === false) {
            await this.expectCancellationNotAllowedOnProduct();
        }

        await this.addOpenProductToCart();

        return this.finishCheckout(options.hour);
    }

    async rentalCheckoutDaily(
        productName: string,
        allowCancellation?: boolean,
    ): Promise<string> {
        await this.openProduct(productName);
        await this.rentalDateSelect(1, this.bookingDateFromInput);
        await this.closeCalendar();
        await this.rentalDateSelect(2, this.bookingDateToInput);

        if (allowCancellation === false) {
            await this.expectCancellationNotAllowedOnProduct();
        }

        await this.addOpenProductToCart();

        return this.finishCheckout();
    }

    async rentalCheckoutHourly(
        productName: string,
        hour: string,
        allowCancellation?: boolean,
    ): Promise<string> {
        await this.openProduct(productName);

        if (allowCancellation === false) {
            await this.expectCancellationNotAllowedOnProduct();
        }

        await this.selectFirstAvailableDate();
        await this.selectFirstSlot();
        await this.selectSlotRange();
        await this.addOpenProductToCart();

        return this.finishCheckout(hour);
    }

    async rentalCheckoutHourlyOrDaily(
        productName: string,
        hourly: boolean,
        hour?: string,
        allowCancellation?: boolean,
    ): Promise<string> {
        await this.openProduct(productName);

        if (allowCancellation === false) {
            await this.expectCancellationNotAllowedOnProduct();
        }

        if (hourly) {
            await this.hourlyRadio.click();
            await this.selectFirstAvailableDate();
            await this.selectFirstSlot();
            await this.selectSlotRange();
            await this.addOpenProductToCart();

            return this.finishCheckout(hour);
        }

        await this.dailyRadio.click();
        await this.rentalDateSelect(1, this.bookingDateFromInput);
        await this.closeCalendar();
        await this.rentalDateSelect(2, this.bookingDateToInput);
        await this.addOpenProductToCart();

        return this.finishCheckout();
    }

    async tableCheckout(
        productName: string,
        table: boolean,
        hour: string,
        allowCancellation?: boolean,
    ): Promise<string> {
        await this.openProduct(productName);

        if (allowCancellation === false) {
            await this.expectCancellationNotAllowedOnProduct();
        }

        await this.selectFirstAvailableDate();
        await this.selectFirstSlot();
        await this.addOpenProductToCart();
        await this.expectCartSummaryTable(table);

        return this.finishCheckout(hour);
    }
}
