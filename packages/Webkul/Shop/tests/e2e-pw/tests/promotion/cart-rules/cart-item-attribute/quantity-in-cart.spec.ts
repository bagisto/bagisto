import { uniqueStamp } from "../../../../utils/faker";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

type CouponType = "fixed" | "percentage";

async function createRuleAndVerifyCoupon({
    adminPage,
    shopPage,
    attribute,
    operator,
    value,
    couponType,
}: {
    adminPage: Page;
    shopPage: Page;
    attribute: string;
    operator: string;
    value: string;
    couponType: CouponType;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.cartRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        attribute,
        operator,
        value,
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
    {
        operator: "==",
        value: "1",
        type: "percentage",
        label: "is equal to",
    },
    {
        operator: "==",
        value: "1",
        type: "fixed",
        label: "is equal to",
    },

    {
        operator: "!=",
        value: "100",
        type: "percentage",
        label: "is not equal to",
    },
    {
        operator: "!=",
        value: "100",
        type: "fixed",
        label: "is not equal to",
    },

    {
        operator: ">=",
        value: "1",
        type: "percentage",
        label: "is greater than or equal to",
    },
    {
        operator: ">=",
        value: "1",
        type: "fixed",
        label: "is greater than or equal to",
    },

    {
        operator: "<=",
        value: "1",
        type: "percentage",
        label: "is less than or equal to",
    },
    {
        operator: "<=",
        value: "1",
        type: "fixed",
        label: "is less than or equal to",
    },

    {
        operator: ">",
        value: "0",
        type: "percentage",
        label: "is greater than",
    },
    {
        operator: ">",
        value: "0",
        type: "fixed",
        label: "is greater than",
    },

    {
        operator: "<",
        value: "2",
        type: "percentage",
        label: "is less than",
    },
    {
        operator: "<",
        value: "2",
        type: "fixed",
        label: "is less than",
    },
];

test.describe("cart rules", () => {
    test.describe("cart item attribute conditions", () => {
        for (const { operator, value, type, label } of cases) {
            test(`should apply coupon when qty in cart is -> ${label} (${operator}) (${type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    attribute: "cart_item|quantity",
                    operator,
                    value,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
