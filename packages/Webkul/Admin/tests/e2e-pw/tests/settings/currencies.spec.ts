import { test, expect } from "../../setup";
import { CurrenciesPage } from "../../pages/admin/settings/CurrenciesPage";
import { generateCurrency } from "../../utils/faker";

test.describe("currency management", () => {
    test("should create a currency", async ({ adminPage }) => {
        const currenciesPage = new CurrenciesPage(adminPage);
        await currenciesPage.createCurrency();
    });

    test("should edit a currency", async ({ adminPage }) => {
        const currenciesPage = new CurrenciesPage(adminPage);
        const currency = generateCurrency();
        await currenciesPage.createCurrency({
            ...currency,
            name: "INVALID_CURRENCY_NAME",
            symbol: "INVALID_CURRENCY_SYMBOL",
        });
        await currenciesPage.editFirstCurrency(currency.name, currency.symbol);
    });

    test("should delete a currency", async ({ adminPage }) => {
        const currenciesPage = new CurrenciesPage(adminPage);
        const currency = await currenciesPage.createCurrency();
        await currenciesPage.deleteFirstCurrency();

        await expect(
            adminPage.getByText(currency.name, { exact: true }),
        ).not.toBeVisible();

        await expect(
            adminPage.getByText(currency.code, { exact: true }),
        ).not.toBeVisible();
    });
});
