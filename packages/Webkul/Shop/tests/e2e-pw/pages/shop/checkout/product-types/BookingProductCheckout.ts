import { Page, expect } from "@playwright/test";
import { CheckoutHelper } from "../CheckoutHelper";
import { ProductDataManager } from "../../../admin/catalog/products/ProductDataManager";

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

    private async rentalDateSelect(count: number = 1, dateInputSelector: string = 'input[name="booking[date]"]') {
        const dateInput = this.page.locator(dateInputSelector);
        await dateInput.scrollIntoViewIfNeeded();
        await dateInput.click({ force: true });
        await this.page.waitForTimeout(500);

        let skipped = 0;
        let maxMonths = 24;

        while (maxMonths > 0) {
            const enabledDates = this.page.locator(
                ".flatpickr-day:not(.disabled):not(.prevMonthDay):not(.nextMonthDay)"
            );

            const availableCount = await enabledDates.count();

            for (let i = 0; i < availableCount; i++) {
                skipped++;
                if (skipped === count) {
                    await enabledDates.nth(i).click({ force: true });
                    return; // done, only one click ever made
                }
            }

            await this.page.locator(".flatpickr-next-month").nth(1).click({ force: true });
            await this.page.waitForTimeout(300);
            maxMonths--;
        }
    }



    private async getOrderId() {
        const text = await this.page.locator('p.text-xl:has(.text-blue-700)').nth(0).textContent();
        if (!text) return null;

        const match = text.match(/#(\d+)/);
        return match ? parseInt(match[1], 10) : null;
    }

    private async hourMatch(hour: string) {
        await this.page.locator('p.flex > span.icon-arrow-down').nth(0).click()
        const timePattern = new RegExp(`${hour}:\\d{2} [AP]M`);
        await expect(this.page.locator("div.grid.gap-2>div>p.text-sm").nth(1))
            .toContainText(timePattern);
        await this.page.locator('span.icon-cancel').nth(1).click()
    }

    private async eventCheckout(hour: string, tickets: number) {
        await this.addToCartButton.click();
        if (tickets === 1) {
            await this.eventTicket.nth(0).click()
        }
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.page.locator('.icon-cart').click();
        await this.page.waitForTimeout(500)
        await this.hourMatch(hour)
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        return await this.getOrderId();
    }

    async rentalCheckoutDaily() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.rentalDateSelect(1, 'input[name="booking[date_from]"]');
        await this.page.locator('body').click({ position: { x: 0, y: 0 } });
        await this.page.waitForTimeout(500);

        await this.rentalDateSelect(2, 'input[name="booking[date_to]"]');
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.nth(0)).toBeVisible();
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        return await this.getOrderId();
    }
    async rentalCheckoutHourly(hour: String) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
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

    /**
     * Booking product checkout
     */
    async checkout(hour?: string, tickets?: number) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        if (tickets !== undefined) {
            await this.eventCheckout(hour!, tickets)
        } else {
            await this.addToCartButton.click();
            await this.selectFirstAvailableDate();
            await this.selectBookingDateTime();
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
}
