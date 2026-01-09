import { Page, expect, Locator } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import fs from "fs";

export class BookingProductCheckout {
    readonly page: Page;
    readonly locators: WebLocators;

    constructor(page: Page) {
        this.page = page;
        this.locators = new WebLocators(page);
    }

    async customerCheckout() {
        const product = JSON.parse(
            fs.readFileSync("product-data.json", "utf-8")
        );

        const productName = product.name;

        await this.page.goto("");
        await this.page.waitForLoadState("networkidle");

        await this.locators.searchInput.fill(productName);
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.click();

        /* ------------------ Select NEXT SUNDAY ------------------ */

        const today = new Date();
        const daysUntilSunday = (7 - today.getDay()) % 7 || 7;

        const nextSunday = new Date(today);
        nextSunday.setDate(today.getDate() + daysUntilSunday);

        // Format YYYY-MM-DD
        const formattedSunday = nextSunday.toISOString().split("T")[0];

        console.log("Next Sunday:", formattedSunday);

        // Type date directly
        const dateInput = this.page.locator('input[name="booking[date]"]');

        await dateInput.fill(formattedSunday);
        await dateInput.press("Enter"); // triggers change event

        /* ------------------ Wait for slots to load ------------------ */

        const slotSelect = this.page.locator('select[name="booking[slot]"]');

        // Wait until slot options are populated
        await this.page.waitForTimeout(2000);

        // Select first available slot
        await slotSelect.selectOption({ value: "1768113000-1768631400" });

        /* ------------------ Continue checkout ------------------ */

        await this.locators.addToCartButton.click();
        await expect(this.locators.addCartSuccess.first()).toBeVisible();
        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.locators.clickProcessButton.click();
        await this.locators.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.locators.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
        // await expect(this.locators.CheckoutsuccessPage).toBeVisible();
    }
}
