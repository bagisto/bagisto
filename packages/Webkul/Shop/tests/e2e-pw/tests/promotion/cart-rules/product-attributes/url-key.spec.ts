import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { uniqueStamp } from "../../../../utils/faker";
import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

type CouponType = "fixed" | "percentage";

let generatedName: string;

async function createRuleAndVerifyUrlKey({
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
        attribute: "product|url_key",
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

    generatedName = `Simple-${uniqueStamp()}`;
    product = await new ProductCreatePage(adminPage).createProduct({
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: generatedName,
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
        type: "fixed",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "==",
        type: "percentage",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "!=",
        type: "fixed",
        value: () => "simple",
    },
    {
        operator: "!=",
        type: "percentage",
        value: () => "simple",
    },
    {
        operator: "{}",
        type: "fixed",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "{}",
        type: "percentage",
        value: () => generatedName.toLowerCase(),
    },
    {
        operator: "!{}",
        type: "fixed",
        value: () => "example",
    },
    {
        operator: "!{}",
        type: "percentage",
        value: () => "example",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const { operator, type, value } of cases) {
            test(`should allow coupon when url key condition is -> ${operator} (${type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyUrlKey({
                    adminPage,
                    shopPage,
                    operator,
                    value: value(),
                    couponType: type as CouponType,
                });
            });
        }
    });
});
