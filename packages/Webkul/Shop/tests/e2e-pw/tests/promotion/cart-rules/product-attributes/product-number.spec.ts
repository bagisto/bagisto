import { test } from "../../../../setup";
import { expect, Page } from "@playwright/test";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

let generatedProductNumber: string;

async function expectGrandTotal(page: Page, expectedAmount: number) {
    const formatted =
        Math.abs(expectedAmount) < 0.01
            ? "$0.00"
            : `$${expectedAmount.toFixed(2)}`;

    await expect(
        page.getByText("Grand Total").locator("..").locator("p").last(),
    ).toContainText(formatted);
}

async function applyCouponAndVerify(
    page: Page,
    ruleApplyPage: RuleApplyPage,
    discountValue: number,
    couponType: CouponType,
) {
    const discountedAmount = await ruleApplyPage.calculateDiscountedAmount(
        discountValue,
        couponType,
    );

    await ruleApplyPage.applyCouponAtCheckout();

    await expect(
        page.getByText("Coupon code applied successfully.").first(),
    ).toBeVisible();

    await expectGrandTotal(page, discountedAmount);
}

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
        attribute: "product|product_number",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");

    if (operator === "!=" || operator === "!{}") {
        const fillValue = (Number(value) + 1).toString();
        await page
            .locator('input[name="product_number"]')
            .first()
            .fill(fillValue);
    } else {
        await page.locator('input[name="product_number"]').first().fill(value);
    }

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(page.getByText("Product updated successfully")).toBeVisible();

    await applyCouponAndVerify(page, ruleApplyPage, discountValue, couponType);
}

test.beforeEach(async ({ adminPage }) => {
    generatedProductNumber = `PN-${Date.now()}`;

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

const testCases = [
    {
        operator: "==",
        type: "fixed",
        valueType: "match",
    },
    {
        operator: "==",
        type: "percentage",
        valueType: "match",
    },
    {
        operator: "!=",
        type: "fixed",
        valueType: "non-match",
    },
    {
        operator: "!=",
        type: "percentage",
        valueType: "non-match",
    },
    {
        operator: "{}",
        type: "fixed",
        valueType: "match",
    },
    {
        operator: "{}",
        type: "percentage",
        valueType: "match",
    },
    {
        operator: "!{}",
        type: "fixed",
        valueType: "non-match",
    },
    {
        operator: "!{}",
        type: "percentage",
        valueType: "non-match",
    },
];

test.describe("cart rules", () => {
    test.describe("product atribute conditions", () => {
        for (const { operator, type, valueType } of testCases) {
            test(`should allow coupon when product number condition is -> ${operator} (${type})`, async ({
                page,
            }) => {
                const value =
                    valueType === "match" ? generatedProductNumber : "123456";

                await createRuleAndVerifyCoupon({
                    page,
                    operator,
                    value,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
