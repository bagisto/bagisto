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
    type,
}: {
    page: any;
    operator: string;
    ruleValue: string;
    productValue: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|cost",
        operator,
        optionSelect: ruleValue,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await page.goto("admin/catalog/products");

    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();

    await page.waitForLoadState("networkidle");

    await page.locator('input[name="cost"]').first().fill(productValue);

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0, type);
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
        ruleValue: "199",
        productValue: "199",
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        ruleValue: "199",
        productValue: "199",
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        ruleValue: "100",
        productValue: "200",
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        ruleValue: "100",
        productValue: "200",
        type: "fixed",
    },
    {
        title: "equals or greater then",
        operator: ">=",
        ruleValue: "199",
        productValue: "199",
        type: "percentage",
    },
    {
        title: "equals or greater then",
        operator: ">=",
        ruleValue: "199",
        productValue: "199",
        type: "fixed",
    },
    {
        title: "equals or less than",
        operator: "<=",
        ruleValue: "200",
        productValue: "198",
        type: "percentage",
    },
    {
        title: "equals or less than",
        operator: "<=",
        ruleValue: "200",
        productValue: "198",
        type: "fixed",
    },
    {
        title: "greater than",
        operator: ">",
        ruleValue: "195",
        productValue: "199",
        type: "percentage",
    },
    {
        title: "greater than",
        operator: ">",
        ruleValue: "195",
        productValue: "199",
        type: "fixed",
    },
    {
        title: "less than",
        operator: "<",
        ruleValue: "200",
        productValue: "195",
        type: "percentage",
    },
    {
        title: "less than",
        operator: "<",
        ruleValue: "200",
        productValue: "195",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when cost condition is -> ${tc.title} (${tc.type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator: tc.operator,
                    ruleValue: tc.ruleValue,
                    productValue: tc.productValue,
                    type: tc.type,
                });
            });
        }
    });
});
