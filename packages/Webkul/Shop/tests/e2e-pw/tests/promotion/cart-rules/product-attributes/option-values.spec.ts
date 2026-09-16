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
        conditionLabel: "attribute family",
        attribute: "product|attribute_family_id",
        rows: [
            { operator: "==", optionSelect: "Default" },
            { operator: "!=", optionSelect: "Jacket" },
        ],
    },
    {
        conditionLabel: "category",
        attribute: "product|category_ids",
        rows: [
            {
                operator: "{}",
                checkboxSelect: "Mens",
                productAfterRule: { kind: "category", name: "Mens" },
            },
            {
                operator: "!{}",
                checkboxSelect: "Mens",
                productAfterRule: { kind: "category", name: "Womens" },
            },
        ],
    },
    {
        conditionLabel: "color",
        attribute: "product|color",
        rows: [
            {
                operator: "==",
                optionSelect: "Red",
                productAfterRule: { kind: "select", code: "color", label: "Red" },
            },
            {
                operator: "!=",
                optionSelect: "Red",
                productAfterRule: { kind: "select", code: "color", label: "Green" },
            },
        ],
    },
    {
        conditionLabel: "featured",
        attribute: "product|featured",
        rows: [
            { operator: "==", optionSelect: "Yes" },
            { operator: "!=", optionSelect: "No" },
        ],
    },
    {
        conditionLabel: "guest checkout",
        attribute: "product|guest_checkout",
        rows: [
            { operator: "==", optionSelect: "Yes" },
            { operator: "!=", optionSelect: "No" },
        ],
    },
    {
        conditionLabel: "new product",
        attribute: "product|new",
        rows: [
            { operator: "==", optionSelect: "Yes" },
            { operator: "!=", optionSelect: "No" },
        ],
    },
    {
        conditionLabel: "size",
        attribute: "product|size",
        rows: [
            {
                operator: "==",
                optionSelect: "S",
                productAfterRule: { kind: "select", code: "size", label: "S" },
            },
            {
                operator: "!=",
                optionSelect: "L",
                productAfterRule: { kind: "select", code: "size", label: "S" },
            },
        ],
    },
    {
        conditionLabel: "visible individually",
        attribute: "product|visible_individually",
        rows: [
            { operator: "==", optionSelect: "Yes" },
            { operator: "!=", optionSelect: "No" },
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
