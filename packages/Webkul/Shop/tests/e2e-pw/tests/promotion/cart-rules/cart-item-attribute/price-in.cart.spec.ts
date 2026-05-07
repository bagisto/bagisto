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
}: {
    page: Page;
    attribute: string;
    operator: string;
    value: string;
    couponType: CouponType;
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

test.describe("cart rules - price conditions", () => {
    const cases = [
        { operator: "==", value: "199", type: "percentage" },
        { operator: "==", value: "199", type: "fixed" },

        { operator: "!=", value: "100", type: "percentage" },
        { operator: "!=", value: "100", type: "fixed" },

        { operator: ">=", value: "199", type: "percentage" },
        { operator: ">=", value: "199", type: "fixed" },

        { operator: "<=", value: "200", type: "percentage" },
        { operator: "<=", value: "200", type: "fixed" },

        { operator: ">", value: "198", type: "percentage" },
        { operator: ">", value: "198", type: "fixed" },

        { operator: "<", value: "200", type: "percentage" },
        { operator: "<", value: "200", type: "fixed" },
    ];

    for (const { operator, value, type } of cases) {
        test(`price ${operator} (${type})`, async ({ page }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_price",
                operator,
                value,
                couponType: type as CouponType,
            });
        });
    }
});
