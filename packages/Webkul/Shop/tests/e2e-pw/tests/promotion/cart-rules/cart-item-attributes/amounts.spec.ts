import { test } from "../../../../setup";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import {
    addRuleCondition,
    applyProductChange,
    buildRuleConditionCases,
    caseTitle,
    createRuleProduct,
} from "../../rule-conditions";

let product: BaseProduct;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];
    product = await createRuleProduct(adminPage);
});

test.afterEach(async ({ adminPage }) => {
    try {
        await new RuleDeletePage(adminPage).deleteCartRulesIfPresent(createdRules);
    } finally {
        await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
    }
});

const cases = buildRuleConditionCases([
    {
        conditionLabel: "price in cart",
        attribute: "cart_item|base_price",
        rows: [
            { operator: "==", value: "199" },
            { operator: "!=", value: "100" },
            { operator: ">=", value: "199" },
            { operator: "<=", value: "200" },
            { operator: ">", value: "198" },
            { operator: "<", value: "200" },
        ],
    },
    {
        conditionLabel: "item subtotal",
        attribute: "cart_item|base_total",
        rows: [
            { operator: "==", value: "199" },
            { operator: "!=", value: "101" },
            { operator: ">=", value: "199" },
            { operator: "<=", value: "200" },
            { operator: ">", value: "198" },
            { operator: "<", value: "200" },
        ],
    },
]);

test.describe("cart rules", () => {
    test.describe("cart item attribute conditions", () => {
        for (const testCase of cases) {
            test(caseTitle(testCase, "discount the grand total"), async ({
                adminPage,
                shopPage,
            }) => {
                const ruleCreatePage = new RuleCreatePage(adminPage);

                await applyProductChange(
                    adminPage,
                    product.name,
                    testCase.productBeforeRule,
                );

                const rule = await ruleCreatePage.cartRuleCreationFlow();

                createdRules.push(rule.name);

                const discountValue = await addRuleCondition(ruleCreatePage, testCase);

                await ruleCreatePage.saveCartRule();

                await applyProductChange(
                    adminPage,
                    product.name,
                    testCase.productAfterRule,
                );

                await new RuleApplyPage(shopPage).expectCouponAppliedWithGrandTotal({
                    productName: product.name,
                    couponCode: rule.couponCode,
                    discountValue,
                    couponType: testCase.couponType,
                    incrementTimes: testCase.cartQuantityIncrements,
                });
            });
        }

        test("should reject the coupon when the price in cart condition does not match", async ({
            adminPage,
            shopPage,
        }) => {
            const ruleCreatePage = new RuleCreatePage(adminPage);

            const rule = await ruleCreatePage.cartRuleCreationFlow();

            createdRules.push(rule.name);

            await ruleCreatePage.addCondition({
                attribute: "cart_item|base_price",
                operator: "==",
                value: "100",
                couponType: "percentage",
            });

            await ruleCreatePage.saveCartRule();

            await new RuleApplyPage(shopPage).expectCouponNotApplicableWithGrandTotal({
                productName: product.name,
                couponCode: rule.couponCode,
            });
        });
    });
});
