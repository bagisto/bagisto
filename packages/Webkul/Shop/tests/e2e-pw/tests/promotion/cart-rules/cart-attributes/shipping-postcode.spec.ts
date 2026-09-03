import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

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
        attribute: "cart|postcode",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

    await ruleCreatePage.saveCartRule();

    await ruleApplyPage.expectCouponAppliedWithGrandTotal(
        discountValue,
        couponType,
    );
}

test.beforeEach(async ({ adminPage }) => {
    const productCreation = new ProductCreatePage(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: `Simple-${Date.now()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: Math.floor(Math.random() * 1000) + 1,
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);

    await ruleDeletePage.deleteRuleAndProduct();
});

const testCases = [
    {
        operator: "==",
        value: "123456",
        couponType: "fixed" as CouponType,
        label: "is equal to (fixed)",
    },
    {
        operator: "==",
        value: "123456",
        couponType: "percentage" as CouponType,
        label: "is equal to (percentage)",
    },
    {
        operator: "!=",
        value: "54321",
        couponType: "fixed" as CouponType,
        label: "is not equal to (fixed)",
    },
    {
        operator: "!=",
        value: "54321",
        couponType: "percentage" as CouponType,
        label: "is not equal to (percentage)",
    },
];

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when shipping postcode condition is -> ${tc.label}`, async ({
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
