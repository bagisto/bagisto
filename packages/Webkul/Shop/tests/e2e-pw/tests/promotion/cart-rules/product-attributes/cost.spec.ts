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

const productCost = 199;

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
        attribute: "product|cost",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

    await ruleCreatePage.saveCartRule();
    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    if (operator === "!=" || operator === "<") {
        const fillValue = (Number(value) - 1).toString();
        await productEditPage.fillInput("cost", fillValue);
    } else if (operator === ">") {
        const fillValue = (Number(value) + 1).toString();
        await productEditPage.fillInput("cost", fillValue);
    } else {
        await productEditPage.fillInput("cost", value);
    }

    await productEditPage.save();

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
        price: productCost,
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
        value: productCost.toString(),
        couponType: "fixed",
        label: "is equal to (fixed)",
    },
    {
        operator: "==",
        value: productCost.toString(),
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
        value: productCost.toString(),
        couponType: "fixed",
        label: "equals or greater than (fixed)",
    },
    {
        operator: ">=",
        value: productCost.toString(),
        couponType: "percentage",
        label: "equals or greater than (percentage)",
    },

    {
        operator: "<=",
        value: productCost.toString(),
        couponType: "fixed",
        label: "equals or less than (fixed)",
    },
    {
        operator: "<=",
        value: productCost.toString(),
        couponType: "percentage",
        label: "equals or less than (percentage)",
    },

    {
        operator: ">",
        value: (productCost - 1).toString(),
        couponType: "fixed",
        label: "greater than (fixed)",
    },
    {
        operator: ">",
        value: (productCost - 1).toString(),
        couponType: "percentage",
        label: "greater than (percentage)",
    },

    {
        operator: "<",
        value: (productCost + 1).toString(),
        couponType: "fixed",
        label: "less than (fixed)",
    },
    {
        operator: "<",
        value: (productCost + 1).toString(),
        couponType: "percentage",
        label: "less than (percentage)",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when cost condition is -> ${tc.label}`, async ({
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
