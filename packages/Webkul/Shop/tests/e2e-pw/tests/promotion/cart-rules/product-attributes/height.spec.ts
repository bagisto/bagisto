import { test } from "../../../../setup";
import { expect, Page } from "@playwright/test";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

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
    const grandTotal = Number(discountedAmount.toFixed(2));

    await ruleApplyPage.applyCouponAtCheckout();

    await expect(
        page.getByText("Coupon code applied successfully.").first(),
    ).toBeVisible();

    const expectedText =
        grandTotal === 0
            ? "$0.00"
            : `$${new Intl.NumberFormat("en-US", {
                  minimumFractionDigits: 2,
              }).format(grandTotal)}`;

    await expect(
        page.locator("text=Grand Total").locator("..").locator("p").nth(1),
    ).toContainText(expectedText);
}

async function createRuleAndVerifyCoupon({
    page,
    operator,
    value,
    couponType,
}: {
    page: Page;
    operator: string;
    value: string;
    couponType: CouponType;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|height",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

    await ruleCreatePage.saveCartRule();

    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");

    if (operator === "!=" || operator === "!{}") {
        const fillValue = (Number(value) + 1).toString();
        await page.locator('input[name="height"]').first().fill(fillValue);
    } else {
        await page.locator('input[name="height"]').first().fill(value);
    }

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();

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

type TestCase = {
    operator: string;
    value: string;
    couponType: CouponType;
    label: string;
};

const testCases: TestCase[] = [
    {
        operator: "==",
        value: "1",
        couponType: "fixed",
        label: "is equal to (fixed)",
    },
    {
        operator: "==",
        value: "1",
        couponType: "percentage",
        label: "is equal to (percentage)",
    },

    {
        operator: "!=",
        value: "2",
        couponType: "fixed",
        label: "is not equal to (fixed)",
    },
    {
        operator: "!=",
        value: "2",
        couponType: "percentage",
        label: "is not equal to (percentage)",
    },

    {
        operator: "{}",
        value: "1",
        couponType: "fixed",
        label: "contains (fixed)",
    },
    {
        operator: "{}",
        value: "1",
        couponType: "percentage",
        label: "contains (percentage)",
    },

    {
        operator: "!{}",
        value: "2",
        couponType: "fixed",
        label: "does not contain (fixed)",
    },
    {
        operator: "!{}",
        value: "2",
        couponType: "percentage",
        label: "does not contain (percentage)",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when height condition is -> ${tc.label}`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator: tc.operator,
                    value: tc.value,
                    couponType: tc.couponType,
                });
            });
        }
    });
});
