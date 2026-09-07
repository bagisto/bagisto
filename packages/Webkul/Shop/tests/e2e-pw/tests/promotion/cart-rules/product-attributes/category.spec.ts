import { uniqueStamp } from "../../../../utils/faker";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { test } from "../../../../setup";
import { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

let product: BaseProduct;
let createdRules: string[];

type CouponType = "fixed" | "percentage";

async function updateProductCategory(adminPage: Page, categoryName: string) {
    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);
    await productEditPage.assignCategory(categoryName);
    await productEditPage.save();
}

async function runCartRuleTest(
    adminPage: Page,
    shopPage: Page,
    {
        operator,
        category,
        couponType,
    }: {
        operator: string;
        category: string;
        couponType: CouponType;
    },
) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.cartRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|category_ids",
        operator,
        checkboxSelect: "Mens",
        couponType,
    });

    if (discountValue === undefined) {
        throw new Error("Discount value was not created.");
    }

    await ruleCreatePage.saveCartRule();
    await updateProductCategory(adminPage, category);
    await ruleApplyPage.expectCouponAppliedWithGrandTotal({
        productName: product.name,
        couponCode: rule.couponCode,
        discountValue: discountValue,
        couponType: couponType,
    });
}

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {

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
            category: string;
            couponType: CouponType;
            label: string;
        };

        const testCases: TestCase[] = [
            {
                operator: "{}",
                category: "Mens",
                couponType: "fixed",
                label: "contains (fixed)",
            },
            {
                operator: "{}",
                category: "Mens",
                couponType: "percentage",
                label: "contains (percentage)",
            },
            {
                operator: "!{}",
                category: "Womens",
                couponType: "fixed",
                label: "does not contain (fixed)",
            },
            {
                operator: "!{}",
                category: "Womens",
                couponType: "percentage",
                label: "does not contain (percentage)",
            },
        ];

        for (const tc of testCases) {
            test(`should apply coupon when category condition is -> ${tc.label}`, async ({
                adminPage,
                shopPage,
            }) => {
                await runCartRuleTest(adminPage, shopPage, tc);
            });
        }
    });
});
