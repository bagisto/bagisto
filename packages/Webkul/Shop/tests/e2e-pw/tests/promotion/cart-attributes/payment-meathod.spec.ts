import { test } from "../../../setup";
import { ProductCreation } from "../../../pages/product";
import { CreateRules } from "../../../pages/rules";

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
        test("should apply coupon when payment meathod condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart|payment_method",
                operator: "==",
                optionSelect: "moneytransfer",
            });
            await createRules.saveCartRule();
            await createRules.applyCouponAtCheckout();
            await createRules.deleteRuleAndProduct();
        });

        test("should apply coupon when payment meathod condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart|payment_method",
                operator: "!=",
                optionSelect: "cashondelivery",
            });
            await createRules.saveCartRule();
            await createRules.applyCouponAtCheckout();
            await createRules.deleteRuleAndProduct();
        });
    });
});
