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

async function updateSpecialPrice(page: Page, specialPrice: string) {
    await page.goto("admin/catalog/products");

    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();

    await page.waitForLoadState("networkidle");

    await page
        .locator('input[name="special_price"]')
        .first()
        .fill(specialPrice);

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}

async function runCatalogRuleTest({
    page,
    operator,
    value,
    specialPrice,
}: {
    page: Page;
    operator: string;
    value: string;
    specialPrice: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|special_price",
        operator,
        value,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await updateSpecialPrice(page, specialPrice);

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0);
}

const testCases = [
    {
        operator: "==",
        value: "150",
        specialPrice: "150",
        label: "is equal to",
    },
    {
        operator: "!=",
        value: "100",
        specialPrice: "150",
        label: "is not equal to",
    },
    {
        operator: ">=",
        value: "150",
        specialPrice: "160",
        label: "equals or greater then",
    },
    {
        operator: "<=",
        value: "200",
        specialPrice: "150",
        label: "equals or less than",
    },
    {
        operator: ">",
        value: "100",
        specialPrice: "150",
        label: "greater than",
    },
    {
        operator: "<",
        value: "200",
        specialPrice: "195",
        label: "less than",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when special price condition is -> ${tc.label}`, async ({
                page,
            }) => {
                await runCatalogRuleTest({
                    page,
                    operator: tc.operator,
                    value: tc.value,
                    specialPrice: tc.specialPrice,
                });
            });
        }
    });
});
