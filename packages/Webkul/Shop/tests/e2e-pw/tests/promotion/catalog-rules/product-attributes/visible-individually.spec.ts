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

test.afterEach(async ({ adminPage }) => {
    const rulesDeletePage = new RuleDeletePage(adminPage);
    await rulesDeletePage.deleteCatalogRuleAndProduct();
});

async function runCatalogRuleTest({
    page,
    operator,
    optionSelect,
    type,
}: {
    page: Page;
    operator: string;
    optionSelect: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|visible_individually",
        operator,
        optionSelect,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0, type);
}

const testCases = [
    {
        operator: "==",
        optionSelect: "1",
        label: "is equal to (yes)",
        type: "percentage",
    },
    {
        operator: "==",
        optionSelect: "1",
        label: "is equal to (yes)",
        type: "fixed",
    },
    {
        operator: "!=",
        optionSelect: "0",
        label: "is not equal to (no)",
        type: "percentage",
    },
    {
        operator: "!=",
        optionSelect: "0",
        label: "is not equal to (no)",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when visible individually condition is -> ${tc.label} (${tc.type})`, async ({
                page,
            }) => {
                await runCatalogRuleTest({
                    page,
                    operator: tc.operator,
                    optionSelect: tc.optionSelect,
                    type: tc.type,
                });
            });
        }
    });
});
