import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsCustomer } from "../../../../utils/customer";
import { loginAsAdmin } from "../../../../utils/admin";

let generatedName = `Simple-${Date.now()}`;

async function createRuleAndVerifyCoupon({
    page,
    operator,
    optionSelect,
    type,
}: {
    page: any;
    operator: string;
    optionSelect: string;
    type: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|guest_checkout",
        operator,
        optionSelect,
        couponType: type,
    });

    await ruleCreatePage.saveCatalogRule();

    await loginAsCustomer(page);

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0, type);
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

const testCases = [
    {
        title: "is equal to",
        operator: "==",
        optionSelect: "1",
        type: "percentage",
    },
    {
        title: "is equal to",
        operator: "==",
        optionSelect: "1",
        type: "fixed",
    },
    {
        title: "is not equal to",
        operator: "!=",
        optionSelect: "0",
        type: "percentage",
    },
    {
        title: "is not equal to",
        operator: "!=",
        optionSelect: "0",
        type: "fixed",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply condition when guest checkout condition is -> ${tc.title} (${tc.type})`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator: tc.operator,
                    optionSelect: tc.optionSelect,
                    type: tc.type,
                });
            });
        }
    });
});
