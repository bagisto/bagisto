import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { generateCurrency } from "../../../utils/faker";

interface CurrencyData {
    code: string;
    name: string;
    symbol: string;
    decimalDigits: string;
    groupSeparator: string;
    decimalSeparator: string;
}

export class CurrenciesPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createCurrencyButton() {
        return this.page.getByRole("button", { name: "Create Currency" });
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get symbolInput() {
        return this.page.locator('input[name="symbol"]');
    }

    private get decimalInput() {
        return this.page.locator('input[name="decimal"]');
    }

    private get groupSeparatorInput() {
        return this.page.locator('input[name="group_separator"]');
    }

    private get decimalSeparatorInput() {
        return this.page.locator('input[name="decimal_separator"]');
    }

    private get saveCurrencyButton() {
        return this.page.getByRole("button", { name: "Save Currency" });
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    async open(): Promise<void> {
        await this.visit("admin/settings/currencies");
    }

    async createCurrency(currency?: CurrencyData): Promise<CurrencyData> {
        const currencyData = currency || generateCurrency();
        await this.open();
        await this.createCurrencyButton.click();
        await this.codeInput.fill(currencyData.code);
        await this.nameInput.fill(currencyData.name);
        await this.symbolInput.fill(currencyData.symbol);
        await this.decimalInput.fill(currencyData.decimalDigits);
        await this.groupSeparatorInput.fill(currencyData.groupSeparator);
        await this.decimalSeparatorInput.fill(currencyData.decimalSeparator);
        await this.saveCurrencyButton.click();

        if (currencyData.code === "USD") {
            await expect(
                this.page.getByText("The code has already been taken."),
            ).toBeVisible();
            return currencyData;
        }

        await expect(
            this.page.getByText("Currency created successfully."),
        ).toBeVisible();

        await expect(
            this.page.getByText(currencyData.name, { exact: true }),
        ).toBeVisible();

        await expect(
            this.page.getByText(currencyData.code, { exact: true }),
        ).toBeVisible();

        return currencyData;
    }

    async editFirstCurrency(newName: string, newSymbol: string): Promise<void> {
        await this.open();
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await this.nameInput.fill(newName);
        await this.symbolInput.fill(newSymbol);
        await this.saveCurrencyButton.click();

        await expect(
            this.page.getByText("Currency updated successfully."),
        ).toBeVisible();

        await expect(
            this.page.getByText(newName, { exact: true }),
        ).toBeVisible();
    }

    async deleteFirstCurrency(): Promise<void> {
        await this.open();
        await this.deleteIcons.first().waitFor({ state: "visible" });
        await this.deleteIcons.first().click();

        await this.page.waitForSelector("text=Are you sure");
        const agreeButton = this.agreeButton;

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            this.page.getByText("Currency deleted successfully."),
        ).toBeVisible();
    }
}
