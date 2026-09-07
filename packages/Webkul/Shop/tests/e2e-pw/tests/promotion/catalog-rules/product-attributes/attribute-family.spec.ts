import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import type { Page } from "@playwright/test";
import { test } from "../../../../setup";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

let product: BaseProduct;
let createdRules: string[];

async function runCatalogRuleTest({
    adminPage,
    shopPage,
    operator,
    optionSelect,
    type,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    optionSelect: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);

    const rule = await ruleCreatePage.catalogRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        scopeSku: product.sku,
        attribute: "product|attribute_family_id",
        operator,
        optionSelect,
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
        optionSelect: "Default",
        label: "is equal to",
        type: "percentage",
    },
    {
        operator: "==",
        optionSelect: "Default",
        label: "is equal to",
        type: "fixed",
    },
    {
        operator: "!=",
        optionSelect: "Jacket",
        label: "is not equal to",
        type: "percentage",
    },
    {
        operator: "!=",
        optionSelect: "Jacket",
        label: "is not equal to",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];
    product = await new ProductCreatePage(adminPage).createProduct({
                type: "simple",
                sku: `SKU-${uniqueStamp()}`,
                name: `Simple-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
});

        test.afterEach(async ({ adminPage }) => {
            try {
                await new RuleDeletePage(adminPage).deleteCatalogRulesIfPresent(createdRules);
            } finally {
                await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
            }
        });

        for (const tc of testCases) {
            test(`should apply coupon when attribute family condition is -> ${tc.label} (${tc.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await runCatalogRuleTest({
                    adminPage,
                    shopPage,
                    operator: tc.operator,
                    optionSelect: tc.optionSelect,
                    type: tc.type,
                });
            });
        }
    });
});
