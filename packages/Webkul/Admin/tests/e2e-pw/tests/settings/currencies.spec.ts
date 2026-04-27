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
            adminPage.getByText("The code has already been taken.")
        ).toBeVisible();

        return;
    }

    await expect(
        adminPage.getByText("Currency created successfully.")
    ).toBeVisible();

    await expect(
        adminPage.getByText(currency.name, { exact: true })
    ).toBeVisible();

    await expect(
        adminPage.getByText(currency.code, { exact: true })
    ).toBeVisible();
}

test.describe("currency management", () => {
    test("should create a currency", async ({ adminPage }) => {
        const currency = generateCurrency();
        await createCurrency(adminPage, currency);
    });

    test("should edit a currency", async ({ adminPage }) => {
        const currency = generateCurrency();
        await createCurrency(adminPage, {
            ...currency,
            name: "INVALID_CURRENCY_NAME",
            symbol: "INVALID_CURRENCY_SYMBOL",
        });

        await adminPage.goto("admin/settings/currencies");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit", {
            state: "visible",
        });
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();
        await adminPage.locator('input[name="name"]').fill(currency.name);
        await adminPage.locator('input[name="symbol"]').fill(currency.symbol);
        await adminPage.getByRole("button", { name: "Save Currency" }).click();

        await expect(
            adminPage.getByText("Currency updated successfully.")
        ).toBeVisible();

        /**
         * Verifying the currency in the listing.
         */

        await expect(
            adminPage.getByText(currency.name, { exact: true })
        ).toBeVisible();

        await expect(
            adminPage.getByText(currency.code, { exact: true })
        ).toBeVisible();
    });

    test("should delete a currency", async ({ adminPage }) => {
        const currency = generateCurrency();
        await createCurrency(adminPage, currency);
        await adminPage.goto("admin/settings/currencies");
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        await adminPage.waitForSelector("text=Are you sure");
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("Currency deleted successfully.")
        ).toBeVisible();
        
        await expect(
            adminPage.getByText(currency.name, { exact: true })
        ).not.toBeVisible();

        await expect(
            adminPage.getByText(currency.code, { exact: true })
        ).not.toBeVisible();
    });
});
