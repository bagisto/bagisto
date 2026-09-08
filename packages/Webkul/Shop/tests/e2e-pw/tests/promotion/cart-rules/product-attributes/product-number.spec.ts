import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import { test } from "../../../../setup";
import type { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

type CouponType = "fixed" | "percentage";

let generatedProductNumber: string;

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
        attribute: "product|product_number",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    if (operator === "!=" || operator === "!{}") {
        const fillValue = (Number(value) + 1).toString();
        await productEditPage.fillInput("product_number", fillValue);
    } else {
        await productEditPage.fillInput("product_number", value);
    }

    await productEditPage.save();

    await ruleApplyPage.expectCouponAppliedWithGrandTotal({
        productName: product.name,
        couponCode: rule.couponCode,
        discountValue,
        couponType,
    });
}

let product: BaseProduct;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];

    generatedProductNumber = `PN-${uniqueStamp()}`;
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
        type: "fixed",
        valueType: "match",
    },
    {
        operator: "==",
        type: "percentage",
        valueType: "match",
    },
    {
        operator: "!=",
        type: "fixed",
        valueType: "non-match",
    },
    {
        operator: "!=",
        type: "percentage",
        valueType: "non-match",
    },
    {
        operator: "{}",
        type: "fixed",
        valueType: "match",
    },
    {
        operator: "{}",
        type: "percentage",
        valueType: "match",
    },
    {
        operator: "!{}",
        type: "fixed",
        valueType: "non-match",
    },
    {
        operator: "!{}",
        type: "percentage",
        valueType: "non-match",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const { operator, type, valueType } of testCases) {
            test(`should allow coupon when product number condition is -> ${operator} (${type})`, async ({
                adminPage,
                shopPage,
            }) => {
                const value =
                    valueType === "match" ? generatedProductNumber : "123456";

                await createRuleAndVerifyCoupon({
                    adminPage,
                    shopPage,
                    operator,
                    value,
                    couponType: type as CouponType,
                });
            });
        }
    });
});
