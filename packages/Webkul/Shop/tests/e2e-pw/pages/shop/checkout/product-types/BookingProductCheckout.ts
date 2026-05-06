import { Locator, Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";
import { ProductDataManager } from "../../../admin/catalog/products/ProductDataManager";
import { loginAsAdmin } from "../../../../utils/admin";

/**
 * Booking product checkout flow
 * Handles date/slot selection and checkout
 */
export class BookingProductCheckout extends CheckoutHelper {
    constructor(page: Page) {
        super(page);
    }

    private async selectFirstAvailableDate() {
        await this.bookingDateInput.click();
        let maxMonths = 24;
        while (maxMonths > 0) {
            const count = await this.flatpickrEnabledDates.count();
            if (count > 0) {
                await this.flatpickrEnabledDates.first().click();
                break;
            } else {
                await this.flatpickrNextMonthButton.click();
                await this.page.waitForTimeout(300);
                maxMonths--;
            }
        }
    }

    private async rentalDateSelect(count: number = 1, dateInput: Locator) {
        await dateInput.click();
        await this.flatpickrOpenCalendar.waitFor({ state: "visible" });
        let maxMonths = 12;
        while (maxMonths > 0) {
            const total = await this.flatpickrOpenEnabledDates.count();
            if (count <= total) {
                await this.flatpickrOpenEnabledDates.nth(count - 1).click();
                return;
            }
            await this.flatpickrNextMonthButton.click();
            await this.page.waitForTimeout(300);
            count -= total;
            maxMonths--;
        }
        throw new Error("Date not found");
    }

    private async getOrderId() {
        const text = await this.orderIdHeading.textContent();
        if (!text) return null;
        const match = text.match(/#(\d+)/);
        return match ? match[1] : null;
    }

    private async tablematch(table: boolean) {
        await this.shoppingCartIcon.click();
        await this.cartSummaryToggle.click()
        if (table) {
            await expect(this.cartSummaryText(7)).toContainText('Per Table')
            await expect(this.cartSummaryText(9)).toContainText('2')
        } else {
            await expect(this.cartSummaryText(7)).toContainText('Per Guest')
        }
        await this.cartDismissButton.click()
    }

    private async hourMatch(hour: string) {
        await this.cartSummaryToggle.click()
        const timePattern = new RegExp(`${hour}:\\d{2} [AP]M`);
        await expect(this.cartSummaryText(1)).toContainText(timePattern);
        await this.cartOverlayDismissButton.click()
    }

    private async eventCheckout(hour: string, tickets: number, allowCancellation?: boolean) {
        await this.addToCartButton.click();
        if (tickets === 1) {
            await this.eventTicket.nth(0).click()
        }
        if (allowCancellation === false) {
            await this.verifyCancellationNotAllowed()
        }
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.shoppingCartIcon.click();
        await this.page.waitForTimeout(500)
        await this.hourMatch(hour)
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        const orderId = await this.getOrderId();
        return orderId
    }

    async rentalCheckoutDaily(allowCancellation?: boolean) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.rentalDateSelect(1, this.bookingDateFromInput);
        await this.pageBody.click({ position: { x: 0, y: 0 } });
        await this.page.waitForTimeout(500);
        await this.rentalDateSelect(2, this.bookingDateToInput);
        if (allowCancellation === false) {
            await this.verifyCancellationNotAllowed()
        }
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.nth(0)).toBeVisible();
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        return await this.getOrderId();
    }

    async rentalCheckoutHourly(hour: String, allowCancellation?: boolean) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        if (allowCancellation === false) {
            await this.verifyCancellationNotAllowed()
        }
        await this.selectFirstAvailableDate();
        await this.selectBookingDateTime();
        await this.selectslot()
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.shoppingCartIcon.click();
        await this.page.waitForTimeout(500)
        if (hour) {
            await this.hourMatch(hour);
        }
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        return await this.getOrderId();
    }

    async rentalcheckoutHourlyDaily(hourly: boolean, hour?: string, allowCancellation?: boolean) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        if (allowCancellation === false) {
            await this.verifyCancellationNotAllowed()
        }
        if (hourly) {
            await this.hourlyRadio.click()
            await this.selectFirstAvailableDate();
            await this.selectBookingDateTime();
            await this.selectslot()
            await this.addToCartButton.click();
            await expect(this.addCartSuccess.first()).toBeVisible();
            await this.shoppingCartIcon.click();
            await this.page.waitForTimeout(500)
            if (hour) {
                await this.hourMatch(hour);
            }
            await this.proceedToCheckout();
            await this.choosePaymentMethod.click();
            await this.placeOrder();
            return await this.getOrderId();
        } else {
            await this.dailyRadio.click()
            await this.rentalDateSelect(1, this.bookingDateFromInput);
            await this.page.waitForLoadState('networkidle')
            if (await this.getMinimizebtn().isVisible()) {
                await this.getMinimizebtn().click();
            }
            await this.rentalDateSelect(2, this.bookingDateToInput);
            await this.addToCartButton.click();
            await expect(this.addCartSuccess.nth(0)).toBeVisible();
            await this.proceedToCheckout();
            await this.choosePaymentMethod.click();
            await this.placeOrder();
            return await this.getOrderId();
        }
    }

    private async selectslot() {
        await this.page.waitForTimeout(2000);
        await this.page.waitForLoadState('networkidle')
        await this.bookingSlotStartSelect.click()
        await this.bookingSlotStartSelect.press("ArrowDown");
        await this.bookingSlotStartSelect.press("Enter");
        await this.bookingSlotEndSelect.click()
        await this.bookingSlotEndSelect.press("ArrowDown");
        await this.bookingSlotEndSelect.press("Enter");
    }

    private async selectBookingDateTime() {
        await this.page.waitForTimeout(2000);
        await this.bookingSlotSelect.click();
        await this.page.waitForLoadState('networkidle')
        await this.bookingSlotSelect.press("ArrowDown");
        await this.bookingSlotSelect.press("Enter");
    }

    async table_checkout(table: boolean, hour: string, allowCancellation?: boolean) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        if (allowCancellation === false) {
            await this.verifyCancellationNotAllowed()
        }
        await this.selectFirstAvailableDate();
        await this.selectBookingDateTime();
        await this.page.waitForLoadState('networkidle')
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.tablematch(table)
        await this.shoppingCartIcon.click();
        await this.hourMatch(hour)
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        return await this.getOrderId();
    }

    async verifyCancellationNotAllowed(orderId?: string) {
        if (orderId) {
            await this.visit(`customer/account/orders/view/${orderId}`)
            await expect(this.bookingItemsWillNotBeCanceledText).toBeVisible()
        } else {
            await expect(this.cancellationNotAllowedText).toBeVisible()
        }
    }

    async checkout(hour?: string, tickets?: number, allowCancellation?: boolean) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        if (tickets !== undefined) {
            return await this.eventCheckout(hour!, tickets, allowCancellation)
        } else {
            await this.addToCartButton.click();
            await this.selectFirstAvailableDate();
            await this.selectBookingDateTime();
            if (allowCancellation === false) {
                await this.verifyCancellationNotAllowed()
            }
            await this.addToCartButton.click();
            await expect(this.addCartSuccess.first()).toBeVisible();
            await this.shoppingCartIcon.click();
            await this.page.waitForTimeout(500)
            if (hour) {
                await this.hourMatch(hour);
            }
            await this.proceedToCheckout();
            await this.choosePaymentMethod.click();
            await this.placeOrder();
            return await this.getOrderId();
        }
    }

    async verifyduration(customer: any, orderId: string, slot: boolean) {
        await this.visit('admin/sales/bookings');
        await loginAsAdmin(this.page);
        if (slot) {
            await this.visit(`admin/sales/orders/view/${orderId}`);
            await this.createInvoiceAction.click();
            await this.canCreateTransactionToggle.click();
            await this.createInvoiceButton.click();
            await expect(this.invoiceCreatedSuccessText).toBeVisible();
            await this.visit('admin/sales/bookings');
            await this.page.waitForLoadState('networkidle');
            const slotgraph = this.slotGraphEvent;
            for (let i = 0; i < 7; i++) {
                const isVisible = await slotgraph.isVisible().catch(() => false);
                if (isVisible) {
                    await expect(this.slotGraphTimeText(slotgraph)).toHaveText("10:35 AM - 11:20 AM");
                    await slotgraph.click();
                    await expect(this.bookingDetailText(2)).toContainText("10:35 AM");
                    await expect(this.bookingDetailText(3)).toContainText("11:20 AM");
                    await expect(this.bookingCustomerNameText).toContainText(`${customer.firstName} ${customer.lastName}`);
                    await this.bookingDialogCloseButton.click();
                    await this.bookingListToggleButton.click();
                    const row = this.bookingRowByOrderId(orderId);
                    await expect(this.bookingRowText(row, 3)).toContainText('10:35AM');
                    await expect(this.bookingRowText(row, 4)).toContainText('11:20AM');
                    await this.visit(`admin/sales/orders/view/${orderId}`);
                    await this.cancelOrderAction.click();
                    await this.refundButton.click();
                    await expect(this.refundCreatedSuccessText).toBeVisible();
                    return;
                }
                await this.bookingCalendarNextButton.click();
                await this.page.waitForLoadState('networkidle');
                await this.page.waitForTimeout(1000);
            }
            await expect(slotgraph).toBeVisible();
        } else {
            await this.visit('admin/sales/bookings');
            await this.page.waitForLoadState('networkidle');
            const customerSlot = this.customerSlotByName(`${customer.firstName} ${customer.lastName}`);
            for (let i = 0; i < 7; i++) {
                const isVisible = await customerSlot.isVisible().catch(() => false);
                if (isVisible) {
                    await customerSlot.click();
                    await expect(this.bookingDetailText(2)).toContainText("12:00 PM");
                    await expect(this.bookingDetailText(3)).toContainText("12:00 PM");
                    await this.bookingDialogCloseButton.click();
                    await this.bookingListToggleButton.click();
                    const row = this.bookingRowByOrderId(orderId);
                    await expect(this.bookingRowText(row, 3)).toContainText('12:00PM');
                    await expect(this.bookingRowText(row, 4)).toContainText('12:00PM');
                    return;
                }
                await this.bookingCalendarNextButton.click();
                await this.page.waitForLoadState('networkidle');
                await this.page.waitForTimeout(1000);
            }
            await expect(customerSlot).toBeVisible();
        }
    }
}
