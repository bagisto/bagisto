import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

let generatedName: string;

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
        attribute: "product|new",
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

test.beforeEach(async ({ adminPage }) => {
    generatedName = `Simple-${Date.now()}`;
    const productCreation = new ProductCreatePage(adminPage);

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

type TestCase = {
    operator: string;
    optionSelect: string;
    couponType: CouponType;
    label: string;
};

const testCases: TestCase[] = [
    {
        operator: "==",
        optionSelect: "Yes",
        couponType: "fixed",
        label: "is equal to (fixed)",
    },
    {
        operator: "==",
        optionSelect: "Yes",
        couponType: "percentage",
        label: "is equal to (percentage)",
    },
    {
        operator: "!=",
        optionSelect: "No",
        couponType: "fixed",
        label: "is not equal to (fixed)",
    },
    {
        operator: "!=",
        optionSelect: "No",
        couponType: "percentage",
        label: "is not equal to (percentage)",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when new product condition is -> ${tc.label}`, async ({
                page,
            }) => {
                await runCartRuleTest(page, tc);
            });
        }
    });
});
