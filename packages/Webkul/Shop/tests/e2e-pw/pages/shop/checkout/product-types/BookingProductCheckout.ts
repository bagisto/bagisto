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
        await dateInput.click()
        while (true) {
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
            }
        }
    }


    private async getOrderId() {
        const text = await this.page.locator('p.text-xl:has(.text-blue-700)').nth(0).textContent();
        if (!text) return null;

        const match = text.match(/#(\d+)/);
        return match ? parseInt(match[1], 10) : null;
    }

    private async hourMatch(hour: string) {
        // const hour = startHour; // e.g., "12"
        const timePattern = new RegExp(`${hour}:\\d{2} [AP]M`);
        await expect(this.page.locator("div.grid.gap-2>div>p.text-sm").nth(1))
            .toContainText(timePattern);
        await this.page.locator('span.icon-cancel').nth(1).click()
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
    async checkout(hour: string) {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.selectFirstAvailableDate();
        await this.selectBookingDateTime();
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.page.locator('.icon-cart').click();
        await this.page.waitForTimeout(500)
        await this.page.locator('div.grid.select-none').nth(0).click()
        await this.hourMatch(hour)

        // await expect(this.page.locator('div.grid.gap-2>div>p.text-sm').nth(1)).toHaveText(/12:00 [AP]M/);
        // await expect(this.page.locator('div.grid.gap-2>div>p.text-sm').nth(3)).toHaveText(/12:00 [AP]M/);
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
        return await this.getOrderId();

    }
}
