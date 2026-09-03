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
    attribute,
    operator,
    value,
    couponType,
}: {
    page: Page;
    attribute: string;
    operator: string;
    value: string;
    couponType: CouponType;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute,
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

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);
    await ruleDeletePage.deleteRuleAndProduct();
});

const cases = [
    {
        operator: "==",
        value: "199",
        type: "percentage",
        label: "equal to",
    },
    {
        operator: "==",
        value: "199",
        type: "fixed",
        label: "equal to",
    },

    {
        operator: "!=",
        value: "101",
        type: "percentage",
        label: "not equal to",
    },
    {
        operator: "!=",
        value: "101",
        type: "fixed",
        label: "not equal to",
    },
    {
        operator: ">=",
        value: "199",
        type: "percentage",
        label: "greater than or equal to",
    },
    {
        operator: ">=",
        value: "199",
        type: "fixed",
        label: "greater than or equal to",
    },
    {
        operator: "<=",
        value: "200",
        type: "percentage",
        label: "less than or equal to",
    },
    {
        operator: "<=",
        value: "200",
        type: "fixed",
        label: "less than or equal to",
    },
    {
        operator: ">",
        value: "198",
        type: "percentage",
        label: "greater than",
    },
    {
        operator: ">",
        value: "198",
        type: "fixed",
        label: "greater than",
    },
    {
        operator: "<",
        value: "200",
        type: "percentage",
        label: "less than",
    },
    {
        operator: "<",
        value: "200",
        type: "fixed",
        label: "less than",
    },
];

test.describe("cart rules", () => {
    test.describe("cart item attribute conditions", () => {
        for (const { operator, value, type, label } of cases) {
            test(`should apply coupon when subtotal condition is -> ${label} (${type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    attribute: "cart_item|base_total",
                    operator,
                    value,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
