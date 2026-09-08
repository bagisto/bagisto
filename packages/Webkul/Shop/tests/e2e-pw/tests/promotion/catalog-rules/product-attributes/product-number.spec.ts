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

let generatedProductNumber: string;

async function createRuleAndVerifyCoupon({
    adminPage,
    shopPage,
    operator,
    value,
    productValue,
    type,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    value: string;
    productValue: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.catalogRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        scopeSku: product.sku,
        attribute: "product|product_number",
        operator,
        value,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    await productEditPage.fillInput("product_number", productValue);
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

    generatedProductNumber = `PN-${uniqueStamp()}`;
    product = await new ProductCreatePage(adminPage).createProduct({
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: `Simple-${uniqueStamp()}`,
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

const conditions = [
    {
        title: "is equal to",
        operator: "==",
        value: () => generatedProductNumber,
        productValue: () => generatedProductNumber,
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        value: () => generatedProductNumber,
        productValue: () => generatedProductNumber,
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: () => "123456",
        productValue: () => generatedProductNumber,
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: () => "123456",
        productValue: () => generatedProductNumber,
        type: "fixed",
    },
    {
        title: "contains",
        operator: "{}",
        value: () => generatedProductNumber,
        productValue: () => generatedProductNumber,
        type: "percentage",
    },
    {
        title: "contains",
        operator: "{}",
        value: () => generatedProductNumber,
        productValue: () => generatedProductNumber,
        type: "fixed",
    },
    {
        title: "does not contain",
        operator: "!{}",
        value: () => "123456",
        productValue: () => generatedProductNumber,
        type: "percentage",
    },
    {
        title: "does not contain",
        operator: "!{}",
        value: () => "123456",
        productValue: () => generatedProductNumber,
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const condition of conditions) {
            test(`should apply condition when product number condition is -> ${condition.title} (${condition.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    operator: condition.operator,
                    value: condition.value(),
                    productValue: condition.productValue(),
                    type: condition.type,
                });
            });
        }
    });
});
