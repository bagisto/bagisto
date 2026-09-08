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
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    value: string;
    couponType: CouponType;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.cartRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|price",
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

type TestCase = {
    operator: string;
    value: string;
    couponType: CouponType;
    label: string;
};

const testCases: TestCase[] = [
    {
        operator: "==",
        value: "199",
        couponType: "fixed",
        label: "is equal to (fixed)",
    },
    {
        operator: "==",
        value: "199",
        couponType: "percentage",
        label: "is equal to (percentage)",
    },

    {
        operator: "!=",
        value: "100",
        couponType: "fixed",
        label: "is not equal to (fixed)",
    },
    {
        operator: "!=",
        value: "100",
        couponType: "percentage",
        label: "is not equal to (percentage)",
    },

    {
        operator: ">=",
        value: "199",
        couponType: "fixed",
        label: "equals or greater then (fixed)",
    },
    {
        operator: ">=",
        value: "199",
        couponType: "percentage",
        label: "equals or greater then (percentage)",
    },

    {
        operator: "<=",
        value: "199",
        couponType: "fixed",
        label: "equals or less than (fixed)",
    },
    {
        operator: "<=",
        value: "199",
        couponType: "percentage",
        label: "equals or less than (percentage)",
    },

    {
        operator: ">",
        value: "198",
        couponType: "fixed",
        label: "greater than (fixed)",
    },
    {
        operator: ">",
        value: "198",
        couponType: "percentage",
        label: "greater than (percentage)",
    },

    {
        operator: "<",
        value: "200",
        couponType: "fixed",
        label: "less than (fixed)",
    },
    {
        operator: "<",
        value: "200",
        couponType: "percentage",
        label: "less than (percentage)",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when price condition is -> ${tc.label}`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    operator: tc.operator,
                    value: tc.value,
                    couponType: tc.couponType,
                });
            });
        }
    });
});
