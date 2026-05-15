import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class CheckoutConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully").first();
    }

    private shoppingCartToggle(selector: string) {
        return this.page.locator(selector);
    }

    private get myCartSummarySelect() {
        return this.page.locator(
            'select[name="sales[checkout][my_cart][summary]"]',
        );
    }

    private get miniCartToggle() {
        return this.page.locator(
            'label[for="sales[checkout][mini_cart][display_mini_cart]"]',
        );
    }

    private get miniCartOfferInput() {
        return this.page.locator(
            'input[name="sales[checkout][mini_cart][offer_info]"]',
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/sales/checkout");
    }

    async toggleShoppingCartSettings(selectors: string[]): Promise<void> {
        for (const selector of selectors) {
            await this.shoppingCartToggle(selector).click();
        }
    }

    async setMyCartSummary(value: string): Promise<void> {
        await this.myCartSummarySelect.selectOption(value);
    }

    async enableMiniCart(offerInfo: string): Promise<void> {
        await this.miniCartToggle.click();
        await this.miniCartOfferInput.fill(offerInfo);
    }

    async getMyCartSummaryValue(): Promise<string> {
        return this.myCartSummarySelect.inputValue();
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
