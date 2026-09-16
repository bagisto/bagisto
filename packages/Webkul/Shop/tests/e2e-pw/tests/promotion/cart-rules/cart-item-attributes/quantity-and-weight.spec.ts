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
        conditionLabel: "quantity in cart",
        attribute: "cart_item|quantity",
        rows: [
            { operator: "==", value: "1" },
            { operator: "!=", value: "100" },
            { operator: ">=", value: "1" },
            { operator: "<=", value: "1" },
            { operator: ">", value: "0" },
            { operator: "<", value: "2" },
        ],
    },
    {
        conditionLabel: "total weight",
        attribute: "cart_item|base_total_weight",
        rows: [
            { operator: "==", value: "1" },
            { operator: "!=", value: "2" },
            { operator: ">=", value: "1" },
            { operator: "<=", value: "2" },
            {
                operator: ">",
                value: "1",
                productAfterRule: { kind: "input", code: "weight", value: "2" },
            },
            { operator: "<", value: "2" },
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
    });
});
