import { test } from "../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../utils/admin";

type CouponType = "fixedAmmountWholeCart";

async function createRuleAndVerifyCoupon({
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
        attribute: "cart|payment_method",
        operator,
        optionSelect,
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
        price: Math.floor(Math.random() * 1000) + 1,
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);
    await ruleDeletePage.deleteRuleAndProduct();
});

const cases = [
    { operator: "==", option: "Money Transfer" },
    { operator: "!=", option: "Cash On Delivery" },
];

test.describe("cart rules", () => {
    for (const { operator, option } of cases) {
        test(`should allow coupon for fixed amount whole cart option -> ${operator}`, async ({
            page,
        }) => {
            await createRuleAndVerifyCoupon({
                page,
                operator,
                optionSelect: option,
                couponType: "fixedAmmountWholeCart",
            });
        });
    }
});
