import { test } from "../../../../setup";
import { expect, Page } from "@playwright/test";
import {
    createTaxRate,
    createTaxCategory,
    createTaxCategoryReturnName,
} from "../../../../utils/admin";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";
import { generateName } from "../../../../utils/faker";

type CouponType = "fixed" | "percentage";

async function createRuleAndVerifyTaxCategory({
    page,
    operator,
    optionSelect,
    couponType,
}: {
    page: Page;
    operator: string;
    optionSelect: string;
    couponType: CouponType;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|tax_category_id",
        operator,
        optionSelect,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    await ruleApplyPage.expectCouponAppliedWithGrandTotal(
        discountValue,
        couponType,
    );
}

const taxCategoryName = generateName();
const taxCategoryName2 = generateName();

test.beforeEach(async ({ adminPage }) => {
    await createTaxRate(adminPage);

    await createTaxCategoryReturnName(taxCategoryName, adminPage);

    await createTaxCategoryReturnName(taxCategoryName2, adminPage);

    const productCreation = new ProductCreatePage(adminPage);

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

    await adminPage.goto("admin/catalog/products");
    await adminPage
        .locator("span.cursor-pointer.icon-sort-right")
        .nth(1)
        .click();
    await adminPage.waitForLoadState("networkidle");
    await adminPage.locator('span:text-is("Tax Category")').click();
    await adminPage
        .locator(`span:text-is("${taxCategoryName}")`)
        .first()
        .click();

    await adminPage.locator('button:has-text("Save Product")').first().click();

    await expect(
        adminPage.getByText("Product updated successfully"),
    ).toBeVisible();
});

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);
    await ruleDeletePage.deleteRuleAndProduct();
});

const cases = [
    { operator: "==", type: "fixed", option: taxCategoryName },
    { operator: "==", type: "percentage", option: taxCategoryName },
    { operator: "!=", type: "fixed", option: taxCategoryName2 },
    { operator: "!=", type: "percentage", option: taxCategoryName2 },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const { operator, type, option } of cases) {
            test(`should apply coupon when tax category condition is -> ${operator} (${type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyTaxCategory({
                    page,
                    operator,
                    optionSelect: option,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
