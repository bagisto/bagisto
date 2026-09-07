import { test } from "../../setup";
import {
    CurrenciesPage,
    type CurrencyData,
} from "../../pages/admin/settings/CurrenciesPage";
import { ExchangeRatesPage } from "../../pages/admin/settings/ExchangeRatesPage";
import { generateCurrencyCode, uniqueStamp } from "../../utils/faker";

test.describe("exchange rate management", () => {
    let currenciesPage: CurrenciesPage;
    let exchangeRatesPage: ExchangeRatesPage;
    let currency: CurrencyData;

    test.beforeEach(async ({ adminPage }) => {
        currenciesPage = new CurrenciesPage(adminPage);
        exchangeRatesPage = new ExchangeRatesPage(adminPage);
        currency = {
            code: generateCurrencyCode(),
            name: `Rate Currency ${uniqueStamp()}`,
            symbol: "¤",
        };

        await currenciesPage.createCurrency(currency);
    });

    test.afterEach(async () => {
        try {
            await exchangeRatesPage.deleteExchangeRatesIfPresent([currency.name]);
        } finally {
            await currenciesPage.deleteCurrenciesIfPresent([currency.name]);
        }
    });

    test("should create an exchange rate and list it against its currency", async () => {
        await exchangeRatesPage.createExchangeRate(currency.name, "1.25");

        await exchangeRatesPage.expectExchangeRateListed(currency.name, "1.25");
    });

    test("should reject a second exchange rate for the same currency", async () => {
        await exchangeRatesPage.createExchangeRate(currency.name, "1.25");
        await exchangeRatesPage.attemptCreateExchangeRate(currency.name, "2.5");

        await exchangeRatesPage.expectValidationError(
            "The target currency has already been taken.",
        );
        await exchangeRatesPage.expectExchangeRateListed(currency.name, "1.25");
    });

    test("should update an exchange rate and keep the new value after reload", async () => {
        await exchangeRatesPage.createExchangeRate(currency.name, "1.25");
        await exchangeRatesPage.updateExchangeRate(currency.name, "3.75");

        await exchangeRatesPage.expectExchangeRateListed(currency.name, "3.75");
        await exchangeRatesPage.expectRateInEditForm(currency.name, "3.75");
    });

    test("should delete an exchange rate and remove it from the grid", async () => {
        await exchangeRatesPage.createExchangeRate(currency.name, "1.25");
        await exchangeRatesPage.deleteExchangeRate(currency.name);

        await exchangeRatesPage.expectExchangeRateAbsent(currency.name);
    });
});
