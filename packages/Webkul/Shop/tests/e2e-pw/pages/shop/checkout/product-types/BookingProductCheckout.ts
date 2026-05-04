import { Page, expect } from "@playwright/test";
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
        const dateInput = this.page.locator('input[name="booking[date]"]');
        await dateInput.click();

        let maxMonths = 24;

        while (maxMonths > 0) {
            const enabledDates = this.page.locator(
                ".flatpickr-day:not(.disabled):not(.prevMonthDay):not(.nextMonthDay)"
            );

            const count = await enabledDates.count();

            if (count > 0) {
                await enabledDates.first().click();
                break;
            } else {
                await this.page
                    .locator(".flatpickr-next-month")
                    .click();
                await this.page.waitForTimeout(300);
                maxMonths--;
            }
        }
    }

    private async rentalDateSelect(
        count: number = 1,
        dateInputSelector: string
    ) {
        const dateInput = this.page.locator(dateInputSelector);

        await dateInput.click();
        await this.page.waitForSelector(".flatpickr-calendar.open");

        let maxMonths = 12;

        while (maxMonths > 0) {
            const enabledDates = this.page.locator(
                ".flatpickr-calendar.open .flatpickr-day:not(.disabled):not(.prevMonthDay):not(.nextMonthDay)"
            );

            const total = await enabledDates.count();

            if (count <= total) {
                await enabledDates.nth(count - 1).click();
                return;
            }

            // go next month
            await this.page.locator(".flatpickr-next-month").click();
            await this.page.waitForTimeout(300);

            count -= total;
            maxMonths--;
        }

        throw new Error("Date not found");
    }

    private async getOrderId() {
        const text = await this.page.locator('p.text-xl:has(.text-blue-700)').nth(0).textContent();
        if (!text) return null;

        const match = text.match(/#(\d+)/);
        return match ? match[1] : null;
    }

    private async tablematch(table: boolean) {
        await this.page.locator('.icon-cart').click();

        await this.page.locator('p.flex > span.icon-arrow-down').nth(0).click()
        if (table) {
            await expect(this.page.locator('div.grid.gap-2>div>p.text-sm').nth(7))
                .toContainText('Per Table')
            await expect(this.page.locator('div.grid.gap-2>div>p.text-sm').nth(9))
                .toContainText('2')
        } else {
            await expect(this.page.locator('div.grid.gap-2>div>p.text-sm').nth(7))
                .toContainText('Per Guest')
        }

        await this.page.locator('span.icon-cancel').nth(1).click()
    }

    private async hourMatch(hour: string) {
        await this.page.locator('p.flex > span.icon-arrow-down').nth(0).click()
        const timePattern = new RegExp(`${hour}:\\d{2} [AP]M`);
        await expect(this.page.locator("div.grid.gap-2>div>p.text-sm").nth(1))
            .toContainText(timePattern);
        await this.page.locator('div.absolute>span.icon-cancel').nth(1).click()
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
        await this.page.locator('.icon-cart').click();
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
        await this.rentalDateSelect(1, 'input[name="booking[date_from]"]');
        await this.page.locator('body').click({ position: { x: 0, y: 0 } });
        await this.page.waitForTimeout(500);

        await this.rentalDateSelect(2, 'input[name="booking[date_to]"]');
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
        await this.page.locator('.icon-cart').click();
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
            await this.page.locator('.icon-cart').click();
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

            await this.rentalDateSelect(1, 'input[name="booking[date_from]"]');
            await this.page.waitForLoadState('networkidle')
            if (await this.getMinimizebtn().isVisible()) {
                await this.getMinimizebtn().click();
            }

            await this.rentalDateSelect(2, 'input[name="booking[date_to]"]');
            await this.addToCartButton.click();
            await expect(this.addCartSuccess.nth(0)).toBeVisible();
            await this.proceedToCheckout();
            await this.choosePaymentMethod.click();
            await this.placeOrder();
            return await this.getOrderId();
        }

    }

    private async selectslot() {
        const slotstart = this.page.locator('select[name="booking[slot][from]"]')
        const slotend = this.page.locator('select[name="booking[slot][to]"]')
        await this.page.waitForTimeout(2000);
        await this.page.waitForLoadState('networkidle')
        await slotstart.click()
        await slotstart.press("ArrowDown");
        await slotstart.press("Enter");
        await slotend.click()
        await slotend.press("ArrowDown");
        await slotend.press("Enter");

    }

    private async selectBookingDateTime() {
        const slotSelect = this.page.locator('select[name="booking[slot]"]');
        await this.page.waitForTimeout(2000);
        await slotSelect.click();
        await this.page.waitForLoadState('networkidle')

        await slotSelect.press("ArrowDown");
        await slotSelect.press("Enter");
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
        await this.page.locator('.icon-cart').click();

        await this.hourMatch(hour)

        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        return await this.getOrderId();

    }

    async verifyCancellationNotAllowed(orderId?: string) {
        if (orderId) {
            await this.visit(`customer/account/orders/view/${orderId}`)
            await expect(this.page.getByText(' Booking Items Will Not Be Canceled ').nth(0)).toBeVisible()
        } else {
            await expect(this.page.getByText(' Cancellation Not Allowed ').nth(0)).toBeVisible()
        }
    }

    /** visit
     * Booking product checkout
     */
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
            await this.page.locator('.icon-cart').click();
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
            await this.page.locator('div.transparent-button:has(.icon-sales)').click();
            await this.page.locator('div.mb-4:has(label[for="can_create_transaction"])').click();
            await this.page.getByRole('button', { name: " Create Invoice " }).nth(0).click();
            await expect(this.page.getByText('Invoice created successfully', { exact: false }).nth(0)).toBeVisible();
            await this.visit('admin/sales/bookings');
            await this.page.waitForLoadState('networkidle')

            const slotgraph = this.page.locator('div.vuecal__event:has(div.slot.border-emerald-500)').nth(0);

            for (let i = 0; i < 7; i++) {
                await slotgraph.scrollIntoViewIfNeeded();
                if (await slotgraph.isVisible()) {
                    await expect(slotgraph.locator("span.truncate"))
                        .toHaveText("10:35 AM - 11:20 AM");
                    await slotgraph.click();
                    await expect(this.page.locator("div.font-medium.text-gray-900").nth(2))
                        .toContainText("10:35 AM");
                    await expect(this.page.locator("div.font-medium.text-gray-900").nth(3))
                        .toContainText("11:20 AM");
                    await expect(this.page.locator('span.font-medium'))
                        .toContainText(`${customer.firstName} ${customer.lastName}`);
                    await this.page.locator('span.icon-cancel-1').nth(0).click()
                    await this.page.locator('button.icon-list').nth(0).click()
                    const row = this.page.locator('div.row.py-4')
                        .filter({ has: this.page.locator('p').nth(1).filter({ hasText: orderId }) })
                        .first();

                    await expect(row.locator('p').nth(3)).toContainText('10:35AM');

                    await expect(row.locator('p').nth(4)).toContainText('11:20AM');
                    await this.visit(`admin/sales/orders/view/${orderId}`);
                    await this.page.locator('div.transparent-button:has(span.icon-cancel)').click();
                    await this.page.getByRole('button', { name: ' Refund ', exact: true }).nth(0).click();
                    await expect(this.page.getByText('Refund created successfully').nth(0)).toBeVisible();
                    return;
                }
                await this.page.locator("span.icon-sort-right").click();
                await this.page.waitForTimeout(500);
            }

            await expect(slotgraph).toBeVisible();
        } else {
            await this.visit('admin/sales/bookings');
            await this.page.waitForLoadState('networkidle')

            const customerSlot = this.page.locator(`div.slot:has-text('${customer.firstName} ${customer.lastName}')`).nth(0);

            for (let i = 0; i < 7; i++) {
                await customerSlot.scrollIntoViewIfNeeded();

                if (await customerSlot.isVisible()) {
                    await customerSlot.click();
                    await expect(this.page.locator("div.font-medium.text-gray-900").nth(2))
                        .toContainText("12:00 PM");
                    await expect(this.page.locator("div.font-medium.text-gray-900").nth(3))
                        .toContainText("12:00 PM");
                    await this.page.locator('span.icon-cancel-1').nth(0).click()
                    await this.page.locator('button.icon-list').nth(0).click()
                    const row = this.page.locator('div.row.py-4')
                        .filter({ has: this.page.locator('p').nth(1).filter({ hasText: orderId }) })
                        .first();

                    await expect(row.locator('p').nth(3)).toContainText('12:00PM');

                    await expect(row.locator('p').nth(4)).toContainText('12:00PM');
                    return;
                }
                await this.page.locator("span.icon-sort-right").click();
                await this.page.waitForTimeout(500);
            }

            await expect(customerSlot).toBeVisible();
        }
    }

}
