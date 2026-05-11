import { test } from "../../../setup";
import { expect, Page } from "@playwright/test";
import { ProductCreation } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../utils/admin";

type CouponType = "fixed" | "percentage" | "fixedAmmountWholeCart";

async function expectCouponAppliedWithGrandTotal(
    page: Page,
    ruleApplyPage: RuleApplyPage,
    discountValue: number,
    couponType: CouponType,
    allowShipping?: string,
) {
    const discountedAmount = await ruleApplyPage.calculateDiscountedAmount(
        discountValue,
        couponType,
    );

    const formatted =
        Math.abs(discountedAmount) < 0.01
            ? "$0.00"
            : `$${discountedAmount.toFixed(2)}`;

    await ruleApplyPage.applyCouponAtCheckout(allowShipping);

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
    optionSelect,
    couponType,
    allowShipping,
}: {
    page: Page;
    operator: string;
    optionSelect: string;
    couponType: CouponType;
    allowShipping?: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "cart|country",
        operator,
        optionSelect,
        couponType,
        allowShipping,
    });

    if (!discountValue) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    await expectCouponAppliedWithGrandTotal(
        page,
        ruleApplyPage,
        discountValue,
        couponType,
        allowShipping,
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
        price: Math.floor(Math.random() * 1000),
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);
    await ruleDeletePage.deleteRuleAndProduct();
});

test.describe("cart rules", () => {
    const cases = [
        {
            operator: "==",
            option: "IN",
            type: "fixed",
            allowShipping: "yes",
            label: "is equal to",
        },
    ];

    for (const { operator, option, type, allowShipping, label } of cases) {
        test("should apply coupon on shipping price", async ({ page }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator,
                optionSelect: option,
                couponType: type as CouponType,
                allowShipping,
            });
        });
    }
});
