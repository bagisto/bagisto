import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";
import { Page } from "@playwright/test";

let generatedSku: string;
generatedSku = `SKU-${Date.now()}`;

test.beforeEach(async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: generatedSku,
        name: `Simple-${Date.now()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);
    await ruleDeletePage.deleteCatalogRuleAndProduct();
});

async function runCatalogRuleTest({
    page,
    operator,
    value,
    type,
}: {
    page: Page;
    operator: string;
    value: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|sku",
        operator,
        value,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0, type);
}

const testCases = [
    {
        operator: "==",
        value: generatedSku,
        label: "is equal to",
        type: "percentage",
    },
    {
        operator: "==",
        value: generatedSku,
        label: "is equal to",
        type: "fixed",
    },
    {
        operator: "!=",
        value: "sku-123",
        label: "is not equal to",
        type: "percentage",
    },
    {
        operator: "!=",
        value: "sku-123",
        label: "is not equal to",
        type: "fixed",
    },
    {
        operator: "{}",
        value: generatedSku,
        label: "contains",
        type: "percentage",
    },
    {
        operator: "{}",
        value: generatedSku,
        label: "contains",
        type: "fixed",
    },
    {
        operator: "!{}",
        value: "example",
        label: "does not contain",
        type: "percentage",
    },
    {
        operator: "!{}",
        value: "example",
        label: "does not contain",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when sku condition is -> ${tc.label} (${tc.type})`, async ({
                page,
            }) => {
                await runCatalogRuleTest({
                    page,
                    operator: tc.operator,
                    value: tc.value,
                    type: tc.type,
                });
            });
        }
    });
});
