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

async function createRuleAndVerifySize({
    adminPage,
    shopPage,
    operator,
    couponType,
    ruleSize,
    productSize,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    couponType: CouponType;
    ruleSize: string;
    productSize: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.cartRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|size",
        operator,
        optionSelect: ruleSize,
        couponType,
    });

    if (discountValue === undefined) throw new Error("Discount not created");

    await ruleCreatePage.saveCartRule();
    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);

    await productEditPage.selectOption("size", productSize);
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
    {
        operator: "==",
        type: "fixed",
        ruleSize: "S",
        productSize: "S",
    },
    {
        operator: "==",
        type: "percentage",
        ruleSize: "S",
        productSize: "S",
    },
    {
        operator: "!=",
        type: "fixed",
        ruleSize: "L",
        productSize: "S",
    },
    {
        operator: "!=",
        type: "percentage",
        ruleSize: "L",
        productSize: "S",
    },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const { operator, type, ruleSize, productSize } of cases) {
            test(`should apply coupon when size condition is -> ${operator} (${type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifySize({
                    adminPage,
                    shopPage,
                    operator,
                    couponType: type as CouponType,
                    ruleSize,
                    productSize,
                });
            });
        }
    });
});
