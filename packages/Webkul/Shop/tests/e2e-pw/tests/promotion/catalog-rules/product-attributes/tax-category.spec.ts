import { test } from "../../../../setup";
import { ProductListPage } from "../../../../pages/admin/catalog/products/ProductListPage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import type { BaseProduct } from "../../../../pages/types/product.types";
import {
    createTaxCategory,
    createTaxRate,
    deleteTaxCategoriesIfPresent,
    deleteTaxRatesIfPresent,
} from "../../../../utils/admin";
import { uniqueStamp } from "../../../../utils/faker";
import {
    addRuleCondition,
    applyProductChange,
    buildRuleConditionCases,
    caseTitle,
    createRuleProduct,
    RULE_PRODUCT_PRICE,
} from "../../rule-conditions";

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

    product = await createRuleProduct(adminPage);

    await applyProductChange(adminPage, product.name, {
        kind: "select",
        code: "tax_category_id",
        label: taxCategories.assigned,
    });
});

test.afterEach(async ({ adminPage }) => {
    try {
        await new RuleDeletePage(adminPage).deleteCatalogRulesIfPresent(createdRules);
    } finally {
        try {
            await new ProductListPage(adminPage).deleteProductsIfPresent([
                product.name,
            ]);
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

const cases = buildRuleConditionCases([
    {
        conditionLabel: "tax category",
        attribute: "product|tax_category_id",
        rows: [
            { operator: "==", optionSelect: () => taxCategories.assigned },
            { operator: "!=", optionSelect: () => taxCategories.other },
        ],
    },
]);

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const testCase of cases) {
            test(caseTitle(testCase, "discount the listed price"), async ({
                adminPage,
                shopPage,
            }) => {
                const ruleCreatePage = new RuleCreatePage(adminPage);
                const ruleApplyPage = new RuleApplyPage(shopPage);

                const rule = await ruleCreatePage.catalogRuleCreationFlow();

                createdRules.push(rule.name);

                const discountValue = await addRuleCondition(
                    ruleCreatePage,
                    testCase,
                    product.sku,
                );

                await ruleCreatePage.saveCatalogRule();

                await ruleApplyPage.searchProduct(product.name);

                await ruleApplyPage.expectCatalogRuleDiscount({
                    productName: product.name,
                    price: RULE_PRODUCT_PRICE,
                    value: discountValue,
                    type: testCase.couponType,
                });
            });
        }
    });
});
