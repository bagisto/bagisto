import { test } from "../../../../setup";
import { expect, Page } from "@playwright/test";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

const specialPriceValue = "150";

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
        attribute: "product|special_price",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");

    if (operator === "!=" || operator === ">=" || operator === ">") {
        const fillValue = (Number(value) + 1).toString();
        await page
            .locator('input[name="special_price"]')
            .first()
            .fill(fillValue);
    } else if (operator === "<" || operator === "<=") {
        const fillValue = (Number(value) - 5).toString();
        await page
            .locator('input[name="special_price"]')
            .first()
            .fill(fillValue);
    } else {
        await page.locator('input[name="special_price"]').first().fill(value);
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

const cases = [
    { operator: "==", type: "fixed", value: specialPriceValue },
    { operator: "==", type: "percentage", value: specialPriceValue },

    { operator: "!=", type: "fixed", value: "100" },
    { operator: "!=", type: "percentage", value: "100" },

    { operator: ">=", type: "fixed", value: specialPriceValue },
    { operator: ">=", type: "percentage", value: specialPriceValue },

    { operator: "<=", type: "fixed", value: "200" },
    { operator: "<=", type: "percentage", value: "200" },

    { operator: ">", type: "fixed", value: "100" },
    { operator: ">", type: "percentage", value: "100" },

    { operator: "<", type: "fixed", value: "200" },
    { operator: "<", type: "percentage", value: "200" },
];

test.describe("cart rules", () => {
    test.describe("product attribute condition", () => {
        for (const { operator, type, value } of cases) {
            test(`should apply coupon when special price condition is->  ${operator} (${type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator,
                    value,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
