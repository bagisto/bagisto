import { test, expect } from "../../setup";
import { generateCurrency } from "../../utils/faker";

async function createCurrency(adminPage, currency) {
    /**
     * Reaching to the currency listing page.
     */
    await adminPage.goto("admin/settings/currencies");

    /**
     * Opening create currency form in modal.
     */
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

    /**
     * Saving currency and closing the modal.
     */
    await adminPage.getByRole("button", { name: "Save Currency" }).click();

    /**
     * The USD currency code was already provided during installation in the test environment.
     */
    if (currency.code === "USD") {
        await expect(
            adminPage.getByText("The code has already been taken.")
        ).toBeVisible();

        return;
    }

    /**
     * Verifying the success message.
     */
    await expect(
        adminPage.getByText("Currency created successfully.")
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
}

test.describe("currency management", () => {
    test("should create a currency", async ({ adminPage }) => {
        const currency = generateCurrency();

        await createCurrency(adminPage, currency);
    });

    test("should edit a currency", async ({ adminPage }) => {
        /**
         * Generating a new currency.
         */
        const currency = generateCurrency();

        await createCurrency(adminPage, {
            ...currency,

            /**
             * Let's use invalid currency name and symbol at time of creation then we use
             * valid currency name and symbol at time of edit.
             */
            name: "INVALID_CURRENCY_NAME",
            symbol: "INVALID_CURRENCY_SYMBOL",
        });

        /**
         * Reaching to the currency listing page.
         */
        await adminPage.goto("admin/settings/currencies");

        /**
         * Clicking on the edit button for the first currency opens the modal.
         */
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit", {
            state: "visible",
        });
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        await adminPage.locator('input[name="name"]').fill(currency.name);
        await adminPage.locator('input[name="symbol"]').fill(currency.symbol);

        /**
         * Saving currency and closing the modal.
         */
        await adminPage.getByRole("button", { name: "Save Currency" }).click();

        /**
         * Verifying the success message.
         */
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
        /**
         * Generating a new currency.
         */
        const currency = generateCurrency();

        await createCurrency(adminPage, currency);

        /**
         * Reaching to the currency listing page.
         */
        await adminPage.goto("admin/settings/currencies");

        /**
         * Delete the first currency.
         */
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

        /**
         * Verifying the success message.
         */
        await expect(
            adminPage.getByText("Currency deleted successfully.")
        ).toBeVisible();

        /**
         * Verifying the currency is not in the listing anymore.
         */
        await expect(
            adminPage.getByText(currency.name, { exact: true })
        ).not.toBeVisible();

        await expect(
            adminPage.getByText(currency.code, { exact: true })
        ).not.toBeVisible();
    });
});
