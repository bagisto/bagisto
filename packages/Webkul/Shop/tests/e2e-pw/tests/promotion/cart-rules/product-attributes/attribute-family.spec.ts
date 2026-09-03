import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

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

    await ruleApplyPage.expectCouponAppliedWithGrandTotal(
        discountValue,
        couponType,
    );
}

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreatePage(adminPage);

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
    {
        operator: "==",
        optionSelect: "Default",
        couponType: "fixed",
    },
    {
        operator: "==",
        optionSelect: "Default",
        couponType: "percentage",
    },
    {
        operator: "!=",
        optionSelect: "Jacket",
        couponType: "fixed",
    },
    {
        operator: "!=",
        optionSelect: "Jacket",
        couponType: "percentage",
    },
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
