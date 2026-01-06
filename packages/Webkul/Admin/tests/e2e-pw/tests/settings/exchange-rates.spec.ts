import { test, expect } from "../../setup";

test.describe("exchange rate management", () => {
    test("create exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");

        /**
         * Click Create button
         */
        await adminPage.getByRole("button", { name: /create/i }).click();

        /**
         * Target currency select
         */
        const currencySelect = adminPage.locator(
            'select[name="target_currency"]'
        );

        /**
         * Wait for select to be visible
         */
        await expect(currencySelect).toBeVisible();
        console.log("Currency select visible");

        /**
         * Wait until options are loaded
         */
        await adminPage.waitForFunction(
            (select) => select.options.length > 1,
            await currencySelect.elementHandle()
        );

        /**
         * Get option count
         */
        const optionCount = await currencySelect.locator("option").count();
        console.log("Total options:", optionCount);

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        /**
         * Select last option (skipping placeholder)
         */
        const lastIndex = optionCount - 1;
        console.log("Selecting last option index:", lastIndex);

        await currencySelect.selectOption({ index: lastIndex });

        /**
         * Fill exchange rate
         */
        await adminPage.fill(
            'input[name="rate"]',
            (Math.random() * 500).toFixed(2)
        );

        /**
         * Submit form
         */
        await adminPage.keyboard.press("Enter");

        /**
         * Verify success message
         */
        await expect(adminPage.locator("#app")).toContainText(
            "Exchange Rate Created Successfully"
        );
    });

    test("edit exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");

        const editIcons = adminPage.locator("a:has(span.icon-edit)");
        await editIcons.nth(0).click();

        /**
         * Target currency select
         */
        const currencySelect = adminPage.locator(
            'select[name="target_currency"]'
        );

        /**
         * Wait for select to be visible
         */
        await expect(currencySelect).toBeVisible();
        console.log("Currency select visible");

        /**
         * Wait until options are loaded
         */
        await adminPage.waitForFunction(
            (select) => select.options.length > 1,
            await currencySelect.elementHandle()
        );

        /**
         * Get option count
         */
        const optionCount = await currencySelect.locator("option").count();
        console.log("Total options:", optionCount);

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        /**
         * Select last option (skipping placeholder)
         */
        const lastIndex = optionCount - 1;
        console.log("Selecting last option index:", lastIndex);

        await currencySelect.selectOption({ index: lastIndex });

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
