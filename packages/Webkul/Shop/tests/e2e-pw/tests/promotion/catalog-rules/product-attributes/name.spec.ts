import type { Page } from "@playwright/test";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import { test } from "../../../../setup";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

let generatedName: string;

async function createRuleAndVerify({
    adminPage,
    shopPage,
    operator,
    value,
    type,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    value: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);

    const rule = await ruleCreatePage.catalogRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        scopeSku: product.sku,
        attribute: "product|name",
        operator,
        value,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule({
        productName: product.name,
        price: product.price ?? 0,
        value: discountValue ?? 0,
        type: type,
    });
}

let product: BaseProduct;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];

    generatedName = `Simple-${uniqueStamp()}`;
    product = await new ProductCreatePage(adminPage).createProduct({
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: generatedName,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 199,
    });
});

test.afterEach(async ({ adminPage }) => {
    try {
        await new RuleDeletePage(adminPage).deleteCatalogRulesIfPresent(createdRules);
    } finally {
        await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
    }
});

const testCases = [
    {
        title: "is equal to",
        operator: "==",
        value: () => generatedName,
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        value: () => generatedName,
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: () => "simple",
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: () => "simple",
        type: "fixed",
    },
    {
        title: "contains",
        operator: "{}",
        value: () => generatedName,
        type: "percentage",
    },
    {
        title: "contains",
        operator: "{}",
        value: () => generatedName,
        type: "fixed",
    },
    {
        title: "does not contain",
        operator: "!{}",
        value: () => "example",
        type: "percentage",
    },
    {
        title: "does not contain",
        operator: "!{}",
        value: () => "example",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const testCase of testCases) {
            test(`should apply condition when product name condition is -> ${testCase.title} (${testCase.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerify({
                    adminPage,
                    shopPage,
                    operator: testCase.operator,
                    value: testCase.value(),
                    type: testCase.type,
                });
            });
        }
    });
});
