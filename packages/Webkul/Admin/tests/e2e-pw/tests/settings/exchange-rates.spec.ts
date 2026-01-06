import { test, expect } from "../../setup";

test.describe("exchange rate management", () => {
    test("create exchange rate", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/exchange-rates");

        await adminPage.getByRole("button", { name: /create/i }).click();

        const currencySelect = adminPage.locator(
            'select[name="target_currency"]'
        );

        /**
         * Wait for the select element
         */
        await expect(currencySelect).toBeVisible();
        console.log("Visible");

        /**
         *  Wait until options are visible
         */
        await adminPage.waitForFunction(
            (select) => select.options.length > 1,
            await currencySelect.elementHandle()
        );

        /**
         * Get actual option count
         */
        const optionCount = await currencySelect.locator("option").count();
        console.log(optionCount);

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        /**
         * Random index
         */
        const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

        console.log("Selecting option index:", randomIndex);

        await currencySelect.selectOption({ index: randomIndex });

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

        /**
         * Wait for the select element
         */
        await expect(currencySelect).toBeVisible();

        /**
         *  Wait until options are visible
         */
        await adminPage.waitForFunction(
            (select) => select.options.length > 1,
            await currencySelect.elementHandle()
        );

        /**
         * Get actual option count
         */
        const optionCount = await currencySelect.locator("option").count();

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        /**
         * Random index
         */
        const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

        console.log("Selecting option index:", randomIndex);

        await currencySelect.selectOption({ index: randomIndex });

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
