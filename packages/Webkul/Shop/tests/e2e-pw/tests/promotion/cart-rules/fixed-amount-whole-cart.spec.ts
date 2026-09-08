import { uniqueStamp } from "../../../utils/faker";
import { ProductListPage } from "../../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../../pages/types/product.types";
import { test } from "../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../pages/shop/rules/RuleApplyPage";

type CouponType = "fixedAmmountWholeCart";

async function createRuleAndVerifyCoupon({
    adminPage,
    shopPage,
    operator,
    optionSelect,
    couponType,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    optionSelect: string;
    couponType: CouponType;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.cartRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "cart|payment_method",
        operator,
        optionSelect,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    await ruleApplyPage.expectCouponAppliedWithGrandTotal({
        productName: product.name,
        couponCode: rule.couponCode,
        discountValue: discountValue,
        couponType: couponType,
    });
}

let product: BaseProduct;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];
    product = await new ProductCreatePage(adminPage).createProduct({
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: `Simple-${uniqueStamp()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(async ({ adminPage }) => {
    try {
        await new RuleDeletePage(adminPage).deleteCartRulesIfPresent(createdRules);
    } finally {
        await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
    }
});

const cases = [
    { operator: "==", option: "Money Transfer" },
    { operator: "!=", option: "Cash On Delivery" },
];

test.describe("cart rules", () => {
    for (const { operator, option } of cases) {
        test(`should allow coupon for fixed amount whole cart option -> ${operator}`, async ({
            adminPage,
            shopPage,
        }) => {
            await createRuleAndVerifyCoupon({
                adminPage,
                shopPage,
                operator,
                optionSelect: option,
                couponType: "fixedAmmountWholeCart",
            });
        });
    }
});
