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

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}

async function expectCouponAppliedWithGrandTotal(
    page: Page,
    ruleApplyPage: RuleApplyPage,
    discountValue: number,
    couponType: CouponType,
) {
    const discountedAmount = await ruleApplyPage.calculateDiscountedAmmount(
        discountValue,
        couponType,
    );
    const grandTotal = Number(discountedAmount.toFixed(2));

    await ruleApplyPage.applyCouponAtCheckout();

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

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

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

test.beforeEach("should create simple product", async ({ adminPage }) => {
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

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const ruleDeletePage = new RuleDeletePage(adminPage);
        await ruleDeletePage.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("cart item attribute conditions", () => {
        test("should apply coupon when total weight in cart condition is -> is equal to (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "==",
                value: "1",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total weight in cart condition is -> is equal to (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "==",
                value: "1",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total weight in cart condition is -> is not equal to (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "!=",
                value: "2",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total weight in cart condition is -> is not equal to (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "!=",
                value: "2",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total weight in cart condition is -> equals or greater then (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: ">=",
                value: "1",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total weight in cart condition is -> equals or greater then (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: ">=",
                value: "1",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total weight in cart condition is -> equals or less than (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "<=",
                value: "2",
                couponType: "fixed",
            });
        });

        test("should apply coupon when total weight in cart condition is -> equals or less than (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "<=",
                value: "2",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total weight in cart condition is -> greater than (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: ">",
                value: "1",
                couponType: "fixed",
                productWeight: "2",
            });
        });

        test("should apply coupon when total weight in cart condition is -> greater than (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: ">",
                value: "1",
                couponType: "percentage",
                productWeight: "2",
            });
        });

        test("should apply coupon when total weight in cart condition is -> less than (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "<",
                value: "2",
                couponType: "percentage",
            });
        });

        test("should apply coupon when total weight in cart condition is -> less than (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                attribute: "cart_item|base_total_weight",
                operator: "<",
                value: "2",
                couponType: "fixed",
            });
        });
    });
});
