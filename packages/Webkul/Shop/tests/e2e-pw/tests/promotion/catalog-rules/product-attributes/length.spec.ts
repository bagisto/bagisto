import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

async function updateProductLength(adminPage: Page, value: string) {
    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    await productEditPage.fillInput("length", value);
    await productEditPage.save();
}

async function createRuleAndVerify({
    adminPage,
    shopPage,
    operator,
    conditionValue,
    productValue,
    type,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    conditionValue: string;
    productValue: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);

    const rule = await ruleCreatePage.catalogRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        scopeSku: product.sku,
        attribute: "product|length",
        operator,
        value: conditionValue,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await updateProductLength(adminPage, productValue);

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
        conditionValue: "1",
        productValue: "1",
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        conditionValue: "1",
        productValue: "1",
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        conditionValue: "1",
        productValue: "2",
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        conditionValue: "1",
        productValue: "2",
        type: "fixed",
    },
    {
        title: "contains",
        operator: "{}",
        conditionValue: "1",
        productValue: "1",
        type: "percentage",
    },
    {
        title: "contains",
        operator: "{}",
        conditionValue: "1",
        productValue: "1",
        type: "fixed",
    },
    {
        title: "does not contain",
        operator: "!{}",
        conditionValue: "1",
        productValue: "2",
        type: "percentage",
    },
    {
        title: "does not contain",
        operator: "!{}",
        conditionValue: "1",
        productValue: "2",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const testCase of testCases) {
            test(`should apply condition when length condition is -> ${testCase.title} (${testCase.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerify({
                    adminPage,
                    shopPage,
                    operator: testCase.operator,
                    conditionValue: testCase.conditionValue,
                    productValue: testCase.productValue,
                    type: testCase.type,
                });
            });
        }
    });
});
