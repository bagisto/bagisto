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

    const grandTotal = Number(discountedAmount.toFixed(2));

    await ruleApplyPage.applyCouponAtCheckout();

    await expect(
        page.getByText("Coupon code applied successfully.").first(),
    ).toBeVisible();

    const expectedText =
        grandTotal === 0
            ? "$0.00"
            : `$${new Intl.NumberFormat("en-US", {
                  minimumFractionDigits: 2,
              }).format(grandTotal)}`;

    await expect(
        page.locator("text=Grand Total").locator("..").locator("p").nth(1),
    ).toContainText(expectedText);
}

async function updateProductCategory(page: Page, categoryName: string) {
    await page.goto("admin/catalog/products");

    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");

    const label = page.locator("label", {
        hasText: new RegExp(`^${categoryName}$`),
    });

    const checkbox = label.locator('input[type="checkbox"]');

    await expect(checkbox).toBeAttached();

    if (!(await checkbox.isChecked())) {
        await label.click();
    }

    await expect(checkbox).toBeChecked();

    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}

async function runCartRuleTest(
    page: Page,
    {
        operator,
        category,
        couponType,
    }: {
        operator: string;
        category: string;
        couponType: CouponType;
    },
) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|category_ids",
        operator,
        checkboxSelect: "Mens", // rule condition always uses Mens
        couponType,
    });

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

    await ruleCreatePage.saveCartRule();
    await updateProductCategory(page, category);
    await expectCouponAppliedWithGrandTotal(
        page,
        ruleApplyPage,
        discountValue,
        couponType,
    );
}

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
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

        type TestCase = {
            operator: string;
            category: string;
            couponType: CouponType;
            label: string;
        };

        const testCases: TestCase[] = [
            {
                operator: "{}",
                category: "Mens",
                couponType: "fixed",
                label: "contains (fixed)",
            },
            {
                operator: "{}",
                category: "Mens",
                couponType: "percentage",
                label: "contains (percentage)",
            },
            {
                operator: "!{}",
                category: "Womens",
                couponType: "fixed",
                label: "does not contain (fixed)",
            },
            {
                operator: "!{}",
                category: "Womens",
                couponType: "percentage",
                label: "does not contain (percentage)",
            },
        ];

        for (const tc of testCases) {
            test(`should apply coupon when category condition is -> ${tc.label}`, async ({
                page,
            }) => {
                await runCartRuleTest(page, tc);
            });
        }
    });
});