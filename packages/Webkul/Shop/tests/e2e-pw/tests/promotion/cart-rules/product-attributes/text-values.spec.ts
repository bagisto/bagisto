import { test } from "../../../../setup";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import {
    addRuleCondition,
    applyProductChange,
    buildRuleConditionCases,
    caseTitle,
    createRuleProduct,
} from "../../rule-conditions";

let product: BaseProduct;
let productNumber: string;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];
    productNumber = `PN-${uniqueStamp()}`;
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
        conditionLabel: "sku",
        attribute: "product|sku",
        rows: [
            { operator: "==", value: () => product.sku },
            { operator: "!=", value: "sku-123" },
            { operator: "{}", value: () => product.sku },
            { operator: "!{}", value: "example" },
        ],
    },
    {
        conditionLabel: "url key",
        attribute: "product|url_key",
        rows: [
            { operator: "==", value: () => product.name.toLowerCase() },
            { operator: "!=", value: "simple" },
            { operator: "{}", value: () => product.name.toLowerCase() },
            { operator: "!{}", value: "example" },
        ],
    },
    {
        conditionLabel: "product number",
        attribute: "product|product_number",
        rows: [
            {
                operator: "==",
                value: () => productNumber,
                productAfterRule: () => ({
                    kind: "input",
                    code: "product_number",
                    value: productNumber,
                }),
            },
            {
                operator: "!=",
                value: "123456",
                productAfterRule: {
                    kind: "input",
                    code: "product_number",
                    value: "123457",
                },
            },
            {
                operator: "{}",
                value: () => productNumber,
                productAfterRule: () => ({
                    kind: "input",
                    code: "product_number",
                    value: productNumber,
                }),
            },
            {
                operator: "!{}",
                value: "123456",
                productAfterRule: {
                    kind: "input",
                    code: "product_number",
                    value: "123457",
                },
            },
        ],
    },
]);

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
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
