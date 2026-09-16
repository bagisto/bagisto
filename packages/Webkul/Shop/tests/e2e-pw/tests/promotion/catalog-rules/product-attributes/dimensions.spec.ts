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
    openShopSession,
    RULE_PRODUCT_PRICE,
} from "../../rule-conditions";

let product: BaseProduct;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];
    product = await createRuleProduct(adminPage);
});

test.afterEach(async ({ adminPage }) => {
    try {
        await new RuleDeletePage(adminPage).deleteCatalogRulesIfPresent(createdRules);
    } finally {
        await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
    }
});

const cases = buildRuleConditionCases([
    {
        conditionLabel: "height",
        attribute: "product|height",
        rows: [
            {
                operator: "==",
                value: "1",
                productAfterRule: { kind: "input", code: "height", value: "1" },
            },
            {
                operator: "!=",
                value: "1",
                productAfterRule: { kind: "input", code: "height", value: "2" },
            },
            {
                operator: "{}",
                value: "1",
                productAfterRule: { kind: "input", code: "height", value: "1" },
            },
            {
                operator: "!{}",
                value: "1",
                productAfterRule: { kind: "input", code: "height", value: "2" },
            },
        ],
    },
    {
        conditionLabel: "length",
        attribute: "product|length",
        rows: [
            {
                operator: "==",
                value: "1",
                productAfterRule: { kind: "input", code: "length", value: "1" },
            },
            {
                operator: "!=",
                value: "1",
                productAfterRule: { kind: "input", code: "length", value: "2" },
            },
            {
                operator: "{}",
                value: "1",
                productAfterRule: { kind: "input", code: "length", value: "1" },
            },
            {
                operator: "!{}",
                value: "1",
                productAfterRule: { kind: "input", code: "length", value: "2" },
            },
        ],
    },
    {
        conditionLabel: "width",
        attribute: "product|width",
        rows: [
            {
                operator: "==",
                value: "1",
                productAfterRule: { kind: "input", code: "width", value: "1" },
            },
            {
                operator: "!=",
                value: "1",
                productAfterRule: { kind: "input", code: "width", value: "2" },
            },
            {
                operator: "{}",
                value: "1",
                productAfterRule: { kind: "input", code: "width", value: "1" },
            },
            {
                operator: "!{}",
                value: "1",
                productAfterRule: { kind: "input", code: "width", value: "2" },
            },
        ],
    },
]);

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const testCase of cases) {
            test(caseTitle(testCase, "discount the listed price"), async ({
                adminPage,
                shopPage,
            }) => {
                const ruleCreatePage = new RuleCreatePage(adminPage);
                const ruleApplyPage = new RuleApplyPage(shopPage);

                await applyProductChange(
                    adminPage,
                    product.name,
                    testCase.productBeforeRule,
                );

                const rule = await ruleCreatePage.catalogRuleCreationFlow();

                createdRules.push(rule.name);

                const discountValue = await addRuleCondition(
                    ruleCreatePage,
                    testCase,
                    product.sku,
                );

                await ruleCreatePage.saveCatalogRule();

                await applyProductChange(
                    adminPage,
                    product.name,
                    testCase.productAfterRule,
                );

                await openShopSession(shopPage, testCase.shopSession);

                await ruleApplyPage.searchProduct(product.name);

                await ruleApplyPage.expectCatalogRuleDiscount({
                    productName: product.name,
                    price: RULE_PRODUCT_PRICE,
                    value: discountValue,
                    type: testCase.couponType,
                });
            });
        }
    });
});
