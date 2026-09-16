import { test } from "../../../setup";
import { ProductCreatePage } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../../pages/admin/catalog/products/ProductListPage";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleDeletePage } from "../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleApplyPage } from "../../../pages/shop/rules/RuleApplyPage";
import type { BaseProduct } from "../../../pages/types/product.types";
import { uniqueStamp } from "../../../utils/faker";

const VARIANT_PRICE = 45;

test.describe("catalog rules", () => {
    test.describe("configurable products", () => {
        let product: BaseProduct;
        let createdRules: string[];

        test.beforeEach(async ({ adminPage }) => {
            createdRules = [];

            product = await new ProductCreatePage(adminPage).createConfigProduct({
                type: "configurable",
                sku: `SKU-${uniqueStamp()}`,
                name: `Configurable-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                allowRma: false,
            });
        });

        test.afterEach(async ({ adminPage }) => {
            try {
                await new RuleDeletePage(adminPage).deleteCatalogRulesIfPresent(createdRules);
            } finally {
                await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
            }
        });

        test("should discount the listed price as soon as a rule matching its variants is saved", async ({
            adminPage,
            shopPage,
        }) => {
            const ruleCreatePage = new RuleCreatePage(adminPage);

            const rule = await ruleCreatePage.catalogRuleCreationFlow();
            createdRules.push(rule.name);

            const discountValue = await ruleCreatePage.addCondition({
                attribute: "product|sku",
                operator: "{}",
                value: product.sku,
                couponType: "percentage",
            });

            await ruleCreatePage.saveCatalogRule();

            const ruleApplyPage = new RuleApplyPage(shopPage);

            await ruleApplyPage.searchProduct(product.name);

            await ruleApplyPage.expectCatalogRuleDiscount({
                productName: product.name,
                price: VARIANT_PRICE,
                value: discountValue ?? 0,
                type: "percentage",
            });
        });
    });
});
