import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

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
        const ruleDeletePage = new RuleDeletePage(adminPage);
        await ruleDeletePage.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
        test("should apply coupon when subtotal condition is -> is equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "==",
                value: "199",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCouponAtCheckout();
        });

        // test("should apply coupon when subtotal condition is -> is not equal to", async ({
        //     page,
        // }) => {
        //     const ruleCreatePage = new RuleCreatePage(page);
        //     const ruleApplyPage = new RuleApplyPage(page);
        //     await loginAsAdmin(page);
        //     await ruleCreatePage.cartRuleCreationFlow();
        //     await ruleCreatePage.addCondition({
        //         attribute: "cart|base_sub_total",
        //         operator: "!=",
        //         value: "100",
        //     });
        //     await ruleCreatePage.saveCartRule();
        //     await ruleApplyPage.applyCouponAtCheckout();
        // });

        // test("should apply coupon when subtotal condition is -> equals or greater then", async ({
        //     page,
        // }) => {
        //     const ruleCreatePage = new RuleCreatePage(page);
        //     const ruleApplyPage = new RuleApplyPage(page);
        //     await loginAsAdmin(page);
        //     await ruleCreatePage.cartRuleCreationFlow();
        //     await ruleCreatePage.addCondition({
        //         attribute: "cart|base_sub_total",
        //         operator: ">=",
        //         value: "199",
        //     });
        //     await ruleCreatePage.saveCartRule();
        //     await ruleApplyPage.applyCouponAtCheckout();
        // });

        test("should apply coupon when subtotal condition is -> equals or less than", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "<=",
                value: "200",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCouponAtCheckout();
        });

        test("should apply coupon when subtotal condition is -> greater than", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: ">",
                value: "198",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCouponAtCheckout();
        });

        test("should apply coupon when subtotal condition is -> less than", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "<",
                value: "200",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCouponAtCheckout();
        });
    });
});
