import {
    createTaxCategory,
    createTaxRate,
    deleteTaxCategoriesIfPresent,
    deleteTaxRatesIfPresent,
} from "../../../../utils/admin";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import { uniqueStamp } from "../../../../utils/faker";
import { test } from "../../../../setup";
import type { BaseProduct } from "../../../../pages/types/product.types";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import type { Page } from "@playwright/test";

type TaxCategoryChoice = "assigned" | "other";

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
        await new RuleDeletePage(adminPage).deleteCatalogRulesIfPresent(createdRules);
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

async function runCatalogRuleTest({
    adminPage,
    shopPage,
    operator,
    option,
    type,
}: {
    adminPage: Page;
    shopPage: Page;
    operator: string;
    option: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const ruleApplyPage = new RuleApplyPage(shopPage);

    const rule = await ruleCreatePage.catalogRuleCreationFlow();
    createdRules.push(rule.name);

    const discountValue = await ruleCreatePage.addCondition({
        scopeSku: product.sku,
        attribute: "product|tax_category_id",
        operator,
        optionSelect: option,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule({
        productName: product.name,
        price: product.price ?? 0,
        value: discountValue ?? 0,
        type: type,
    });
}

const testCases: {
    operator: string;
    option: TaxCategoryChoice;
    label: string;
    type: string;
}[] = [
    {
        operator: "==",
        option: "assigned",
        label: "is equal to",
        type: "percentage",
    },
    {
        operator: "==",
        option: "assigned",
        label: "is equal to",
        type: "fixed",
    },
    {
        operator: "!=",
        option: "other",
        label: "is not equal to",
        type: "percentage",
    },
    {
        operator: "!=",
        option: "other",
        label: "is not equal to",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when tax category condition is -> ${tc.label} (${tc.type})`, async ({
                adminPage,
                shopPage,
            }) => {
                await runCatalogRuleTest({
                    adminPage,
                    shopPage,
                    operator: tc.operator,
                    option: taxCategories[tc.option],
                    type: tc.type,
                });
            });
        }
    });
});
