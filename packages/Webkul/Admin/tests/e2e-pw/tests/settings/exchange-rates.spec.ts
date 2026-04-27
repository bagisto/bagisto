import { test, expect } from "../../setup";
import { generateCurrency } from "../../utils/faker";

async function createCurrency(adminPage, currency) {
    await adminPage.goto("admin/settings/currencies");
    await adminPage.getByRole("button", { name: "Create Currency" }).click();
    await adminPage.locator('input[name="code"]').fill(currency.code);
    await adminPage.locator('input[name="name"]').fill(currency.name);
    await adminPage.locator('input[name="symbol"]').fill(currency.symbol);
    await adminPage
        .locator('input[name="decimal"]')
        .fill(currency.decimalDigits);
    await adminPage
        .locator('input[name="group_separator"]')
        .fill(currency.groupSeparator);
    await adminPage
        .locator('input[name="decimal_separator"]')
        .fill(currency.decimalSeparator);
    await adminPage.getByRole("button", { name: "Save Currency" }).click();

    if (currency.code === "USD") {
        await expect(
            adminPage.getByText("The code has already been taken."),
        ).toBeVisible();

        return;
    }

    await expect(
        adminPage.getByText("Currency created successfully."),
    ).toBeVisible();

    await expect(
        adminPage.getByText(currency.name, { exact: true }),
    ).toBeVisible();

    await expect(
        adminPage.getByText(currency.code, { exact: true }),
    ).toBeVisible();
}
test.describe("exchange rate management", () => {
    test("create exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates", {
            waitUntil: "networkidle",
        });
        await adminPage.getByRole("button", { name: /create/i }).click();

        const baseCurrencyInput = adminPage.locator(
            'input[name="base_currency"]',
        );
        const baseCurrency = await baseCurrencyInput.inputValue();

        let currency;

        do {
            currency = generateCurrency();
        } while (currency.code === baseCurrency);

        await createCurrency(adminPage, currency);
        await adminPage.goto("admin/settings/exchange-rates", {
            waitUntil: "networkidle",
        });
        await adminPage.getByRole("button", { name: /create/i }).click();

        const currencySelect = adminPage.locator(
            'select[name="target_currency"]',
        );

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
        await adminPage.fill(
            'input[name="rate"]',
            (Math.random() * 500).toFixed(2),
        );
        await adminPage.keyboard.press("Enter");

        await expect(adminPage.locator("#app")).toContainText(
            "Exchange Rate Created Successfully",
            { timeout: 30_000 },
        );
    });

    test("edit exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");

        const editIcons = adminPage.locator("a:has(span.icon-edit)");
        await editIcons.nth(0).click();

        const currencySelect = adminPage.locator(
            'select[name="target_currency"]',
        );

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
        await adminPage.fill(
            'input[name="rate"]',
            (Math.random() * 500).toFixed(2),
        );
        await adminPage.keyboard.press("Enter");

        await expect(
            adminPage.getByText("Exchange Rate Updated Successfully").first(),
        ).toBeVisible();
    });

    test("delete exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");
        const iconDelete = adminPage.locator("a:has(span.icon-delete)");
        await iconDelete.nth(0).click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible",
        );

        await expect(
            adminPage.getByText("Exchange Rate Deleted Successfully").first(),
        ).toBeVisible();
    });
});
