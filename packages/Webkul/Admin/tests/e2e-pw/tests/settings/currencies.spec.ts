import { test } from "../../setup";
import {
    buildCurrency,
    CurrenciesPage,
} from "../../pages/admin/settings/CurrenciesPage";
import { uniqueStamp } from "../../utils/faker";

test.describe("currency management", () => {
    let currenciesPage: CurrenciesPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        currenciesPage = new CurrenciesPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await currenciesPage.deleteCurrenciesIfPresent(created);
    });

    test("should create a currency and list it with its code", async () => {
        const currency = buildCurrency();
        created.push(currency.name);

        await currenciesPage.createCurrency(currency);

        await currenciesPage.expectCurrencyListed(currency);
    });

    test("should reject a currency without a code and name", async () => {
        await currenciesPage.submitEmptyCreateForm();

        await currenciesPage.expectValidationError(
            "The Code field is required",
        );
        await currenciesPage.expectValidationError(
            "The Name field is required",
        );
    });

    test("should reject a currency whose code is already used", async () => {
        const existing = buildCurrency();
        const duplicate = buildCurrency({ code: existing.code });
        created.push(existing.name, duplicate.name);

        await currenciesPage.createCurrency(existing);
        await currenciesPage.attemptCreateCurrency(duplicate);

        await currenciesPage.expectValidationError(
            "The code has already been taken.",
        );
        await currenciesPage.expectCurrencyAbsent(duplicate.name);
        await currenciesPage.expectCurrencyCodeListedOnce(existing.code);
    });

    test("should update a currency and keep the new values after reload", async () => {
        const currency = buildCurrency();
        const changes = { name: `Currency ${uniqueStamp()} renamed`, symbol: "₿" };
        created.push(currency.name, changes.name);

        await currenciesPage.createCurrency(currency);
        await currenciesPage.updateCurrency(currency.name, changes);

        await currenciesPage.expectCurrencyListed({ ...currency, ...changes });
        await currenciesPage.expectCurrencyAbsent(currency.name);
        await currenciesPage.expectSymbolInEditForm(
            changes.name,
            changes.symbol,
        );
    });

    test("should delete a currency and remove it from the grid", async () => {
        const currency = buildCurrency();
        const untouched = buildCurrency();
        created.push(currency.name, untouched.name);

        await currenciesPage.createCurrency(currency);
        await currenciesPage.createCurrency(untouched);
        await currenciesPage.deleteCurrency(currency.name);

        await currenciesPage.expectCurrencyAbsent(currency.name);
        await currenciesPage.expectCurrencyListed(untouched);
    });
});
