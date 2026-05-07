import { test, expect } from "../../../../setup";
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

async function assignCategory(page: Page, categoryName: string) {
    await page.goto("admin/catalog/products");

    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();

    await page.waitForLoadState("networkidle");

    const categoryLabel = page.locator("label", {
        hasText: new RegExp(`^${categoryName}$`),
    });

    const categoryCheckbox = categoryLabel.locator('input[type="checkbox"]');

    await expect(categoryCheckbox).toBeAttached();

    if (!(await categoryCheckbox.isChecked())) {
        await categoryLabel.click();
    }

    await expect(categoryCheckbox).toBeChecked();

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}

async function runCatalogRuleTest({
    page,
    operator,
    checkboxSelect,
    assignCategoryName,
}: {
    page: Page;
    operator: string;
    checkboxSelect: string;
    assignCategoryName: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|category_ids",
        operator,
        checkboxSelect,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await assignCategory(page, assignCategoryName);

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0);
}

const testCases = [
    {
        operator: "{}",
        checkboxSelect: "Mens",
        assignCategoryName: "Mens",
        label: "contains",
    },
    {
        operator: "!{}",
        checkboxSelect: "Mens",
        assignCategoryName: "Womens",
        label: "does not contains",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when category of product condition is -> ${tc.label}`, async ({
                page,
            }) => {
                await runCatalogRuleTest({
                    page,
                    operator: tc.operator,
                    checkboxSelect: tc.checkboxSelect,
                    assignCategoryName: tc.assignCategoryName,
                });
            });
        }
    });
});
