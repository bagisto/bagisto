import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

async function createRuleAndVerifyCoupon({
    page,
    operator,
    ruleValue,
    productValue,
}: {
    page: any;
    operator: string;
    ruleValue: string;
    productValue: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|color",
        operator,
        optionSelect: ruleValue,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await page.goto("admin/catalog/products");

    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();

    await page.waitForLoadState("networkidle");

    await page
        .locator('select[name="color"]')
        .first()
        .selectOption(productValue);

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();

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
        title: "should apply coupon when color condition is -> is equal to",
        operator: "==",
        ruleValue: "1",
        productValue: "1",
    },
    {
        title: "should apply coupon when color condition is -> is not equal to",
        operator: "!=",
        ruleValue: "1",
        productValue: "2",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(tc.title, async ({ page }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator: tc.operator,
                    ruleValue: tc.ruleValue,
                    productValue: tc.productValue,
                });
            });
        }
    });
});
