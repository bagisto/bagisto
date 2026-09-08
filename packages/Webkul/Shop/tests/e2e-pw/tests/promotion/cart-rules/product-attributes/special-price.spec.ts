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

const specialPriceValue = "150";

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
        attribute: "product|special_price",
        operator,
        value,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();

    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    if (operator === "!=" || operator === ">=" || operator === ">") {
        const fillValue = (Number(value) + 1).toString();
        await productEditPage.fillInput("special_price", fillValue);
    } else if (operator === "<" || operator === "<=") {
        const fillValue = (Number(value) - 5).toString();
        await productEditPage.fillInput("special_price", fillValue);
    } else {
        await productEditPage.fillInput("special_price", value);
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
    { operator: "==", type: "fixed", value: specialPriceValue },
    { operator: "==", type: "percentage", value: specialPriceValue },

    { operator: "!=", type: "fixed", value: "100" },
    { operator: "!=", type: "percentage", value: "100" },

    { operator: ">=", type: "fixed", value: specialPriceValue },
    { operator: ">=", type: "percentage", value: specialPriceValue },

    { operator: "<=", type: "fixed", value: "200" },
    { operator: "<=", type: "percentage", value: "200" },

    { operator: ">", type: "fixed", value: "100" },
    { operator: ">", type: "percentage", value: "100" },

    { operator: "<", type: "fixed", value: "200" },
    { operator: "<", type: "percentage", value: "200" },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const { operator, type, value } of cases) {
            test(`should apply coupon when special price condition is -> ${operator} (${type})`, async ({
                adminPage,
                shopPage,
            }) => {
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
