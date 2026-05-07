import { expect, test } from "../../../../setup";
import { Page } from "playwright/test";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

async function updateProductHeight(page: Page, value: string) {
    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");
    await page.locator('input[name="height"]').first().fill(value);
    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}

async function createRuleAndVerify({
    page,
    operator,
    conditionValue,
    productValue,
}: {
    page: Page;
    operator: string;
    conditionValue: string;
    productValue: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|height",
        operator,
        value: conditionValue,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await updateProductHeight(page, productValue);

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0);
}

test.beforeEach("should create simple product", async ({ adminPage }) => {
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

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const ruleDeletePage = new RuleDeletePage(adminPage);

        await ruleDeletePage.deleteCatalogRuleAndProduct();
    },
);

const testCases = [
    {
        title: "is equal to",
        operator: "==",
        conditionValue: "1",
        productValue: "1",
    },
    {
        title: "is not equal to",
        operator: "!=",
        conditionValue: "1",
        productValue: "2",
    },
    {
        title: "contains",
        operator: "{}",
        conditionValue: "1",
        productValue: "1",
    },
    {
        title: "does not contain",
        operator: "!{}",
        conditionValue: "1",
        productValue: "2",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const testCase of testCases) {
            test(`should apply coupon when height condition is -> ${testCase.title}`, async ({
                page,
            }) => {
                await createRuleAndVerify({
                    page,
                    operator: testCase.operator,
                    conditionValue: testCase.conditionValue,
                    productValue: testCase.productValue,
                });
            });
        }
    });
});
