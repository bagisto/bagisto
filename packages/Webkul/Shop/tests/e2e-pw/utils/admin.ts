import { expect } from "../setup";
import type { Page } from "@playwright/test";
import { env } from "./env";
import { generateDescription, generateName, generateSlug } from "./faker";

export async function loginAsAdmin(page: Page) {
    const adminCredentials = {
        email: env.adminEmail,
        password: env.adminPassword,
    };

    await page.goto("admin/login");
    await page.locator('input[name="email"]').fill(adminCredentials.email);
    await page
        .locator('input[name="password"]')
        .fill(adminCredentials.password);
    await page.press('input[name="password"]', "Enter");

    await page.waitForURL("**/admin/dashboard");

    return adminCredentials;
}

export async function createTaxRate(adminPage) {
    const taxRate = {
        identifier: generateSlug("_"),
        country: "IN",
        state: "DL",
    };

    await adminPage.goto("admin/settings/taxes/rates");
    await adminPage.waitForSelector(
        'a.primary-button:has-text("Create Tax Rate")',
        { state: "visible" },
    );
    await adminPage.click('a.primary-button:has-text("Create Tax Rate")');

    await adminPage.waitForSelector(
        'form[action*="/settings/taxes/rates/create"]',
    );

    await adminPage
        .locator('input[name="identifier"]')
        .fill(taxRate.identifier);
    await adminPage
        .locator('select[name="country"]')
        .selectOption(taxRate.country);
    await adminPage.locator('select[name="state"]').selectOption(taxRate.state);
    await adminPage.locator('input[name="tax_rate"]').fill("18");

    await adminPage.getByRole("button", { name: "Save Tax Rate" }).click();

    return taxRate;
}

export async function createTaxCategory(adminPage) {
    const taxRate = await createTaxRate(adminPage);

    await adminPage.goto("admin/settings/taxes/categories");

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

    await adminPage.getByRole("button", { name: "Save Tax Category" }).click();

    await expect(
        adminPage.getByText("Tax category created successfully."),
    ).toBeVisible();
}

export async function createTaxCategoryReturnName(name: string, adminPage) {
    const taxRate = await createTaxRate(adminPage);

    await adminPage.goto("admin/settings/taxes/categories");

    await adminPage
        .getByRole("button", { name: "Create Tax Category" })
        .click();
    await adminPage.locator('input[name="code"]').fill(generateSlug("_"));
    await adminPage.locator('input[name="name"]').fill(name);
    await adminPage
        .locator('textarea[name="description"]')
        .fill(generateDescription());
    await adminPage.locator('select[name="taxrates[]"]').selectOption([
        {
            label: taxRate.identifier,
        },
    ]);

    await adminPage.getByRole("button", { name: "Save Tax Category" }).click();

    await expect(
        adminPage.getByText("Tax category created successfully."),
    ).toBeVisible();
}
