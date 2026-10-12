import { test } from "../../../setup";
import { Page } from "@playwright/test";
import { ProductCreation } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../utils/admin";

type CouponType = "fixed" | "percentage" | "fixedAmmountWholeCart";

function expectedDiscounts(
    discountValue: number,
    couponType: CouponType,
    subtotal: number,
    shipping: number,
) {
    if (couponType === "percentage") {
        return {
            items: (subtotal * discountValue) / 100,
            shipping: (shipping * discountValue) / 100,
        };
    }

    return {
        items: Math.min(discountValue, subtotal),
        shipping: Math.min(discountValue, shipping),
    };
}

async function expectCouponAppliedToShipping(
    ruleApplyPage: RuleApplyPage,
    discountValue: number,
    couponType: CouponType,
) {
    const subtotal = await ruleApplyPage.getSummaryAmount("Subtotal");
    const shipping = await ruleApplyPage.getDeliveryChargesValue();

    const discounts = expectedDiscounts(
        discountValue,
        couponType,
        subtotal,
        shipping,
    );

    if (discounts.items + discounts.shipping > 0) {
        await ruleApplyPage.expectDiscountBreakdownToBe(
            discounts.items,
            discounts.shipping,
        );
    }

    await ruleApplyPage.expectGrandTotalToBe(
        subtotal + shipping - discounts.items - discounts.shipping,
    );
}

async function expectCouponAppliedWithGrandTotal(
    ruleApplyPage: RuleApplyPage,
    discountValue: number,
    couponType: CouponType,
    allowShipping?: string,
) {
    const discountedAmount = await ruleApplyPage.calculateDiscountedAmount(
        discountValue,
        couponType,
    );

    await ruleApplyPage.applyCouponAtCheckout(allowShipping);

    await ruleApplyPage.expectCouponApplied();

    if (allowShipping === "yes") {
        await expectCouponAppliedToShipping(
            ruleApplyPage,
            discountValue,
            couponType,
        );

        return;
    }

    await ruleApplyPage.expectGrandTotalToBe(discountedAmount);
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
            option: "India",
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
