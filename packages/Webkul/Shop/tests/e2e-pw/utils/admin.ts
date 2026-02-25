import { expect } from "../setup";
import { generateDescription, generateName, generateSlug } from "./faker";

export async function loginAsAdmin(page) {
    /**
     * Admin credentials.
     */
    const adminCredentials = {
        email: "admin@example.com",
        password: "admin123",
    };

    /**
     * Authenticate the admin user.
     */
    await page.goto("admin/login");
    await page.locator('input[name="email"]').fill(adminCredentials.email);
    await page.locator('input[name="password"]').fill(adminCredentials.password);
    await page.press('input[name="password"]', "Enter");

    /**
     * Wait for the dashboard to load.
     */
    await page.waitForURL("**/admin/dashboard");

    return adminCredentials;
}


export async function createTaxRate(adminPage) {
    const taxRate = {
        identifier: generateSlug("_"),
        country: "IN",
        state: "DL",
    };

    /**
     * Reaching to the create tax rate page.
     */
    await adminPage.goto("admin/settings/taxes/rates");
    await adminPage.waitForSelector(
        'a.primary-button:has-text("Create Tax Rate")',
        { state: "visible" },
    );
    await adminPage.click('a.primary-button:has-text("Create Tax Rate")');

    /**
     * Waiting for the main form to be visible.
     */
    await adminPage.waitForSelector(
        'form[action*="/settings/taxes/rates/create"]',
    );

    /**
     * General Section.
     */
    await adminPage
        .locator('input[name="identifier"]')
        .fill(taxRate.identifier);
    await adminPage
        .locator('select[name="country"]')
        .selectOption(taxRate.country);
    await adminPage.locator('select[name="state"]').selectOption(taxRate.state);
    await adminPage.locator('input[name="tax_rate"]').fill("18");

    /**
     * Save tax rate.
     */
    await adminPage.getByRole("button", { name: "Save Tax Rate" }).click();

    return taxRate;
}

export async function createTaxCategory(adminPage) {
    /**
     * Creating a tax rate.
     */
    const taxRate = await createTaxRate(adminPage);

    /**
     * Reaching to the tax category listing page.
     */
    await adminPage.goto("admin/settings/taxes/categories");

    /**
     * Opening create tax category form in modal.
     */
    await adminPage
        .getByRole("button", { name: "Create Tax Category" })
        .click();
    await adminPage.locator('input[name="code"]').fill(generateSlug("_"));
    await adminPage.locator('input[name="name"]').fill(generateName());
    await adminPage
        .locator('textarea[name="description"]')
        .fill(generateDescription());
    await adminPage.locator('select[name="taxrates[]"]').selectOption([
        {
            label: taxRate.identifier,
        },
    ]);

    /**
     * Saving tax category and closing the modal.
     */
    await adminPage.getByRole("button", { name: "Save Tax Category" }).click();

    /**
     * Asserting.
     */
    await expect(
        adminPage.getByText("New Tax Category Created."),
    ).toBeVisible();
}
