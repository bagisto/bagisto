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

    await ruleApplyPage.expectCouponAppliedWithGrandTotal(
        discountValue,
        couponType,
        { incrementTimes },
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
