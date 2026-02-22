import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { CreateRules } from "../../pages/rules";

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

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
        test("should apply coupon when total item quantity condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition("cart|items_qty", "==", "1");
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when total item quantity condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition("cart|items_qty", "!=", "2");
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when total item quantity condition is -> equals or greater then", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition("cart|items_qty", ">=", "1");
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when total item quantity condition is -> equals or less than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition("cart|items_qty", "<=", "2");
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when total item quantity condition is -> greater than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition("cart|items_qty", ">", "1");
            await createRules.saveCartRule();
            await createRules.applyCoupon(1);
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when total item quantity condition is -> less than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition("cart|items_qty", "<", "2");
            await createRules.saveCartRule();
            await createRules.applyCoupon();
            await createRules.deleteRuleAndProduct();
        });
    });
});
