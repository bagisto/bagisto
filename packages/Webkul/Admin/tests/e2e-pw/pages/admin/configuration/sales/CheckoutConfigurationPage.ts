import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import {
    setBooleanSetting,
    setBooleanSettings,
} from "../../../../utils/configuration";

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

    private get myCartSummarySelect() {
        return this.page.locator(
            'select[name="sales[checkout][mini_cart][summary]"]',
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

    async enableShoppingCartSettings(names: string[]): Promise<void> {
        await setBooleanSettings(this.page, names);
    }

    async setMyCartSummary(value: string): Promise<void> {
        await this.myCartSummarySelect.selectOption(value);
    }

    async enableMiniCart(offerInfo: string): Promise<void> {
        await setBooleanSetting(
            this.page,
            "sales[checkout][mini_cart][display_mini_cart]",
        );
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
