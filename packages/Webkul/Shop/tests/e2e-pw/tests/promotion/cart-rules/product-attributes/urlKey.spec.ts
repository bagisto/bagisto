import { test } from "../../../../setup";
import { expect, Page } from "@playwright/test";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

let generatedName: string;

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

async function createRuleAndVerifyUrlKey({
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
        attribute: "product|url_key",
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
    generatedName = `Simple-${Date.now()}`;

    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: generatedName,
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
        type: "fixed",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "==",
        type: "percentage",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "!=",
        type: "fixed",
        value: () => "simple",
    },
    {
        operator: "!=",
        type: "percentage",
        value: () => "simple",
    },
    {
        operator: "{}",
        type: "fixed",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "{}",
        type: "percentage",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "!{}",
        type: "fixed",
        value: () => "example",
    },
    {
        operator: "!{}",
        type: "percentage",
        value: () => "example",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute cndition", () => {
        for (const { operator, type, value } of cases) {
            test(`should allow coupon when url key condition is ->  ${operator} (${type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyUrlKey({
                    page,
                    operator,
                    value: value(),
                    couponType: type as CouponType,
                });
            });
        }
    });
});
