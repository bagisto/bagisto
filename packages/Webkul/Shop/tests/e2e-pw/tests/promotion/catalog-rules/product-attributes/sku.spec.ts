import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

let generatedSku: string;
generatedSku = `SKU-${Date.now()}`;

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: generatedSku,
        name: `Simple-${Date.now()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const ruleDeletePage = new RuleDeletePage(adminPage);
        await ruleDeletePage.deleteCatalogRuleAndProduct();
    },
);

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        test("should apply coupon when sku of product condition is -> is equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|sku",
                operator: "==",
                value: generatedSku,
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when sku of product condition is -> is not equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|sku",
                operator: "!=",
                value: "sku-123",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when sku of product condition is -> contains", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|sku",
                operator: "{}",
                value: generatedSku,
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when sku of product condition is -> does not contain", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|sku",
                operator: "!{}",
                value: "example",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });
    });
});
