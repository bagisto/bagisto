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

const conditions = [
    {
        title: "is equal to",
        operator: "==",
        optionSelect: "Yes",
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        optionSelect: "Yes",
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        optionSelect: "No",
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        optionSelect: "No",
        type: "fixed",
    },
];

async function createRuleAndVerifyCoupon({
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
        attribute: "product|new",
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

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const condition of conditions) {
            test(`should apply condition when new product condition is -> ${condition.title} (${condition.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    operator: condition.operator,
                    optionSelect: condition.optionSelect,
                    type: condition.type,
                });
            });
        }
    });
});
