import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/product";
import { CreateRules } from "../../../../pages/rules";

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: `Simple-${Date.now()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const createRules = new CreateRules(adminPage);
        await createRules.deleteCatalogRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        test("should apply coupon when size condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|size",
                operator: "==",
                optionSelect: "6",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            await page.locator('select[name="size"]').first().selectOption("6");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
        });

        test("should apply coupon when size condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|size",
                operator: "!=",
                optionSelect: "6",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            await page.locator('select[name="size"]').first().selectOption("7");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
        });
    });
});
