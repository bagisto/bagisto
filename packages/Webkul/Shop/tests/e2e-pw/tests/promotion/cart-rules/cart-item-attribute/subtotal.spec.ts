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
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    attribute: "cart_item|base_total",
                    operator,
                    value,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
