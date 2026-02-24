import { createTaxRate, createTaxCategory } from "../../../../utils/admin";
import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/product";
import { CreateRules } from "../../../../pages/rules";

let generatedSku: string;
generatedSku = `SKU-${Date.now()}`;

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        test.beforeEach(
            "should create simple product and tax category",
            async ({ adminPage }) => {
                
                await createTaxRate(adminPage);
                await createTaxCategory(adminPage);

                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "simple",
                    sku: generatedSku,
                    name: `Simple-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
                await adminPage.goto("admin/catalog/products");
                await adminPage
                    .locator("span.cursor-pointer.icon-sort-right")
                    .nth(1)
                    .click();
                await adminPage
                    .locator('select[name="tax_category_id"]')
                    .selectOption("1");
                await adminPage
                    .locator('button:has-text("Save Product")')
                    .first()
                    .click();
                await expect(
                    adminPage.getByText("Product updated successfully").first(),
                ).toBeVisible();
            },
        );

        test("should apply coupon when sku of product condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|sku",
                operator: "==",
                value: generatedSku,
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when sku of product condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|sku",
                operator: "!=",
                value: "sku-123",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when sku of product condition is -> contains", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|sku",
                operator: "{}",
                value: generatedSku,
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when sku of product condition is -> does not contain", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|sku",
                operator: "!{}",
                value: "example",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });
    });
});
