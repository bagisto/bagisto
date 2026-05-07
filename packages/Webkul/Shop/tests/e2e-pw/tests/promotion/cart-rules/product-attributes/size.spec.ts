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

    const grandTotal =
        Math.abs(discountedAmount) < 0.01
            ? "$0.00"
            : `$${discountedAmount.toFixed(2)}`;

    await ruleApplyPage.applyCouponAtCheckout();

    await expect(
        page.getByText("Coupon code applied successfully.").first(),
    ).toBeVisible();

    await expect(
        page.getByText("Grand Total").locator("..").locator("p").last(),
    ).toContainText(grandTotal);
}

async function createRuleAndVerifySize({
    page,
    operator,
    couponType,
    ruleSize,
    productSize,
}: {
    page: Page;
    operator: string;
    couponType: CouponType;
    ruleSize: string;
    productSize: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|size",
        operator,
        optionSelect: ruleSize,
        couponType,
    });

    if (!discountValue) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();
    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.locator('select[name="size"]').first().selectOption(productSize);
    await page.locator('button:has-text("Save Product")').first().click();

    await expect(page.getByText("Product updated successfully")).toBeVisible();

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
        type: "fixed",
        ruleSize: "6",
        productSize: "6",
    },
    {
        operator: "==",
        type: "percentage",
        ruleSize: "6",
        productSize: "6",
    },
    {
        operator: "!=",
        type: "fixed",
        ruleSize: "6",
        productSize: "7",
    },
    {
        operator: "!=",
        type: "percentage",
        ruleSize: "6",
        productSize: "7",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute condition", () => {
        for (const { operator, type, ruleSize, productSize } of cases) {
            test(`should apply coupon when size condition is ->  ${operator} (${type})`, async ({
                page,
            }) => {
                await createRuleAndVerifySize({
                    page,
                    operator,
                    couponType: type as CouponType,
                    ruleSize,
                    productSize,
                });
            });
        }
    });
});
