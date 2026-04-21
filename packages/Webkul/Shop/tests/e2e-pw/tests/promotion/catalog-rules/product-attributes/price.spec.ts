import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
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
        test("should apply coupon when price condition is -> is equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|price",
                operator: "==",
                value: "199",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when price condition is -> is not equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|price",
                operator: "!=",
                value: "100",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when price condition is -> equals or greater then", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|price",
                operator: ">=",
                value: "199",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when price condition is -> equals or less than", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|price",
                operator: "<=",
                value: "200",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when price condition is -> greater than", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|price",
                operator: ">",
                value: "198",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });

        test("should apply coupon when price condition is -> less than", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.catalogRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|price",
                operator: "<",
                value: "200",
            });
            await ruleCreatePage.saveCatalogRule();
            await ruleApplyPage.verifyCatalogRule();
        });
    });
});
