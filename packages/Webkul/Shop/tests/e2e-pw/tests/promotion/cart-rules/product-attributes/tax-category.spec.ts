import { test } from "../../../../setup";
import { expect, Page } from "@playwright/test";
import { createTaxRate, createTaxCategory } from "../../../../utils/admin";
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

async function createRuleAndVerifyTaxCategory({
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
        attribute: "product|tax_category_id",
        operator,
        optionSelect,
        couponType,
    });

    if (!discountValue) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    await expectCouponAppliedWithGrandTotal(
        page,
        ruleApplyPage,
        discountValue,
        couponType,
    );
}

test.describe("cart rules - tax category conditions", () => {
    test.beforeEach(async ({ adminPage }) => {
        await createTaxRate(adminPage);
        await createTaxCategory(adminPage);

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

        await adminPage.goto("admin/catalog/products");

        await adminPage
            .locator('select[name="tax_category_id"]')
            .first()
            .selectOption("1");

        await adminPage
            .locator('button:has-text("Save Product")')
            .first()
            .click();

        await expect(
            adminPage.getByText("Product updated successfully"),
        ).toBeVisible();
    });

    test.afterEach(async ({ adminPage }) => {
        const ruleDeletePage = new RuleDeletePage(adminPage);
        await ruleDeletePage.deleteRuleAndProduct();
    });

    const cases = [
        { operator: "==", type: "fixed", option: "1" },
        { operator: "==", type: "percentage", option: "1" },
        { operator: "!=", type: "fixed", option: "2" },
        { operator: "!=", type: "percentage", option: "2" },
    ];

    for (const { operator, type, option } of cases) {
        test(`tax category ${operator} (${type})`, async ({ page }) => {
            await createRuleAndVerifyTaxCategory({
                page,
                operator,
                optionSelect: option,
                couponType: type as CouponType,
            });
        });
    }
});
