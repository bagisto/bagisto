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
    openShopSession,
    RULE_PRODUCT_PRICE,
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
        await new RuleDeletePage(adminPage).deleteCatalogRulesIfPresent(createdRules);
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
        conditionLabel: "product name",
        attribute: "product|name",
        rows: [
            { operator: "==", value: () => product.name },
            { operator: "!=", value: "simple" },
            { operator: "{}", value: () => product.name },
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
                productAfterRule: () => ({
                    kind: "input",
                    code: "product_number",
                    value: productNumber,
                }),
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
                productAfterRule: () => ({
                    kind: "input",
                    code: "product_number",
                    value: productNumber,
                }),
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
