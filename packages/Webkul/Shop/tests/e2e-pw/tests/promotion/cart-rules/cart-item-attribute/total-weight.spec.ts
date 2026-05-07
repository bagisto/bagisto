import { test } from "../../../../setup";
import { expect, Page } from "@playwright/test";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

async function updateProductWeight(page: Page, weight: string) {
    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");
    await page.locator('input[name="weight"]').first().fill(weight);
    await page.locator('button:has-text("Save Product")').first().click();

    await expect(page.getByText("Product updated successfully")).toBeVisible();
}

async function expectCouponAppliedWithGrandTotal(
    page: Page,
    ruleApplyPage: RuleApplyPage,
    discountValue: number,
    couponType: CouponType,
) {
    const discountedAmount = await ruleApplyPage.calculateDiscountedAmount(
        discountValue,
        couponType,
    );

    const formatted =
        Math.abs(discountedAmount) < 0.01
            ? "$0.00"
            : `$${discountedAmount.toFixed(2)}`;

    await ruleApplyPage.applyCouponAtCheckout();

    await expect(
        page.getByText("Coupon code applied successfully.").first(),
    ).toBeVisible();

    await expect(
        page.getByText("Grand Total").locator("..").locator("p").last(),
    ).toContainText(formatted);
}

async function createRuleAndVerifyCoupon({
    page,
    attribute,
    operator,
    value,
    couponType,
    productWeight,
}: {
    page: Page;
    attribute: string;
    operator: string;
    value: string;
    couponType: CouponType;
    productWeight?: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute,
        operator,
        value,
        couponType,
    });

    if (!discountValue) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    if (productWeight) {
        await updateProductWeight(page, productWeight);
    }

    await expectCouponAppliedWithGrandTotal(
        page,
        ruleApplyPage,
        discountValue,
        couponType,
    );
}

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
    await ruleDeletePage.deleteRuleAndProduct();
});

const cases = [
    {
        operator: "==",
        value: "1",
        type: "percentage",
        label: "equal to (percentage)",
    },
    {
        operator: "==",
        value: "1",
        type: "fixed",
        label: "equal to (fixed)",
    },

    {
        operator: "!=",
        value: "2",
        type: "percentage",
        label: "not equal to (percentage)",
    },
    {
        operator: "!=",
        value: "2",
        type: "fixed",
        label: "not equal to (fixed)",
    },

    {
        operator: ">=",
        value: "1",
        type: "percentage",
        label: "greater than or equal to (percentage)",
    },
    {
        operator: ">=",
        value: "1",
        type: "fixed",
        label: "greater than or equal to (fixed)",
    },

    {
        operator: "<=",
        value: "2",
        type: "percentage",
        label: "less than or equal to (percentage)",
    },
    {
        operator: "<=",
        value: "2",
        type: "fixed",
        label: "less than or equal to (fixed)",
    },
    {
        operator: ">",
        value: "1",
        type: "percentage",
        weight: "2",
        label: "greater than (percentage)",
    },
    {
        operator: ">",
        value: "1",
        type: "fixed",
        weight: "2",
        label: "greater than (fixed)",
    },

    {
        operator: "<",
        value: "2",
        type: "percentage",
        label: "less than (percentage)",
    },
    {
        operator: "<",
        value: "2",
        type: "fixed",
        label: "less than (fixed)",
    },
];

test.describe("cart rules", () => {
    test.describe("cart item attributes", () => {
        for (const { operator, value, type, weight, label } of cases) {
            test(`should apply coupon when total weight condition is -> ${label}`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    attribute: "cart_item|base_total_weight",
                    operator,
                    value,
                    couponType: type as CouponType,
                    productWeight: weight,
                });
            });
        }
    });
});
