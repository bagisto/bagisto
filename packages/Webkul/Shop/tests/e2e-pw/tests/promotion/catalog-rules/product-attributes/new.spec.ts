import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

let generatedName: string;
generatedName = `Simple-${Date.now()}`;

const conditions = [
    {
        title: "is equal to",
        operator: "==",
        optionSelect: "1",
    },
    {
        title: "is not equal to",
        operator: "!=",
        optionSelect: "0",
    },
];

async function createRuleAndVerifyCoupon({
    page,
    operator,
    optionSelect,
}: {
    page: any;
    operator: string;
    optionSelect: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|new",
        operator,
        optionSelect,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0);
}

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
        for (const condition of conditions) {
            test(`should apply coupon when new product condition is -> ${condition.title}`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator: condition.operator,
                    optionSelect: condition.optionSelect,
                });
            });
        }
    });
});
