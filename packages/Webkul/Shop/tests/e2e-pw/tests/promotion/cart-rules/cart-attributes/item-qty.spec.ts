import { expect, test } from "../../../../setup";
import { Page } from "@playwright/test";
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
    incrementTimes?: number,
) {
    const discountedAmount = await ruleApplyPage.calculateDiscountedAmmount(
        discountValue,
        couponType,
        incrementTimes,
    );
    const grandTotal = Number(discountedAmount.toFixed(2));

    await ruleApplyPage.applyCoupon();

    await expect(
        page.getByText("Coupon code applied successfully.").first(),
    ).toBeVisible();
    await page.waitForTimeout(2000);

    if (grandTotal == 0) {
        await expect(
            page.locator("text=Grand Total").locator("..").locator("p").nth(1),
        ).toContainText("$0.00");
    } else {
        const formattedAmount = new Intl.NumberFormat("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(grandTotal);

        await expect(
            page.locator("text=Grand Total").locator("..").locator("p").nth(1),
        ).toContainText(`$${formattedAmount}`);
    }
}

async function createRuleAndVerifyCoupon({
    page,
    operator,
    value,
    couponType,
    incrementTimes,
}: {
    page: Page;
    operator: string;
    value: string;
    couponType: CouponType;
    incrementTimes?: number;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();
    const discountValue = await ruleCreatePage.addCondition({
        attribute: "cart|items_qty",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

    await ruleCreatePage.saveCartRule();
    await expectCouponAppliedWithGrandTotal(
        page,
        ruleApplyPage,
        discountValue,
        couponType,
        incrementTimes,
    );
}

test.beforeEach("should create simple product", async ({ adminPage }) => {
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

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const ruleDeletePage = new RuleDeletePage(adminPage);
        await ruleDeletePage.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
        test("should apply coupon when total item quantity condition is -> is equal to (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "==",
                value: "1",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total item quantity condition is -> is equal to (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "==",
                value: "1",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total item quantity condition is -> is not equal to (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "!=",
                value: "2",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total item quantity condition is -> is not equal to (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "!=",
                value: "2",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total item quantity condition is -> equals or greater then (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: ">=",
                value: "1",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total item quantity condition is -> equals or greater then (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: ">=",
                value: "1",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total item quantity condition is -> equals or less than (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "<=",
                value: "2",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total item quantity condition is -> equals or less than (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "<=",
                value: "2",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total item quantity condition is -> greater than (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: ">",
                value: "1",
                couponType: "fixed",
                incrementTimes: 2,
            });
        });

        test("should apply coupon when total item quantity condition is -> greater than (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: ">",
                value: "1",
                couponType: "percentage",
                incrementTimes: 2,
            });
        });

        test("should apply coupon when total item quantity condition is -> less than (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "<",
                value: "2",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total item quantity condition is -> less than (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "<",
                value: "2",
                couponType: "percentage",
            });
        });
    });
});
