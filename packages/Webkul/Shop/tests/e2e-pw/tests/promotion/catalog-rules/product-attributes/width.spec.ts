import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";
import { Page } from "@playwright/test";

test.beforeEach(async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
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

async function updateWidth(page: Page, width: string) {
    await page.goto("admin/catalog/products");

    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();

    await page.waitForLoadState("networkidle");

    await page.locator('input[name="width"]').first().fill(width);

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}

async function runCatalogRuleTest({
    page,
    operator,
    value,
    width,
    type,
}: {
    page: Page;
    operator: string;
    value: string;
    width: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|width",
        operator,
        value,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await updateWidth(page, width);

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0, type);
}

const testCases = [
    {
        operator: "==",
        value: "1",
        width: "1",
        label: "is equal to",
        type: "percentage",
    },
    {
        operator: "==",
        value: "1",
        width: "1",
        label: "is equal to",
        type: "fixed",
    },
    {
        operator: "!=",
        value: "1",
        width: "2",
        label: "is not equal to",
        type: "percentage",
    },
    {
        operator: "!=",
        value: "1",
        width: "2",
        label: "is not equal to",
        type: "fixed",
    },
    {
        operator: "{}",
        value: "1",
        width: "1",
        label: "contains",
        type: "percentage",
    },
    {
        operator: "{}",
        value: "1",
        width: "1",
        label: "contains",
        type: "fixed",
    },
    {
        operator: "!{}",
        value: "1",
        width: "2",
        label: "does not contain",
        type: "percentage",
    },
    {
        operator: "!{}",
        value: "1",
        width: "2",
        label: "does not contain",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when width condition is -> ${tc.label} (${tc.type})`, async ({
                page,
            }) => {
                await runCatalogRuleTest({
                    page,
                    operator: tc.operator,
                    value: tc.value,
                    width: tc.width,
                    type: tc.type,
                });
            });
        }
    });
});
