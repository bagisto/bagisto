import { test } from "../../../../setup";
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
        await createRules.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("cart item attribute conditions", () => {
        test("should apply coupon when subtotal condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart_item|base_total",
                operator: "==",
                value: "199",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });

        test("should apply coupon when subtotal condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart_item|base_total",
                operator: "!=",
                value: "199",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon(1);
        });

        test("should apply coupon when subtotal condition is -> equals or greater then", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart|base_sub_total",
                operator: ">=",
                value: "199",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });

        test("should apply coupon when subtotal condition is -> equals or less than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart_item|base_total",
                operator: "<=",
                value: "200",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });

        test("should apply coupon when subtotal condition is -> greater than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart_item|base_total",
                operator: ">",
                value: "198",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });

        test("should apply coupon when subtotal condition is -> less than", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "cart_item|base_total",
                operator: "<",
                value: "200",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });
    });
});
