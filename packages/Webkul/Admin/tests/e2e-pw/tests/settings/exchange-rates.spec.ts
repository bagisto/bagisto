import { test, expect } from "../../setup";
import { ExchangeRatesPage } from "../../pages/admin/settings/ExchangeRatesPage";
test.describe("exchange rate management", () => {
    test("create exchange rate", async ({ adminPage }) => {
        const exchangeRatesPage = new ExchangeRatesPage(adminPage);
        await exchangeRatesPage.createExchangeRate();
    });

    test("edit exchange rate", async ({ adminPage }) => {
        const exchangeRatesPage = new ExchangeRatesPage(adminPage);
        await exchangeRatesPage.editFirstExchangeRate();
    });

    test("delete exchange rate", async ({ adminPage }) => {
        const exchangeRatesPage = new ExchangeRatesPage(adminPage);
        await exchangeRatesPage.deleteFirstExchangeRate();
    });
});
