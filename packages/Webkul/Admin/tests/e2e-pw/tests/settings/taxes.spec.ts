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

    /**
     * Reaching to the create tax rate page.
     */
    await adminPage.goto("admin/settings/taxes/rates");
    await adminPage.waitForSelector(
        'a.primary-button:has-text("Create Tax Rate")',
        { state: "visible" }
    );
    await adminPage.click('a.primary-button:has-text("Create Tax Rate")');

    /**
     * Waiting for the main form to be visible.
     */
    await adminPage.waitForSelector(
        'form[action*="/settings/taxes/rates/create"]'
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

async function createTaxCategory(adminPage) {
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
        adminPage.getByText("New Tax Category Created.")
    ).toBeVisible();
}

test.describe("tax management", () => {
    test.describe("tax rate management", () => {
        test("should create a tax rate without zip range", async ({
            adminPage,
        }) => {
            /**
             * Creating a tax rate.
             */
            const taxRate = await createTaxRate(adminPage);

            /**
             * Asserting.
             */
            await expect(
                adminPage.getByText("Tax rate created successfully.")
            ).toBeVisible();
            await expect(adminPage.getByText(taxRate.identifier)).toBeVisible();
        });

        test("should edit a tax rate without zip range", async ({
            adminPage,
        }) => {
            /**
             * Creating a tax rate.
             */
            await createTaxRate(adminPage);

            /**
             * Reaching to the tax rate listing page.
             */
            await adminPage.goto("admin/settings/taxes/rates");

            /**
             * Reaching to the edit tax rate page.
             */
            await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
            const iconEdit = await adminPage.$$(
                "span.cursor-pointer.icon-edit"
            );
            await iconEdit[0].click();

            /**
             * Waiting for the main form to be visible.
             */
            await adminPage.waitForSelector(
                'form[action*="/settings/taxes/rates/edit"]'
            );

            /**
             * General Section.
             */
            await adminPage.locator('input[name="tax_rate"]').fill("36");

            /**
             * Save tax rate.
             */
            await adminPage
                .getByRole("button", { name: "Save Tax Rate" })
                .click();

            /**
             * Asserting.
             */
            await expect(
                adminPage.getByText("Tax Rate Update Successfully")
            ).toBeVisible();
        });

        test("should delete a tax rate", async ({ adminPage }) => {
            /**
             * Creating a tax rate.
             */
            await createTaxRate(adminPage);

            /**
             * Reaching to the tax rate listing page.
             */
            await adminPage.goto("admin/settings/taxes/rates");

            /**
             * Now deleting the recent tax rate.
             */
            await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
            const iconDelete = await adminPage.$$(
                "span.cursor-pointer.icon-delete"
            );
            await iconDelete[0].click();

            /**
             * Agreeing to the confirmation dialog.
             */
            await adminPage.waitForSelector("text=Are you sure");
            const agreeButton = await adminPage.locator(
                'button.primary-button:has-text("Agree")'
            );

            /**
             * Clicking the agree button to delete the tax rate.
             */
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
            /**
             * Creating a tax category.
             */
            await createTaxCategory(adminPage);

            /**
             * Reaching to the tax category listing page.
             */
            await adminPage.goto("admin/settings/taxes/categories");

            /**
             * Opening edit tax category form in modal.
             */
            await adminPage.waitForSelector("span.cursor-pointer.icon-edit", {
                state: "visible",
            });
            const iconEdit = await adminPage.$$(
                "span.cursor-pointer.icon-edit"
            );
            await iconEdit[0].click();

            /**
             * Saving tax category and closing the modal.
             */
            await adminPage
                .getByRole("button", { name: "Save Tax Category" })
                .click();

            /**
             * Asserting.
             */
            await expect(
                adminPage.getByText("Tax Category Successfully Updated.")
            ).toBeVisible();
        });
    });
});
