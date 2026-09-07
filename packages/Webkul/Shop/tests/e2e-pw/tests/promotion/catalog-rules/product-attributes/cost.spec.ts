import type { Page } from "@playwright/test";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import { test } from "../../../../setup";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

async function createRuleAndVerifyCoupon({
    adminPage,
    shopPage,
    operator,
    ruleValue,
    productValue,
    type,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    ruleValue: string;
    productValue: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);

    const rule = await ruleCreatePage.catalogRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        scopeSku: product.sku,
        attribute: "product|cost",
        operator,
        value: ruleValue,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    await productEditPage.fillInput("cost", productValue);

    await productEditPage.save();

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

const testCases = [
    {
        title: "is equal to",
        operator: "==",
        ruleValue: "199",
        productValue: "199",
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        ruleValue: "199",
        productValue: "199",
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        ruleValue: "100",
        productValue: "200",
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        ruleValue: "100",
        productValue: "200",
        type: "fixed",
    },
    {
        title: "equals or greater then",
        operator: ">=",
        ruleValue: "199",
        productValue: "199",
        type: "percentage",
    },
    {
        title: "equals or greater then",
        operator: ">=",
        ruleValue: "199",
        productValue: "199",
        type: "fixed",
    },
    {
        title: "equals or less than",
        operator: "<=",
        ruleValue: "200",
        productValue: "198",
        type: "percentage",
    },
    {
        title: "equals or less than",
        operator: "<=",
        ruleValue: "200",
        productValue: "198",
        type: "fixed",
    },
    {
        title: "greater than",
        operator: ">",
        ruleValue: "195",
        productValue: "199",
        type: "percentage",
    },
    {
        title: "greater than",
        operator: ">",
        ruleValue: "195",
        productValue: "199",
        type: "fixed",
    },
    {
        title: "less than",
        operator: "<",
        ruleValue: "200",
        productValue: "195",
        type: "percentage",
    },
    {
        title: "less than",
        operator: "<",
        ruleValue: "200",
        productValue: "195",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when cost condition is -> ${tc.title} (${tc.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    operator: tc.operator,
                    ruleValue: tc.ruleValue,
                    productValue: tc.productValue,
                    type: tc.type,
                });
            });
        }
    });
});
