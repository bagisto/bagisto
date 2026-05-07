import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

async function createRuleAndVerifyCoupon({
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
        attribute: "product|price",
        operator,
        value,
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

const conditions = [
    {
        title: "is equal to",
        operator: "==",
        value: "199",
    },
    {
        title: "is not equal to",
        operator: "!=",
        value: "100",
    },
    {
        title: "equals or greater then",
        operator: ">=",
        value: "199",
    },
    {
        title: "equals or less than",
        operator: "<=",
        value: "200",
    },
    {
        title: "greater than",
        operator: ">",
        value: "198",
    },
    {
        title: "less than",
        operator: "<",
        value: "200",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const condition of conditions) {
            test(`should apply coupon when price condition is -> ${condition.title}`, async ({
                page,
            }) => {
                await createRuleAndVerifyCoupon({
                    page,
                    operator: condition.operator,
                    value: condition.value,
                });
            });
        }
    });
});
