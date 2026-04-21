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

    /**
     * Select next Sunday and available slot
     */
    private async selectBookingDateTime() {
        const today = new Date();
        const daysUntilSunday = (7 - today.getDay()) % 7 || 7;
        const nextSunday = new Date(today);
        nextSunday.setDate(today.getDate() + daysUntilSunday);

        const formattedSunday = nextSunday.toISOString().split("T")[0];

        const dateInput = this.page.locator('input[name="booking[date]"]');
        await dateInput.fill(formattedSunday);
        await dateInput.press("Enter");

        const slotSelect = this.page.locator('select[name="booking[slot]"]');
        await this.page.waitForTimeout(2000);
        await slotSelect.click();
        await slotSelect.press("ArrowDown");
        await dateInput.press("Enter");
    }

    /**
     * Booking product checkout
     */
    async checkout() {
        const productName = ProductDataManager.readProductData();
        await this.searchProduct(productName);
        await this.addToCartButton.click();
        await this.selectBookingDateTime();
        await this.addToCartButton.click();
        await expect(this.addCartSuccess.first()).toBeVisible();
        await this.proceedToCheckout();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
