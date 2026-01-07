import { test, expect } from "../../setup";

test.describe("exchange rate management", () => {
    test("create exchange rate", async ({ adminPage }) => {
        /**
         * Open exchange rates page
         */
        await adminPage.goto("admin/settings/exchange-rates", {
            waitUntil: "networkidle",
        });

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
        await expect(currencySelect).toBeVisible({ timeout: 30_000 });
        await currencySelect.click();
        
        const buffer = await adminPage.screenshot();
        console.log(buffer.toString("base64"));

        /**
         * Wait until options are loaded
         */
        const options = currencySelect.locator("option");

        await expect
            .poll(async () => await options.count(), { timeout: 60_000 })
            .toBeGreaterThan(1);

        /**
         * Get option count
         */
        const optionCount = await options.count();
        console.log("Total currency options:", optionCount);

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        /**
         * Pick random option (skip placeholder at index 0)
         */
        const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

        console.log("Selecting random option index:", randomIndex);

        await currencySelect.selectOption({ index: randomIndex });

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
            "Exchange Rate Created Successfully",
            { timeout: 30_000 }
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
        await expect(currencySelect).toBeVisible({ timeout: 30_000 });

        /**
         * Wait until options are loaded
         */
        const options = currencySelect.locator("option");

        await expect
            .poll(async () => await options.count(), { timeout: 60_000 })
            .toBeGreaterThan(1);

        /**
         * Get option count
         */
        const optionCount = await options.count();
        console.log("Total currency options:", optionCount);

        if (optionCount <= 1) {
            throw new Error("No selectable currency options available");
        }

        /**
         * Pick random option (skip placeholder at index 0)
         */
        const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

        console.log("Selecting random option index:", randomIndex);

        await currencySelect.selectOption({ index: randomIndex });

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
