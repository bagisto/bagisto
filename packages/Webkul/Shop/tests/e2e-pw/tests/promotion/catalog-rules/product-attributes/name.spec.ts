import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

let generatedName: string;

async function createRuleAndVerify({
    page,
    operator,
    value,
}: {
    page: any;
    operator: string;
    value: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|name",
        operator,
        value,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0);
}

test.beforeEach("should create simple product", async ({ adminPage }) => {
    generatedName = `Simple-${Date.now()}`;

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
        value: () => generatedName,
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: () => "simple",
    },
    {
        title: "contains",
        operator: "{}",
        value: () => generatedName,
    },
    {
        title: "does not contain",
        operator: "!{}",
        value: () => "example",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const testCase of testCases) {
            test(`should apply coupon when name of product condition is -> ${testCase.title}`, async ({
                page,
            }) => {
                await createRuleAndVerify({
                    page,
                    operator: testCase.operator,
                    value: testCase.value(),
                });
            });
        }
    });
});
