import { uniqueStamp } from "../../../../utils/faker";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { test } from "../../../../setup";
import type { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

type CouponType = "fixed" | "percentage";

async function updateProductWeight(adminPage: Page, weight: string) {
    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    await productEditPage.fillInput("weight", weight);
    await productEditPage.save();
}

async function createRuleAndVerifyCoupon({
    adminPage,
    shopPage,
    attribute,
    operator,
    value,
    couponType,
    productWeight,
}: {
    adminPage: Page;
    shopPage: Page;
    attribute: string;
    operator: string;
    value: string;
    couponType: CouponType;
    productWeight?: string;
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

    if (productWeight) {
        await updateProductWeight(adminPage, productWeight);
    }

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
        label: "equal to (percentage)",
    },
    {
        operator: "==",
        value: "1",
        type: "fixed",
        label: "equal to (fixed)",
    },

    {
        operator: "!=",
        value: "2",
        type: "percentage",
        label: "not equal to (percentage)",
    },
    {
        operator: "!=",
        value: "2",
        type: "fixed",
        label: "not equal to (fixed)",
    },

    {
        operator: ">=",
        value: "1",
        type: "percentage",
        label: "greater than or equal to (percentage)",
    },
    {
        operator: ">=",
        value: "1",
        type: "fixed",
        label: "greater than or equal to (fixed)",
    },

    {
        operator: "<=",
        value: "2",
        type: "percentage",
        label: "less than or equal to (percentage)",
    },
    {
        operator: "<=",
        value: "2",
        type: "fixed",
        label: "less than or equal to (fixed)",
    },
    {
        operator: ">",
        value: "1",
        type: "percentage",
        weight: "2",
        label: "greater than (percentage)",
    },
    {
        operator: ">",
        value: "1",
        type: "fixed",
        weight: "2",
        label: "greater than (fixed)",
    },

    {
        operator: "<",
        value: "2",
        type: "percentage",
        label: "less than (percentage)",
    },
    {
        operator: "<",
        value: "2",
        type: "fixed",
        label: "less than (fixed)",
    },
];

test.describe("cart rules", () => {
    test.describe("cart item attribute conditions", () => {
        for (const { operator, value, type, weight, label } of cases) {
            test(`should apply coupon when total weight condition is -> ${label}`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    attribute: "cart_item|base_total_weight",
                    operator,
                    value,
                    couponType: type as CouponType,
                    productWeight: weight,
                });
            });
        }
    });
});
