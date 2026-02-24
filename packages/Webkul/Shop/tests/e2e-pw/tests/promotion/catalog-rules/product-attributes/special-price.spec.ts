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

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        test("should apply coupon when cost condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|special_price",
                operator: "==",
                value: "150",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            await page.locator('input[name="special_price"]').first().fill("150");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
            await createRules.deleteCatalogRuleAndProduct();
        });

        test("should apply coupon when cost condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|special_price",
                operator: "!=",
                value: "100",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            await page.locator('input[name="special_price"]').first().fill("150");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
            await createRules.deleteCatalogRuleAndProduct();
        });

        test("should apply coupon when price condition is -> equals or greater then", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|special_price",
                operator: ">=",
                value: "150",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            await page.locator('input[name="special_price"]').first().fill("160");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
            await createRules.deleteCatalogRuleAndProduct();
        });

        test("should apply coupon when price condition is -> equals or less than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|special_price",
                operator: "<=",
                value: "200",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            await page.locator('input[name="special_price"]').first().fill("150");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
            await createRules.deleteCatalogRuleAndProduct();
        });

        test("should apply coupon when price condition is -> greater than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|special_price",
                operator: ">",
                value: "100",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.locator('input[name="special_price"]').first().fill("150");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
            await createRules.deleteCatalogRuleAndProduct();
        });

        test("should apply coupon when special price condition is -> less than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|special_price",
                operator: "<",
                value: "200",
            });
            await createRules.saveCatalogRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            await page.locator('input[name="special_price"]').first().fill("195");
            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();
            await createRules.verifyCatalogRule();
            await createRules.deleteCatalogRuleAndProduct();
        });
    });
});
