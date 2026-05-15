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

    if (discountValue === undefined) throw new Error("Discount not created");

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

const cases = [
    {
        operator: "==",
        value: "1",
        type: "percentage",
        label: "is equal to",
    },
    {
        operator: "==",
        value: "1",
        type: "fixed",
        label: "is equal to",
    },

    {
        operator: "!=",
        value: "100",
        type: "percentage",
        label: "is not equal to",
    },
    {
        operator: "!=",
        value: "100",
        type: "fixed",
        label: "is not equal to",
    },

    {
        operator: ">=",
        value: "1",
        type: "percentage",
        label: "is greater than or equal to",
    },
    {
        operator: ">=",
        value: "1",
        type: "fixed",
        label: "is greater than or equal to",
    },

    {
        operator: "<=",
        value: "1",
        type: "percentage",
        label: "is less than or equal to",
    },
    {
        operator: "<=",
        value: "1",
        type: "fixed",
        label: "is less than or equal to",
    },

    {
        operator: ">",
        value: "0",
        type: "percentage",
        label: "is greater than",
    },
    {
        operator: ">",
        value: "0",
        type: "fixed",
        label: "is greater than",
    },

    {
        operator: "<",
        value: "2",
        type: "percentage",
        label: "is less than",
    },
    {
        operator: "<",
        value: "2",
        type: "fixed",
        label: "is less than",
    },
];

test.describe("cart rules", () => {
    test.describe("cart item attrubute", () => {
        for (const { operator, value, type, label } of cases) {
            test(`should apply coupon when qty in cart is ->  ${label} (${operator}) (${type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    attribute: "cart_item|quantity",
                    operator,
                    value,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
