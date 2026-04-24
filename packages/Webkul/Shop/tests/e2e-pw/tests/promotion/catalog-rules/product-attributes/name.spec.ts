import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

let generatedName: string;
generatedName = `Simple-${Date.now()}`;

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: generatedName,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 199,
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
        test("should apply coupon when name of product condition is -> is equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|name",
                operator: "==",
                value: generatedName,
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when name of product condition is -> is not equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|name",
                operator: "!=",
                value: "simple",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when name of product condition is -> contains", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|name",
                operator: "{}",
                value: generatedName,
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when name of product condition is -> does not contain", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|name",
                operator: "!{}",
                value: "example",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });
    });
});
