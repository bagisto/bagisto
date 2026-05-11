import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";
import { Page } from "@playwright/test";

let generatedName: string;
generatedName = `Simple-${Date.now()}`;

test.beforeEach(async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: generatedName,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 199,
    });
});

test.afterEach(async ({ page }) => {
    const ruleDeletePage = new RuleDeletePage(page);
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
        attribute: "product|url_key",
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
        value: generatedName.toLowerCase(),
        label: "is equal to",
        type: "percentage",
    },
    {
        operator: "==",
        value: generatedName.toLowerCase(),
        label: "is equal to",
        type: "fixed",
    },
    {
        operator: "!=",
        value: "simple",
        label: "is not equal to",
        type: "percentage",
    },
    {
        operator: "!=",
        value: "simple",
        label: "is not equal to",
        type: "fixed",
    },
    {
        operator: "{}",
        value: generatedName.toLowerCase(),
        label: "contains",
        type: "percentage",
    },
    {
        operator: "{}",
        value: generatedName.toLowerCase(),
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
            test(`should apply condition when url key condition is -> ${tc.label} (${tc.type})`, async ({
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
