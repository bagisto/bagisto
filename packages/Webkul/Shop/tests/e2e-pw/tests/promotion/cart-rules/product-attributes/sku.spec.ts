import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/product";
import { CreateRules } from "../../../../pages/rules";

let generatedSku: string;
generatedSku = `SKU-${Date.now()}`;

test.beforeEach("should create simple product", async ({ adminPage }) => {
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
});

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const createRules = new CreateRules(adminPage);
        await createRules.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
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
        });
    });
});
