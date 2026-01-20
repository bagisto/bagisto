// import { test, expect } from "../../setup";
// import { generateCurrency } from "../../utils/faker";

// async function createCurrency(adminPage, currency) {
//     /**
//      * Reaching to the currency listing page.
//      */
//     await adminPage.goto("admin/settings/currencies");

//     /**
//      * Opening create currency form in modal.
//      */
//     await adminPage.getByRole("button", { name: "Create Currency" }).click();
//     await adminPage.locator('input[name="code"]').fill(currency.code);
//     await adminPage.locator('input[name="name"]').fill(currency.name);
//     await adminPage.locator('input[name="symbol"]').fill(currency.symbol);
//     await adminPage
//         .locator('input[name="decimal"]')
//         .fill(currency.decimalDigits);
//     await adminPage
//         .locator('input[name="group_separator"]')
//         .fill(currency.groupSeparator);
//     await adminPage
//         .locator('input[name="decimal_separator"]')
//         .fill(currency.decimalSeparator);

//     /**
//      * Saving currency and closing the modal.
//      */
//     await adminPage.getByRole("button", { name: "Save Currency" }).click();

//     /**
//      * The USD currency code was already provided during installation in the test environment.
//      */
//     if (currency.code === "USD") {
//         await expect(
//             adminPage.getByText("The code has already been taken.")
//         ).toBeVisible();

//         return;
//     }

//     /**
//      * Verifying the success message.
//      */
//     await expect(
//         adminPage.getByText("Currency created successfully.")
//     ).toBeVisible();

//     /**
//      * Verifying the currency in the listing.
//      */
//     await expect(
//         adminPage.getByText(currency.name, { exact: true })
//     ).toBeVisible();

//     await expect(
//         adminPage.getByText(currency.code, { exact: true })
//     ).toBeVisible();
// }
// test.describe("exchange rate management", () => {
//     test("create exchange rate", async ({ adminPage }) => {
//         /**
//          * Open exchange rates page
//          */
//         await adminPage.goto("admin/settings/exchange-rates", {
//             waitUntil: "networkidle",
//         });
//         await adminPage.getByRole("button", { name: /create/i }).click();

//         /**
//          *  Get the input element
//          */
//         const baseCurrencyInput = adminPage.locator(
//             'input[name="base_currency"]'
//         );
//         const baseCurrency = await baseCurrencyInput.inputValue();

//         /**
//          * Create currency for exchange rate
//          */
//         let currency;

//         do {
//             currency = generateCurrency();
//         } while (currency.code === baseCurrency);

//         await createCurrency(adminPage, currency);

//         await adminPage.goto("admin/settings/exchange-rates", {
//             waitUntil: "networkidle",
//         });
//         await adminPage.getByRole("button", { name: /create/i }).click();

//         /**
//          * Target currency select
//          */
//         const currencySelect = adminPage.locator(
//             'select[name="target_currency"]'
//         );

//         /**
//          * Wait for select to be visible
//          */
//         await expect(currencySelect).toBeVisible({ timeout: 30_000 });

//         /**
//          * Wait until options are loaded
//          */
//         const options = currencySelect.locator("option");

//         await expect
//             .poll(async () => await options.count(), { timeout: 60_000 })
//             .toBeGreaterThan(0);

//         /**
//          * Get option count
//          */
//         const optionCount = await options.count();

//         if (optionCount <= 1) {
//             throw new Error("No selectable currency options available");
//         }

//         /**
//          * Pick random option (skip placeholder at index 0)
//          */
//         const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

//         await currencySelect.selectOption({ index: randomIndex });

//         /**
//          * Fill exchange rate
//          */
//         await adminPage.fill(
//             'input[name="rate"]',
//             (Math.random() * 500).toFixed(2)
//         );

//         /**
//          * Submit form
//          */
//         await adminPage.keyboard.press("Enter");

//         /**
//          * Verify success message
//          */
//         await expect(adminPage.locator("#app")).toContainText(
//             "Exchange Rate Created Successfully",
//             { timeout: 30_000 }
//         );
//     });

//     test("edit exchange rate", async ({ adminPage }) => {
//         await adminPage.goto("admin/settings/exchange-rates");

//         const editIcons = adminPage.locator("a:has(span.icon-edit)");
//         await editIcons.nth(0).click();

//         /**
//          * Target currency select
//          */
//         const currencySelect = adminPage.locator(
//             'select[name="target_currency"]'
//         );

//         /**
//          * Wait for select to be visible
//          */
//         await expect(currencySelect).toBeVisible({ timeout: 30_000 });

//         /**
//          * Wait until options are loaded
//          */
//         const options = currencySelect.locator("option");

//         await expect
//             .poll(async () => await options.count(), { timeout: 60_000 })
//             .toBeGreaterThan(0);

//         /**
//          * Get option count
//          */
//         const optionCount = await options.count();

//         if (optionCount <= 1) {
//             throw new Error("No selectable currency options available");
//         }

//         /**
//          * Pick random option (skip placeholder at index 0)
//          */
//         const randomIndex = Math.floor(Math.random() * (optionCount - 1)) + 1;

//         await currencySelect.selectOption({ index: randomIndex });

//         /**
//          * Fill exchange rate
//          */
//         await adminPage.fill(
//             'input[name="rate"]',
//             (Math.random() * 500).toFixed(2)
//         );

//         /**
//          * Submit form
//          */
//         await adminPage.keyboard.press("Enter");

//         await expect(
//             adminPage.getByText("Exchange Rate Updated Successfully").first()
//         ).toBeVisible();
//     });

//     test("delete exchange rate", async ({ adminPage }) => {
//         await adminPage.goto("admin/settings/exchange-rates");

//         const iconDelete = adminPage.locator("a:has(span.icon-delete)");
//         await iconDelete.nth(0).click();

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("Exchange Rate Deleted Successfully").first()
//         ).toBeVisible();
//     });
// });
