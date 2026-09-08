import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { test } from "../../../../setup";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { Page } from "@playwright/test";

let generatedName: string;

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

async function runCatalogRuleTest({
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
        attribute: "product|url_key",
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

const testCases = [
    {
        operator: "==",
        value: () => generatedName.toLowerCase(),
        label: "is equal to",
        type: "percentage",
    },
    {
        operator: "==",
        value: () => generatedName.toLowerCase(),
        label: "is equal to",
        type: "fixed",
    },
    {
        operator: "!=",
        value: () => "simple",
        label: "is not equal to",
        type: "percentage",
    },
    {
        operator: "!=",
        value: () => "simple",
        label: "is not equal to",
        type: "fixed",
    },
    {
        operator: "{}",
        value: () => generatedName.toLowerCase(),
        label: "contains",
        type: "percentage",
    },
    {
        operator: "{}",
        value: () => generatedName.toLowerCase(),
        label: "contains",
        type: "fixed",
    },
    {
        operator: "!{}",
        value: () => "example",
        label: "does not contain",
        type: "percentage",
    },
    {
        operator: "!{}",
        value: () => "example",
        label: "does not contain",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when url key condition is -> ${tc.label} (${tc.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await runCatalogRuleTest({
                    adminPage,
                    shopPage,
                    operator: tc.operator,
                    value: tc.value(),
                    type: tc.type,
                });
            });
        }
    });
});
