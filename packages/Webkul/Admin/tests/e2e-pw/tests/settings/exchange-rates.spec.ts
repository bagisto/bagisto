import { test, expect } from "../../setup";

test.describe("exchange rate management", () => {
    test("create exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");

        await adminPage.getByRole("button", { name: /create/i }).click();

        const currencySelect = adminPage.locator(
            'select[name="target_currency"]'
        );

        await expect(currencySelect).toBeVisible();

        /**
         * Wait for at least one valid option to exist
         */
        await currencySelect
            .locator('option[value]:not([value=""])')
            .first()
            .waitFor({ state: "attached", timeout: 10000 });

        /**
         * Get all valid option values
         */
        const values = await currencySelect
            .locator('option[value]:not([value=""])')
            .evaluateAll((opts) => opts.map((o) => o.value));

        if (values.length === 0) {
            throw new Error("No valid currency options available");
        }

        const selectedValue =
            values.length === 1
                ? values[0]
                : values[Math.floor(Math.random() * values.length)];

        console.log("Selected currency:", selectedValue);

        await currencySelect.selectOption(selectedValue);

        await adminPage.fill(
            'input[name="rate"]',
            (Math.random() * 500).toFixed(2)
        );

        await adminPage.keyboard.press("Enter");

        await expect(adminPage.locator("#app")).toContainText(
            "Exchange Rate Created Successfully"
        );
    });

    test("edit exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");

        const editIcons = adminPage.locator("a:has(span.icon-edit)");
        await editIcons.nth(0).click();

        const currencySelect = adminPage.locator(
            'select[name="target_currency"]'
        );

        await expect(currencySelect).toBeVisible();

        /**
         * Wait for at least one valid option to exist
         */
        await currencySelect
            .locator('option[value]:not([value=""])')
            .first()
            .waitFor({ state: "attached", timeout: 10000 });
        /**
         * Get all valid option values
         */
        const values = await currencySelect
            .locator('option[value]:not([value=""])')
            .evaluateAll((opts) => opts.map((o) => o.value));

        if (values.length === 0) {
            throw new Error("No valid currency options available");
        }

        const selectedValue =
            values.length === 1
                ? values[0]
                : values[Math.floor(Math.random() * values.length)];

        console.log("Selected currency:", selectedValue);

        await currencySelect.selectOption(selectedValue);

        await adminPage.fill(
            'input[name="rate"]',
            (Math.random() * 500).toFixed(2)
        );

        await adminPage.keyboard.press("Enter");

        await adminPage.press('input[name="rate"]', "Enter");

        await expect(
            adminPage.getByText("Exchange Rate Updated Successfully").first()
        ).toBeVisible();
    });

    test("delete exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");

        const iconDelete = adminPage.locator("a:has(span.icon-delete)");
        await iconDelete.nth(0).click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );

        await expect(
            adminPage.getByText("Exchange Rate Deleted Successfully").first()
        ).toBeVisible();
    });
});
