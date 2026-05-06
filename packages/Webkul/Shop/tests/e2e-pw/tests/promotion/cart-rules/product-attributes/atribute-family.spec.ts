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

async function runCartRuleTest(
    page: Page,
    {
        operator,
        optionSelect,
        couponType,
    }: {
        operator: string;
        optionSelect: string;
        couponType: CouponType;
    },
) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|attribute_family_id",
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

type TestCase = {
    operator: string;
    optionSelect: string;
    couponType: CouponType;
};

const testCases: TestCase[] = [
    { operator: "==", optionSelect: "1", couponType: "fixed" },
    { operator: "==", optionSelect: "1", couponType: "percentage" },
    { operator: "!=", optionSelect: "2", couponType: "fixed" },
    { operator: "!=", optionSelect: "2", couponType: "percentage" },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when attribute family ${tc.operator} (${tc.couponType})`, async ({
                page,
            }) => {
                await runCartRuleTest(page, tc);
            });
        }
    });
});