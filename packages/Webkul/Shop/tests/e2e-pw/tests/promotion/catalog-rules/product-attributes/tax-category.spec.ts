import { createTaxRate, createTaxCategory } from "../../../../utils/admin";
import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";
import { Page } from "@playwright/test";

let generatedSku: string;
generatedSku = `SKU-${Date.now()}`;

test.beforeEach(
    "should create simple product and tax category",
    async ({ adminPage }) => {
        await createTaxRate(adminPage);
        await createTaxCategory(adminPage);

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

        await assignTaxCategory(adminPage);
    },
);

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);

    await ruleDeletePage.deleteCatalogRuleAndProduct();
});

async function assignTaxCategory(page: Page) {
    await page.goto("admin/catalog/products");

    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();

    await page.waitForLoadState("networkidle");

    await page.locator('select[name="tax_category_id"]').selectOption("1");

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}

async function runCatalogRuleTest({
    page,
    operator,
    value,
}: {
    page: Page;
    operator: string;
    value: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|sku",
        operator,
        value,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0);
}

const testCases = [
    {
        operator: "==",
        value: generatedSku,
        label: "is equal to",
    },
    {
        operator: "!=",
        value: "sku-123",
        label: "is not equal to",
    },
    {
        operator: "{}",
        value: generatedSku,
        label: "contains",
    },
    {
        operator: "!{}",
        value: "example",
        label: "does not contain",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when tax category condition is -> ${tc.label}`, async ({
                page,
            }) => {
                await runCatalogRuleTest({
                    page,
                    operator: tc.operator,
                    value: tc.value,
                });
            });
        }
    });
});
