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
    operator,
    value,
    couponType,
    incrementTimes,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    value: string;
    couponType: CouponType;
    incrementTimes?: number;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);

    const rule = await ruleCreatePage.cartRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "cart|items_qty",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

    await ruleCreatePage.saveCartRule();

    await ruleApplyPage.expectCouponAppliedWithGrandTotal({
        productName: product.name,
        couponCode: rule.couponCode,
        discountValue: discountValue,
        couponType: couponType,
        ...{ incrementTimes },
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

const testCases = [
    {
        operator: "==",
        value: "1",
        incrementTimes: undefined,
        label: "is equal to",
    },
    {
        operator: "!=",
        value: "2",
        incrementTimes: undefined,
        label: "is not equal to",
    },
    {
        operator: ">=",
        value: "1",
        incrementTimes: undefined,
        label: "equals or greater then",
    },
    {
        operator: "<=",
        value: "2",
        incrementTimes: undefined,
        label: "equals or less than",
    },
    {
        operator: ">",
        value: "1",
        incrementTimes: 2,
        label: "greater than",
    },
    {
        operator: "<",
        value: "2",
        incrementTimes: undefined,
        label: "less than",
    },
];

const couponTypes: CouponType[] = ["fixed", "percentage"];

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
        for (const tc of testCases) {
            for (const couponType of couponTypes) {
                test(`should apply coupon when total item quantity condition is -> ${tc.label} (${couponType})`, async ({
                    adminPage,
                    shopPage,
                }) => {
                    await createRuleAndVerifyCoupon({
                        adminPage,
                        shopPage,
                        operator: tc.operator,
                        value: tc.value,
                        couponType,
                        incrementTimes: tc.incrementTimes,
                    });
                });
            }
        }
    });
});
