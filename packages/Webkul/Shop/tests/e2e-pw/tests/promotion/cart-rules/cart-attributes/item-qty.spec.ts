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
    const discountedAmount = await ruleApplyPage.calculateDiscountedAmount(
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

    if (grandTotal === 0) {
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

const testCases = [
    {
        operator: "==",
        value: "1",
        incrementTimes: undefined,
        label: "is equal to",
    },
    {
        operator: "!=",
        value: "2",
        incrementTimes: undefined,
        label: "is not equal to",
    },
    {
        operator: ">=",
        value: "1",
        incrementTimes: undefined,
        label: "equals or greater then",
    },
    {
        operator: "<=",
        value: "2",
        incrementTimes: undefined,
        label: "equals or less than",
    },
    {
        operator: ">",
        value: "1",
        incrementTimes: 2,
        label: "greater than",
    },
    {
        operator: "<",
        value: "2",
        incrementTimes: undefined,
        label: "less than",
    },
];

const couponTypes: CouponType[] = ["fixed", "percentage"];

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
        for (const tc of testCases) {
            for (const couponType of couponTypes) {
                test(`should apply coupon when total item quantity condition is -> ${tc.label} (${couponType})`, async ({
                    page,
                }) => {
                    await createRuleAndVerifyCoupon({
                        page,
                        operator: tc.operator,
                        value: tc.value,
                        couponType,
                        incrementTimes: tc.incrementTimes,
                    });
                });
            }
        }
    });
});
