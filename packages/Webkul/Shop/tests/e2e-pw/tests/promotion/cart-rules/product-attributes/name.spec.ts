
import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/product";
import { CreateRules } from "../../../../pages/rules";

let generatedName: string;
generatedName=`Simple-${Date.now()}`;

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: generatedName,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 199,
    });
});

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        test("should apply coupon when name of product condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|name",
                operator: "==",
                value: generatedName,
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when name of product condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|name",
                operator: "!=",
                value: "simple",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when name of product condition is -> contains", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|name",
                operator: "{}",
                value: generatedName,
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when name of product condition is -> does not contain", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|name",
                operator: "!{}",
                value: "example",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });
    });
});
