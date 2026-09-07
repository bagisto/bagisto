import { expect } from "../setup";
import type { Page } from "@playwright/test";
import { env } from "./env";
import { generateDescription, generateSlug } from "./faker";

const GRID_ROWS = "div.row:not(.datagrid-head):not(:has(.shimmer))";

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

export async function setConfigSwitch(
    adminPage: Page,
    configPath: string,
    fieldName: string,
    enabled: boolean,
): Promise<boolean> {
    await adminPage.goto(configPath);

    const checkbox = adminPage.locator(`input[type="checkbox"][name="${fieldName}"]`);

    await expect(checkbox).toBeAttached();

    const original = await checkbox.isChecked();

    if (original !== enabled) {
        await adminPage.locator(`label:has(> input[type="checkbox"][name="${fieldName}"])`).click();

        await expect(checkbox).toBeChecked({ checked: enabled });

        await adminPage.getByRole("button", { name: "Save Configuration" }).click();

        await expect(adminPage.getByText("Configuration saved successfully")).toBeVisible();
    }

    return original;
}

export async function createTaxRate(adminPage: Page): Promise<string> {
    const identifier = generateSlug("_");

    await adminPage.goto("admin/settings/taxes/rates");
    await adminPage.getByRole("link", { name: "Create Tax Rate" }).click();

    await expect(adminPage.locator('input[name="identifier"]')).toBeVisible();

    await adminPage.locator('input[name="identifier"]').fill(identifier);
    await adminPage.locator('select[name="country"]').selectOption("IN");
    await adminPage.locator('select[name="state"]').selectOption("DL");
    await adminPage.locator('input[name="tax_rate"]').fill("18");
    await adminPage.getByRole("button", { name: "Save Tax Rate" }).click();

    await expect(
        adminPage.getByText("Tax rate created successfully."),
    ).toBeVisible();

    return identifier;
}

export async function createTaxCategory(
    adminPage: Page,
    name: string,
    rateIdentifier: string,
): Promise<void> {
    await adminPage.goto("admin/settings/taxes/categories");
    await adminPage
        .getByRole("button", { name: "Create Tax Category" })
        .click();

    await expect(adminPage.locator('input[name="code"]')).toBeVisible();

    await adminPage.locator('input[name="code"]').fill(generateSlug("_"));
    await adminPage.locator('input[name="name"]').fill(name);
    await adminPage
        .locator('textarea[name="description"]')
        .fill(generateDescription());
    await adminPage
        .locator('select[name="taxrates[]"]')
        .selectOption([{ label: rateIdentifier }]);
    await adminPage.getByRole("button", { name: "Save Tax Category" }).click();

    await expect(
        adminPage.getByText("Tax category created successfully."),
    ).toBeVisible();
}

export async function deleteTaxCategoriesIfPresent(
    adminPage: Page,
    names: string[],
): Promise<void> {
    await deleteGridRowsIfPresent(
        adminPage,
        "admin/settings/taxes/categories",
        names,
        "Tax category deleted successfully.",
    );
}

export async function deleteTaxRatesIfPresent(
    adminPage: Page,
    identifiers: string[],
): Promise<void> {
    await deleteGridRowsIfPresent(
        adminPage,
        "admin/settings/taxes/rates",
        identifiers,
        "Tax rate deleted successfully",
    );
}

export async function deleteNewsletterSubscribersIfPresent(
    adminPage: Page,
    emails: string[],
): Promise<void> {
    await deleteGridRowsIfPresent(
        adminPage,
        "admin/marketing/communications/subscribers",
        emails,
        "Subscriber Deleted Successfully",
    );
}

async function deleteGridRowsIfPresent(
    page: Page,
    gridPath: string,
    texts: string[],
    successMessage: string,
): Promise<void> {
    const failures: string[] = [];

    for (const text of texts) {
        try {
            await page.goto(gridPath);

            await expect
                .poll(async () => (await page.locator(GRID_ROWS).count()) > 0, {
                    message: `The datagrid at ${gridPath} never finished loading`,
                })
                .toBe(true);

            await page.locator('input[name="search"]').fill(text);

            await Promise.all([
                page.waitForResponse((response) => {
                    const url = decodeURIComponent(response.url()).replace(/\+/g, " ");

                    return url.includes("filters[all]") && url.includes(text);
                }),
                page.locator('input[name="search"]').press("Enter"),
            ]);

            const row = page.locator(GRID_ROWS).filter({ hasText: text });

            if (!(await row.count())) {
                continue;
            }

            await row.locator("span.icon-delete").click();
            await page.getByRole("button", { name: "Agree", exact: true }).click();

            await expect(page.getByText(successMessage)).toBeVisible();
        } catch (error) {
            failures.push(`${text}: ${error}`);
        }
    }

    if (failures.length) {
        throw new Error(`Cleanup failed for:\n${failures.join("\n")}`);
    }
}
