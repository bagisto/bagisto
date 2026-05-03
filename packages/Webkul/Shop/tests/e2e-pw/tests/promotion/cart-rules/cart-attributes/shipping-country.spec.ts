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
    operator,
    optionSelect,
    couponType,
}: {
    page: Page;
    operator: string;
    optionSelect: string;
    couponType: CouponType;
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
        test("should apply coupon when country condition is -> is equal to (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "==",
                optionSelect: "IN",
                couponType: "fixed",
            });
        });

        test("should apply coupon when country condition is -> is equal to (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "==",
                optionSelect: "IN",
                couponType: "percentage",
            });
        });

        test("should apply coupon when country condition is -> is not equal to (fixed)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "!=",
                optionSelect: "US",
                couponType: "fixed",
            });
        });

        test("should apply coupon when country condition is -> is not equal to (percentage)", async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator: "!=",
                optionSelect: "US",
                couponType: "percentage",
            });
        });
    });
});
