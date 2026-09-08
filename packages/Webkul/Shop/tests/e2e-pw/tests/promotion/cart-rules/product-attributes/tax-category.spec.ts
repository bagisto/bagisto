import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { uniqueStamp } from "../../../../utils/faker";
import { test } from "../../../../setup";
import type { Page } from "@playwright/test";
import {
    createTaxCategory,
    createTaxRate,
    deleteTaxCategoriesIfPresent,
    deleteTaxRatesIfPresent,
} from "../../../../utils/admin";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";

type CouponType = "fixed" | "percentage";

type TaxCategoryChoice = "assigned" | "other";

async function createRuleAndVerifyTaxCategory({
    adminPage,
    shopPage,
    operator,
    optionSelect,
    couponType,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    optionSelect: string;
    couponType: CouponType;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);
    const rule = await ruleCreatePage.cartRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|tax_category_id",
        operator,
        optionSelect,
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

let taxCategories: Record<TaxCategoryChoice, string>;
let rateIdentifier: string;
let product: BaseProduct;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    const stamp = uniqueStamp();

    createdRules = [];
    taxCategories = {
        assigned: `Assigned Tax ${stamp}`,
        other: `Other Tax ${stamp}`,
    };

    rateIdentifier = await createTaxRate(adminPage);

    await createTaxCategory(adminPage, taxCategories.assigned, rateIdentifier);
    await createTaxCategory(adminPage, taxCategories.other, rateIdentifier);

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

    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(product.name);
    await productEditPage.selectOption("tax_category_id", taxCategories.assigned);
    await productEditPage.save();
});

test.afterEach(async ({ adminPage }) => {
    try {
        await new RuleDeletePage(adminPage).deleteCartRulesIfPresent(createdRules);
    } finally {
        try {
            await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
        } finally {
            try {
                await deleteTaxCategoriesIfPresent(adminPage, [
                    taxCategories.assigned,
                    taxCategories.other,
                ]);
            } finally {
                await deleteTaxRatesIfPresent(adminPage, [rateIdentifier]);
            }
        }
    }
});

const cases: { operator: string; type: CouponType; option: TaxCategoryChoice }[] = [
    { operator: "==", type: "fixed", option: "assigned" },
    { operator: "==", type: "percentage", option: "assigned" },
    { operator: "!=", type: "fixed", option: "other" },
    { operator: "!=", type: "percentage", option: "other" },
];

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        for (const { operator, type, option } of cases) {
            test(`should apply coupon when tax category condition is -> ${operator} (${type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await createRuleAndVerifyTaxCategory({
                    adminPage,
                    shopPage,
                    operator,
                    optionSelect: taxCategories[option],
                    couponType: type,
                });
            });
        }
    });
});
