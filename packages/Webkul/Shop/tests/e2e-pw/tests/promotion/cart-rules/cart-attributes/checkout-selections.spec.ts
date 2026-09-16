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
        conditionLabel: "payment method",
        attribute: "cart|payment_method",
        rows: [
            { operator: "==", optionSelect: "Money Transfer" },
            { operator: "!=", optionSelect: "Cash On Delivery" },
        ],
    },
    {
        conditionLabel: "shipping method",
        attribute: "cart|shipping_method",
        rows: [
            { operator: "==", optionSelect: "Free Shipping" },
            { operator: "!=", optionSelect: "Flat Rate" },
        ],
    },
    {
        conditionLabel: "shipping country",
        attribute: "cart|country",
        rows: [
            { operator: "==", optionSelect: "India" },
            { operator: "!=", optionSelect: "Syria" },
        ],
    },
    {
        conditionLabel: "shipping postcode",
        attribute: "cart|postcode",
        rows: [
            { operator: "==", value: "123456" },
            { operator: "!=", value: "54321" },
        ],
    },
]);

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
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
