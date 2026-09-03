import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

type CouponType = "fixed" | "percentage";

let generatedSku: string;

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
        attribute: "product|sku",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    await ruleApplyPage.expectCouponAppliedWithGrandTotal(
        discountValue,
        couponType,
    );
}

test.beforeEach(async ({ adminPage }) => {
    generatedSku = `SKU-${Date.now()}`;

    const productCreation = new ProductCreatePage(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: generatedSku,
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
        value: () => generatedSku,
    },
    {
        operator: "==",
        type: "percentage",
        value: () => generatedSku,
    },
    {
        operator: "!=",
        type: "fixed",
        value: () => "sku-123",
    },
    {
        operator: "!=",
        type: "percentage",
        value: () => "sku-123",
    },
    {
        operator: "{}",
        type: "fixed",
        value: () => generatedSku,
    },
    {
        operator: "{}",
        type: "percentage",
        value: () => generatedSku,
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
    test.describe("product attribute conditions", () => {
        for (const { operator, type, value } of cases) {
            test(`should apply coupon when sku condition is -> ${operator} (${type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator,
                    value: value(),
                    couponType: type as CouponType,
                });
            });
        }
    });
});
