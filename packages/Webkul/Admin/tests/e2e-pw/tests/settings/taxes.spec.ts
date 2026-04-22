import { test, expect } from "../../setup";
import {
    generateDescription,
    generateName,
    generateSlug,
} from "../../utils/faker";

async function createTaxRate(adminPage) {
    const taxRate = {
        identifier: generateSlug("_"),
        country: "IN",
        state: "DL",
    };

    await adminPage.goto("admin/settings/taxes/rates");
    await adminPage.waitForSelector(
        'a.primary-button:has-text("Create Tax Rate")',
        { state: "visible" }
    );
    await adminPage.click('a.primary-button:has-text("Create Tax Rate")');
    await adminPage.waitForSelector(
        'form[action*="/settings/taxes/rates/create"]'
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

async function createTaxCategory(adminPage) {
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
        adminPage.getByText("Tax category created successfully.").first()
    ).toBeVisible();
}

test.describe("tax management", () => {
    test.describe("tax rate management", () => {
        test("should create a tax rate without zip range", async ({
            adminPage,
        }) => {
            const taxRate = await createTaxRate(adminPage);

            await expect(
                adminPage.getByText("Tax rate created successfully.").first()
            ).toBeVisible();

            await expect(adminPage.getByText(taxRate.identifier)).toBeVisible();
        });

        test("should edit a tax rate without zip range", async ({
            adminPage,
        }) => {
            await createTaxRate(adminPage);
            await adminPage.goto("admin/settings/taxes/rates");
            await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
            const iconEdit = await adminPage.$$(
                "span.cursor-pointer.icon-edit"
            );
            await iconEdit[0].click();
            await adminPage.waitForSelector(
                'form[action*="/settings/taxes/rates/edit"]'
            );
            await adminPage.locator('input[name="tax_rate"]').fill("36");
            await adminPage
                .getByRole("button", { name: "Save Tax Rate" })
                .click();

            await expect(
                adminPage.getByText("Tax Rate Update Successfully").first()
            ).toBeVisible();
        });

        test("should delete a tax rate", async ({ adminPage }) => {
            await createTaxRate(adminPage);
            await adminPage.goto("admin/settings/taxes/rates");
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
                adminPage.getByText("Tax rate deleted successfully")
            ).toBeVisible();
        });
    });

    test.describe("tax category management", () => {
        test("should create a tax category", async ({ adminPage }) => {
            await createTaxCategory(adminPage);
        });

        test("should edit a tax category", async ({ adminPage }) => {
            await createTaxCategory(adminPage);
            await adminPage.goto("admin/settings/taxes/categories");
            await adminPage.waitForSelector("span.cursor-pointer.icon-edit", {
                state: "visible",
            });
            const iconEdit = await adminPage.$$(
                "span.cursor-pointer.icon-edit"
            );
            await iconEdit[0].click();
            await adminPage
                .getByRole("button", { name: "Save Tax Category" })
                .click();

            await expect(
                adminPage.getByText("Tax category updated successfully.").first()
            ).toBeVisible();
        });
    });
});
