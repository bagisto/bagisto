import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

let generatedProductNumber: string;
generatedProductNumber = `PN-${Date.now()}`;

async function createRuleAndVerifyCoupon({
    page,
    operator,
    value,
    productValue,
}: {
    page: any;
    operator: string;
    value: string;
    productValue: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|product_number",
        operator,
        value,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");
    await page
        .locator('input[name="product_number"]')
        .first()
        .fill(productValue);
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
        inventory: 199,
    });
});

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const ruleDeletePage = new RuleDeletePage(adminPage);
        await ruleDeletePage.deleteCatalogRuleAndProduct();
    },
);

const conditions = [
    {
        title: "is equal to",
        operator: "==",
        value: generatedProductNumber,
        productValue: generatedProductNumber,
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: "123456",
        productValue: generatedProductNumber,
    },
    {
        title: "contains",
        operator: "{}",
        value: generatedProductNumber,
        productValue: generatedProductNumber,
    },
    {
        title: "does not contain",
        operator: "!{}",
        value: "123456",
        productValue: generatedProductNumber,
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const condition of conditions) {
            test(`should apply coupon when product number condition is -> ${condition.title}`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator: condition.operator,
                    value: condition.value,
                    productValue: condition.productValue,
                });
            });
        }
    });
});
