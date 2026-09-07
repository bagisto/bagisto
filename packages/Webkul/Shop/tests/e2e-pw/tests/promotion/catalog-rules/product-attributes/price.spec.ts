import { uniqueStamp } from "../../../../utils/faker";
import type { Page } from "@playwright/test";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { test } from "../../../../setup";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

async function createRuleAndVerifyCoupon({
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
        attribute: "product|price",
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

async function createRuleAndExpectNoDiscount({
    adminPage,
    shopPage,
    operator,
    value,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    value: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.catalogRuleCreationFlow();
    createdRules.push(rule.name);

    await ruleCreatePage.addCondition({
        scopeSku: product.sku,
        attribute: "product|price",
        operator,
        value,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.expectNoCatalogDiscount(product.name, product.price ?? 0);
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

const conditions = [
    {
        title: "is equal to",
        operator: "==",
        value: "199",
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        value: "199",
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: "100",
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: "100",
        type: "fixed",
    },
    {
        title: "equals or greater then",
        operator: ">=",
        value: "199",
        type: "percentage",
    },
    {
        title: "equals or greater then",
        operator: ">=",
        value: "199",
        type: "fixed",
    },
    {
        title: "equals or less than",
        operator: "<=",
        value: "200",
        type: "percentage",
    },
    {
        title: "equals or less than",
        operator: "<=",
        value: "200",
        type: "fixed",
    },
    {
        title: "greater than",
        operator: ">",
        value: "198",
        type: "percentage",
    },
    {
        title: "greater than",
        operator: ">",
        value: "198",
        type: "fixed",
    },
    {
        title: "less than",
        operator: "<",
        value: "200",
        type: "percentage",
    },
    {
        title: "less than",
        operator: "<",
        value: "200",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const condition of conditions) {
            test(`should apply condition when price condition is -> ${condition.title} (${condition.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    operator: condition.operator,
                    value: condition.value,
                    type: condition.type,
                });
            });
        }

        test("should leave the price untouched when the price condition does not match", async ({
            adminPage,
            shopPage,
        }) => {
            await createRuleAndExpectNoDiscount({
                adminPage,
                shopPage,
                operator: "==",
                value: "100",
            });
        });
    });
});
