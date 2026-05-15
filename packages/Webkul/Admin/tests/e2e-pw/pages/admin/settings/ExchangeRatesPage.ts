import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { generateCurrency } from "../../../utils/faker";
import { CurrenciesPage } from "./CurrenciesPage";

export class ExchangeRatesPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.getByRole("button", { name: /create/i });
    }

    private get baseCurrencyInput() {
        return this.page.locator('input[name="base_currency"]');
    }

    private get targetCurrencySelect() {
        return this.page.locator('select[name="target_currency"]');
    }

    private get rateInput() {
        return this.page.locator('input[name="rate"]');
    }

    private get editIcons() {
        return this.page.locator("a:has(span.icon-edit)");
    }

    private get deleteIcons() {
        return this.page.locator("a:has(span.icon-delete)");
    }

    private get agreeButton() {
        return this.page.locator(
            "button.transparent-button + button.primary-button:visible",
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/settings/exchange-rates");
        await this.page.waitForLoadState("networkidle");
    }

    async createExchangeRate(): Promise<void> {
        await this.open();
        await this.createButton.click();

        const baseCurrencyInput = this.baseCurrencyInput;
        const baseCurrency = await baseCurrencyInput.inputValue();

        let currency;
        do {
            currency = generateCurrency();
        } while (currency.code === baseCurrency);

        const currenciesPage = new CurrenciesPage(this.page);
        await currenciesPage.createCurrency(currency);

        await this.open();
        await this.createButton.click();

        const currencySelect = this.targetCurrencySelect;

        await expect(currencySelect).toBeVisible({ timeout: 30_000 });
        const options = currencySelect.locator("option");

        await expect
            .poll(async () => await options.count(), { timeout: 60_000 })
            .toBeGreaterThan(0);

        const optionCount = await options.count();

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

        await currencySelect.selectOption({ index: randomIndex });
        await this.page.fill(
            'input[name="rate"]',
            (Math.random() * 500).toFixed(2),
        );
        await this.page.keyboard.press("Enter");

        await expect(this.page.locator("#app")).toContainText(
            "Exchange Rate Created Successfully",
            { timeout: 30_000 },
        );
    }

    async editFirstExchangeRate(): Promise<void> {
        await this.open();

        const editIcons = this.editIcons;
        await editIcons.nth(0).click();

        const currencySelect = this.targetCurrencySelect;

        await expect(currencySelect).toBeVisible({ timeout: 30_000 });
        const options = currencySelect.locator("option");
        await expect
            .poll(async () => await options.count(), { timeout: 60_000 })
            .toBeGreaterThan(0);
        const optionCount = await options.count();

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

        await currencySelect.selectOption({ index: randomIndex });
        await this.page.fill(
            'input[name="rate"]',
            (Math.random() * 500).toFixed(2),
        );
        await this.page.keyboard.press("Enter");

        await expect(
            this.page.getByText("Exchange Rate Updated Successfully").first(),
        ).toBeVisible();
    }

    async deleteFirstExchangeRate(): Promise<void> {
        await this.open();
        const iconDelete = this.deleteIcons;
        await iconDelete.nth(0).click();

        await this.agreeButton.click();

        await expect(
            this.page.getByText("Exchange Rate Deleted Successfully").first(),
        ).toBeVisible();
    }
}
