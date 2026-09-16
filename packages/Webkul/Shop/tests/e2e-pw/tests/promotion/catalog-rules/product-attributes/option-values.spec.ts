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

const PRODUCT_CATEGORY = "Mens";
const OTHER_CATEGORY = "Womens";

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
                checkboxSelect: PRODUCT_CATEGORY,
                productBeforeRule: { kind: "category", name: PRODUCT_CATEGORY },
            },
            {
                operator: "!{}",
                checkboxSelect: OTHER_CATEGORY,
                productBeforeRule: { kind: "category", name: PRODUCT_CATEGORY },
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
            { operator: "==", optionSelect: "Yes", shopSession: "customer" },
            { operator: "!=", optionSelect: "No", shopSession: "customer" },
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
                optionSelect: "S",
                productAfterRule: { kind: "select", code: "size", label: "L" },
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

        test("should leave the listed price untouched when the product is not in the rule category", async ({
            adminPage,
            shopPage,
        }) => {
            const ruleCreatePage = new RuleCreatePage(adminPage);
            const ruleApplyPage = new RuleApplyPage(shopPage);

            await applyProductChange(adminPage, product.name, {
                kind: "category",
                name: PRODUCT_CATEGORY,
            });

            const rule = await ruleCreatePage.catalogRuleCreationFlow();

            createdRules.push(rule.name);

            await ruleCreatePage.addCondition({
                scopeSku: product.sku,
                attribute: "product|category_ids",
                operator: "{}",
                checkboxSelect: OTHER_CATEGORY,
                couponType: "percentage",
            });

            await ruleCreatePage.saveCatalogRule();

            await ruleApplyPage.searchProduct(product.name);

            await ruleApplyPage.expectNoCatalogDiscount(
                product.name,
                RULE_PRODUCT_PRICE,
            );
        });
    });
});
