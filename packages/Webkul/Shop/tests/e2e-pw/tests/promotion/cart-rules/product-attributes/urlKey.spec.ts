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
    async ({ page }) => {
        const ruleDeletePage = new RuleDeletePage(page);
        await ruleDeletePage.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        test("should apply coupon when url key of product condition is -> is equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|url_key",
                operator: "==",
                value: generatedName.toLowerCase(),
                couponType: "fixed",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCoupon("yes");
        });

        test("should apply coupon when url key of product condition is -> is not equal to", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|url_key",
                operator: "!=",
                value: "simple",
                couponType: "fixed",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCoupon("yes");
        });

        test("should apply coupon when url key of product condition is -> contains", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|url_key",
                operator: "{}",
                value: generatedName.toLowerCase(),
                couponType: "fixed",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCoupon("yes");
        });

        test("should apply coupon when url key of product condition is -> does not contain", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|url_key",
                operator: "!{}",
                value: "example",
                couponType: "fixed",
            });
            await ruleCreatePage.saveCartRule();
            await ruleApplyPage.applyCoupon("yes");
        });
    });
});
